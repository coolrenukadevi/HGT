# Holiday Guru Travel — site update v4 (30 Sep 2026)

Replaces v3 and every patch sent since (mail patch, header patches v1–v3, site patches v4–v7).
Upload the whole public_html again.

## New in v4
- Header: compact (87px instead of 127px); text-only "Holiday Guru Travel" script logo (larger on desktop,
  fills the header on phones); Call and WhatsApp as icons only; compact mega menus.
- Top bar: "Secure SSL-encrypted website", 24×7 Support (WhatsApp) and a Login menu
  (Login / Sign up, Check my booking, Email us).
- Homepage: photo slider with the title only — "Your journey." (logo orange) "Our expertise." (logo navy),
  photos lightened behind the title; intro text, buttons and benefit points removed.
- Cookie banner: not shown on phones/tablets (analytics stays off there); slim full-width bar on laptops.
- Footer: larger round logo, "Guru Travel" in the logo orange, justified intro and contact details,
  policy links on the right.
- Mail: Hostinger SMTP (smtp.hostinger.com, port 587, TLS/STARTTLS, info@holidaygurutravel.in).
  Enquiry emails are sent from and to info@holidaygurutravel.in.

## Already in v2/v3 (included again)
- Mega menus (Domestic / International / Inbound Tours / Special Tours / Fixed Departure / About Us),
  redesigned footer, versioned CSS/JS links (no Ctrl+F5 needed after uploads).
- © 2014–{year} M/S Holiday Guru Travel; old entity name and CIN removed. Mobile + WhatsApp +91 80066 92040.
- "Preparing Your Journey" loader, image loading effects.

## Upload
1. Back up the current public_html.
2. Upload the contents of public_html/ over the site root (existing photos in assets/img/ are kept).
3. Mail: create or edit hgt-config.php in the folder ABOVE public_html (e.g. /home/<user>/hgt-config.php)
   from config/hgt-config.example.php and put your SMTP password in place of CHANGE_ME. Until then the forms
   work but no email is sent. Never put this file inside public_html.
4. Optional: php build_images.php public_html/assets/img  — creates fast WebP sizes for your existing photos.
5. Open the homepage and check it loads over https:// (the top bar says "Secure SSL-encrypted website").

Not included, on purpose: the staging CMS (cms/), tests, any passwords or credentials.
Still open (owner action): set a new, strong SMTP password (the earlier one was shared in chat).
Still open: the "What our travellers say" section shows a development placeholder; send genuine reviews or ask
for the section to be removed.
