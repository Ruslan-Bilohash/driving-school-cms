# Changelog

All notable changes to **Driving School CMS** are documented in this file.

The version source of truth is `VERSION` (mirrored in `cms/VERSION`).

Format inspired by [Keep a Changelog](https://keepachangelog.com/).  
Versioning follows [Semantic Versioning](https://semver.org/).

---

## [1.1.0] — 2026-11-08

### Added — Marketing module (`/cms/admin/marketing.php`)
- **Overview dashboard** — requests per day, status funnel (new → contacted → done), conversion rate,
  share of requests with source data, breakdowns by channel, source, campaign, licence category,
  landing page and language; periods 7 / 30 / 90 / 365 days / all time
- **Lead attribution** — `assets/js/marketing.js` captures `utm_*`, external referrer, landing page and
  click IDs (`gclid`, `fbclid`, `msclkid`, `ttclid`) and sends them with the contact form and callback popup;
  every new request in `orders.json` gets a `marketing` block with an auto-detected channel
  (paid, social, organic, email, referral, campaign, direct)
- **Analytics & pixels** — Google Analytics 4, Google Tag Manager and Meta Pixel by ID only (validated),
  optional loading only after cookie consent (GDPR); conversion events `generate_lead` / `Lead`,
  `contact_click` / `Contact` (phone, email, WhatsApp, Telegram), `promo_click`, `promo_close` + `dataLayer` events
- **Promo banner** — bar above the header on all public pages: per-language text and button,
  4 colour styles, start/end dates, dismissible (re-appears when the text changes), live preview in admin;
  on the demo only on-site links are allowed
- **UTM link builder** — presets (Facebook, Instagram, Google Ads, TikTok, Snapchat, FINN.no, newsletter, flyer/QR)
- **CSV export** of requests with attribution (UTF-8 BOM, `;` separator, formula-injection safe)
- Source / campaign chip on every request in **Admin → Requests**; marketing actions in the activity log
- Admin UI translations: EN, UK, RU, NO (SV / PL / LT fall back to English)

### Fixed
- `api/contact.php` — UTF-8 BOM before `declare(strict_types=1)` caused a fatal error, so no request could be sent;
  file rewritten: correct UTF-8 messages, Norwegian (`nb` / `no`), Swedish and Polish messages, site name instead
  of old branding, request counts as successful once it is saved in the admin
- Contact / callback / chat forms posted to `/api/...` ignoring `BASE_PATH` — now use `window.DS_BASE`
- UTF-8 BOM removed from 38 PHP files — every public page started with a BOM, which pushed `<title>` and meta
  tags out of `<head>` (SEO) and also broke `admin/ai-generate.php`
- Admin: outline buttons were unreadable on hover; top menu wraps instead of hiding "Log out"

## [1.0.0] — 2026-08-05

First public open-source release on GitHub: [Ruslan-Bilohash/driving-school-cms](https://github.com/Ruslan-Bilohash/driving-school-cms).

### Added

#### Product landing (`/driving-school/`)
- Multi-language product page (EN, NO, SV, PL, UK, RU)
- Sections: features, modules, admin, Schema/SEO, screenshots, demo access, full description
- Links to live CMS demo and admin
- Screenshot gallery assets under `screenshot/`

#### Public CMS (`/cms/`)
- Home page with hero, categories, NOK price tables, FAQ, contact
- Licence category pages (B, A, BE, C, CE, CCE/CCED, D, lost licence, …)
- District / area SEO pages (local landings)
- News list + post pages (multi-language JSON)
- Gallery
- Privacy & terms
- Language routes: `en/`, `uk/`, `ru/`, plus PL/SV shells
- Schema.org DrivingSchool-oriented markup, `sitemap.php` / `sitemap.xml`, `robots.txt`, `llms.txt`

#### Admin panel (`/cms/admin/`)
- Login / logout (demo credentials on portfolio)
- Dashboard
- Leads / orders pipeline
- Categories management
- News management (+ AI generate hooks; keys empty by default)
- Gallery management + image upload
- Settings (school contact, hours, multi-lang descriptions, prices)
- Payment / billing portal notes (client cabinet link) — no live payment secrets
- Activity log

#### Technical
- PHP 8+, JSON file storage under `cms/inc/data/` (no MySQL required)
- Central `cms/inc/config.php` with `BASE_PATH` / `SITE_URL` (demo points to bilohash.com)
- `DS_DEMO_MODE` disables real Telegram / payment side-effects on portfolio
- Multi-language public + admin UI packs
- Cron helpers: cleanup, seed-demo

### Security
- **No live API keys** in repository: `openai_key`, `grok_key`, reCAPTCHA, Telegram token empty / disabled
- Demo admin password only for portfolio (`demo` / `demo`)
- Documented: never commit secrets; configure only on private servers

### Demo
- Live landing: https://bilohash.com/driving-school/
- Live CMS: https://bilohash.com/driving-school/cms/
- Admin: https://bilohash.com/driving-school/cms/admin/login.php (`demo` / `demo`)
- Fictional school: **Nordisk Trafikkskole**, Oslo, NOK prices

### Documentation
- README.md (English) + readme-ua / ru / no / lt
- CHANGELOG.md, VERSION
- `docs/screenshots/` for GitHub README embeds

---

## Pre-1.0.0 (internal)

- Portfolio integration on bilohash.com
- Rewrite for Norway market from earlier Autoteisės-style stack
- News article: https://bilohash.com/news/driving-school-cms.html
