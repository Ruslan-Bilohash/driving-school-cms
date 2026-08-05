<?php
/**
 * Driving School CMS — full product landing (bilohash.com/driving-school/)
 */
declare(strict_types=1);

$base = '/driving-school';
$demo = $base . '/cms';
$admin = $demo . '/admin/login.php';
$site_url = 'https://bilohash.com' . $base;
$demo_url = 'https://bilohash.com' . $demo;
$year = (int) date('Y');

$lang = 'no';
if (!empty($_GET['lang']) && in_array($_GET['lang'], ['en', 'no', 'sv', 'pl', 'uk', 'ru'], true)) {
    $lang = $_GET['lang'];
}

$T = [
    'en' => [
        'html' => 'en',
        'title' => 'Driving School CMS — full PHP script for trafikkskoler | Norway SEO, admin, demo',
        'desc' => 'Driving School CMS: complete PHP product for driving schools — licence categories B–CE, leads, news AI, gallery, NOK prices, Schema.org DrivingSchool, multilingual admin. Live demo Nordisk Trafikkskole (Oslo). BILOHASH ecosystem.',
        'badge' => 'PHP · Driving School CMS · BILOHASH ecosystem',
        'h1' => 'Driving School CMS',
        'sub' => 'Full-stack PHP script for trafikkskoler and driving schools: public SEO site, category & district pages, lead pipeline, news, gallery, price tables in NOK and a complete admin panel — demo school in Oslo.',
        'cta_demo' => 'Open live demo (site)',
        'cta_admin' => 'Open admin panel',
        'cta_contact' => 'Order / discuss',
        'nav_features' => 'Features',
        'nav_modules' => 'Modules',
        'nav_admin' => 'Admin',
        'nav_schema' => 'SEO & Schema',
        'nav_shots' => 'Screenshots',
        'nav_demo' => 'Demo access',
        'nav_long' => 'Full description',
        'intro_h' => 'What is Driving School CMS?',
        'intro' => 'Driving School CMS is a standalone PHP product for driving schools (trafikkskoler / автошколи). It ships a marketing storefront plus JSON-backed admin — no heavy framework. The live demo is the fictional school Nordisk Trafikkskole in Oslo (NOK, Norwegian SEO). Stack proven on Autoteisės (Vilnius), rewritten for Norway and the BILOHASH ecosystem.',
        'features_h' => 'Key capabilities',
        'modules_h' => 'Script modules (public site)',
        'admin_h' => 'Admin panel modules',
        'schema_h' => 'SEO, microdata & technical markup',
        'shots_h' => 'Screenshots',
        'demo_h' => 'Live demo & login',
        'long_h' => 'Complete product specification',
        'prices_h' => 'Demo price list (NOK)',
        'use_h' => 'Who it is for',
        'stack_h' => 'Tech stack & requirements',
        'creds' => 'Admin credentials',
        'user' => 'Username',
        'pass' => 'Password',
        'footer' => 'Driving School CMS · BILOHASH · Demo only — fictional school · Not real services',
        'demo_site' => 'Public demo site',
        'demo_site_d' => 'Nordisk Trafikkskole — Oslo demo: hero, categories, prices, FAQ, contact, news, gallery. Languages: NO (default), EN, UK, RU.',
        'demo_admin_d' => 'Manage leads, categories, news, gallery, settings, activity log. Portfolio demo login.',
        'wrong_path' => 'Always use ASCII URL',
        'wrong_path_d' => 'Correct demo: bilohash.com/driving-school/cms/ — do not use Cyrillic “п” in the path.',
        'lang_label' => 'Language',
        'menu' => 'Menu',
        'close' => 'Close',
    ],
    'sv' => [
        'html' => 'sv',
        'title' => 'Driving School CMS — PHP-skript för körskolor | Norsk SEO, admin, demo',
        'desc' => 'Driving School CMS: komplett PHP för körskolor — klasser B–CE, leads, nyheter, galleri, NOK, Schema.org, flerspråkig admin. Live demo Nordisk Trafikkskole (Oslo).',
        'badge' => 'PHP · Driving School CMS · BILOHASH',
        'h1' => 'Driving School CMS',
        'sub' => 'Fullt PHP-skript för körskolor: SEO-webb, klasser, leads, nyheter, galleri, priser i NOK och komplett admin — demoskola i Oslo.',
        'cta_demo' => 'Öppna live demo (webb)',
        'cta_admin' => 'Öppna adminpanel',
        'cta_contact' => 'Beställ / diskutera',
        'nav_features' => 'Funktioner',
        'nav_modules' => 'Moduler',
        'nav_admin' => 'Admin',
        'nav_schema' => 'SEO & Schema',
        'nav_shots' => 'Skärmdumpar',
        'nav_demo' => 'Demo',
        'nav_long' => 'Full beskrivning',
        'intro_h' => 'Vad är Driving School CMS?',
        'intro' => 'Fristående PHP-produkt för körskolor: marknadsföringssida + JSON-admin. Demo: Nordisk Trafikkskole i Oslo (NOK, norsk SEO). BILOHASH-ekosystem.',
        'features_h' => 'Huvudfunktioner',
        'modules_h' => 'Moduler (offentlig sida)',
        'admin_h' => 'Adminmoduler',
        'schema_h' => 'SEO och mikrodata',
        'shots_h' => 'Skärmdumpar',
        'demo_h' => 'Live demo och inloggning',
        'long_h' => 'Full produktspecifikation',
        'prices_h' => 'Demo prislista (NOK)',
        'use_h' => 'Vem det passar',
        'stack_h' => 'Teknik och krav',
        'creds' => 'Admininloggning',
        'user' => 'Användarnamn',
        'pass' => 'Lösenord',
        'footer' => 'Driving School CMS · BILOHASH · Endast demo',
        'demo_site' => 'Offentlig demo',
        'demo_site_d' => 'Nordisk Trafikkskole — Oslo: klasser, priser, FAQ, kontakt, nyheter, galleri. NO/EN/SV/PL/UK/RU.',
        'demo_admin_d' => 'Leads, kategorier, nyheter, galleri, inställningar. Demo-login.',
        'wrong_path' => 'Använd alltid ASCII-URL',
        'wrong_path_d' => 'Rätt demo: bilohash.com/driving-school/cms/',
        'lang_label' => 'Språk',
        'menu' => 'Meny',
        'close' => 'Stäng',
    ],
    'pl' => [
        'html' => 'pl',
        'title' => 'Driving School CMS — pełny skrypt PHP dla szkół jazdy | SEO Norwegia, admin, demo',
        'desc' => 'Driving School CMS: pełny PHP dla szkół jazdy — kategorie B–CE, leady, newsy, galeria, NOK, Schema.org, wielojęzyczny admin. Demo Nordisk Trafikkskole (Oslo).',
        'badge' => 'PHP · Driving School CMS · ekosystem BILOHASH',
        'h1' => 'Driving School CMS',
        'sub' => 'Pełny skrypt PHP dla szkół jazdy: strona SEO, kategorie, leady, newsy, galeria, ceny NOK i pełny panel admina — demo w Oslo.',
        'cta_demo' => 'Otwórz live demo (strona)',
        'cta_admin' => 'Otwórz panel admina',
        'cta_contact' => 'Zamów / omów',
        'nav_features' => 'Możliwości',
        'nav_modules' => 'Moduły',
        'nav_admin' => 'Admin',
        'nav_schema' => 'SEO i Schema',
        'nav_shots' => 'Zrzuty ekranu',
        'nav_demo' => 'Dostęp demo',
        'nav_long' => 'Pełny opis',
        'intro_h' => 'Czym jest Driving School CMS?',
        'intro' => 'Osobny produkt PHP dla szkół jazdy: publiczna strona SEO + admin JSON. Live demo — fikcyjna szkoła Nordisk Trafikkskole w Oslo (NOK, SEO Norwegia). Ekosystem BILOHASH.',
        'features_h' => 'Kluczowe możliwości',
        'modules_h' => 'Moduły skryptu (strona publiczna)',
        'admin_h' => 'Moduły panelu admina',
        'schema_h' => 'SEO, mikrodata i technika',
        'shots_h' => 'Zrzuty ekranu',
        'demo_h' => 'Live demo i logowanie',
        'long_h' => 'Pełna specyfikacja produktu',
        'prices_h' => 'Cennik demo (NOK)',
        'use_h' => 'Dla kogo',
        'stack_h' => 'Stack i wymagania',
        'creds' => 'Dostęp do admina',
        'user' => 'Login',
        'pass' => 'Hasło',
        'footer' => 'Driving School CMS · BILOHASH · Tylko demo',
        'demo_site' => 'Publiczne demo',
        'demo_site_d' => 'Nordisk Trafikkskole — Oslo: kategorie, ceny, FAQ, kontakt, newsy, galeria. NO/EN/SV/PL/UK/RU.',
        'demo_admin_d' => 'Leady, kategorie, newsy, galeria, ustawienia. Login demo.',
        'wrong_path' => 'Tylko ASCII URL',
        'wrong_path_d' => 'Prawidłowe demo: bilohash.com/driving-school/cms/',
        'lang_label' => 'Język',
        'menu' => 'Menu',
        'close' => 'Zamknij',
    ],
    'no' => [
        'html' => 'nb',
        'title' => 'Driving School CMS — PHP-skript for trafikkskoler | Norsk SEO, admin, demo',
        'desc' => 'Driving School CMS: komplett PHP for kjøreskoler — klasser B–CE, leads, nyheter, galleri, NOK, Schema.org, flerspråklig admin. Live demo Nordisk Trafikkskole (Oslo).',
        'badge' => 'PHP · Driving School CMS · BILOHASH',
        'h1' => 'Driving School CMS',
        'sub' => 'Fullt PHP-skript for trafikkskoler: SEO-nettside, klasser, leads, nyheter, galleri, priser i NOK og komplett admin — demoskola i Oslo.',
        'cta_demo' => 'Åpne live demo (nettsted)',
        'cta_admin' => 'Åpne adminpanel',
        'cta_contact' => 'Bestill / diskuter',
        'nav_features' => 'Funksjoner',
        'nav_modules' => 'Moduler',
        'nav_admin' => 'Admin',
        'nav_schema' => 'SEO & Schema',
        'nav_shots' => 'Skjermbilder',
        'nav_demo' => 'Demo',
        'nav_long' => 'Full beskrivelse',
        'intro_h' => 'Hva er Driving School CMS?',
        'intro' => 'Frittstående PHP-produkt for trafikkskoler: markedsføringsside + JSON-admin. Demo: Nordisk Trafikkskole i Oslo (NOK, norsk SEO). BILOHASH-økosystem.',
        'features_h' => 'Hovedfunksjoner',
        'modules_h' => 'Moduler (offentlig side)',
        'admin_h' => 'Adminmoduler',
        'schema_h' => 'SEO og mikrodata',
        'shots_h' => 'Skjermbilder',
        'demo_h' => 'Live demo og innlogging',
        'long_h' => 'Full produktspesifikasjon',
        'prices_h' => 'Demo prisliste (NOK)',
        'use_h' => 'Hvem passer det for',
        'stack_h' => 'Teknologi og krav',
        'creds' => 'Admin-innlogging',
        'user' => 'Brukernavn',
        'pass' => 'Passord',
        'footer' => 'Driving School CMS · BILOHASH · Kun demo',
        'demo_site' => 'Offentlig demo',
        'demo_site_d' => 'Nordisk Trafikkskole — Oslo: klasser, priser, FAQ, kontakt, nyheter, galleri. NO/EN/UK/RU.',
        'demo_admin_d' => 'Leads, kategorier, nyheter, galleri, innstillinger. Demo-login.',
        'wrong_path' => 'Bruk alltid ASCII-URL',
        'wrong_path_d' => 'Riktig demo: bilohash.com/driving-school/cms/',
        'lang_label' => 'Språk',
        'menu' => 'Meny',
        'close' => 'Lukk',
    ],
    'uk' => [
        'html' => 'uk',
        'title' => 'Driving School CMS — повний PHP скрипт для автошкіл | SEO Норвегія, адмінка, демо',
        'desc' => 'Driving School CMS: повний PHP для автошкіл — категорії B–CE, заявки, новини AI, галерея, ціни NOK, Schema.org DrivingSchool, багатомовна адмінка. Демо Nordisk Trafikkskole (Осло). Екосистема BILOHASH.',
        'badge' => 'PHP · Driving School CMS · екосистема BILOHASH',
        'h1' => 'Driving School CMS',
        'sub' => 'Повний PHP-скрипт для автошкіл: SEO-сайт, категорії та райони, заявки, новини, галерея, ціни в NOK і повна адмін-панель — демо-школа в Осло.',
        'cta_demo' => 'Відкрити live демо (сайт)',
        'cta_admin' => 'Відкрити адмінку',
        'cta_contact' => 'Замовити / обговорити',
        'nav_features' => 'Можливості',
        'nav_modules' => 'Модулі',
        'nav_admin' => 'Адмінка',
        'nav_schema' => 'SEO і Schema',
        'nav_shots' => 'Скріншоти',
        'nav_demo' => 'Демо-доступ',
        'nav_long' => 'Повний опис',
        'intro_h' => 'Що таке Driving School CMS?',
        'intro' => 'Окремий PHP-продукт для автошкіл: публічний SEO-сайт + JSON-адмінка без важких фреймворків. Live-демо — вигадана школа Nordisk Trafikkskole в Осло (NOK, норвезьке SEO). Стек перевірений на Autoteisės (Вільнюс), адаптований під Норвегію та екосистему BILOHASH.',
        'features_h' => 'Ключові можливості',
        'modules_h' => 'Модулі скрипта (публічний сайт)',
        'admin_h' => 'Модулі адмін-панелі',
        'schema_h' => 'SEO, мікророзмітка і техніка',
        'shots_h' => 'Скріншоти',
        'demo_h' => 'Live демо та вхід',
        'long_h' => 'Повна специфікація продукту',
        'prices_h' => 'Демо-прайс (NOK)',
        'use_h' => 'Для кого',
        'stack_h' => 'Стек і вимоги',
        'creds' => 'Доступ до адмінки',
        'user' => 'Логін',
        'pass' => 'Пароль',
        'footer' => 'Driving School CMS · BILOHASH · Лише демо — вигадана школа',
        'demo_site' => 'Публічне демо',
        'demo_site_d' => 'Nordisk Trafikkskole — Осло: hero, категорії, ціни, FAQ, контакт, новини, галерея. NO / EN / UK / RU.',
        'demo_admin_d' => 'Заявки, категорії, новини, галерея, налаштування, журнал. Portfolio demo.',
        'wrong_path' => 'Лише ASCII URL',
        'wrong_path_d' => 'Правильне демо: bilohash.com/driving-school/cms/ — без кирилиці «п» у шляху.',
        'lang_label' => 'Мова',
        'menu' => 'Меню',
        'close' => 'Закрити',
    ],
    'ru' => [
        'html' => 'ru',
        'title' => 'Driving School CMS — полный PHP скрипт для автошкол | SEO Норвегия, админка, демо',
        'desc' => 'Driving School CMS: полный PHP для автошкол — категории B–CE, заявки, новости, галерея, NOK, Schema.org, админка. Демо Nordisk Trafikkskole (Осло).',
        'badge' => 'PHP · Driving School CMS · экосистема BILOHASH',
        'h1' => 'Driving School CMS',
        'sub' => 'Полный PHP-скрипт для автошкол: SEO-сайт, категории, заявки, новости, галерея, цены NOK и админ-панель — демо в Осло.',
        'cta_demo' => 'Открыть live демо (сайт)',
        'cta_admin' => 'Открыть админку',
        'cta_contact' => 'Заказать / обсудить',
        'nav_features' => 'Возможности',
        'nav_modules' => 'Модули',
        'nav_admin' => 'Админка',
        'nav_schema' => 'SEO и Schema',
        'nav_shots' => 'Скриншоты',
        'nav_demo' => 'Демо-доступ',
        'nav_long' => 'Полное описание',
        'intro_h' => 'Что такое Driving School CMS?',
        'intro' => 'Отдельный PHP-продукт для автошкол: публичный SEO-сайт + JSON-админка. Демо — вымышленная школа Nordisk Trafikkskole в Осло. Экосистема BILOHASH.',
        'features_h' => 'Ключевые возможности',
        'modules_h' => 'Модули скрипта (сайт)',
        'admin_h' => 'Модули админки',
        'schema_h' => 'SEO и микроразметка',
        'shots_h' => 'Скриншоты',
        'demo_h' => 'Live демо и вход',
        'long_h' => 'Полная спецификация',
        'prices_h' => 'Демо-прайс (NOK)',
        'use_h' => 'Для кого',
        'stack_h' => 'Стек и требования',
        'creds' => 'Доступ в админку',
        'user' => 'Логин',
        'pass' => 'Пароль',
        'footer' => 'Driving School CMS · BILOHASH · Только демо',
        'demo_site' => 'Публичное демо',
        'demo_site_d' => 'Nordisk Trafikkskole — Осло. NO/EN/UK/RU.',
        'demo_admin_d' => 'Лиды, категории, новости, галерея, настройки.',
        'wrong_path' => 'Только ASCII URL',
        'wrong_path_d' => 'Правильное демо: bilohash.com/driving-school/cms/',
        'lang_label' => 'Язык',
        'menu' => 'Меню',
        'close' => 'Закрыть',
    ],
];
$L = $T[$lang] ?? $T['en'];
// Fallbacks for older keys
foreach (['lang_label' => 'Language', 'menu' => 'Menu', 'close' => 'Close'] as $k => $fb) {
    if (empty($L[$k])) {
        $L[$k] = $T['en'][$k] ?? $fb;
    }
}

