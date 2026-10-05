<?php
/**
 * Settings: My account (name, email, password, profile photo), forced password change (new accounts, admin-set
 * passwords, and every 90 days), Forgot password / reset by email for every user, and staff users with roles.
 * Reset links are emailed from the info@ mailbox (src/mail.php) and are one-time, valid for 1 hour; only a hash of
 * the token is stored. Owner request 2026-10-03.
 */

const HG_RESET_TTL = 3600;

/* ---------------- Forgot password / reset (no login) ---------------- */

function auth_page($title, $body)
{
    cms_render('auth', array('title' => $title, 'body' => $body));
}

function forgot_get($sent = false)
{
    $b = $sent
        ? '<p class="cms-flash cms-flash--ok" role="status">If that email belongs to a CMS account, a reset link is on its way. It works once and expires in 1 hour. Check the Spam folder too.</p><p><a href="/login">Back to sign in</a></p>'
        : '<p class="cms-muted">Enter your CMS email. We will email you a link to set a new password.</p>'
          . '<form method="post" action="/forgot" class="cms-stack">' . csrf_field()
          . field_text('email', 'Email', post('email'), array('type' => 'email', 'required' => true, 'autocomplete' => 'username'))
          . '<button class="cms-btn cms-btn--primary cms-btn--block" type="submit">Email me a reset link</button></form><p><a href="/login">Back to sign in</a></p>';
    auth_page('Reset your password', $b);
}

function forgot_post()
{
    csrf_check();
    $email = strtolower(trim(post('email')));
    // Same answer whether or not the email exists (no account discovery). Max 3 requests per email per hour.
    $recent = (int) qv('SELECT COUNT(*) FROM login_attempts WHERE email = ? AND at > ?', array('reset:' . $email, gmdate('Y-m-d H:i:s', time() - 3600)));
    q('INSERT INTO login_attempts(email, at, ok) VALUES (?,?,?)', array('reset:' . $email, now(), 0));
    $u = $recent < 3 ? q1('SELECT * FROM users WHERE email = ? AND active = 1', array($email)) : null;
    if ($u) reset_send($u, 'requested');
    forgot_get(true);
}

/** Create a one-time reset link for $u and email it. $why: 'requested' (forgot form) or 'admin' (sent by an admin). */
function reset_send(array $u, $why)
{
    $token = bin2hex(random_bytes(32));
    q('UPDATE password_resets SET used_at = ? WHERE user_id = ? AND used_at IS NULL', array(now(), $u['user_id']));
    q('INSERT INTO password_resets(token_hash, user_id, created_at, expires_at, ip) VALUES (?,?,?,?,?)', array(
        hash('sha256', $token), $u['user_id'], now(), gmdate('Y-m-d H:i:s', time() + HG_RESET_TTL), isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'cli'));
    $link = cms_url('/reset?token=' . $token);
    $text = "Hello " . $u['name'] . ",\n\n"
        . ($why === 'admin' ? "An administrator has asked you to set a new password for the Holiday Guru Travel CMS.\n\n" : "Someone (hopefully you) asked to reset your Holiday Guru Travel CMS password.\n\n")
        . "Set a new password here (works once, valid for 1 hour):\n" . $link . "\n\n"
        . "If you did not ask for this, ignore this email: your password stays the same.\n\nHoliday Guru Travel CMS";
    $ok = cms_mail($u['email'], 'Set your Holiday Guru Travel CMS password', $text);
    $notify = cms_config('reset_notify');
    if ($notify && strcasecmp($notify, $u['email']) !== 0) {
        cms_mail($notify, 'CMS password reset ' . ($why === 'admin' ? 'sent by an admin' : 'requested') . ': ' . $u['email'],
            'A password reset link was sent to ' . $u['name'] . ' <' . $u['email'] . '> on ' . date('d M Y H:i') . ' (' . ($why === 'admin' ? 'sent by ' . (cms_user() ? cms_user()['name'] : 'an admin') : 'requested on the sign-in page') . ").\nNo action is needed unless this was unexpected.");
    }
    cms_log($why === 'admin' ? 'password reset link sent' : 'password reset requested', null, 'user', '', $u['email']);
    return $ok;
}

