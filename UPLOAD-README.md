# Holiday Guru Travel — site update v3 (commit 7205b7e, 30 Sep 2026)

Replaces v2. Upload the whole public_html again: many shared files changed.

## New in v3
- Homepage slider fixed: your screenshot showed the new page with the OLD cached stylesheet (photo inset on navy,
  text missing). CSS/JS links now carry a version (?v=…), so browsers always load the current files after an upload.
  The photos run edge to edge under the header tabs.
- Header: redesigned mega menus — Domestic and International with region tabs, destinations and cities;
  new Inbound Tours, Fixed Departure and About Us menus. All tabs now in one font weight.
- Footer: redesigned to your reference (brand + tagline, coloured social icons, Tours / Destinations / Company /
  Support / Contact columns, © line with policy links).

## Already in v2 (included again)
- © 2014–{year} M/S Holiday Guru Travel; old entity name and CIN removed. Mobile + WhatsApp +91 80066 92040.
- "Preparing Your Journey" loader (first page of a visit), image loading effects, homepage photo slider.

## Upload
1. Back up the current public_html.
2. Upload the contents of public_html/ over the site root (existing photos in assets/img/ are kept).
3. Keep your real hgt-config.php as it is; config/hgt-config.example.php is only a template.
4. Optional: php build_images.php public_html/assets/img  — creates fast WebP sizes for your existing photos.
5. Open the homepage once; if the old look still shows, press Ctrl+F5 (Cmd+Shift+R on Mac). From now on the
   version tags make this unnecessary.

Not included, on purpose: the staging CMS (cms/), tests, any passwords or credentials.
Still open (owner action): rotate the SMTP password that was exposed earlier.

## Mail settings (30 Sep 2026, after v3)
- Mail defaults now point to Hostinger: smtp.hostinger.com, port 587, TLS/STARTTLS, login info@holidaygurutravel.in
  (public_html/include/mail_helper.php and config/hgt-config.example.php).
- The SMTP password goes only into hgt-config.php ABOVE public_html on the server. Until it is set, forms work but
  no email is sent.
