<?php
require_once __DIR__ . '/store.php';

function default_settings(): array {
    return [
        'phone1' => COMPANY_PHONE_1,
        'phone2' => COMPANY_PHONE_2,
        'email' => CONTACT_EMAIL_TO,
        'address' => COMPANY_ADDRESS,
        'hours_no' => 'Man–fre 08:00–18:00, lør 10:00–14:00',
        'hours_en' => 'Mon–Fri 08:00–18:00, Sat 10:00–14:00',
        'hours_sv' => 'Mån–fre 08:00–18:00, lör 10:00–14:00',
        'hours_pl' => 'Pon–pt 08:00–18:00, sob 10:00–14:00',
        'hours_uk' => 'Пн–Пт 08:00–18:00, Сб 10:00–14:00',
        'hours_ru' => 'Пн–Пт 08:00–18:00, Сб 10:00–14:00',
        'hours_lt' => 'I–V 08:00–18:00, VI 10:00–14:00',
        'description_no' => 'Nordisk Trafikkskole — demo kjøreskole i Oslo. Kurs for klasse B, A/A1/A2, C, CE, D, BE og førerkortgjenoppretting. Fiktiv demo for Driving School CMS — ikke en ekte skole.',
        'description_en' => 'Nordisk Trafikkskole — demo driving school in Oslo. Courses for categories B, A/A1/A2, C, CE, D, BE and licence reinstatement. Fictional demo for Driving School CMS — not a real school.',
        'description_sv' => 'Nordisk Trafikkskole — demo körskola i Oslo. Kurser för klass B, A/A1/A2, C, CE, D, BE och återställande av körkort. Fiktiv BILOHASH-demo.',
        'description_pl' => 'Nordisk Trafikkskole — demo szkoła jazdy w Oslo. Kursy B, A/A1/A2, C, CE, D, BE oraz odzyskanie prawa jazdy. Fikcyjne demo BILOHASH.',
        'description_uk' => 'Nordisk Trafikkskole — демо автошкола в Осло. Курси категорій B, A/A1/A2, C, CE, D, BE та повернення прав. Демо Driving School CMS — не справжня школа.',
        'description_ru' => 'Nordisk Trafikkskole — демо автошкола в Осло. Курсы категорий B, A/A1/A2, C, CE, D, BE и восстановление прав. Демо Driving School CMS — не настоящая школа.',
        'description_lt' => 'Nordisk Trafikkskole — demo vairavimo mokykla Osle. B, A, C, CE, D, BE kursai. Driving School CMS demo.',
        // full course prices (NOK) — demo only
        'price_full' => [
            'B' => 18900, 'A' => 12900, 'C' => 24900, 'CCE' => 34900,
            'CCED' => 44900, 'D' => 27900, 'CE' => 19900, 'BE' => 9900, 'LOST' => 4900,
        ],
        'price_extra' => [
            'B' => 890, 'A' => 890, 'CCE' => 1190, 'D' => 1490, 'BE' => 990,
        ],
        'openai_key' => '',
        'grok_key' => '',
        'chat_widget_enabled' => false,
        'chat_widget_provider' => 'openai',
        'recaptcha_site_key' => '',
        'recaptcha_secret_key' => '',
        'billing_portal_url' => 'https://bilohash.com/client/',
        'billing_client_name' => 'Demo Client',
        'billing_client_email' => 'demo@bilohash.com',
        'billing_client_password' => '',
        'billing_service_name' => 'Driving School CMS demo — bilohash.com',
        'billing_invoices' => [
            ['id' => 'DS-DEMO-00001', 'client' => 'Demo Client', 'amount' => '49,00 NOK', 'status' => 'Demo', 'date' => '2026-07-01 10:00:00'],
        ],
    ];
}

function get_settings(): array {
    static $settings = null;
    if ($settings === null) {
        $stored = read_json('settings.json', []);
        $settings = array_replace_recursive(default_settings(), is_array($stored) ? $stored : []);
    }
    return $settings;
}

function save_settings(array $data): bool {
    return write_json('settings.json', $data);
}
