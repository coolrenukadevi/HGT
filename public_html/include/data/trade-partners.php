<?php
/**
 * "Our Trade Partners" marquee on the homepage (include/global/trade-partners.php). Owner-supplied logos, 2026-10-01.
 * The section is hidden when this list is empty.
 *
 * Each entry:
 *   'name' => partner name, used as the logo's alt text (and shown as text if the logo file is missing)  (required)
 *   'logo' => file name in /assets/img/partners/ without extension; upload both NAME.png and NAME.webp    (optional)
 *   'url'  => official website, opens in a new tab                                                       (optional)
 */
return array(
    array('name' => 'Hindustan', 'logo' => 'hindustan'),
    array('name' => 'Dainik Roorkee', 'logo' => 'dainik-roorkee'),
    array('name' => 'Sudarshan News', 'logo' => 'sudarshan-news'),
    array('name' => 'Bloomberg', 'logo' => 'bloomberg'),
    array('name' => 'ISSO India (International Schools Sports Organisation)', 'logo' => 'isso-india'),
    array('name' => 'Infinity Foundation', 'logo' => 'infinity-foundation'),
    array('name' => "'D' India", 'logo' => 'd-india'),
    array('name' => 'KEI Wires & Cables', 'logo' => 'kei-wires-cables'),
    array('name' => 'UTEN', 'logo' => 'uten'), // logo pending: owner to send the image as a file
);
