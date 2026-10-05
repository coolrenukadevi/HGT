<?php
/** Small view helpers: icons, pills, form fields. */

function icon($name, $cls = '')
{
    static $p = array(
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'x' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
        'eye' => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
        'save' => '<path d="M5 3h11l3 3v15H5z"/><path d="M8 3v5h8V3M8 21v-7h8v7"/>',
        'send' => '<path d="M4 12l16-8-6 16-2-7z"/>',
        'check' => '<path d="M5 12l5 5 9-10"/>',
        'alert' => '<path d="M12 3l10 18H2z"/><path d="M12 10v5M12 18v.5"/>',
        'dot' => '<circle cx="12" cy="12" r="4"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'copy' => '<rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8V5a1 1 0 00-1-1H5a1 1 0 00-1 1v10a1 1 0 001 1h3"/>',
        'trash' => '<path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13"/>',
        'up' => '<path d="M12 19V5M5 12l7-7 7 7"/>',
        'down' => '<path d="M12 5v14M5 12l7 7 7-7"/>',
        'grip' => '<circle cx="9" cy="6" r="1.2"/><circle cx="15" cy="6" r="1.2"/><circle cx="9" cy="12" r="1.2"/><circle cx="15" cy="12" r="1.2"/><circle cx="9" cy="18" r="1.2"/><circle cx="15" cy="18" r="1.2"/>',
        'image' => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M21 17l-6-6-9 9"/>',
        'upload' => '<path d="M12 16V4M6 10l6-6 6 6M4 20h16"/>',
        'download' => '<path d="M12 4v12M6 10l6 6 6-6M4 20h16"/>',
        'star' => '<path d="M12 3l2.8 5.8 6.2.9-4.5 4.4 1 6.2L12 17.4 6.5 20.3l1-6.2L3 9.7l6.2-.9z"/>',
        'crop' => '<path d="M6 2v16h16M2 6h16v16"/>',
        'lock' => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/>',
        'box' => '<path d="M3 7l9-4 9 4v10l-9 4-9-4z"/><path d="M3 7l9 4 9-4M12 11v10"/>',
        'route' => '<circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M8 19h6a4 4 0 000-8h-4a4 4 0 010-8h6"/>',
        'tag' => '<path d="M3 12V3h9l9 9-9 9z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
        'rupee' => '<path d="M6 4h12M6 9h12M6 4c7 0 7 10 0 10l8 7"/>',
        'history' => '<path d="M3 12a9 9 0 103-6.7L3 8"/><path d="M3 3v5h5M12 7v5l3 3"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1L7 17M17 7l2.1-2.1"/>',
        'inbox' => '<path d="M3 13l3-8h12l3 8v6H3z"/><path d="M3 13h5l1 3h6l1-3h5"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0116 0"/>',
        'phone' => '<path d="M5 3h4l2 5-3 2a11 11 0 006 6l2-3 5 2v4a2 2 0 01-2 2A18 18 0 013 5a2 2 0 012-2z"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9M18 14v6H4V6h6"/>',
        'more' => '<circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>',
        'archive' => '<rect x="3" y="4" width="18" height="4"/><path d="M5 8v12h14V8M10 12h4"/>',
        'pause' => '<path d="M8 5v14M16 5v14"/>',
        'restore' => '<path d="M3 12a9 9 0 109-9 9 9 0 00-6.4 2.6L3 8"/><path d="M3 3v5h5"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/>',
        'chevron' => '<path d="M6 9l6 6 6-6"/>',
        'bell' => '<path d="M6 8a6 6 0 1112 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 003.4 0"/>',
        'left' => '<path d="M15 18l-6-6 6-6"/>',
        'right' => '<path d="M9 18l6-6-6-6"/>',
    );
    return '<svg class="cms-i ' . e($cls) . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . (isset($p[$name]) ? $p[$name] : '') . '</svg>';
}

function status_pill($status)
{
    $label = isset(HG_STATUSES[$status]) ? HG_STATUSES[$status] : $status;
    return '<span class="cms-pill cms-pill--' . e($status) . '">' . e($label) . '</span>';
}

/** Package ID as shown inside the CMS. */
function pkg_id_badge(array $p, $long = false)
{
    list($id, $state) = pkg_id_state($p);
    if ($state === 'approved') return '<span class="cms-pid"><span class="cms-pid__k">Package ID</span> <b>' . e($id) . '</b></span>';
    if ($state === 'proposed') return '<span class="cms-pid cms-pid--proposed" title="Proposed in the migration mapping. Not assigned until the owner approves it; never shown publicly."><span class="cms-pid__k">Package ID</span> <b>' . e($id) . '</b> <small>' . ($long ? 'Proposed · pending owner approval' : 'Proposed') . '</small></span>';
    return '<span class="cms-pid cms-pid--pending"><span class="cms-pid__k">Package ID</span> <small>Pending approval</small></span>';
}