$features = [
    ['icon' => 'id-card', 'title' => 'Licence categories B–CE + reinstatement', 'desc' => 'SEO pages for B, A/A1/A2, C, C+CE, C+CE+D, D, CE, BE and lost-licence programmes — editable per language in admin.'],
    ['icon' => 'map-marker-alt', 'title' => 'District / local SEO pages', 'desc' => 'Area landings for local search intent (demo mapped to Oslo-style positioning).'],
    ['icon' => 'inbox', 'title' => 'Leads & callbacks', 'desc' => 'Contact form: name, phone, category, language, source page → admin orders (new / contacted).'],
    ['icon' => 'newspaper', 'title' => 'News + AI drafts', 'desc' => 'Multilingual news CRUD, cover upload, optional OpenAI / Grok keys for article drafts.'],
    ['icon' => 'images', 'title' => 'Photo gallery', 'desc' => 'Admin gallery for cars, instructors and school life.'],
    ['icon' => 'coins', 'title' => 'NOK price tables', 'desc' => 'Full course + extra lesson prices in settings JSON — Norwegian kroner on the demo.'],
    ['icon' => 'globe', 'title' => '4 languages', 'desc' => 'NO default, EN, UK, RU — hreflang, language switcher, per-locale content files.'],
    ['icon' => 'user-shield', 'title' => 'Secure admin', 'desc' => 'Sessions, CSRF, login throttle, activity log. Demo: demo / demo.'],
    ['icon' => 'search', 'title' => 'Norway SEO pack', 'desc' => 'Schema.org DrivingSchool, FAQPage, BreadcrumbList, OG/Twitter, sitemap, llms.txt, robots.'],
    ['icon' => 'clock', 'title' => 'Cron jobs', 'desc' => 'cron/cleanup.php (login attempts, activity log) and cron/seed-demo.php.'],
    ['icon' => 'mobile-alt', 'title' => 'Responsive UI', 'desc' => 'Mobile-first public CSS + admin CSS — no SPA build step.'],
    ['icon' => 'server', 'title' => 'Shared hosting ready', 'desc' => 'PHP 8 + JSON storage under /driving-school/cms on Apache / LiteSpeed.'],
];

