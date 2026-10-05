<?php
/**
 * Outgoing email for the CMS (password reset links, account notices). It reuses the website's mail setup, so there
 * is one SMTP account and one password, kept outside the web root:
 *   - PHPMailer from the website:      {site_root}/PHPMailer/PHPMailerAutoload.php
 *   - SMTP settings (info@ mailbox):   hgt-config.php in the folder that contains public_html
 *     (override with 'mail_config' in config.php).
 * Test runs: set HG_CMS_MAIL_DUMP to a file path and messages are written there instead of being sent.
 */

function cms_mail_config()
{
    $file = cms_config('mail_config') ?: dirname(rtrim(cms_config('site_root'), '/')) . '/hgt-config.php';
    $c = is_readable($file) ? (include $file) : null;
    return is_array($c) && !empty($c['smtp_password']) ? $c : null;
}

/** Send one email. Returns true when handed to the mail server. Never throws; failures go to the PHP error log. */
function cms_mail($to, $subject, $text)
{
    $dump = getenv('HG_CMS_MAIL_DUMP');
    if ($dump) { file_put_contents($dump, je(array('to' => $to, 'subject' => $subject, 'text' => $text)) . "\n", FILE_APPEND); return true; }
    $c = cms_mail_config();
    $lib = rtrim(cms_config('site_root'), '/') . '/PHPMailer/PHPMailerAutoload.php';
    if (!$c || !is_file($lib)) { error_log('hg cms mail: SMTP not configured (hgt-config.php or PHPMailer not found)'); return false; }
    require_once $lib;
    $m = new PHPMailer();
    $m->CharSet = 'UTF-8';
    $m->isSMTP(); $m->SMTPAuth = true; $m->SMTPDebug = 0; $m->Timeout = 15;
    $m->Host = $c['smtp_host']; $m->Port = $c['smtp_port']; $m->SMTPSecure = $c['smtp_secure'];
    $m->Username = $c['smtp_username']; $m->Password = $c['smtp_password'];
    $m->setFrom($c['mail_from'], 'Holiday Guru Travel CMS');
    $m->addAddress($to);
    $m->Subject = $subject;
    $m->Body = $text;
    if (!$m->send()) { error_log('hg cms mail: send failed: ' . $m->ErrorInfo); return false; }
    return true;
}

/** The CMS address used in emailed links. Taken from config (never from the request), so links cannot be spoofed. */
function cms_url($path = '/')
{
    return rtrim(cms_config('cms_url') ?: 'https://cms.holidaygurutravel.in', '/') . $path;
}