function field_text($name, $label, $value, array $o = array())
{
    $id = isset($o['id']) ? $o['id'] : 'f-' . preg_replace('/[^a-z0-9_]+/i', '-', $name);
    $attrs = '';
    foreach (array('maxlength', 'placeholder', 'min', 'max', 'step', 'pattern', 'inputmode', 'autocomplete') as $a) if (isset($o[$a])) $attrs .= ' ' . $a . '="' . e($o[$a]) . '"';
    if (!empty($o['required'])) $attrs .= ' required';
    if (!empty($o['readonly'])) $attrs .= ' readonly';
    if (!empty($o['disabled'])) $attrs .= ' disabled';
    if (!empty($o['counter'])) $attrs .= ' data-cms-count="' . e($o['counter']) . '"';
    $hint = isset($o['hint']) ? '<small class="cms-hint" id="' . $id . '-h">' . $o['hint'] . '</small>' : '';
    if ($hint) $attrs .= ' aria-describedby="' . $id . '-h"';
    $type = isset($o['type']) ? $o['type'] : 'text';
    $req = !empty($o['required']) ? ' <span class="cms-req" aria-hidden="true">*</span>' : '';
    if ($type === 'textarea') {
        $input = '<textarea id="' . $id . '" name="' . e($name) . '" rows="' . (isset($o['rows']) ? (int) $o['rows'] : 3) . '"' . $attrs . '>' . e($value) . '</textarea>';
    } else {
        $input = '<input id="' . $id . '" name="' . e($name) . '" type="' . e($type) . '" value="' . e($value) . '"' . $attrs . '>';
    }
    return '<div class="cms-field' . (isset($o['class']) ? ' ' . e($o['class']) : '') . '"><label for="' . $id . '">' . e($label) . $req . '</label>' . $input . $hint . '</div>';
}

function field_select($name, $label, $value, array $options, array $o = array())
{
    $id = isset($o['id']) ? $o['id'] : 'f-' . preg_replace('/[^a-z0-9_]+/i', '-', $name);
    $h = '';
    foreach ($options as $k => $v) {
        if (is_int($k)) $k = $v;
        $h .= '<option value="' . e($k) . '"' . ((string) $k === (string) $value ? ' selected' : '') . '>' . e($v === '' ? '— Select —' : $v) . '</option>';
    }
    $req = !empty($o['required']) ? ' <span class="cms-req" aria-hidden="true">*</span>' : '';
    $attrs = (!empty($o['required']) ? ' required' : '') . (!empty($o['disabled']) ? ' disabled' : '') . (isset($o['data']) ? ' ' . $o['data'] : '');
    $hint = isset($o['hint']) ? '<small class="cms-hint" id="' . $id . '-h">' . $o['hint'] . '</small>' : '';
    if ($hint) $attrs .= ' aria-describedby="' . $id . '-h"';
    return '<div class="cms-field' . (isset($o['class']) ? ' ' . e($o['class']) : '') . '"><label for="' . $id . '">' . e($label) . $req . '</label><select id="' . $id . '" name="' . e($name) . '"' . $attrs . '>' . $h . '</select>' . $hint . '</div>';
}

function field_check($name, $label, $checked, $value = '1', array $o = array())
{
    $id = isset($o['id']) ? $o['id'] : 'f-' . preg_replace('/[^a-z0-9_]+/i', '-', $name . '-' . $value);
    return '<label class="cms-check" for="' . $id . '"><input type="checkbox" id="' . $id . '" name="' . e($name) . '" value="' . e($value) . '"' . ($checked ? ' checked' : '') . (!empty($o['disabled']) ? ' disabled' : '') . '><span>' . e($label) . '</span></label>';
}

function card($title, $body, array $o = array())
{
    $id = isset($o['id']) ? ' id="' . e($o['id']) . '"' : '';
    $hid = 'c-' . substr(md5($title . (isset($o['id']) ? $o['id'] : '')), 0, 8);
    $sub = isset($o['sub']) ? '<p class="cms-card__sub">' . $o['sub'] . '</p>' : '';
    $act = isset($o['actions']) ? '<div class="cms-card__actions">' . $o['actions'] . '</div>' : '';
    return '<section class="cms-card' . (isset($o['class']) ? ' ' . e($o['class']) : '') . '"' . $id . ' aria-labelledby="' . $hid . '"><header class="cms-card__head"><div><h2 class="cms-card__title" id="' . $hid . '">' . e($title) . '</h2>' . $sub . '</div>' . $act . '</header>' . $body . '</section>';
}

