<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/icons.php';

$content = [
    'lt' => [
        'html_lang' => 'lt',
        'title' => 'Privatumo politika â€” ' . SITE_NAME,
        'home_url' => '/',
        'home_label' => 'GrÄ¯Å¾ti Ä¯ pradÅ¾iÄ…',
        'h1' => 'Privatumo politika',
        'updated' => 'PaskutinÄ¯ kartÄ… atnaujinta: 2026-07-29',
        'sections' => [
            ['h' => '1. DuomenÅ³ valdytojas', 'p' => SITE_NAME . ' (Vilniaus vairavimo mokykla), Ä¯m. kodas ' . COMPANY_CODE . ', adresas ' . COMPANY_ADDRESS . '.'],
            ['h' => '2. Kokius duomenis renkame', 'p' => 'KontaktinÄ—s formos pagalba renkame: vardÄ… ir pavardÄ™, telefono numerÄ¯, el. paÅ¡tÄ… (jei nurodote) ir Å¾inutÄ—s turinÄ¯. Naudojant pokalbiÅ³ asistentÄ… (chat), papildomai saugomas jÅ«sÅ³ paraÅ¡ytos Å¾inutÄ—s tekstas.'],
            ['h' => '3. DuomenÅ³ naudojimo tikslas', 'p' => 'Duomenys naudojami tik tam, kad galÄ—tume susisiekti dÄ—l jÅ«sÅ³ uÅ¾klausos, apdoroti registracijÄ… Ä¯ kursÄ… ir suteikti informacijÄ… apie vairavimo kursus.'],
            ['h' => '4. UÅ¾klausÅ³ persiuntimas (el. paÅ¡tas ir Telegram)', 'p' => 'Gavus jÅ«sÅ³ uÅ¾klausÄ… svetainÄ—je, jos duomenys automatiÅ¡kai persiunÄiami el. paÅ¡tu ir Telegram praneÅ¡imu mokyklos atsakingiems asmenims, kad uÅ¾klausa bÅ«tÅ³ kuo greiÄiau apdorota ir priimta. Å ie duomenys naudojami tik vidiniam uÅ¾klausÅ³ priÄ—mimui ir nÄ—ra vieÅ¡ai skelbiami ar perduodami treÄiosioms Å¡alims.'],
            ['h' => '5. Lankomumo statistika', 'p' => 'SvetainÄ—s prieglobos (hostingo) paslaugÅ³ teikÄ—jas renka bendrÄ…, suvestinÄ™ lankomumo statistikÄ… (pvz., apsilankymÅ³ skaiÄiÅ³, puslapio uÅ¾klausas serverio Å¾urnaluose) techniniam svetainÄ—s veikimui uÅ¾tikrinti. Å i statistika nÄ—ra naudojama jÅ«sÅ³ asmeniui identifikuoti ir nesusiejama su kontaktinÄ—s formos duomenimis.'],
            ['h' => '6. PokalbiÅ³ asistentas (chat)', 'p' => 'SvetainÄ—je veikiantis pokalbiÅ³ asistentas naudoja dirbtinio intelekto paslaugÄ… (OpenAI arba xAI) jÅ«sÅ³ klausimams apdoroti ir atsakymams generuoti. JÅ«sÅ³ Ä¯vestas Å¾inutÄ—s tekstas perduodamas Å¡iai paslaugai tik atsakymui suformuoti ir nÄ—ra naudojamas rinkodaros tikslais.'],
            ['h' => '7. JÅ«sÅ³ teisÄ—s', 'p' => 'Turite teisÄ™ praÅ¡yti susipaÅ¾inti su savo duomenimis, juos iÅ¡taisyti ar iÅ¡trinti. DÄ—l to kreipkitÄ—s el. paÅ¡tu ' . CONTACT_EMAIL_TO . '.'],
        ],
    ],
    'uk' => [
        'html_lang' => 'uk',
        'title' => 'ÐŸÐ¾Ð»Ñ–Ñ‚Ð¸ÐºÐ° ÐºÐ¾Ð½Ñ„Ñ–Ð´ÐµÐ½Ñ†Ñ–Ð¹Ð½Ð¾ÑÑ‚Ñ– â€” ' . SITE_NAME,
        'home_url' => '/uk/',
        'home_label' => 'ÐŸÐ¾Ð²ÐµÑ€Ð½ÑƒÑ‚Ð¸ÑÑ Ð½Ð° Ð³Ð¾Ð»Ð¾Ð²Ð½Ñƒ',
        'h1' => 'ÐŸÐ¾Ð»Ñ–Ñ‚Ð¸ÐºÐ° ÐºÐ¾Ð½Ñ„Ñ–Ð´ÐµÐ½Ñ†Ñ–Ð¹Ð½Ð¾ÑÑ‚Ñ–',
        'updated' => 'ÐžÑÑ‚Ð°Ð½Ð½Ñ” Ð¾Ð½Ð¾Ð²Ð»ÐµÐ½Ð½Ñ: 29.07.2026',
        'sections' => [
            ['h' => '1. ÐšÐ¾Ð½Ñ‚Ñ€Ð¾Ð»ÐµÑ€ Ð´Ð°Ð½Ð¸Ñ…', 'p' => SITE_NAME . ' (Vilniaus vairavimo mokykla), ÐºÐ¾Ð´ ÐºÐ¾Ð¼Ð¿Ð°Ð½Ñ–Ñ— ' . COMPANY_CODE . ', Ð°Ð´Ñ€ÐµÑÐ° ' . COMPANY_ADDRESS . '.'],
            ['h' => '2. Ð¯ÐºÑ– Ð´Ð°Ð½Ñ– Ð¼Ð¸ Ð·Ð±Ð¸Ñ€Ð°Ñ”Ð¼Ð¾', 'p' => 'Ð—Ð° Ð´Ð¾Ð¿Ð¾Ð¼Ð¾Ð³Ð¾ÑŽ ÐºÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð½Ð¾Ñ— Ñ„Ð¾Ñ€Ð¼Ð¸ Ð¼Ð¸ Ð·Ð±Ð¸Ñ€Ð°Ñ”Ð¼Ð¾: Ñ–Ð¼\'Ñ Ñ‚Ð° Ð¿Ñ€Ñ–Ð·Ð²Ð¸Ñ‰Ðµ, Ð½Ð¾Ð¼ÐµÑ€ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½Ñƒ, ÐµÐ». Ð¿Ð¾ÑˆÑ‚Ñƒ (ÑÐºÑ‰Ð¾ Ð²ÐºÐ°Ð·Ð°Ð½Ð¾) Ñ‚Ð° Ð·Ð¼Ñ–ÑÑ‚ Ð¿Ð¾Ð²Ñ–Ð´Ð¾Ð¼Ð»ÐµÐ½Ð½Ñ. ÐŸÑ–Ð´ Ñ‡Ð°Ñ Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð°Ð½Ð½Ñ Ñ‡Ð°Ñ‚-Ð°ÑÐ¸ÑÑ‚ÐµÐ½Ñ‚Ð° Ð´Ð¾Ð´Ð°Ñ‚ÐºÐ¾Ð²Ð¾ Ð·Ð±ÐµÑ€Ñ–Ð³Ð°Ñ”Ñ‚ÑŒÑÑ Ñ‚ÐµÐºÑÑ‚ Ð²Ð°ÑˆÐ¾Ð³Ð¾ Ð¿Ð¾Ð²Ñ–Ð´Ð¾Ð¼Ð»ÐµÐ½Ð½Ñ.'],
            ['h' => '3. ÐœÐµÑ‚Ð° Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð°Ð½Ð½Ñ Ð´Ð°Ð½Ð¸Ñ…', 'p' => 'Ð”Ð°Ð½Ñ– Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð¾Ð²ÑƒÑŽÑ‚ÑŒÑÑ Ð²Ð¸ÐºÐ»ÑŽÑ‡Ð½Ð¾ Ð´Ð»Ñ Ñ‚Ð¾Ð³Ð¾, Ñ‰Ð¾Ð± Ð·Ð²\'ÑÐ·Ð°Ñ‚Ð¸ÑÑ Ð· Ð²Ð°Ð¼Ð¸ Ñ‰Ð¾Ð´Ð¾ Ð·Ð°ÑÐ²ÐºÐ¸, Ð¾Ð±Ñ€Ð¾Ð±Ð¸Ñ‚Ð¸ Ñ€ÐµÑ”ÑÑ‚Ñ€Ð°Ñ†Ñ–ÑŽ Ð½Ð° ÐºÑƒÑ€Ñ Ñ‚Ð° Ð½Ð°Ð´Ð°Ñ‚Ð¸ Ñ–Ð½Ñ„Ð¾Ñ€Ð¼Ð°Ñ†Ñ–ÑŽ Ð¿Ñ€Ð¾ ÐºÑƒÑ€ÑÐ¸ Ð²Ð¾Ð´Ñ–Ð½Ð½Ñ.'],
            ['h' => '4. ÐŸÐµÑ€ÐµÑÐ¸Ð»Ð°Ð½Ð½Ñ Ð·Ð°ÑÐ²Ð¾Ðº (ÐµÐ». Ð¿Ð¾ÑˆÑ‚Ð° Ñ‚Ð° Telegram)', 'p' => 'ÐŸÑ–ÑÐ»Ñ Ð¾Ñ‚Ñ€Ð¸Ð¼Ð°Ð½Ð½Ñ Ð²Ð°ÑˆÐ¾Ñ— Ð·Ð°ÑÐ²ÐºÐ¸ Ð½Ð° ÑÐ°Ð¹Ñ‚Ñ– Ñ—Ñ— Ð´Ð°Ð½Ñ– Ð°Ð²Ñ‚Ð¾Ð¼Ð°Ñ‚Ð¸Ñ‡Ð½Ð¾ Ð¿ÐµÑ€ÐµÑÐ¸Ð»Ð°ÑŽÑ‚ÑŒÑÑ ÐµÐ». Ð¿Ð¾ÑˆÑ‚Ð¾ÑŽ Ñ‚Ð° Ð¿Ð¾Ð²Ñ–Ð´Ð¾Ð¼Ð»ÐµÐ½Ð½ÑÐ¼ Ñƒ Telegram Ð²Ñ–Ð´Ð¿Ð¾Ð²Ñ–Ð´Ð°Ð»ÑŒÐ½Ð¸Ð¼ Ð¾ÑÐ¾Ð±Ð°Ð¼ ÑˆÐºÐ¾Ð»Ð¸, Ñ‰Ð¾Ð± Ð·Ð°ÑÐ²ÐºÑƒ Ð¼Ð¾Ð¶Ð½Ð° Ð±ÑƒÐ»Ð¾ ÑÐºÐ½Ð°Ð¹ÑˆÐ²Ð¸Ð´ÑˆÐµ Ð¾Ð±Ñ€Ð¾Ð±Ð¸Ñ‚Ð¸ Ð¹ Ð¿Ñ€Ð¸Ð¹Ð½ÑÑ‚Ð¸. Ð¦Ñ– Ð´Ð°Ð½Ñ– Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð¾Ð²ÑƒÑŽÑ‚ÑŒÑÑ Ð»Ð¸ÑˆÐµ Ð´Ð»Ñ Ð²Ð½ÑƒÑ‚Ñ€Ñ–ÑˆÐ½ÑŒÐ¾Ð³Ð¾ Ð¿Ñ€Ð¸Ð¹Ð¾Ð¼Ñƒ Ð·Ð°ÑÐ²Ð¾Ðº Ñ– Ð½Ðµ Ð¿ÑƒÐ±Ð»Ñ–ÐºÑƒÑŽÑ‚ÑŒÑÑ Ñ‚Ð° Ð½Ðµ Ð¿ÐµÑ€ÐµÐ´Ð°ÑŽÑ‚ÑŒÑÑ Ñ‚Ñ€ÐµÑ‚Ñ–Ð¼ Ð¾ÑÐ¾Ð±Ð°Ð¼.'],
            ['h' => '5. Ð¡Ñ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÐ° Ð²Ñ–Ð´Ð²Ñ–Ð´ÑƒÐ²Ð°Ð½Ð¾ÑÑ‚Ñ–', 'p' => 'ÐŸÐ¾ÑÑ‚Ð°Ñ‡Ð°Ð»ÑŒÐ½Ð¸Ðº Ð¿Ð¾ÑÐ»ÑƒÐ³ Ñ…Ð¾ÑÑ‚Ð¸Ð½Ð³Ñƒ ÑÐ°Ð¹Ñ‚Ñƒ Ð·Ð±Ð¸Ñ€Ð°Ñ” Ð·Ð°Ð³Ð°Ð»ÑŒÐ½Ñƒ, Ð·Ð²ÐµÐ´ÐµÐ½Ñƒ ÑÑ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÑƒ Ð²Ñ–Ð´Ð²Ñ–Ð´ÑƒÐ²Ð°Ð½Ð¾ÑÑ‚Ñ– (Ð½Ð°Ð¿Ñ€Ð¸ÐºÐ»Ð°Ð´, ÐºÑ–Ð»ÑŒÐºÑ–ÑÑ‚ÑŒ Ð²Ñ–Ð·Ð¸Ñ‚Ñ–Ð², Ð·Ð°Ð¿Ð¸Ñ‚Ð¸ ÑÑ‚Ð¾Ñ€Ñ–Ð½Ð¾Ðº Ñƒ Ð¶ÑƒÑ€Ð½Ð°Ð»Ð°Ñ… ÑÐµÑ€Ð²ÐµÑ€Ð°) Ð´Ð»Ñ Ñ‚ÐµÑ…Ð½Ñ–Ñ‡Ð½Ð¾Ð³Ð¾ Ð·Ð°Ð±ÐµÐ·Ð¿ÐµÑ‡ÐµÐ½Ð½Ñ Ñ€Ð¾Ð±Ð¾Ñ‚Ð¸ ÑÐ°Ð¹Ñ‚Ñƒ. Ð¦Ñ ÑÑ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÐ° Ð½Ðµ Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð¾Ð²ÑƒÑ”Ñ‚ÑŒÑÑ Ð´Ð»Ñ Ñ–Ð´ÐµÐ½Ñ‚Ð¸Ñ„Ñ–ÐºÐ°Ñ†Ñ–Ñ— Ð²Ð°ÑˆÐ¾Ñ— Ð¾ÑÐ¾Ð±Ð¸ Ñ– Ð½Ðµ Ð¿Ð¾Ð²\'ÑÐ·ÑƒÑ”Ñ‚ÑŒÑÑ Ð· Ð´Ð°Ð½Ð¸Ð¼Ð¸ ÐºÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð½Ð¾Ñ— Ñ„Ð¾Ñ€Ð¼Ð¸.'],
            ['h' => '6. Ð§Ð°Ñ‚-Ð°ÑÐ¸ÑÑ‚ÐµÐ½Ñ‚', 'p' => 'Ð§Ð°Ñ‚-Ð°ÑÐ¸ÑÑ‚ÐµÐ½Ñ‚, Ñ‰Ð¾ Ð¿Ñ€Ð°Ñ†ÑŽÑ” Ð½Ð° ÑÐ°Ð¹Ñ‚Ñ–, Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð¾Ð²ÑƒÑ” Ð¿Ð¾ÑÐ»ÑƒÐ³Ñƒ ÑˆÑ‚ÑƒÑ‡Ð½Ð¾Ð³Ð¾ Ñ–Ð½Ñ‚ÐµÐ»ÐµÐºÑ‚Ñƒ (OpenAI Ð°Ð±Ð¾ xAI) Ð´Ð»Ñ Ð¾Ð±Ñ€Ð¾Ð±ÐºÐ¸ Ð²Ð°ÑˆÐ¸Ñ… Ð¿Ð¸Ñ‚Ð°Ð½ÑŒ Ñ– Ð³ÐµÐ½ÐµÑ€Ð°Ñ†Ñ–Ñ— Ð²Ñ–Ð´Ð¿Ð¾Ð²Ñ–Ð´ÐµÐ¹. Ð’Ð²ÐµÐ´ÐµÐ½Ð¸Ð¹ Ð²Ð°Ð¼Ð¸ Ñ‚ÐµÐºÑÑ‚ Ð¿Ð¾Ð²Ñ–Ð´Ð¾Ð¼Ð»ÐµÐ½Ð½Ñ Ð¿ÐµÑ€ÐµÐ´Ð°Ñ”Ñ‚ÑŒÑÑ Ñ†Ñ–Ð¹ ÑÐ»ÑƒÐ¶Ð±Ñ– Ð»Ð¸ÑˆÐµ Ð´Ð»Ñ Ñ„Ð¾Ñ€Ð¼ÑƒÐ²Ð°Ð½Ð½Ñ Ð²Ñ–Ð´Ð¿Ð¾Ð²Ñ–Ð´Ñ– Ñ– Ð½Ðµ Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð¾Ð²ÑƒÑ”Ñ‚ÑŒÑÑ Ð² Ñ€ÐµÐºÐ»Ð°Ð¼Ð½Ð¸Ñ… Ñ†Ñ–Ð»ÑÑ….'],
            ['h' => '7. Ð’Ð°ÑˆÑ– Ð¿Ñ€Ð°Ð²Ð°', 'p' => 'Ð’Ð¸ Ð¼Ð°Ñ”Ñ‚Ðµ Ð¿Ñ€Ð°Ð²Ð¾ Ð·Ð°Ð¿Ñ€Ð¾ÑÐ¸Ñ‚Ð¸ Ð´Ð¾ÑÑ‚ÑƒÐ¿ Ð´Ð¾ ÑÐ²Ð¾Ñ—Ñ… Ð´Ð°Ð½Ð¸Ñ…, Ñ—Ñ… Ð²Ð¸Ð¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð½Ñ Ñ‡Ð¸ Ð²Ð¸Ð´Ð°Ð»ÐµÐ½Ð½Ñ. Ð”Ð»Ñ Ñ†ÑŒÐ¾Ð³Ð¾ Ð·Ð²ÐµÑ€Ñ‚Ð°Ð¹Ñ‚ÐµÑÑŒ Ð½Ð° ÐµÐ». Ð¿Ð¾ÑˆÑ‚Ñƒ ' . CONTACT_EMAIL_TO . '.'],
        ],
    ],
    'en' => [
        'html_lang' => 'en',
        'title' => 'Privacy Policy â€” ' . SITE_NAME,
        'home_url' => '/en/',
        'home_label' => 'Back to home',
        'h1' => 'Privacy Policy',
        'updated' => 'Last updated: 2026-07-29',
        'sections' => [
            ['h' => '1. Data controller', 'p' => SITE_NAME . ' (Vilniaus vairavimo mokykla), company code ' . COMPANY_CODE . ', address ' . COMPANY_ADDRESS . '.'],
            ['h' => '2. What data we collect', 'p' => 'Through the contact form we collect: your full name, phone number, email (if provided) and the message content. When you use the chat assistant, the text of your message is additionally stored.'],
            ['h' => '3. Purpose of use', 'p' => 'The data is used solely to contact you regarding your request, to process your course registration, and to provide information about driving courses.'],
            ['h' => '4. Forwarding requests (email and Telegram)', 'p' => 'When your request is received on the website, its data is automatically forwarded by email and Telegram message to the school\'s responsible staff so the request can be processed and accepted as quickly as possible. This data is used only for internal request handling and is not published or shared with third parties.'],
            ['h' => '5. Visitor statistics', 'p' => 'Our website hosting provider collects general, aggregated visitor statistics (e.g. visit counts, page requests in server logs) to keep the site running technically. This statistics data is not used to identify you personally and is not linked to contact-form data.'],
            ['h' => '6. Chat assistant', 'p' => 'The chat assistant on the site uses an AI service (OpenAI or xAI) to process your questions and generate replies. The message text you enter is sent to this service only to generate a reply and is not used for marketing purposes.'],
            ['h' => '7. Your rights', 'p' => 'You have the right to request access to, correction of, or deletion of your data. Please contact us at ' . CONTACT_EMAIL_TO . '.'],
        ],
    ],
    'ru' => [
        'html_lang' => 'ru',
        'title' => 'ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° ÐºÐ¾Ð½Ñ„Ð¸Ð´ÐµÐ½Ñ†Ð¸Ð°Ð»ÑŒÐ½Ð¾ÑÑ‚Ð¸ â€” ' . SITE_NAME,
        'home_url' => '/ru/',
        'home_label' => 'Ð’ÐµÑ€Ð½ÑƒÑ‚ÑŒÑÑ Ð½Ð° Ð³Ð»Ð°Ð²Ð½ÑƒÑŽ',
        'h1' => 'ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° ÐºÐ¾Ð½Ñ„Ð¸Ð´ÐµÐ½Ñ†Ð¸Ð°Ð»ÑŒÐ½Ð¾ÑÑ‚Ð¸',
        'updated' => 'ÐŸÐ¾ÑÐ»ÐµÐ´Ð½ÐµÐµ Ð¾Ð±Ð½Ð¾Ð²Ð»ÐµÐ½Ð¸Ðµ: 29.07.2026',
        'sections' => [
            ['h' => '1. ÐšÐ¾Ð½Ñ‚Ñ€Ð¾Ð»Ñ‘Ñ€ Ð´Ð°Ð½Ð½Ñ‹Ñ…', 'p' => SITE_NAME . ' (Vilniaus vairavimo mokykla), ÐºÐ¾Ð´ ÐºÐ¾Ð¼Ð¿Ð°Ð½Ð¸Ð¸ ' . COMPANY_CODE . ', Ð°Ð´Ñ€ÐµÑ ' . COMPANY_ADDRESS . '.'],
            ['h' => '2. ÐšÐ°ÐºÐ¸Ðµ Ð´Ð°Ð½Ð½Ñ‹Ðµ Ð¼Ñ‹ ÑÐ¾Ð±Ð¸Ñ€Ð°ÐµÐ¼', 'p' => 'Ð¡ Ð¿Ð¾Ð¼Ð¾Ñ‰ÑŒÑŽ ÐºÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð½Ð¾Ð¹ Ñ„Ð¾Ñ€Ð¼Ñ‹ Ð¼Ñ‹ ÑÐ¾Ð±Ð¸Ñ€Ð°ÐµÐ¼: Ð¸Ð¼Ñ Ð¸ Ñ„Ð°Ð¼Ð¸Ð»Ð¸ÑŽ, Ð½Ð¾Ð¼ÐµÑ€ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½Ð°, ÑÐ». Ð¿Ð¾Ñ‡Ñ‚Ñƒ (ÐµÑÐ»Ð¸ ÑƒÐºÐ°Ð·Ð°Ð½Ð°) Ð¸ ÑÐ¾Ð´ÐµÑ€Ð¶Ð°Ð½Ð¸Ðµ ÑÐ¾Ð¾Ð±Ñ‰ÐµÐ½Ð¸Ñ. ÐŸÑ€Ð¸ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ð½Ð¸Ð¸ Ñ‡Ð°Ñ‚-Ð°ÑÑÐ¸ÑÑ‚ÐµÐ½Ñ‚Ð° Ð´Ð¾Ð¿Ð¾Ð»Ð½Ð¸Ñ‚ÐµÐ»ÑŒÐ½Ð¾ ÑÐ¾Ñ…Ñ€Ð°Ð½ÑÐµÑ‚ÑÑ Ñ‚ÐµÐºÑÑ‚ Ð²Ð°ÑˆÐµÐ³Ð¾ ÑÐ¾Ð¾Ð±Ñ‰ÐµÐ½Ð¸Ñ.'],
            ['h' => '3. Ð¦ÐµÐ»ÑŒ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ð½Ð¸Ñ Ð´Ð°Ð½Ð½Ñ‹Ñ…', 'p' => 'Ð”Ð°Ð½Ð½Ñ‹Ðµ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·ÑƒÑŽÑ‚ÑÑ Ð¸ÑÐºÐ»ÑŽÑ‡Ð¸Ñ‚ÐµÐ»ÑŒÐ½Ð¾ Ð´Ð»Ñ Ñ‚Ð¾Ð³Ð¾, Ñ‡Ñ‚Ð¾Ð±Ñ‹ ÑÐ²ÑÐ·Ð°Ñ‚ÑŒÑÑ Ñ Ð²Ð°Ð¼Ð¸ Ð¿Ð¾ Ð·Ð°ÑÐ²ÐºÐµ, Ð¾Ð±Ñ€Ð°Ð±Ð¾Ñ‚Ð°Ñ‚ÑŒ Ð·Ð°Ð¿Ð¸ÑÑŒ Ð½Ð° ÐºÑƒÑ€Ñ Ð¸ Ð¿Ñ€ÐµÐ´Ð¾ÑÑ‚Ð°Ð²Ð¸Ñ‚ÑŒ Ð¸Ð½Ñ„Ð¾Ñ€Ð¼Ð°Ñ†Ð¸ÑŽ Ð¾ ÐºÑƒÑ€ÑÐ°Ñ… Ð²Ð¾Ð¶Ð´ÐµÐ½Ð¸Ñ.'],
            ['h' => '4. ÐŸÐµÑ€ÐµÑÑ‹Ð»ÐºÐ° Ð·Ð°ÑÐ²Ð¾Ðº (ÑÐ». Ð¿Ð¾Ñ‡Ñ‚Ð° Ð¸ Telegram)', 'p' => 'ÐŸÐ¾ÑÐ»Ðµ Ð¿Ð¾Ð»ÑƒÑ‡ÐµÐ½Ð¸Ñ Ð²Ð°ÑˆÐµÐ¹ Ð·Ð°ÑÐ²ÐºÐ¸ Ð½Ð° ÑÐ°Ð¹Ñ‚Ðµ ÐµÑ‘ Ð´Ð°Ð½Ð½Ñ‹Ðµ Ð°Ð²Ñ‚Ð¾Ð¼Ð°Ñ‚Ð¸Ñ‡ÐµÑÐºÐ¸ Ð¿ÐµÑ€ÐµÑÑ‹Ð»Ð°ÑŽÑ‚ÑÑ Ð¿Ð¾ ÑÐ». Ð¿Ð¾Ñ‡Ñ‚Ðµ Ð¸ ÑÐ¾Ð¾Ð±Ñ‰ÐµÐ½Ð¸ÐµÐ¼ Ð² Telegram Ð¾Ñ‚Ð²ÐµÑ‚ÑÑ‚Ð²ÐµÐ½Ð½Ñ‹Ð¼ Ð»Ð¸Ñ†Ð°Ð¼ ÑˆÐºÐ¾Ð»Ñ‹, Ñ‡Ñ‚Ð¾Ð±Ñ‹ Ð·Ð°ÑÐ²ÐºÐ° Ð±Ñ‹Ð»Ð° ÐºÐ°Ðº Ð¼Ð¾Ð¶Ð½Ð¾ Ð±Ñ‹ÑÑ‚Ñ€ÐµÐµ Ð¾Ð±Ñ€Ð°Ð±Ð¾Ñ‚Ð°Ð½Ð° Ð¸ Ð¿Ñ€Ð¸Ð½ÑÑ‚Ð°. Ð­Ñ‚Ð¸ Ð´Ð°Ð½Ð½Ñ‹Ðµ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·ÑƒÑŽÑ‚ÑÑ Ñ‚Ð¾Ð»ÑŒÐºÐ¾ Ð´Ð»Ñ Ð²Ð½ÑƒÑ‚Ñ€ÐµÐ½Ð½ÐµÐ³Ð¾ Ð¿Ñ€Ð¸Ñ‘Ð¼Ð° Ð·Ð°ÑÐ²Ð¾Ðº Ð¸ Ð½Ðµ Ð¿ÑƒÐ±Ð»Ð¸ÐºÑƒÑŽÑ‚ÑÑ Ð¸ Ð½Ðµ Ð¿ÐµÑ€ÐµÐ´Ð°ÑŽÑ‚ÑÑ Ñ‚Ñ€ÐµÑ‚ÑŒÐ¸Ð¼ Ð»Ð¸Ñ†Ð°Ð¼.'],
            ['h' => '5. Ð¡Ñ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÐ° Ð¿Ð¾ÑÐµÑ‰Ð°ÐµÐ¼Ð¾ÑÑ‚Ð¸', 'p' => 'ÐŸÐ¾ÑÑ‚Ð°Ð²Ñ‰Ð¸Ðº ÑƒÑÐ»ÑƒÐ³ Ñ…Ð¾ÑÑ‚Ð¸Ð½Ð³Ð° ÑÐ°Ð¹Ñ‚Ð° ÑÐ¾Ð±Ð¸Ñ€Ð°ÐµÑ‚ Ð¾Ð±Ñ‰ÑƒÑŽ, ÑÐ²Ð¾Ð´Ð½ÑƒÑŽ ÑÑ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÑƒ Ð¿Ð¾ÑÐµÑ‰Ð°ÐµÐ¼Ð¾ÑÑ‚Ð¸ (Ð½Ð°Ð¿Ñ€Ð¸Ð¼ÐµÑ€, ÐºÐ¾Ð»Ð¸Ñ‡ÐµÑÑ‚Ð²Ð¾ Ð²Ð¸Ð·Ð¸Ñ‚Ð¾Ð², Ð·Ð°Ð¿Ñ€Ð¾ÑÑ‹ ÑÑ‚Ñ€Ð°Ð½Ð¸Ñ† Ð² Ð¶ÑƒÑ€Ð½Ð°Ð»Ð°Ñ… ÑÐµÑ€Ð²ÐµÑ€Ð°) Ð´Ð»Ñ Ñ‚ÐµÑ…Ð½Ð¸Ñ‡ÐµÑÐºÐ¾Ð³Ð¾ Ð¾Ð±ÐµÑÐ¿ÐµÑ‡ÐµÐ½Ð¸Ñ Ñ€Ð°Ð±Ð¾Ñ‚Ñ‹ ÑÐ°Ð¹Ñ‚Ð°. Ð­Ñ‚Ð° ÑÑ‚Ð°Ñ‚Ð¸ÑÑ‚Ð¸ÐºÐ° Ð½Ðµ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·ÑƒÐµÑ‚ÑÑ Ð´Ð»Ñ Ð¸Ð´ÐµÐ½Ñ‚Ð¸Ñ„Ð¸ÐºÐ°Ñ†Ð¸Ð¸ Ð²Ð°ÑˆÐµÐ¹ Ð»Ð¸Ñ‡Ð½Ð¾ÑÑ‚Ð¸ Ð¸ Ð½Ðµ ÑÐ²ÑÐ·Ñ‹Ð²Ð°ÐµÑ‚ÑÑ Ñ Ð´Ð°Ð½Ð½Ñ‹Ð¼Ð¸ ÐºÐ¾Ð½Ñ‚Ð°ÐºÑ‚Ð½Ð¾Ð¹ Ñ„Ð¾Ñ€Ð¼Ñ‹.'],
            ['h' => '6. Ð§Ð°Ñ‚-Ð°ÑÑÐ¸ÑÑ‚ÐµÐ½Ñ‚', 'p' => 'Ð§Ð°Ñ‚-Ð°ÑÑÐ¸ÑÑ‚ÐµÐ½Ñ‚, Ñ€Ð°Ð±Ð¾Ñ‚Ð°ÑŽÑ‰Ð¸Ð¹ Ð½Ð° ÑÐ°Ð¹Ñ‚Ðµ, Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·ÑƒÐµÑ‚ ÑÐµÑ€Ð²Ð¸Ñ Ð¸ÑÐºÑƒÑÑÑ‚Ð²ÐµÐ½Ð½Ð¾Ð³Ð¾ Ð¸Ð½Ñ‚ÐµÐ»Ð»ÐµÐºÑ‚Ð° (OpenAI Ð¸Ð»Ð¸ xAI) Ð´Ð»Ñ Ð¾Ð±Ñ€Ð°Ð±Ð¾Ñ‚ÐºÐ¸ Ð²Ð°ÑˆÐ¸Ñ… Ð²Ð¾Ð¿Ñ€Ð¾ÑÐ¾Ð² Ð¸ Ð³ÐµÐ½ÐµÑ€Ð°Ñ†Ð¸Ð¸ Ð¾Ñ‚Ð²ÐµÑ‚Ð¾Ð². Ð’Ð²ÐµÐ´Ñ‘Ð½Ð½Ñ‹Ð¹ Ð²Ð°Ð¼Ð¸ Ñ‚ÐµÐºÑÑ‚ ÑÐ¾Ð¾Ð±Ñ‰ÐµÐ½Ð¸Ñ Ð¿ÐµÑ€ÐµÐ´Ð°Ñ‘Ñ‚ÑÑ ÑÑ‚Ð¾Ð¼Ñƒ ÑÐµÑ€Ð²Ð¸ÑÑƒ Ñ‚Ð¾Ð»ÑŒÐºÐ¾ Ð´Ð»Ñ Ñ„Ð¾Ñ€Ð¼Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ñ Ð¾Ñ‚Ð²ÐµÑ‚Ð° Ð¸ Ð½Ðµ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·ÑƒÐµÑ‚ÑÑ Ð² Ñ€ÐµÐºÐ»Ð°Ð¼Ð½Ñ‹Ñ… Ñ†ÐµÐ»ÑÑ….'],
            ['h' => '7. Ð’Ð°ÑˆÐ¸ Ð¿Ñ€Ð°Ð²Ð°', 'p' => 'Ð’Ñ‹ Ð¸Ð¼ÐµÐµÑ‚Ðµ Ð¿Ñ€Ð°Ð²Ð¾ Ð·Ð°Ð¿Ñ€Ð¾ÑÐ¸Ñ‚ÑŒ Ð´Ð¾ÑÑ‚ÑƒÐ¿ Ðº ÑÐ²Ð¾Ð¸Ð¼ Ð´Ð°Ð½Ð½Ñ‹Ð¼, Ð¸Ñ… Ð¸ÑÐ¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð¸Ðµ Ð¸Ð»Ð¸ ÑƒÐ´Ð°Ð»ÐµÐ½Ð¸Ðµ. Ð”Ð»Ñ ÑÑ‚Ð¾Ð³Ð¾ Ð¾Ð±Ñ€Ð°Ñ‰Ð°Ð¹Ñ‚ÐµÑÑŒ Ð½Ð° ÑÐ». Ð¿Ð¾Ñ‡Ñ‚Ñƒ ' . CONTACT_EMAIL_TO . '.'],
        ],
    ],
];