$publicModules = [
    ['Home / hero', 'index.php', 'Hero, stats, about, category grid, prices, process, FAQ, contact form'],
    ['Category SEO pages', 'kategorija-*.php', 'B, A, C, CE, D, BE, packages, licence reinstatement'],
    ['District SEO pages', 'rajonas-*.php', 'Local landing pages for geo queries'],
    ['News', 'news.php, news-post.php', 'List + article with meta'],
    ['Gallery', 'gallery.php', 'Public photo grid'],
    ['Legal', 'privacy.php, terms.php', 'Privacy & terms templates'],
    ['SEO files', 'sitemap.php, robots.txt, llms.txt', 'Crawl & AI discovery'],
    ['API contact', 'api/contact.php', 'Lead submit endpoint'],
];

$adminModules = [
    ['Orders / leads', 'admin/orders.php', 'Inbox, status, contact data'],
    ['Categories editor', 'admin/categories.php', 'Per-language category SEO text'],
    ['News manager', 'admin/news.php', 'CRUD + images + AI generate'],
    ['Gallery manager', 'admin/gallery.php', 'Upload / reorder photos'],
    ['Settings', 'admin/settings.php', 'Phones, address, hours, prices, API keys'],
    ['Activity log', 'admin/activity.php', 'Admin actions audit'],
    ['Payments panel', 'admin/payment.php', 'Billing / portal info block'],
    ['AI generate', 'admin/ai-generate.php', 'Draft helper endpoint'],
    ['Login / logout', 'admin/login.php', 'demo / demo on portfolio'],
];