function page_head($title, $sub = '', $right = '', $meta = '')
{
    return '<div class="cms-pagehead"><div class="cms-pagehead__text"><h1>' . e($title) . '</h1>' . ($sub ? '<p>' . e($sub) . '</p>' : '') . ($meta ? '<div class="cms-pagehead__meta">' . $meta . '</div>' : '') . '</div>' . ($right ? '<div class="cms-pagehead__actions">' . $right . '</div>' : '') . '</div>';
}

function empty_state($title, $text, $action = '')
{
    return '<div class="cms-empty">' . icon('box') . '<p class="cms-empty__t">' . e($title) . '</p><p>' . $text . '</p>' . $action . '</div>';
}

/* ---------- Dashboard charts (plain HTML, no library) ----------
 * One series per chart, so one hue (brand blue, validated against the white card) and no legend: the card title
 * names the series. Every mark has a hover/focus tooltip, values are direct-labelled on bars, and each chart has a
 * "View as table" fallback so nothing depends on colour or hover alone. */

/** Horizontal bars. $rows: list of [label, value, href|null]. */
function chart_hbars(array $rows, $unit, $caption)
{
    if (!$rows) return '<p class="cms-muted">No data yet.</p>';
    $max = max(1, max(array_map(function ($r) { return (int) $r[1]; }, $rows)));
    $h = '<ul class="cms-hbar" aria-hidden="true">';
    foreach ($rows as $r) {
        $tip = $r[0] . ': ' . (int) $r[1] . ' ' . $unit;
        $label = $r[2] ? '<a href="' . e($r[2]) . '" tabindex="-1">' . e($r[0]) . '</a>' : e($r[0]);
        $h .= '<li><span class="cms-hbar__l">' . $label . '</span><span class="cms-hbar__track" data-tip="' . e($tip) . '">'
            . '<span class="cms-hbar__fill" style="width:' . ((int) $r[1] ? max(1, round(100 * (int) $r[1] / $max, 1)) : 0) . '%"></span></span>'
            . '<span class="cms-hbar__n">' . (int) $r[1] . '</span></li>';
    }
    return $h . '</ul>' . chart_table($rows, $caption, ucfirst($unit));
}

/** Columns over time. $rows: list of [Y-m-d, value]. */
function chart_columns(array $rows, $unit, $caption)
{
    $max = max(1, max(array_map(function ($r) { return (int) $r[1]; }, $rows)));
    $total = array_sum(array_map(function ($r) { return (int) $r[1]; }, $rows));
    $h = '<div class="cms-cols" aria-hidden="true"><div class="cms-cols__plot">';
    foreach ($rows as $r) {
        $tip = date('D d M', strtotime($r[0])) . ': ' . (int) $r[1] . ' ' . $unit;
        $h .= '<span class="cms-cols__col" data-tip="' . e($tip) . '"><span class="cms-cols__fill" style="height:' . ((int) $r[1] ? max(2, round(100 * (int) $r[1] / $max, 1)) : 0) . '%"></span></span>';
    }
    $first = reset($rows); $last = end($rows);
    $h .= '</div><div class="cms-cols__axis"><span>' . e(date('d M', strtotime($first[0]))) . '</span><span>' . e(date('d M', strtotime($last[0]))) . '</span></div></div>';
    if (!$total) $h .= '<p class="cms-muted cms-chart__empty">No ' . e($unit) . ' in this period yet.</p>';
    $tbl = array_map(function ($r) { return array(date('d M Y', strtotime($r[0])), $r[1], null); }, $rows);
    return $h . chart_table($tbl, $caption, ucfirst($unit));
}

function chart_table(array $rows, $caption, $valueHead)
{
    $t = '<details class="cms-chart__table"><summary>View as table</summary><table class="cms-table"><caption class="cms-sr">' . e($caption) . '</caption><thead><tr><th scope="col">Item</th><th scope="col" class="cms-num">' . e($valueHead) . '</th></tr></thead><tbody>';
    foreach ($rows as $r) $t .= '<tr><td>' . e($r[0]) . '</td><td class="cms-num">' . (int) $r[1] . '</td></tr>';
    return $t . '</tbody></table></details>';
}