$c = $content[$lang];
?><!DOCTYPE html>
<html lang="<?= $c['html_lang'] ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($c['title']) ?></title>
<meta name="robots" content="noindex, follow">
<link rel="icon" href="<?= BASE_PATH ?>/assets/img/favicon-v3.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_url('/assets/css/style.css') ?>">
</head>
<body>
<header class="site-header scrolled">
  <div class="container header-inner">
    <a href="<?= $c['home_url'] ?>" class="logo">
      <span class="logo-mark"><img src="<?= BASE_PATH ?>/assets/img/logo-v3.webp" alt="" width="38" height="38"></span>
      <span class="logo-text"><?= htmlspecialchars(SITE_NAME) ?></span>
    </a>
    <a href="<?= $c['home_url'] ?>" class="btn btn-ghost btn-sm" style="margin-left:auto"><?= icon('arrow') ?> <?= htmlspecialchars($c['home_label']) ?></a>
  </div>
</header>
<main class="container section" style="max-width:820px">
  <h1><?= htmlspecialchars($c['h1']) ?></h1>
  <p class="lead" style="margin-bottom:2em"><?= htmlspecialchars($c['updated']) ?></p>
  <?php foreach ($c['sections'] as $s): ?>
    <h3><?= htmlspecialchars($s['h']) ?></h3>
    <p><?= htmlspecialchars($s['p']) ?></p>
  <?php endforeach; ?>
</main>
<footer class="site-footer">
  <div class="container footer-bottom">
    <span>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME) ?></span>
  </div>
</footer>
</body>
</html>
