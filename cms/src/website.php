<?php
/**
 * Entry point for the public website, loaded in-process by public_html/include/cms_client.php when the CMS is on the
 * same server (cms/ beside public_html, or public_html/cms). No network call, no intake token: the website saves
 * enquiries straight into the CMS database and reads enquiry status from it. Defines functions only (no session,
 * no output, no headers).
 */
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/packages.php';
require_once __DIR__ . '/controllers/enquiries.php';
require_once __DIR__ . '/intake.php';
