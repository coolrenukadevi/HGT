# Holiday Guru Travel — working rules (owner instructions)

Website: `public_html/` (PHP). CMS/CRM: `cms/`. These rules come from the owner and apply to every session.

## Git, deploy, secrets
- Commit or push only with the owner's explicit approval. Never deploy; the owner uploads to Hostinger. Build ZIPs only on request. No PRs, no merge to main.
- Never put the SMTP password or any secret in code, the repo or chat. Secrets live only in `hgt-config.php` above public_html. `cms/config.php` and `cms/storage/*` stay git-ignored; never ship cms/storage.
- Keep the unrelated mail work out of commits: `public_html/mail.php`, `public_html/include/mail_helper.php`, `UPLOAD-README.md`.

## Company and content
- Never use "Swaasthik Vocation Pvt. Ltd." or CIN U74999UP2021PTC154544. Company name on quotations: "M/S Holiday Guru Travel".
- No fabricated reviews, prices, ratings, offers, testimonials or synthetic images.
- Content priority: CRM CUSTOM > PACKAGE > HGT STANDARD.
- Standard: hotel "Standard / 3-star equivalent", meals Breakfast, the owner's standard inclusions/exclusions. No unsupported premium services (private cab, luxury, houseboats, tickets, guides, flights, insurance) unless the package supports them; optional extras are written as "paid locally".
- Days = Nights + 1. The suggested-plan note shows only for generated itineraries.
- Cancellation slabs: 30+ / 29–20 / 19–14 / 13–8 / ≤7 days before departure.

## URLs (owner rule 2026-10-05)
- Package URLs never contain numbers: no nights/days such as `4n-5d`, `4night`, `05-days`, `03nt04dy`.
- Pattern for new packages: `/{destination}-{route}`, unique; when two packages share a route, distinguish them by a real difference (e.g. `-with-kedarnath-night`, `-tsomgo-lake`), never by duration.
- Any URL change: rename the page file, update packages.json (slug, url, corrections note), package-registry.json (`previous_slugs`), seo-meta.php, sitemap.xml, search-index.json and content links; add a single-hop 301 in `.htaccess` (also `.php` and trailing-slash forms) and a row in `docs/url-migrations.csv`.

## Package programme
- Package IDs are append-only (`include/data/package-registry.json`); never renumber or reuse.
- Domestic expansion to 250 packages in batches of 25 (done). Owner rule 2026-10-08: domestic target 550, i.e. 300 more (0312–0611) in 5 phases of 60 (D1–D5), mostly new states; each phase is scored to 98+ with a report, and the next phase starts only after owner approval. IDs 0309–0311 stay reserved for Myanmar (proposed, unpublished).
- Photos: use a related photo from the existing library, otherwise leave `image` blank for the owner to add. Package images ≤ 960 px; never use hero images as package images.
