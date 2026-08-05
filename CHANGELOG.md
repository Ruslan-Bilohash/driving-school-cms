# Changelog

All notable changes to **Driving School CMS** are documented in this file.

The version source of truth is `VERSION` (mirrored in `cms/VERSION`).

Format inspired by [Keep a Changelog](https://keepachangelog.com/).  
Versioning follows [Semantic Versioning](https://semver.org/).

---

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
