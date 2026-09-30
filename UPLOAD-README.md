# Holiday Guru Travel — site update v5 (30 Sep 2026)

Replaces v4 (and v3 with every patch before it). Upload the whole public_html again.

## New in v5
- Company pages from your page pack, built into the site design: Why Us (/why-us), Careers (/career),
  Blog (/blog, draft) and Disclaimer (/disclaimer, draft); About, Leadership, Our Team and Payment Policy updated.
  The pack's old company name, CIN and old phone number are not used anywhere.
- Menu: "Enquire Now" (logo-orange pill) replaces "Contact Us" and opens an enquiry window on the right side of the
  screen (30% of the width; full screen on phones). Contact Us stays in the About Us menu and the footer.
- Homepage: title "Explore your Dream Destination with us" on one line; phones get an "Enquiry Now" button beside
  "Where do you want to go?".
- "Need help?" panel: Enquire Now, Chat with us, Email us, in the logo orange.
- Header: larger text-only script logo. Footer: animated plane badge (133 KB), "Holiday Guru Travel" with
  "Your Journey Our Expertise" under it, the same on phone and desktop.
- Draft pages (Blog, Disclaimer) are visible by direct link only: hidden from Google, the menu and the footer.
  To publish one, change 'draft' to 'approved' in public_html/include/data/page-status.php.

## Already in v4 (included again)
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
Still open (owner):
- Bank and UPI details for the Payment Policy page: HG_BANK_* in public_html/include/site_config.php.
- Confirm leadership titles (Founder / Managing Director) and the founding year (2014 or 2015).
- A privacy policy page (the enquiry forms collect personal details).
- Legal review of the Disclaimer before approving it.