$schemas = [
    'DrivingSchool', 'FAQPage', 'BreadcrumbList', 'Offer + priceCurrency NOK',
    'PostalAddress (Oslo, NO)', 'GeoCoordinates', 'OpeningHoursSpecification',
    'Person founder', 'hreflang nb-NO + en + uk + ru + x-default',
    'Open Graph', 'Twitter Card', 'canonical URLs', 'sitemap.xml / sitemap.php',
    'llms.txt', 'robots.txt', 'WebPage / SoftwareApplication (product landing)',
];

$useCases = [
    'Private trafikkskole / driving school websites',
    'Multi-category schools (car, moto, truck, bus)',
    'Local SEO for city districts',
    'Lead capture instead of only phone',
    'News / tips blog for pupils',
    'Portfolio demo for BILOHASH clients (Norway & EU)',
];

$stack = [
    'PHP 8+ (no Laravel/Symfony required)',
    'JSON file storage (orders, settings, news, gallery)',
    'Apache / LiteSpeed .htaccess, BASE_PATH=/driving-school/cms',
    'Font CSS: Manrope + Sora; Font Awesome on product landing',
    'Optional: Telegram, SMTP, reCAPTCHA v3, OpenAI, Grok',
    'Cron CLI or ?key= for cleanup / re-seed',
];

$prices = [
    ['B', '18 900', '890'],
    ['A / A1 / A2', '12 900', '890'],
    ['C', '24 900', '—'],
    ['C+CE', '34 900', '1 190'],
    ['C+CE+D', '44 900', '—'],
    ['D', '27 900', '1 490'],
    ['CE', '19 900', '—'],
    ['BE', '9 900', '990'],
    ['Licence reinstatement', '4 900', '—'],
];

$long_html = <<<'HTML'
<p><strong>Driving School CMS</strong> is a production-oriented PHP package for driving schools. It includes everything needed to run a public marketing website and an internal admin console without frameworks.</p>

<h3>1. Public storefront</h3>
<ul>
<li>Hero with school branding, key stats and dual CTAs (sign up / prices)</li>
<li>About block with feature cards (instructors, prices, schedule, reinstatement)</li>
<li>Category grid linked to dedicated SEO pages for every licence class</li>
<li>Full-course and extra-lesson price tables (demo currency: <strong>NOK</strong>)</li>
<li>Process steps (how training works) and FAQ accordion with FAQPage schema</li>
<li>Contact / lead form with category selection and consent link</li>
<li>News index + single posts, photo gallery, privacy &amp; terms</li>
<li>Multilingual routing: default Norwegian root, <code>/en/</code>, <code>/uk/</code>, <code>/ru/</code></li>
</ul>

<h3>2. Licence category SEO</h3>
<ul>
<li>Pages: <code>kategorija-b.php</code>, <code>kategorija-a.php</code>, <code>kategorija-c.php</code>, <code>kategorija-ce.php</code>, <code>kategorija-d.php</code>, packages (C+CE, C+CE+D), BE, lost licence</li>
<li>Per-language content files under <code>inc/categories/</code> + JSON overrides from admin</li>
<li>Unique titles, descriptions and body copy for Statens vegvesen-style category intent</li>
</ul>

<h3>3. Local / district SEO</h3>
<ul>
<li><code>rajonas-*.php</code> pages for geo-targeted landing content</li>
<li>Editable district data per language — useful for “kjøreskole Oslo / bydel” style queries</li>
</ul>

<h3>4. Admin panel</h3>
<ul>
<li>Authentication with password hashes, session regeneration, CSRF tokens</li>
<li>Login rate limiting (JSON attempts file)</li>
<li>Orders/leads management pipeline</li>
<li>Category content editor, news manager with image upload, gallery manager</li>
<li>Global settings: phones, email, address, opening hours, price maps, AI keys, reCAPTCHA</li>
<li>Activity log of admin actions</li>
<li>AI generate endpoint for news drafts (keys optional)</li>
</ul>

<h3>5. Norway-focused SEO (demo configuration)</h3>
<ul>
<li>Brand: <em>Nordisk Trafikkskole</em> (fictional portfolio school in Oslo)</li>
<li>Schema.org type <code>DrivingSchool</code> with address, geo, opening hours, offers in NOK</li>
<li>FAQPage + BreadcrumbList on the homepage template</li>
<li>hreflang including <code>nb-NO</code>, Open Graph, Twitter cards, canonical</li>
<li><code>sitemap.php</code>, <code>robots.txt</code>, <code>llms.txt</code></li>
</ul>

<h3>6. Operations</h3>
<ul>
<li><code>cron/cleanup.php</code> — prune old login attempts, trim activity log</li>
<li><code>cron/seed-demo.php</code> — restore demo leads if empty</li>
<li>BASE_PATH aware URLs for subdirectory install on bilohash.com</li>
<li>BILOHASH ecosystem product page + planet on homepage hub</li>
</ul>

<h3>7. Licence</h3>
<p>Portfolio / evaluation demo on bilohash.com. Commercial use via BILOHASH ecosystem subscription. Contact via bilohash.com.</p>
HTML;

