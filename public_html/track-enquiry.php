<?php
// Track your enquiry: the customer enters the enquiry number (HGT-E-00012) shown when the enquiry was sent, plus the
// email or mobile number used on it. The status comes from the CMS (include/cms_client.php), server to server.
// Not indexed and not in the sitemap (a personal utility page). Linked under Help & Support.
require __DIR__ . '/include/ui/core.php';
require __DIR__ . '/include/cms_client.php';

$no = ''; $contact = ''; $result = null; $error = '';
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $no = strtoupper(trim(mb_substr((string) (isset($_POST['enquiry_no']) ? $_POST['enquiry_no'] : ''), 0, 30)));
    $contact = trim(mb_substr((string) (isset($_POST['contact']) ? $_POST['contact'] : ''), 0, 150));
    // Honeypot and a per-visitor limit (10 lookups per 10 minutes), so enquiry numbers cannot be guessed in bulk.
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $rl = sys_get_temp_dir() . '/hgt_track_' . md5($ip);
    $hits = is_readable($rl) ? (array) json_decode((string) @file_get_contents($rl), true) : array();
    $hits = array_values(array_filter($hits, function ($t) { return $t > time() - 600; }));
    if (!empty($_POST['website'])) {
        $error = 'not_found';
    } elseif (count($hits) >= 10) {
        $error = 'limit';
    } elseif (!preg_match('/^(?:HGT)?[\s\-]*E[\s\-]*\d{1,9}$/i', $no) || $contact === '') {
        $error = 'input';
    } else {
        $hits[] = time();
        @file_put_contents($rl, json_encode($hits), LOCK_EX);
        $r = hgt_cms_status($no, $contact);
        if (isset($r['ok'])) $result = $r['ok']; else $error = $r['error'];
    }
}
$dmy = function ($d) { return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $d) ? date('j M Y', strtotime($d)) : ''; };

hg_layout_start(array(
    'title' => 'Track Your Enquiry | Holiday Guru Travel',
    'description' => 'Check the status of your Holiday Guru Travel enquiry with your enquiry number and the email or mobile number you used.',
    'path' => '/track-enquiry', 'index' => false,
    'breadcrumbs' => array(array('Home', '/'), array('Track your enquiry', null)),
));
?>
<section class="hg-pagehead" aria-labelledby="page-title">
    <div class="hg-container hg-narrow">
        <p class="hg-eyebrow">Help &amp; Support</p>
        <h1 class="hg-h1" id="page-title">Track your enquiry</h1>
        <p class="hg-lead">Enter the enquiry number you received when you sent your enquiry (for example HGT-E-00012) and the email address or mobile number you gave us.</p>
    </div>
</section>
<section class="hg-section hg-section--tight">
    <div class="hg-container hg-narrow">
        <form class="hg-form hg-track" method="post" action="/track-enquiry">
            <div class="hg-track__fields">
                <div class="hg-field"><label for="tr-no">Enquiry number</label>
                    <input id="tr-no" name="enquiry_no" required maxlength="30" autocomplete="off" placeholder="HGT-E-00012" value="<?= hg_e($no) ?>"></div>
                <div class="hg-field"><label for="tr-contact">Email or mobile number</label>
                    <input id="tr-contact" name="contact" required maxlength="150" autocomplete="email" placeholder="you@example.com or 98xxx xxxxx" value="<?= hg_e($contact) ?>"></div>
            </div>
            <p class="hg-sr" aria-hidden="true"><label>Leave this empty <input name="website" tabindex="-1" autocomplete="off"></label></p>
            <button class="hg-btn hg-btn--primary" type="submit">Track enquiry</button>
        </form>

        <?php if ($result) { ?>
        <div class="hg-track__result" role="status">
            <h2 class="hg-h3">Enquiry <?= hg_e($result['enquiry_no']) ?></h2>
            <p class="hg-track__status"><span class="hg-track__badge"><?= hg_e($result['status']) ?></span> <?= hg_e($result['status_text']) ?></p>
            <dl class="hg-qf">
                <div><dt>Received</dt><dd><?= hg_e($dmy($result['received'])) ?></dd></div>
                <?php if (!empty($result['package_name'])) { ?><div><dt>Tour</dt><dd><?= hg_e($result['package_name']) ?><?= !empty($result['package_id']) ? ' (Package ID ' . hg_e($result['package_id']) . ')' : '' ?></dd></div><?php } ?>
                <?php if (!empty($result['travel_date'])) { ?><div><dt>Travel date</dt><dd><?= hg_e($dmy($result['travel_date'])) ?></dd></div><?php } ?>
                <?php if (!empty($result['quotation_sent'])) { ?><div><dt>Quotation sent</dt><dd><?= hg_e($dmy($result['quotation_sent'])) ?></dd></div><?php } ?>
            </dl>
            <p>Questions or changes? Call <a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_e(HG_PHONE_DISPLAY) ?></a> or <a href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener">WhatsApp us</a> with your enquiry number.</p>
        </div>
        <?php } elseif ($error !== '') { ?>
        <p class="hg-track__msg" role="alert"><?php
            if ($error === 'not_found') echo 'We could not find an enquiry with that number and email or mobile number. Please check both, exactly as you gave them on the enquiry.';
            elseif ($error === 'input') echo 'Please enter your enquiry number (for example HGT-E-00012) and the email or mobile number you used.';
            elseif ($error === 'limit') echo 'Too many attempts. Please try again in a few minutes.';
            else echo 'Enquiry tracking is not available right now.';
            ?> You can also call <a href="<?= hg_e(hg_tel_href()) ?>"><?= hg_e(HG_PHONE_DISPLAY) ?></a> or <a href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener">WhatsApp us</a>.</p>
        <?php } ?>
        <p class="hg-muted hg-track__note">Enquiries sent before 9 October 2026 have no enquiry number; please call or WhatsApp us for those.</p>
    </div>
</section>
<?php hg_layout_end();