function reset_row($token)
{
    if (!preg_match('/^[a-f0-9]{64}$/', (string) $token)) return null;
    return q1('SELECT r.*, u.email, u.name, u.password_hash FROM password_resets r JOIN users u ON u.user_id = r.user_id AND u.active = 1
        WHERE r.token_hash = ? AND r.used_at IS NULL AND r.expires_at > ?', array(hash('sha256', $token), now()));
}

function reset_get($error = '')
{
    $token = get('token');
    $r = reset_row($token);
    if (!$r) { auth_page('Link expired', '<p>This reset link has expired or was already used.</p><p><a class="cms-btn cms-btn--primary" href="/forgot">Request a new link</a></p>'); return; }
    $b = '<p class="cms-muted">Set a new password for <strong>' . e($r['email']) . '</strong>.</p>'
        . ($error ? '<p class="cms-flash cms-flash--err" role="alert">' . e($error) . '</p>' : '')
        . '<form method="post" action="/reset?token=' . e($token) . '" class="cms-stack">' . csrf_field()
        . field_text('new_password', 'New password', '', array('type' => 'password', 'required' => true, 'autocomplete' => 'new-password', 'hint' => 'At least 10 characters, with letters and numbers.'))
        . field_text('new_password2', 'Repeat new password', '', array('type' => 'password', 'required' => true, 'autocomplete' => 'new-password'))
        . '<button class="cms-btn cms-btn--primary cms-btn--block" type="submit">Save new password</button></form>';
    auth_page('Set a new password', $b);
}

function reset_post()
{
    csrf_check();
    $r = reset_row(get('token'));
    if (!$r) return reset_get();
    $pw = (string) post('new_password');
    if ($pw !== (string) post('new_password2')) return reset_get('The two passwords do not match.');
    if ($p = pw_problem($pw, $r['password_hash'])) return reset_get($p);
    pw_set((int) $r['user_id'], $pw);
    q('UPDATE password_resets SET used_at = ? WHERE user_id = ? AND used_at IS NULL', array(now(), $r['user_id']));
    q('DELETE FROM login_attempts WHERE email = ?', array($r['email']));
    cms_log('password reset completed', null, 'user', '', $r['email']);
    flash('Your password has been changed. Sign in with the new password.');
    redirect('/login');
}

/** Store a new password (hash only) and restart the 90-day clock. */
function pw_set($uid, $pw, $mustChange = 0)
{
    q('UPDATE users SET password_hash = ?, password_changed_at = ?, must_change = ? WHERE user_id = ?', array(password_hash($pw, PASSWORD_DEFAULT), now(), $mustChange, $uid));
}

/* ---------------- My account (logged in) ---------------- */

function account_get()
{
    $u = q1('SELECT * FROM users WHERE user_id = ?', array(uid()));
    $left = pw_days_left($u);
    ob_start();
    echo '<div class="cms-grid2">';
    // Profile
    $b = '<form method="post" action="/account" class="cms-stack">' . csrf_field()
        . field_text('name', 'Full name', $u['name'], array('required' => true, 'maxlength' => 120, 'autocomplete' => 'name'))
        . field_text('email', 'Email (used to sign in and for reset links)', $u['email'], array('type' => 'email', 'required' => true, 'autocomplete' => 'email'))
        . field_text('current_password', 'Current password (only needed to change the email)', '', array('type' => 'password', 'autocomplete' => 'current-password'))
        . '<button class="cms-btn cms-btn--primary" type="submit">' . icon('save') . 'Save profile</button></form>';
    echo card('Profile', $b, array('sub' => 'Role: ' . e(HG_ROLES[$u['role']] ?? $u['role']) . '. Only a Super Admin or Admin can change roles.'));
    // Photo
    $img = $u['photo'] ? '<img class="cms-avatar-lg" src="/file?p=' . e(rawurlencode($u['photo'])) . '" alt="Your profile photo" width="96" height="96">' : '<span class="cms-avatar-lg cms-avatar-lg--empty" aria-hidden="true">' . e(mb_substr($u['name'], 0, 1)) . '</span>';
    $b = '<div class="cms-photo">' . $img . '<form method="post" action="/account/photo" enctype="multipart/form-data" class="cms-stack">' . csrf_field()
        . '<div class="cms-field"><label for="f-photo">Upload a photo</label><input id="f-photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" required aria-describedby="f-photo-h"><small class="cms-hint" id="f-photo-h">JPG, PNG or WEBP, up to 2 MB. It is cropped to a square and shown next to your name.</small></div>'
        . '<div class="cms-row"><button class="cms-btn cms-btn--primary" type="submit">' . icon('upload') . 'Upload photo</button></form>'
        . ($u['photo'] ? '<form method="post" action="/account/photo">' . csrf_field() . '<input type="hidden" name="remove" value="1"><button class="cms-btn cms-btn--ghost" type="submit">Remove photo</button></form>' : '')
        . '</div></div>';
    echo card('Profile photo', $b);
    echo '</div><div class="cms-grid2">';
    // Password
    echo card('Password', pw_form('/account/password', true), array('sub' => $left > 0 ? 'Your password expires in ' . $left . ' day' . ($left === 1 ? '' : 's') . '. Passwords must be changed every ' . pw_max_age_days() . ' days.' : 'Your password has expired. Set a new one now.'));
    $b = '<div class="cms-plain"><p>Forgot your password? Use “Forgot password?” on the sign-in page: a one-time link is emailed from info@holidaygurutravel.in.</p>'
        . '<p>Passwords expire every ' . pw_max_age_days() . ' days; the CMS then asks for a new one before you continue.</p>'
        . '<p>Last sign-in: ' . e($u['last_login_at'] ? dmy($u['last_login_at']) . ' ' . substr($u['last_login_at'], 11, 5) . ' (UTC)' : '—') . '</p></div>';
    echo card('Security', $b);
    echo '</div>';
    cms_render('layout', array('title' => 'My account', 'active' => 'account', 'crumbs' => array(array('Dashboard', '/'), array('My account', null)),
        'head' => page_head('My account', 'Your name, email, password and profile photo.'), 'content' => ob_get_clean()));
}

function pw_form($action, $withCurrent)
{
    return '<form method="post" action="' . e($action) . '" class="cms-stack">' . csrf_field()
        . ($withCurrent ? field_text('current_password', 'Current password', '', array('type' => 'password', 'required' => true, 'autocomplete' => 'current-password', 'id' => 'pw-cur')) : '')
        . field_text('new_password', 'New password', '', array('type' => 'password', 'required' => true, 'autocomplete' => 'new-password', 'id' => 'pw-new', 'hint' => 'At least 10 characters, with letters and numbers, different from the current one.'))
        . field_text('new_password2', 'Repeat new password', '', array('type' => 'password', 'required' => true, 'autocomplete' => 'new-password', 'id' => 'pw-new2'))
        . '<button class="cms-btn cms-btn--primary" type="submit">' . icon('lock') . 'Change password</button></form>';
}

function account_post()
{
    $u = q1('SELECT * FROM users WHERE user_id = ?', array(uid()));
    $name = trim(post('name')); $email = strtolower(trim(post('email')));
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { flash('Enter your name and a valid email.', 'err'); redirect('/account'); }
    if ($email !== $u['email']) {
        if (!password_verify((string) post('current_password'), $u['password_hash'])) { flash('To change the email, enter your current password.', 'err'); redirect('/account'); }
        if (qv('SELECT 1 FROM users WHERE email = ? AND user_id <> ?', array($email, $u['user_id']))) { flash('Another account already uses that email.', 'err'); redirect('/account'); }
        cms_mail($u['email'], 'Your CMS sign-in email was changed', 'Hello ' . $u['name'] . ",\n\nThe sign-in email of your Holiday Guru Travel CMS account was changed to " . $email . ' on ' . date('d M Y H:i') . ".\nIf this was not you, tell the Super Admin at once.\n\nHoliday Guru Travel CMS");
        cms_log('email changed', null, 'user', $u['email'], $email);
    }
    q('UPDATE users SET name = ?, email = ? WHERE user_id = ?', array($name, $email, $u['user_id']));
    flash('Profile saved.');
    redirect('/account');
}

function account_photo_post()
{
    $u = q1('SELECT * FROM users WHERE user_id = ?', array(uid()));
    $dir = rtrim(cms_config('upload_dir'), '/') . '/avatars';
    $old = $u['photo'] ? media_abs($u['photo']) : null;
    if (post('remove')) {
        if ($old && is_file($old)) @unlink($old);
        q("UPDATE users SET photo = '' WHERE user_id = ?", array($u['user_id']));
        flash('Photo removed.'); redirect('/account');
    }
    $f = isset($_FILES['photo']) ? $_FILES['photo'] : null;
    try {
        if (!$f || $f['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($f['tmp_name'])) throw new RuntimeException('Choose a photo to upload.');
        if ($f['size'] > 2 * 1048576) throw new RuntimeException('The photo is larger than 2 MB.');
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
        if (!in_array($mime, array('image/jpeg', 'image/png', 'image/webp'), true)) throw new RuntimeException('Use a JPG, PNG or WEBP photo.');
        $info = @getimagesize($f['tmp_name']);
        if (!$info || $info[0] < 64 || $info[1] < 64) throw new RuntimeException('Use a photo of at least 64×64 pixels.');
        $src = gd_open($f['tmp_name'], $mime);
        if (!$src) throw new RuntimeException('The photo could not be read.');
        // Centre square crop, 256×256 JPEG: small, and strips any hidden content of the original file.
        $side = min($info[0], $info[1]);
        $dst = imagecreatetruecolor(256, 256);
        imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        imagecopyresampled($dst, $src, 0, 0, (int) (($info[0] - $side) / 2), (int) (($info[1] - $side) / 2), 256, 256, $side, $side);
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $name = 'u' . (int) $u['user_id'] . '-' . bin2hex(random_bytes(6)) . '.jpg';
        imagejpeg($dst, $dir . '/' . $name, 85);
        if ($old && is_file($old)) @unlink($old);
        q('UPDATE users SET photo = ? WHERE user_id = ?', array('upload:avatars/' . $name, $u['user_id']));
        flash('Photo updated.');
    } catch (RuntimeException $x) { flash($x->getMessage(), 'err'); }
    redirect('/account');
}

/** POST /account/password: from My account (current password required) or the forced-change page. */
function password_post()
{
    $u = q1('SELECT * FROM users WHERE user_id = ?', array(uid()));
    $back = pw_expired($u) ? '/account/password' : '/account';
    $pw = (string) post('new_password');
    if (!password_verify((string) post('current_password'), $u['password_hash'])) { flash('Your current password is not correct.', 'err'); redirect($back); }
    if ($pw !== (string) post('new_password2')) { flash('The two new passwords do not match.', 'err'); redirect($back); }
    if ($p = pw_problem($pw, $u['password_hash'])) { flash($p, 'err'); redirect($back); }
    pw_set((int) $u['user_id'], $pw);
    session_regenerate_id(true);
    cms_log('password changed', null, 'user', '', $u['email']);
    flash('Password changed. The next change is due in ' . pw_max_age_days() . ' days.');
    redirect($back === '/account' ? '/account' : '/');
}

/** GET /account/password: the page shown when a password must be changed (new, admin-set or older than 90 days). */
function password_get()
{
    $u = cms_user();
    if (!pw_expired($u)) redirect('/account');
    $why = !empty($u['must_change']) ? 'Your password was set by an administrator. Choose your own password to continue.' : 'Your password is more than ' . pw_max_age_days() . ' days old. Choose a new one to continue.';
    $b = '<p class="cms-flash cms-flash--warn" role="status">' . e($why) . '</p>';
    foreach (flash() as $f) $b .= '<p class="cms-flash cms-flash--' . e($f[0]) . '" role="' . ($f[0] === 'err' ? 'alert' : 'status') . '">' . e($f[1]) . '</p>';
    $b .= pw_form('/account/password', true) . '<form method="post" action="/logout" class="cms-mt">' . csrf_field() . '<button class="cms-btn cms-btn--ghost cms-btn--block" type="submit">Sign out</button></form>';
    auth_page('Change your password', $b);
}

/* ---------------- Staff users (Super Admin / Admin) ---------------- */

function users_can_edit(array $target = null, $newRole = null)
{
    // Admins manage staff; only a Super Admin may create, edit or appoint a Super Admin.
    if (role() === 'super_admin') return true;
    if ($target && $target['role'] === 'super_admin') return false;
    if ($newRole === 'super_admin') return false;
    return true;
}

function active_super_admins($exceptUid = 0)
{
    return (int) qv("SELECT COUNT(*) FROM users WHERE role = 'super_admin' AND active = 1 AND user_id <> ?", array($exceptUid));
}

function user_form_get($id = null)
{
    need('users', 'manage');
    $t = $id ? q1('SELECT * FROM users WHERE user_id = ?', array((int) $id)) : null;
    if ($id && !$t) { http_response_code(404); cms_render('error', array('title' => 'Not found', 'message' => 'No such user.')); return; }
    if ($t && !users_can_edit($t)) deny('Only a Super Admin can edit a Super Admin account.');
    $roles = HG_ROLES; if (role() !== 'super_admin') unset($roles['super_admin']);
    $self = $t && (int) $t['user_id'] === uid();
    ob_start();
    $b = '<form method="post" action="' . ($t ? '/users/' . (int) $t['user_id'] : '/users/new') . '" class="cms-stack">' . csrf_field()
        . field_text('name', 'Full name', $t ? $t['name'] : post('name'), array('required' => true, 'maxlength' => 120))
        . field_text('email', 'Email', $t ? $t['email'] : post('email'), array('type' => 'email', 'required' => true))
        . field_select('role', 'Access role', $t ? $t['role'] : (post('role') ?: 'travel_consultant'), $roles, array('required' => true, 'disabled' => $self, 'hint' => $self ? 'You cannot change your own role.' : 'What this person can see and do. See “Role permissions” on the Users page.'))
        . ($t ? field_check('active', 'Account active (untick to block sign-in)', (bool) $t['active'], '1', array('disabled' => $self)) : '')
        . field_text('temp_password', $t ? 'Set a temporary password (optional)' : 'Temporary password', '', array('type' => 'password', 'required' => !$t, 'autocomplete' => 'new-password', 'hint' => 'At least 10 characters with letters and numbers. The person must choose their own password at first sign-in. Or leave it empty and use “Email a set-password link”.'))
        . '<div class="cms-row"><button class="cms-btn cms-btn--primary" type="submit">' . icon('save') . ($t ? 'Save user' : 'Create user') . '</button><a class="cms-btn cms-btn--ghost" href="/users">Cancel</a></div></form>';
    echo card($t ? 'Edit user' : 'Add a staff user', $b);
    if ($t) echo card('Password reset', '<p>Email ' . e($t['name']) . ' a one-time link (valid 1 hour) to set a new password. It is sent from info@holidaygurutravel.in.</p><form method="post" action="/users/' . (int) $t['user_id'] . '/reset">' . csrf_field() . '<button class="cms-btn cms-btn--ghost" type="submit">' . icon('mail') . 'Email a set-password link</button></form>');
    cms_render('layout', array('title' => $t ? 'Edit user' : 'Add user', 'active' => 'users', 'crumbs' => array(array('Dashboard', '/'), array('Users', '/users'), array($t ? $t['name'] : 'Add user', null)),
        'head' => page_head($t ? 'Edit user' : 'Add user', $t ? $t['email'] : 'Give a staff member their own sign-in and access role.'), 'content' => ob_get_clean()));
}

function user_form_post($id = null)
{
    need('users', 'manage');
    $t = $id ? q1('SELECT * FROM users WHERE user_id = ?', array((int) $id)) : null;
    if ($id && !$t) redirect('/users');
    $self = $t && (int) $t['user_id'] === uid();
    $name = trim(post('name')); $email = strtolower(trim(post('email')));
    $role = $self ? $t['role'] : post('role');
    $active = $t ? ($self ? 1 : (post('active') ? 1 : 0)) : 1;
    $temp = (string) post('temp_password');
    $back = $t ? '/users/' . (int) $t['user_id'] : '/users/new';
    if (!users_can_edit($t, $role)) deny('Only a Super Admin can create, edit or appoint a Super Admin.');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !isset(HG_ROLES[$role])) { flash('Enter a name, a valid email and a role.', 'err'); redirect($back); }
    if (qv('SELECT 1 FROM users WHERE email = ? AND user_id <> ?', array($email, $t ? $t['user_id'] : 0))) { flash('Another account already uses that email.', 'err'); redirect($back); }
    if ($t && $t['role'] === 'super_admin' && ($role !== 'super_admin' || !$active) && active_super_admins($t['user_id']) === 0) { flash('Keep at least one active Super Admin.', 'err'); redirect($back); }
    if ($temp !== '' && ($p = pw_problem($temp))) { flash('Temporary password: ' . $p, 'err'); redirect($back); }
    if (!$t) {
        if ($temp === '') { flash('Set a temporary password, or create the user and then email a set-password link.', 'err'); redirect($back); }
        q('INSERT INTO users(email, name, role, password_hash, active, created_at, password_changed_at, must_change) VALUES (?,?,?,?,1,?,?,1)', array($email, $name, $role, password_hash($temp, PASSWORD_DEFAULT), now(), now()));
        cms_log('user created', null, 'user', '', $email . ' (' . HG_ROLES[$role] . ')');
        flash('User created. Give them the temporary password in person or by phone; they choose their own at first sign-in.');
        redirect('/users');
    }
    q('UPDATE users SET name = ?, email = ?, role = ?, active = ? WHERE user_id = ?', array($name, $email, $role, $active, $t['user_id']));
    if ($temp !== '') pw_set((int) $t['user_id'], $temp, 1);
    if ($t['role'] !== $role) cms_log('user role changed', null, 'user', HG_ROLES[$t['role']], $email . ' → ' . HG_ROLES[$role]);
    if ((int) $t['active'] !== $active) cms_log($active ? 'user enabled' : 'user disabled', null, 'user', '', $email);
    flash('User saved.' . ($temp !== '' ? ' They must choose their own password at next sign-in.' : ''));
    redirect('/users');
}

function user_reset_post($id)
{
    need('users', 'manage');
    $t = q1('SELECT * FROM users WHERE user_id = ? AND active = 1', array((int) $id));
    if (!$t) { flash('That user is disabled or does not exist.', 'err'); redirect('/users'); }
    if (!users_can_edit($t)) deny('Only a Super Admin can reset a Super Admin password.');
    $ok = reset_send($t, 'admin');
    flash($ok ? 'Set-password link emailed to ' . $t['email'] . '.' : 'The email could not be sent. Check the mail settings (hgt-config.php).', $ok ? 'ok' : 'err');
    redirect('/users/' . (int) $t['user_id']);
}