$shots = [];
$shotDir = __DIR__ . '/screenshot';
$shotUrlBase = $base . '/screenshot';
if (!is_dir($shotDir)) {
    $shotDir = __DIR__ . '/assets/screenshots';
    $shotUrlBase = $base . '/assets/screenshots';
}
if (is_dir($shotDir)) {
    $files = scandir($shotDir) ?: [];
    sort($files, SORT_NATURAL);
    foreach ($files as $f) {
        if (preg_match('/\.(jpe?g|png|webp)$/i', $f) && !preg_match('/-480\./i', $f)) {
            $shots[] = $f;
        }
    }
}

$lang_qs = static fn(string $code): string => $base . '/?lang=' . $code;

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'SoftwareApplication',
            'name' => 'Driving School CMS',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web / PHP 8',
            'softwareVersion' => '1.0',
            'offers' => [
                '@type' => 'Offer',
                'price' => '49',
                'priceCurrency' => 'NOK',
                'description' => 'Demo listing on BILOHASH shop / ecosystem',
            ],
            'url' => $site_url . '/',
            'downloadUrl' => $demo_url . '/',
            'screenshot' => array_map(static fn($f) => 'https://bilohash.com' . $shotUrlBase . '/' . rawurlencode($f), array_slice($shots, 0, 5)),
            'author' => ['@type' => 'Organization', 'name' => 'BILOHASH', 'url' => 'https://bilohash.com/'],
            'featureList' => array_column($features, 'title'),
        ],
        [
            '@type' => 'WebPage',
            'name' => $L['title'],
            'description' => $L['desc'],
            'url' => $site_url . '/?lang=' . $lang,
            'inLanguage' => $L['html'],
            'isPartOf' => ['@type' => 'WebSite', 'name' => 'BILOHASH', 'url' => 'https://bilohash.com/'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'BILOHASH', 'item' => 'https://bilohash.com/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Ecosystem', 'item' => 'https://bilohash.com/#ecosystem'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Driving School CMS', 'item' => $site_url . '/'],
            ],
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'What is Driving School CMS?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'A PHP CMS for driving schools with public SEO site, licence categories, leads, news, gallery and admin panel.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Where is the live demo?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'https://bilohash.com/driving-school/cms/ — admin demo/demo at /driving-school/cms/admin/login.php'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Is the demo school real?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'No. Nordisk Trafikkskole is a fictional Oslo demo for portfolio purposes only.'],
                ],
            ],
        ],
    ],
];
?><!DOCTYPE html>
<html lang="<?= htmlspecialchars($L['html']) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= htmlspecialchars($L['title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($L['desc']) ?>">
<meta name="keywords" content="Driving School CMS, trafikkskole PHP, kjøreskole script, driving school website Norway, Schema.org DrivingSchool, bilohash">
<meta name="robots" content="index, follow, max-image-preview:large">
<link rel="canonical" href="<?= htmlspecialchars($site_url . '/?lang=' . $lang) ?>">
<?php
$hreflangMap = ['no' => 'nb-NO', 'en' => 'en', 'sv' => 'sv-SE', 'pl' => 'pl-PL', 'uk' => 'uk', 'ru' => 'ru'];
foreach ($hreflangMap as $c => $hl): ?>
<link rel="alternate" hreflang="<?= htmlspecialchars($hl) ?>" href="<?= htmlspecialchars($site_url . '/?lang=' . $c) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($site_url . '/?lang=no') ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= htmlspecialchars($L['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($L['desc']) ?>">
<meta property="og:url" content="<?= htmlspecialchars($site_url . '/?lang=' . $lang) ?>">
<meta property="og:locale" content="<?= htmlspecialchars(str_replace('-', '_', $hreflangMap[$lang] ?? 'nb_NO')) ?>">
<meta property="og:site_name" content="Driving School CMS · BILOHASH">
<?php if ($shots): ?>
<meta property="og:image" content="https://bilohash.com<?= htmlspecialchars($shotUrlBase . '/' . rawurlencode($shots[0])) ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin>
<link rel="stylesheet" href="<?= htmlspecialchars($base) ?>/assets/css/site.css">
<script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<style>
/* Landing extras + mobile-first fixes */
html{scroll-behavior:smooth}
body{overflow-x:hidden}
.shs-header-inner{flex-wrap:nowrap;min-width:0}
.shs-logo{min-width:0;flex-shrink:1}
.shs-logo-text{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:min(46vw,220px)}
@media(min-width:480px){.shs-logo-text{max-width:none}}
.ds-table-wrap{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;margin:0 0 4px;border-radius:12px}
.ds-table{width:100%;min-width:520px;border-collapse:collapse;background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(15,23,42,.06)}
.ds-table th,.ds-table td{padding:12px 14px;border-bottom:1px solid #e2e8f0;text-align:left;font-size:.95rem;vertical-align:top}
.ds-table th{background:#eff6ff;color:#1e3a8a;font-weight:700;white-space:nowrap}
.ds-table tr:last-child td{border-bottom:0}
.ds-table code{word-break:break-all;font-size:.85em}
.ds-note{background:#fef3c7;border:1px solid #fcd34d;border-radius:12px;padding:14px 16px;margin:16px 0;color:#92400e;overflow-wrap:anywhere}
.ds-note code{word-break:break-all}
.ds-use{display:flex;flex-wrap:wrap;gap:10px;margin-top:12px}
.ds-use span{background:#fff;border:1px solid #e2e8f0;border-radius:999px;padding:8px 14px;font-size:.9rem;font-weight:600}
.ds-stack{columns:1;column-gap:24px}
@media(min-width:720px){.ds-stack{columns:2}}
.ds-stack li{break-inside:avoid;margin-bottom:8px}
.ds-schema-grid{display:grid;gap:10px;grid-template-columns:1fr}
@media(min-width:560px){.ds-schema-grid{grid-template-columns:repeat(2,1fr)}}
@media(min-width:900px){.ds-schema-grid{grid-template-columns:repeat(3,1fr)}}
.ds-schema-chip{display:flex;align-items:flex-start;gap:8px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px;font-weight:600;color:#1e3a8a;font-size:.92rem;line-height:1.35}
.ds-schema-chip i{margin-top:3px;flex-shrink:0}
.ds-shots{display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,160px),1fr));gap:12px}
.ds-shot{border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;background:#fff;margin:0}
.ds-shot img{width:100%;height:140px;object-fit:cover;display:block}
.ds-shot figcaption{padding:8px 10px;font-size:.8rem;color:#64748b;text-align:center}
.ds-long{max-width:820px;overflow-wrap:anywhere}
.ds-long h3{color:#0f172a;margin:1.4em 0 .6em}
.ds-long ul{padding-left:1.2rem}
.ds-long code{font-size:.88em;word-break:break-all}
.shs-hero-cta{display:flex;flex-wrap:wrap;gap:10px}
.shs-hero-cta .shs-btn-lg{justify-content:center;text-align:center}
.shs-hero-sub{max-width:100%}
/* Desktop nav */
.shs-nav{display:none;flex-wrap:wrap;gap:2px;align-items:center}
.shs-nav a{font-size:.88rem;padding:6px 8px}
.shs-header-tools{display:flex;align-items:center;gap:8px;margin-left:auto;flex-shrink:0}
.shs-header-cta{display:none;gap:8px;align-items:center}
/* Language dropdown */
.shs-lang-dropdown{position:relative}
.shs-lang-btn{
  display:inline-flex;align-items:center;gap:6px;
  border:1px solid #e2e8f0;background:#fff;color:#1e293b;
  padding:8px 12px;border-radius:10px;cursor:pointer;font-weight:700;font-size:.9rem;
  min-height:40px;
}
.shs-lang-btn:hover,.shs-lang-btn[aria-expanded="true"]{border-color:#c2410c;color:#c2410c}
.shs-lang-btn .flag{font-size:1rem;line-height:1}
.shs-lang-menu{
  position:absolute;top:calc(100% + 6px);right:0;z-index:120;
  list-style:none;margin:0;padding:6px;min-width:180px;
  background:#fff;border:1px solid #e2e8f0;border-radius:12px;
  box-shadow:0 12px 32px rgba(15,23,42,.12);
}
.shs-lang-menu[hidden]{display:none!important}
.shs-lang-menu a{
  display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;
  color:#1e293b;font-weight:600;font-size:.92rem;text-decoration:none;
}
.shs-lang-menu a:hover{background:#fff7ed;color:#c2410c}
.shs-lang-menu a.active{background:#ffedd5;color:#9a3412}
.shs-lang-menu .code{margin-left:auto;font-size:.75rem;color:#94a3b8;font-weight:700}
/* Mobile menu */
.shs-menu-toggle{
  display:inline-flex;align-items:center;justify-content:center;
  border:1px solid #e2e8f0;background:#fff;width:40px;height:40px;border-radius:10px;cursor:pointer;color:#1e293b;font-size:1.05rem;
}
.shs-menu-toggle:hover{border-color:#c2410c;color:#c2410c}
.shs-mobile-panel{border-top:1px solid #e2e8f0;background:#fff;padding:12px 20px 18px}
.shs-mobile-panel[hidden]{display:none!important}
.shs-nav-mobile{display:flex;flex-direction:column;gap:2px}
.shs-nav-mobile a{
  display:flex;align-items:center;gap:10px;padding:12px 4px;
  color:#1e293b;font-weight:600;border-bottom:1px solid #f1f5f9;text-decoration:none;
}
.shs-nav-mobile a:last-child{border-bottom:0}
.shs-mobile-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:14px}
.shs-mobile-actions a{flex:1 1 140px;justify-content:center}
body.ds-nav-open{overflow:hidden}
@media(min-width:1024px){
  .shs-nav{display:flex}
  .shs-header-cta{display:flex}
  .shs-menu-toggle{display:none}
  .shs-mobile-panel{display:none!important}
}
/* Sticky CTAs — safe area + less clutter on tiny screens */
.ds-sticky-cta{
  position:fixed;bottom:max(12px,env(safe-area-inset-bottom));
  right:max(12px,env(safe-area-inset-right));
  z-index:50;display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;
  max-width:calc(100vw - 24px);
}
.ds-sticky-cta a{box-shadow:0 8px 24px rgba(194,65,12,.28);font-size:.9rem;padding:10px 14px}
@media(max-width:480px){
  .ds-sticky-cta .ds-sticky-admin{display:none}
  .shs-hero{padding:36px 0 48px}
  .shs-section{padding:40px 0}
  .shs-section-title{font-size:1.4rem}
}
/* Footer */
.ds-footer-row{display:flex;flex-wrap:wrap;gap:16px;justify-content:space-between;align-items:flex-start}
.ds-footer-links{display:flex;gap:12px;flex-wrap:wrap}
</style>
</head>
<body>
<?php
$langMeta = [
    'no' => ['lab' => 'NO', 'name' => 'Norsk', 'flag' => '🇳🇴'],
    'en' => ['lab' => 'EN', 'name' => 'English', 'flag' => '🇬🇧'],
    'sv' => ['lab' => 'SV', 'name' => 'Svenska', 'flag' => '🇸🇪'],
    'pl' => ['lab' => 'PL', 'name' => 'Polski', 'flag' => '🇵🇱'],
    'uk' => ['lab' => 'UA', 'name' => 'Українська', 'flag' => '🇺🇦'],
    'ru' => ['lab' => 'RU', 'name' => 'Русский', 'flag' => '🇷🇺'],
];
$curLang = $langMeta[$lang] ?? $langMeta['no'];
$navItems = [
    ['#features', $L['nav_features'], 'fa-star'],
    ['#modules', $L['nav_modules'], 'fa-puzzle-piece'],
    ['#admin', $L['nav_admin'], 'fa-user-shield'],
    ['#schema', $L['nav_schema'], 'fa-search'],
    ['#shots', $L['nav_shots'], 'fa-images'],
    ['#demo', $L['nav_demo'], 'fa-globe'],
    ['#specs', $L['nav_long'], 'fa-file-alt'],
];
?>
<header class="shs-header" id="dsHeader">
  <div class="shs-header-inner">
    <a class="shs-logo" href="<?= htmlspecialchars($base) ?>/?lang=<?= urlencode($lang) ?>">
      <span class="shs-logo-icon"><i class="fas fa-car" aria-hidden="true"></i></span>
      <span class="shs-logo-text">Driving <em>School</em> CMS</span>
    </a>
    <nav class="shs-nav" aria-label="Main">
      <?php foreach ($navItems as $ni): ?>
      <a href="<?= htmlspecialchars($ni[0]) ?>"><?= htmlspecialchars($ni[1]) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="shs-header-tools">
      <div class="shs-lang-dropdown" id="dsLangDropdown">
        <button type="button" class="shs-lang-btn" id="dsLangBtn"
                aria-haspopup="listbox" aria-expanded="false" aria-controls="dsLangMenu"
                aria-label="<?= htmlspecialchars($L['lang_label']) ?>">
          <span class="flag" aria-hidden="true"><?= $curLang['flag'] ?></span>
          <span><?= htmlspecialchars($curLang['lab']) ?></span>
          <i class="fas fa-chevron-down" style="font-size:.7rem;opacity:.7" aria-hidden="true"></i>
        </button>
        <ul class="shs-lang-menu" id="dsLangMenu" role="listbox" hidden>
          <?php foreach ($langMeta as $c => $meta): ?>
          <li role="option" aria-selected="<?= $c === $lang ? 'true' : 'false' ?>">
            <a href="<?= htmlspecialchars($lang_qs($c)) ?>" class="<?= $c === $lang ? 'active' : '' ?>" hreflang="<?= htmlspecialchars($c === 'no' ? 'nb' : $c) ?>">
              <span aria-hidden="true"><?= $meta['flag'] ?></span>
              <span><?= htmlspecialchars($meta['name']) ?></span>
              <span class="code"><?= htmlspecialchars($meta['lab']) ?></span>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="shs-header-cta">
        <a class="shs-btn-outline" href="<?= htmlspecialchars($admin) ?>"><i class="fas fa-user-shield" aria-hidden="true"></i> Admin</a>
        <a class="shs-btn-primary" href="<?= htmlspecialchars($demo) ?>/"><i class="fas fa-external-link-alt" aria-hidden="true"></i> Demo</a>
      </div>
      <button type="button" class="shs-menu-toggle" id="dsMenuBtn"
              aria-expanded="false" aria-controls="dsMobilePanel"
              aria-label="<?= htmlspecialchars($L['menu']) ?>">
        <i class="fas fa-bars" aria-hidden="true"></i>
      </button>
    </div>
  </div>
  <div class="shs-mobile-panel" id="dsMobilePanel" hidden>
    <nav class="shs-nav-mobile" aria-label="<?= htmlspecialchars($L['menu']) ?>">
      <?php foreach ($navItems as $ni): ?>
      <a href="<?= htmlspecialchars($ni[0]) ?>"><i class="fas <?= htmlspecialchars($ni[2]) ?>" aria-hidden="true"></i> <?= htmlspecialchars($ni[1]) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="shs-mobile-actions">
      <a class="shs-btn-primary" href="<?= htmlspecialchars($demo) ?>/"><i class="fas fa-globe" aria-hidden="true"></i> Demo</a>
      <a class="shs-btn-outline" href="<?= htmlspecialchars($admin) ?>"><i class="fas fa-user-shield" aria-hidden="true"></i> Admin</a>
      <a class="shs-btn-ghost" href="https://bilohash.com/contact.php"><i class="fas fa-comments" aria-hidden="true"></i> <?= htmlspecialchars($L['cta_contact']) ?></a>
    </div>
  </div>
</header>

<section class="shs-hero">
  <div class="shs-container shs-hero-inner">
    <div class="shs-hero-content">
      <span class="shs-badge"><?= htmlspecialchars($L['badge']) ?></span>
      <h1><?= htmlspecialchars($L['h1']) ?></h1>
      <p class="shs-hero-sub"><?= htmlspecialchars($L['sub']) ?></p>
      <div class="shs-hero-cta">
        <a class="shs-btn-primary shs-btn-lg" href="<?= htmlspecialchars($demo) ?>/"><i class="fas fa-car-side"></i> <?= htmlspecialchars($L['cta_demo']) ?></a>
        <a class="shs-btn-outline shs-btn-lg" href="<?= htmlspecialchars($admin) ?>"><i class="fas fa-user-shield"></i> <?= htmlspecialchars($L['cta_admin']) ?></a>
        <a class="shs-btn-ghost shs-btn-lg" href="https://bilohash.com/contact.php"><i class="fas fa-comments"></i> <?= htmlspecialchars($L['cta_contact']) ?></a>
      </div>
      <p style="margin-top:14px;font-size:.9rem;color:#64748b">
        <a href="https://bilohash.com/#ecosystem">BILOHASH ecosystem planet</a> ·
        <a href="https://bilohash.com/shop/">Shop CMS catalog</a> ·
        <a href="https://bilohash.com/news/">News hub</a>
      </p>
    </div>
    <div class="shs-hero-preview" aria-hidden="true">
      <div class="shs-mockup">
        <div class="shs-mockup-header"><span></span><span></span><span></span><span class="shs-mockup-title">Nordisk Trafikkskole · Oslo demo</span></div>
        <div class="shs-mockup-body">
          <article class="shs-mockup-item active"><div class="shs-mock-thumb"></div><div><strong>Klasse B</strong><span>18 900 NOK</span></div></article>
          <article class="shs-mockup-item"><div class="shs-mock-thumb"></div><div><strong>Klasse A</strong><span>12 900 NOK</span></div></article>
          <article class="shs-mockup-item"><div class="shs-mock-thumb"></div><div><strong>Klasse CE</strong><span>19 900 NOK</span></div></article>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="shs-section shs-intro" id="intro">
  <div class="shs-container">
    <h2><?= htmlspecialchars($L['intro_h']) ?></h2>
    <p class="shs-lead"><?= htmlspecialchars($L['intro']) ?></p>
    <div class="ds-note"><strong><?= htmlspecialchars($L['wrong_path']) ?>:</strong> <?= htmlspecialchars($L['wrong_path_d']) ?><br>
      ✅ <code>https://bilohash.com/driving-school/cms/</code><br>
      ❌ <code>…/drivinп-school/cms/</code> (Cyrillic “п”)</div>
  </div>
</section>

<section class="shs-section" id="features">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['features_h']) ?></h2>
    <div class="shs-features-grid">
      <?php foreach ($features as $f): ?>
      <article class="shs-feature-card">
        <div class="shs-feature-icon"><i class="fas fa-<?= htmlspecialchars($f['icon']) ?>"></i></div>
        <h3><?= htmlspecialchars($f['title']) ?></h3>
        <p><?= htmlspecialchars($f['desc']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="shs-section shs-tech" id="modules">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['modules_h']) ?></h2>
    <div class="ds-table-wrap">
      <table class="ds-table">
        <thead><tr><th>Module</th><th>Files</th><th>Description</th></tr></thead>
        <tbody>
        <?php foreach ($publicModules as $row): ?>
          <tr>
            <td><strong><?= htmlspecialchars($row[0]) ?></strong></td>
            <td><code><?= htmlspecialchars($row[1]) ?></code></td>
            <td><?= htmlspecialchars($row[2]) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="shs-section" id="admin">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['admin_h']) ?></h2>
    <div class="ds-table-wrap">
      <table class="ds-table">
        <thead><tr><th>Admin module</th><th>Path</th><th>Description</th></tr></thead>
        <tbody>
        <?php foreach ($adminModules as $row): ?>
          <tr>
            <td><strong><?= htmlspecialchars($row[0]) ?></strong></td>
            <td><code><?= htmlspecialchars($row[1]) ?></code></td>
            <td><?= htmlspecialchars($row[2]) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="shs-section shs-tech" id="prices">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['prices_h']) ?></h2>
    <p class="shs-lead">Fictional demo prices for Nordisk Trafikkskole (Oslo). Not a real offer.</p>
    <div class="ds-table-wrap">
      <table class="ds-table">
        <thead><tr><th>Category</th><th>Full course (NOK)</th><th>Extra lesson (NOK)</th></tr></thead>
        <tbody>
        <?php foreach ($prices as $row): ?>
          <tr>
            <td><strong><?= htmlspecialchars($row[0]) ?></strong></td>
            <td><?= htmlspecialchars($row[1]) ?></td>
            <td><?= htmlspecialchars($row[2]) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="shs-section" id="schema">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['schema_h']) ?></h2>
    <div class="ds-schema-grid">
      <?php foreach ($schemas as $s): ?>
      <div class="ds-schema-chip"><i class="fas fa-check"></i> <?= htmlspecialchars($s) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="shs-section shs-tech" id="use">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['use_h']) ?></h2>
    <div class="ds-use">
      <?php foreach ($useCases as $u): ?><span><?= htmlspecialchars($u) ?></span><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="shs-section" id="stack">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['stack_h']) ?></h2>
    <ul class="ds-stack shs-tech-list">
      <?php foreach ($stack as $item): ?>
      <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($item) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="shs-section" id="shots">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['shots_h']) ?></h2>
    <div class="ds-shots">
      <?php foreach ($shots as $i => $file): ?>
      <figure class="ds-shot">
        <a href="<?= htmlspecialchars($shotUrlBase . '/' . rawurlencode($file)) ?>" target="_blank" rel="noopener">
          <img src="<?= htmlspecialchars($shotUrlBase . '/' . rawurlencode($file)) ?>" alt="Driving School CMS screenshot <?= $i + 1 ?>" loading="lazy" width="480" height="160">
        </a>
        <figcaption>UI <?= $i + 1 ?></figcaption>
      </figure>
      <?php endforeach; ?>
      <?php if (!$shots): ?>
      <p class="shs-lead">Place images in <code>/driving-school/screenshot/</code></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="shs-section shs-demo-block" id="demo">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['demo_h']) ?></h2>
    <div class="shs-demo-grid">
      <article class="shs-demo-card">
        <h3><i class="fas fa-globe"></i> <?= htmlspecialchars($L['demo_site']) ?></h3>
        <p><?= htmlspecialchars($L['demo_site_d']) ?></p>
        <p><code>https://bilohash.com/driving-school/cms/</code></p>
        <a class="shs-btn-primary" href="<?= htmlspecialchars($demo) ?>/"><?= htmlspecialchars($L['cta_demo']) ?> →</a>
      </article>
      <article class="shs-demo-card">
        <h3><i class="fas fa-user-shield"></i> <?= htmlspecialchars($L['creds']) ?></h3>
        <p><?= htmlspecialchars($L['demo_admin_d']) ?></p>
        <div class="ds-demo-creds">
          <?= htmlspecialchars($L['user']) ?>: <strong>demo</strong><br>
          <?= htmlspecialchars($L['pass']) ?>: <strong>demo</strong><br>
          URL: <strong>/driving-school/cms/admin/login.php</strong>
        </div>
        <p style="margin-top:14px"><a class="shs-btn-outline" href="<?= htmlspecialchars($admin) ?>"><?= htmlspecialchars($L['cta_admin']) ?> →</a></p>
      </article>
    </div>
  </div>
</section>

<section class="shs-section" id="specs">
  <div class="shs-container">
    <h2 class="shs-section-title"><?= htmlspecialchars($L['long_h']) ?></h2>
    <div class="ds-long shs-lead"><?= $long_html ?></div>
  </div>
</section>

<footer class="shs-footer" style="padding:32px 0 88px;border-top:1px solid #e2e8f0;margin-top:40px">
  <div class="shs-container ds-footer-row">
    <div>
      <strong>Driving School CMS</strong>
      <p style="color:#64748b;margin:6px 0 0"><?= htmlspecialchars($L['footer']) ?></p>
    </div>
    <div class="ds-footer-links">
      <a href="<?= htmlspecialchars($demo) ?>/">Demo</a>
      <a href="<?= htmlspecialchars($admin) ?>">Admin</a>
      <a href="https://bilohash.com/#ecosystem">Ecosystem</a>
      <a href="https://bilohash.com/shop/">Shop</a>
      <a href="https://bilohash.com/contact.php">Contact</a>
    </div>
  </div>
  <div class="shs-container" style="margin-top:12px;color:#94a3b8;font-size:.9rem">© <?= $year ?> BILOHASH</div>
</footer>

<div class="ds-sticky-cta" aria-label="Quick demo links">
  <a class="shs-btn-primary" href="<?= htmlspecialchars($demo) ?>/"><i class="fas fa-globe" aria-hidden="true"></i> Demo</a>
  <a class="shs-btn-outline ds-sticky-admin" href="<?= htmlspecialchars($admin) ?>" style="background:#fff"><i class="fas fa-user-shield" aria-hidden="true"></i> Admin</a>
</div>
<script>
(function () {
  var menuBtn = document.getElementById('dsMenuBtn');
  var panel = document.getElementById('dsMobilePanel');
  var langBtn = document.getElementById('dsLangBtn');
  var langMenu = document.getElementById('dsLangMenu');
  var langDrop = document.getElementById('dsLangDropdown');

  function setMenuOpen(open) {
    if (!menuBtn || !panel) return;
    panel.hidden = !open;
    menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    menuBtn.setAttribute('aria-label', open
      ? <?= json_encode($L['close'], JSON_UNESCAPED_UNICODE) ?>
      : <?= json_encode($L['menu'], JSON_UNESCAPED_UNICODE) ?>);
    document.body.classList.toggle('ds-nav-open', open);
    var icon = menuBtn.querySelector('i');
    if (icon) icon.className = open ? 'fas fa-times' : 'fas fa-bars';
  }

  function setLangOpen(open) {
    if (!langBtn || !langMenu) return;
    langMenu.hidden = !open;
    langBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  if (menuBtn && panel) {
    menuBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var willOpen = panel.hidden;
      setLangOpen(false);
      setMenuOpen(willOpen);
    });
    panel.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () { setMenuOpen(false); });
    });
  }

  if (langBtn && langMenu) {
    langBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      setMenuOpen(false);
      setLangOpen(langMenu.hidden);
    });
  }

  document.addEventListener('click', function (e) {
    if (langDrop && !langDrop.contains(e.target)) setLangOpen(false);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      setLangOpen(false);
      setMenuOpen(false);
    }
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth >= 1024) setMenuOpen(false);
  });
})();
</script>
</body>
</html>