/** Rounded axis maximum and step (1/2/5 × 10^k) so gridline labels are whole, readable numbers. */
function chart_nice_max($max, $ticks = 4)
{
    $max = max(1, $max);
    $raw = $max / $ticks; $mag = pow(10, floor(log10($raw))); $step = $mag;
    foreach (array(1, 2, 5, 10) as $m) { if ($m * $mag >= $raw) { $step = $m * $mag; break; } }
    $step = max(1, $step);
    return array($step * ceil($max / $step), $step);
}

/**
 * Smooth line + area over time (one series). $rows: list of [Y-m-d, value]. Monotone cubic curve, so it never
 * dips below zero or overshoots a value. Hover readout (guide line, dot, tooltip) is added by cms.js.
 */
function chart_line(array $rows, $unit, $caption)
{
    $n = count($rows); $W = 1000; $H = 300;
    $vals = array_map(function ($r) { return (int) $r[1]; }, $rows);
    list($top, $step) = chart_nice_max(max($vals));
    $pt = array();
    foreach ($vals as $i => $v) $pt[] = array($n > 1 ? $i * $W / ($n - 1) : 0, $H - $H * $v / $top);
    // Monotone cubic (Fritsch–Carlson) tangents
    $d = array(); $m = array();
    for ($i = 0; $i < $n - 1; $i++) $d[$i] = ($pt[$i + 1][1] - $pt[$i][1]) / ($pt[$i + 1][0] - $pt[$i][0]);
    for ($i = 0; $i < $n; $i++) {
        if ($i === 0) $m[$i] = $d[0]; elseif ($i === $n - 1) $m[$i] = $d[$n - 2];
        else $m[$i] = ($d[$i - 1] * $d[$i] <= 0) ? 0 : ($d[$i - 1] + $d[$i]) / 2;
    }
    for ($i = 0; $i < $n - 1; $i++) {
        if ($d[$i] == 0) { $m[$i] = $m[$i + 1] = 0; continue; }
        $a = $m[$i] / $d[$i]; $b = $m[$i + 1] / $d[$i]; $s = $a * $a + $b * $b;
        if ($s > 9) { $t = 3 / sqrt($s); $m[$i] = $t * $a * $d[$i]; $m[$i + 1] = $t * $b * $d[$i]; }
    }
    $f = function ($x) { return round($x, 1); };
    $path = 'M' . $f($pt[0][0]) . ' ' . $f($pt[0][1]);
    for ($i = 0; $i < $n - 1; $i++) {
        $h = ($pt[$i + 1][0] - $pt[$i][0]) / 3;
        $path .= ' C' . $f($pt[$i][0] + $h) . ' ' . $f($pt[$i][1] + $m[$i] * $h) . ' ' . $f($pt[$i + 1][0] - $h) . ' ' . $f($pt[$i + 1][1] - $m[$i + 1] * $h) . ' ' . $f($pt[$i + 1][0]) . ' ' . $f($pt[$i + 1][1]);
    }
    $area = $path . ' L' . $W . ' ' . $H . ' L0 ' . $H . ' Z';
    $data = array_map(function ($r) { return array(date('D d M', strtotime($r[0])), (int) $r[1]); }, $rows);
    $grid = '';
    for ($v = 0; $v <= $top; $v += $step) $grid .= '<span class="cms-line__g" style="bottom:' . round(100 * $v / $top, 2) . '%"><span>' . $v . '</span></span>';
    $first = reset($rows); $last = end($rows);
    $h = '<div class="cms-line" data-cms-line="' . e(json_encode($data)) . '" data-unit="' . e($unit) . '" aria-hidden="true">'
       . '<div class="cms-line__plot">' . $grid
       . '<svg viewBox="0 0 ' . $W . ' ' . $H . '" preserveAspectRatio="none" focusable="false"><defs><linearGradient id="cmsLineFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#1689D8" stop-opacity=".22"/><stop offset="1" stop-color="#1689D8" stop-opacity=".02"/></linearGradient></defs>'
       . '<path class="cms-line__area" d="' . $area . '"/><path class="cms-line__stroke" d="' . $path . '"/></svg>'
       . '<span class="cms-line__guide" hidden></span><span class="cms-line__dot" hidden></span><span class="cms-line__tip" hidden></span></div>'
       . '<div class="cms-line__axis"><span>' . e(date('d M', strtotime($first[0]))) . '</span><span>' . e(date('d M', strtotime($last[0]))) . '</span></div></div>';
    if (!array_sum($vals)) $h .= '<p class="cms-muted cms-chart__empty">No ' . e($unit) . ' in this period yet.</p>';
    $tbl = array_map(function ($r) { return array(date('d M Y', strtotime($r[0])), $r[1], null); }, $rows);
    return $h . chart_table($tbl, $caption, ucfirst($unit));
}
