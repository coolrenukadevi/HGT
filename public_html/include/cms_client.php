<?php
/**
 * Website → CMS (CRM) connection. When the CMS is on the same server (cms/ next to public_html, or public_html/cms)
 * the website loads it in-process (cms/src/website.php) and writes to its database directly: no network call and no
 * key needed. Otherwise it falls back to HTTPS with the shared intake key from the CMS config.php (never sent to the
 * browser).
 *   hgt_cms_forward()  record a website enquiry in the CMS; returns its enquiry number (HGT-E-00012) or ''.
 *   hgt_cms_status()   status of one enquiry for "Track your enquiry" (number + the customer's email or phone).
 * Never blocks a visitor: short timeouts; any failure is only logged.
 */

if (!function_exists('hgt_cms_config')) {
    /** The CMS folder on this server, or '' (beside public_html first, then inside it). */
    function hgt_cms_dir()
    {
        foreach (array(dirname(dirname(__DIR__)) . '/cms', dirname(__DIR__) . '/cms') as $d) {
            if (is_file($d . '/src/website.php') && is_readable($d . '/config.php')) return $d;
        }
        return '';
    }

    /** Load the CMS in-process. True when its functions are available; any failure is logged and returns false. */
    function hgt_cms_local()
    {
        static $ok = null;
        if ($ok !== null) return $ok;
        $ok = false;
        $d = hgt_cms_dir();
        if ($d === '' || !extension_loaded('pdo_sqlite') && !extension_loaded('pdo_mysql')) return false;
        try {
            require_once $d . '/src/website.php';
            $ok = function_exists('cms_intake_enquiry');
        } catch (Throwable $x) {
            error_log('hgt cms local: ' . $x->getMessage());
        }
        return $ok;
    }

    function hgt_cms_config()
    {
        static $cfg = false;
        if ($cfg !== false) return $cfg;
        $cfg = null;
        foreach (array(dirname(__DIR__) . '/cms/config.php', dirname(dirname(__DIR__)) . '/cms/config.php') as $f) {
            if (is_readable($f)) { $c = include $f; if (is_array($c)) { $cfg = $c; break; } }
        }
        return $cfg;
    }

    /** POST JSON to the CMS. Returns [http code, decoded body or null]; code 0 when not connected. */
    function hgt_cms_call($path, array $data)
    {
        $cfg = hgt_cms_config();
        if (!$cfg || empty($cfg['intake_token']) || !function_exists('curl_init')) return array(0, null);
        $url = rtrim(!empty($cfg['cms_url']) ? $cfg['cms_url'] : 'https://cms.holidaygurutravel.in', '/') . $path;
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'X-HG-Intake-Token: ' . $cfg['intake_token']),
            CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_TIMEOUT => 4,
        ));
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($code === 0 || $code >= 500) error_log('hgt cms ' . $path . ': ' . ($err ?: 'HTTP ' . $code));
        return array($code, is_string($body) ? json_decode($body, true) : null);
    }

    function hgt_cms_forward(array $data)
    {
        if (hgt_cms_local()) {
            try {
                $r = cms_intake_enquiry($data);
                if (isset($r['enquiry_no'])) return $r['enquiry_no'];
                error_log('hgt cms intake: ' . (isset($r['error']) ? $r['error'] : 'not saved'));
                return '';
            } catch (Throwable $x) {
                error_log('hgt cms intake (local): ' . $x->getMessage());
                return '';
            }
        }
        list($code, $res) = hgt_cms_call('/api/enquiries', $data);
        if ($code !== 201) { if ($code) error_log('hgt cms intake: HTTP ' . $code); return ''; }
        return is_array($res) && isset($res['enquiry_no']) && preg_match('/^HGT-E-\d{5,}$/', $res['enquiry_no']) ? $res['enquiry_no'] : '';
    }

    /** ['ok' => array] | ['error' => 'not_found' | 'unavailable'] */
    function hgt_cms_status($enquiryNo, $contact)
    {
        if (hgt_cms_local()) {
            try {
                $r = cms_enquiry_status(array('enquiry_no' => $enquiryNo, 'contact' => $contact));
                return $r ? array('ok' => $r) : array('error' => 'not_found');
            } catch (Throwable $x) {
                error_log('hgt cms status (local): ' . $x->getMessage());
                return array('error' => 'unavailable');
            }
        }
        list($code, $res) = hgt_cms_call('/api/enquiries/status', array('enquiry_no' => $enquiryNo, 'contact' => $contact));
        if ($code === 200 && is_array($res)) return array('ok' => $res);
        return array('error' => $code === 404 ? 'not_found' : 'unavailable');
    }
}
