<?php
/**
 * HOMEPAGE COMPONENT: "Our Trade Partners" logo marquee, shown above the footer on the homepage only (index.php).
 * Data: include/data/trade-partners.php; hidden when the list is empty. A logo is used only if its PNG exists,
 * otherwise the name is shown as text. The list is rendered twice for a seamless loop; the copy is hidden from
 * screen readers and keyboard. Styles: assets/css/src/components/trade-partners.css (motion off for reduced-motion).
 */
$hgTp = array_values(array_filter((array) include dirname(__DIR__) . '/data/trade-partners.php', function ($e) { return !empty($e['name']); }));
if ($hgTp) {
    $hgTpDir = dirname(__DIR__, 2) . '/assets/img/partners/';
    $hgTpItems = function ($copy) use ($hgTp, $hgTpDir) {
        $out = '';
        foreach ($hgTp as $e) {
            $logo = !empty($e['logo']) ? basename($e['logo']) : '';
            $png = $logo !== '' && is_file($hgTpDir . $logo . '.png') ? $hgTpDir . $logo . '.png' : '';
            if ($png !== '') {
                $sz = @getimagesize($png);
                $alt = $copy ? '' : $e['name'];
                $inner = '<picture>' . (is_file($hgTpDir . $logo . '.webp') ? '<source type="image/webp" srcset="' . hg_e(hg_asset('/assets/img/partners/' . $logo . '.webp')) . '">' : '')
                    . '<img src="' . hg_e(hg_asset('/assets/img/partners/' . $logo . '.png')) . '" alt="' . hg_e($alt) . '"'
                    . ($sz ? ' width="' . (int) $sz[0] . '" height="' . (int) $sz[1] . '"' : '') . ' fetchpriority="low" decoding="async"></picture>';
            } else {
                $inner = '<span class="hg-tp__name">' . hg_e($e['name']) . '</span>';
            }
            $out .= '<li class="hg-tp__item">' . (!empty($e['url'])
                ? '<a class="hg-tp__logo" href="' . hg_e($e['url']) . '" target="_blank" rel="noopener"' . ($copy ? ' tabindex="-1"' : '') . '>' . $inner . '</a>'
                : '<span class="hg-tp__logo">' . $inner . '</span>') . '</li>';
        }
        return $out;
    };
    ?>
<section class="hg-section hg-section--tight hg-tp" aria-labelledby="hg-tp-title">
    <div class="hg-container">
        <p class="hg-eyebrow">Trade partners</p>
        <h2 class="hg-h2" id="hg-tp-title">Our Trade Partners</h2>
    </div>
    <div class="hg-tp__marquee">
        <div class="hg-tp__track">
            <ul class="hg-tp__list"><?= $hgTpItems(false) ?></ul>
            <ul class="hg-tp__list" aria-hidden="true"><?= $hgTpItems(true) ?></ul>
        </div>
    </div>
</section>
<?php
}
