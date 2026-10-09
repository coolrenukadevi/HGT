<?php
/**
 * Tour Package CMS configuration — EXAMPLE (no secrets).
 * Copy to cms/config.php (gitignored) and adjust. Never commit config.php.
 */
return array(
    // 'staging' shows the staging ribbon and allows the setup script. Use 'production' only after owner approval.
    'env' => 'staging',

    // Website sync. A staging CMS never writes the website's data files (packages, rates, offers, curation, sitemap)
    // unless this is true. Turn it on only with owner approval, after "Import new packages" and "Re-sync from website".
    'site_sync' => false,

    // SQLite database for staging. Production uses MySQL/MariaDB (schema/mysql.sql):
    //   'dsn' => 'mysql:host=localhost;dbname=hgt_cms;charset=utf8mb4', 'db_user' => '…', 'db_pass' => '…'
    'dsn' => 'sqlite:' . __DIR__ . '/storage/cms.sqlite',
    'db_user' => null,
    'db_pass' => null,

    // The public website's folder. The CMS reads its data files (import) and stylesheets (preview).
    // The CMS never writes into it.
    // Found automatically: cms/ beside public_html/ (the repository layout) or cms/ inside public_html/.
    'site_root' => is_dir(__DIR__ . '/../public_html') ? __DIR__ . '/../public_html' : dirname(__DIR__),
    'site_url' => 'https://holidaygurutravel.in',

    // Uploaded media (outside the web root; served through the CMS after login).
    'upload_dir' => __DIR__ . '/storage/uploads',
    'upload_max_bytes' => 8 * 1024 * 1024,

    // Package ID assignment stays OFF until the owner approves the Package ID mapping
    // (docs/top-tours/OWNER-PACKAGE-ID-DECISIONS.csv). While off, packages show "Pending" / "Proposed".
    'package_id_assignment' => false,

    // Per-package online checkout: not connected. While false, the package Pay Now button stays disabled.
    // The website's general Pay Now link (Razorpay, HG_PAY_LINK in public_html/include/site_config.php) is read
    // from the website and shown in Pricing and Preview; it does not need this switch.
    'payment_gateway' => false,

    // Website → CRM enquiries. On the same server as the website (cms/ beside public_html) the website saves enquiries
    // directly into this CMS: leave this EMPTY. Only if the CMS runs on another server: set a long random value here;
    // the website then sends enquiries over HTTPS (POST /api/enquiries) with it.
    'intake_token' => '',

    // Address used in emailed links (password reset). Never taken from the request.
    'cms_url' => 'https://cms.holidaygurutravel.in',

    // Passwords must be changed every this many days.
    'password_max_age_days' => 90,

    // Email: the CMS reuses the website's SMTP settings (hgt-config.php next to public_html, info@ mailbox).
    // Set a path here only if that file lives elsewhere.
    'mail_config' => null,

    // Every password reset also sends a short notice here (who, when). '' to switch off.
    'reset_notify' => 'info@holidaygurutravel.in',

    // Offer Zone limit (internal): at most this many offers can be published at once.
    'offer_zone_limit' => 100,
);
