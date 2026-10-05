<?php
/**
 * Create a CMS user, or reset an existing user's password, role and status, from the command line.
 * Works on staging and production. The password is never stored or printed: only its hash is saved.
 *
 *   php cms/bin/create-user.php --email=superadmin@holidaygurutravel.in --name="Super Admin" --role=super_admin
 *
 * The password is asked for (hidden) and must be typed twice. For scripted use, pipe it on standard
 * input instead (one line; it is then not asked twice). Do not put the password on the command line:
 * it would be kept in the shell history.
 * Roles: see HG_ROLES in src/permissions.php (super_admin, admin, package_manager, ...).
 */
require __DIR__ . '/../src/bootstrap.php';

if (PHP_SAPI !== 'cli') exit(1);

$opt = getopt('', array('email:', 'name:', 'role:'));
$email = strtolower(trim(isset($opt['email']) ? $opt['email'] : ''));
$name = trim(isset($opt['name']) ? $opt['name'] : '');
$role = isset($opt['role']) ? $opt['role'] : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $name === '' || !isset(HG_ROLES[$role])) {
    fwrite(STDERR, "Usage: php cms/bin/create-user.php --email=EMAIL --name=\"NAME\" --role=ROLE\nRoles: " . implode(', ', array_keys(HG_ROLES)) . "\n");
    exit(2);
}

$tty = function_exists('posix_isatty') ? posix_isatty(STDIN) : false;
$read = function ($prompt) use ($tty) {
    if (!$tty) return rtrim((string) fgets(STDIN), "\r\n");
    fwrite(STDERR, $prompt);
    shell_exec('stty -echo');
    $v = rtrim((string) fgets(STDIN), "\r\n");
    shell_exec('stty echo');
    fwrite(STDERR, "\n");
    return $v;
};
$pw = $read('Password: ');
if ($tty && $read('Repeat password: ') !== $pw) { fwrite(STDERR, "The passwords do not match.\n"); exit(3); }
if (strlen($pw) < 10 || !preg_match('/[A-Za-z]/', $pw) || !preg_match('/[0-9]/', $pw)) {
    fwrite(STDERR, "Use at least 10 characters with letters and numbers.\n");
    exit(3);
}

$db = cms_db();
cms_migrate($db);
$hash = password_hash($pw, PASSWORD_DEFAULT);
$pw = null;
$existing = qv('SELECT user_id FROM users WHERE email = ?', array($email));
if ($existing) {
    q('UPDATE users SET name = ?, role = ?, password_hash = ?, active = 1, password_changed_at = ?, must_change = 0 WHERE user_id = ?', array($name, $role, $hash, now(), $existing));
    q('DELETE FROM login_attempts WHERE email = ?', array($email));
    cms_log('user.reset', null, 'user', '', $email . ' (' . HG_ROLES[$role] . ')');
    echo "Updated $email: role " . HG_ROLES[$role] . ", password changed, account active.\n";
} else {
    q('INSERT INTO users(email, name, role, password_hash, created_at, password_changed_at) VALUES (?,?,?,?,?,?)', array($email, $name, $role, $hash, now(), now()));
    cms_log('user.create', null, 'user', '', $email . ' (' . HG_ROLES[$role] . ')');
    echo "Created $email as " . HG_ROLES[$role] . ".\n";
}
