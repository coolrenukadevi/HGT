<?php
// Copy to the directory ABOVE public_html as hgt-config.php (never inside the web root)
// and fill in the real values. This file is not served and must not be committed.
// Hostinger mail: smtp.hostinger.com, port 587, TLS/STARTTLS, authentication on.
return array(
    'smtp_host'     => 'smtp.hostinger.com',
    'smtp_port'     => 587,
    'smtp_secure'   => 'tls',
    'smtp_username' => 'info@holidaygurutravel.in',
    'smtp_password' => 'CHANGE_ME',
    'mail_from'     => 'info@holidaygurutravel.in', // Hostinger requires From = the login mailbox
    'mail_to'       => 'info@holidaygurutravel.in',
);
