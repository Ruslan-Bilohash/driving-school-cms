<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/settings.php';
$s = get_settings();

$content = [
    'lt' => [
        'html_lang' => 'lt', 'home_url' => '/', 'home_label' => 'GrÄ¯Å¾ti Ä¯ pradÅ¾iÄ…',
        'title' => 'Naudojimosi taisyklÄ—s ir paslaugÅ³ teikimo sÄ…lygos â€” ' . SITE_NAME,
        'h1' => 'Naudojimosi taisyklÄ—s ir paslaugÅ³ teikimo sÄ…lygos',
        'updated' => 'PaskutinÄ¯ kartÄ… atnaujinta: 2026-07-27',
        'part1_title' => 'I. SvetainÄ—s naudojimosi taisyklÄ—s',
        'part1' => [
            ['h' => '1. Bendrosios nuostatos', 'p' => 'Å ios taisyklÄ—s reglamentuoja svetainÄ—s autoteises.lt naudojimÄ…. Naudodamiesi svetaine, sutinkate su Å¡iomis sÄ…lygomis.'],
            ['h' => '2. IntelektinÄ— nuosavybÄ—', 'p' => 'Visas svetainÄ—s turinys (tekstai, logotipas, dizainas) priklauso ' . SITE_NAME . ' arba naudojamas teisÄ—tai. Kopijuoti be sutikimo draudÅ¾iama.'],
            ['h' => '3. AtsakomybÄ—s apribojimas', 'p' => 'Siekiame, kad informacija svetainÄ—je bÅ«tÅ³ tiksli, taÄiau negarantuojame jos absoliutaus tikslumo. DÄ—l konkreÄiÅ³ sÄ…lygÅ³ rekomenduojame pasitikslinti tiesiogiai.'],
            ['h' => '4. DraudÅ¾iami veiksmai', 'p' => 'DraudÅ¾iama naudoti svetainÄ™ neteisÄ—tiems tikslams, bandyti paÅ¾eisti jos saugumÄ… ar trikdyti veikimÄ….'],
            ['h' => '5. Taikoma teisÄ—', 'p' => 'Å ioms taisyklÄ—ms taikoma Lietuvos Respublikos teisÄ—. GinÄai sprendÅ¾iami derybÅ³ bÅ«du, o nepavykus susitarti â€” Lietuvos Respublikos teismuose.'],
        ],
        'part2_title' => 'II. Vairavimo kursÅ³ paslaugÅ³ teikimo taisyklÄ—s',
        'part2' => [
            ['h' => '1. Registracija Ä¯ kursÄ…', 'p' => 'Registracija vykdoma uÅ¾pildÅ¾ius formÄ… svetainÄ—je, el. paÅ¡tu arba telefonu. Sutartis dÄ—l paslaugÅ³ teikimo laikoma sudaryta nuo registracijos patvirtinimo.'],
            ['h' => '2. ApmokÄ—jimo tvarka', 'p' => 'Kurso kaina nurodoma svetainÄ—je ir gali bÅ«ti mokama dalimis arba iÅ¡ karto â€” konkreÄiÄ… tvarkÄ… aptariame registracijos metu.'],
            ['h' => '3. AtÅ¡aukimas ir perkÄ—limas', 'p' => 'Mokinys gali atÅ¡aukti ar perkelti pamokÄ… iÅ¡ anksto informavÄ™s instruktoriÅ³ ar mokyklos administracijÄ…. NepraneÅ¡us laiku, pamoka gali bÅ«ti laikoma Ä¯vykusia.'],
            ['h' => '4. Å aliÅ³ atsakomybÄ—', 'p' => SITE_NAME . ' Ä¯sipareigoja suteikti kokybiÅ¡kas vairavimo pamokas pagal galiojanÄius teisÄ—s aktus. Mokinys Ä¯sipareigoja laikytis saugaus eismo taisykliÅ³ ir instruktoriaus nurodymÅ³ pamokÅ³ metu.'],
            ['h' => '5. SkundÅ³ nagrinÄ—jimas', 'p' => 'Skundus dÄ—l paslaugÅ³ kokybÄ—s praÅ¡ome teikti el. paÅ¡tu ' . $s['email'] . '. Nepavykus iÅ¡sprÄ™sti ginÄo, vartotojas gali kreiptis Ä¯ ValstybinÄ™ vartotojÅ³ teisiÅ³ apsaugos tarnybÄ… (www.vvtat.lt) arba teismÄ….'],
        ],
        'contact_note' => 'KlausimÅ³ turintys asmenys gali kreiptis: ' . $s['email'] . ', ' . $s['phone1'],
    ],
    'uk' => [
        'html_lang' => 'uk', 'home_url' => '/uk/', 'home_label' => 'ÐŸÐ¾Ð²ÐµÑ€Ð½ÑƒÑ‚Ð¸ÑÑ Ð½Ð° Ð³Ð¾Ð»Ð¾Ð²Ð½Ñƒ',
        'title' => 'ÐŸÑ€Ð°Ð²Ð¸Ð»Ð° ÐºÐ¾Ñ€Ð¸ÑÑ‚ÑƒÐ²Ð°Ð½Ð½Ñ Ñ‚Ð° Ð½Ð°Ð´Ð°Ð½Ð½Ñ Ð¿Ð¾ÑÐ»ÑƒÐ³ â€” ' . SITE_NAME,
        'h1' => 'ÐŸÑ€Ð°Ð²Ð¸Ð»Ð° ÐºÐ¾Ñ€Ð¸ÑÑ‚ÑƒÐ²Ð°Ð½Ð½Ñ ÑÐ°Ð¹Ñ‚Ð¾Ð¼ Ñ‚Ð° Ð½Ð°Ð´Ð°Ð½Ð½Ñ Ð¿Ð¾ÑÐ»ÑƒÐ³',
        'updated' => 'ÐžÑÑ‚Ð°Ð½Ð½Ñ” Ð¾Ð½Ð¾Ð²Ð»ÐµÐ½Ð½Ñ: 27.07.2026',
        'part1_title' => 'I. ÐŸÑ€Ð°Ð²Ð¸Ð»Ð° ÐºÐ¾Ñ€Ð¸ÑÑ‚ÑƒÐ²Ð°Ð½Ð½Ñ ÑÐ°Ð¹Ñ‚Ð¾Ð¼',
        'part1' => [
            ['h' => '1. Ð—Ð°Ð³Ð°Ð»ÑŒÐ½Ñ– Ð¿Ð¾Ð»Ð¾Ð¶ÐµÐ½Ð½Ñ', 'p' => 'Ð¦Ñ– Ð¿Ñ€Ð°Ð²Ð¸Ð»Ð° Ñ€ÐµÐ³ÑƒÐ»ÑŽÑŽÑ‚ÑŒ Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð°Ð½Ð½Ñ ÑÐ°Ð¹Ñ‚Ñƒ autoteises.lt. ÐšÐ¾Ñ€Ð¸ÑÑ‚ÑƒÑŽÑ‡Ð¸ÑÑŒ ÑÐ°Ð¹Ñ‚Ð¾Ð¼, Ð²Ð¸ Ð¿Ð¾Ð³Ð¾Ð´Ð¶ÑƒÑ”Ñ‚ÐµÑÑŒ Ñ–Ð· Ñ†Ð¸Ð¼Ð¸ ÑƒÐ¼Ð¾Ð²Ð°Ð¼Ð¸.'],
            ['h' => '2. Ð†Ð½Ñ‚ÐµÐ»ÐµÐºÑ‚ÑƒÐ°Ð»ÑŒÐ½Ð° Ð²Ð»Ð°ÑÐ½Ñ–ÑÑ‚ÑŒ', 'p' => 'Ð’ÐµÑÑŒ ÐºÐ¾Ð½Ñ‚ÐµÐ½Ñ‚ ÑÐ°Ð¹Ñ‚Ñƒ (Ñ‚ÐµÐºÑÑ‚Ð¸, Ð»Ð¾Ð³Ð¾Ñ‚Ð¸Ð¿, Ð´Ð¸Ð·Ð°Ð¹Ð½) Ð½Ð°Ð»ÐµÐ¶Ð¸Ñ‚ÑŒ ' . SITE_NAME . ' Ð°Ð±Ð¾ Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð¾Ð²ÑƒÑ”Ñ‚ÑŒÑÑ Ð·Ð°ÐºÐ¾Ð½Ð½Ð¾. ÐšÐ¾Ð¿Ñ–ÑŽÐ²Ð°Ð½Ð½Ñ Ð±ÐµÐ· Ð·Ð³Ð¾Ð´Ð¸ Ð·Ð°Ð±Ð¾Ñ€Ð¾Ð½ÐµÐ½Ð¾.'],
            ['h' => '3. ÐžÐ±Ð¼ÐµÐ¶ÐµÐ½Ð½Ñ Ð²Ñ–Ð´Ð¿Ð¾Ð²Ñ–Ð´Ð°Ð»ÑŒÐ½Ð¾ÑÑ‚Ñ–', 'p' => 'ÐœÐ¸ Ð¿Ñ€Ð°Ð³Ð½ÐµÐ¼Ð¾, Ñ‰Ð¾Ð± Ñ–Ð½Ñ„Ð¾Ñ€Ð¼Ð°Ñ†Ñ–Ñ Ð½Ð° ÑÐ°Ð¹Ñ‚Ñ– Ð±ÑƒÐ»Ð° Ñ‚Ð¾Ñ‡Ð½Ð¾ÑŽ, Ð¿Ñ€Ð¾Ñ‚Ðµ Ð½Ðµ Ð³Ð°Ñ€Ð°Ð½Ñ‚ÑƒÑ”Ð¼Ð¾ Ñ—Ñ— Ð°Ð±ÑÐ¾Ð»ÑŽÑ‚Ð½Ð¾Ñ— Ñ‚Ð¾Ñ‡Ð½Ð¾ÑÑ‚Ñ–. Ð©Ð¾Ð´Ð¾ ÐºÐ¾Ð½ÐºÑ€ÐµÑ‚Ð½Ð¸Ñ… ÑƒÐ¼Ð¾Ð² Ñ€ÐµÐºÐ¾Ð¼ÐµÐ½Ð´ÑƒÑ”Ð¼Ð¾ ÑƒÑ‚Ð¾Ñ‡Ð½ÑŽÐ²Ð°Ñ‚Ð¸ Ð±ÐµÐ·Ð¿Ð¾ÑÐµÑ€ÐµÐ´Ð½ÑŒÐ¾.'],
            ['h' => '4. Ð—Ð°Ð±Ð¾Ñ€Ð¾Ð½ÐµÐ½Ñ– Ð´Ñ–Ñ—', 'p' => 'Ð—Ð°Ð±Ð¾Ñ€Ð¾Ð½ÐµÐ½Ð¾ Ð²Ð¸ÐºÐ¾Ñ€Ð¸ÑÑ‚Ð¾Ð²ÑƒÐ²Ð°Ñ‚Ð¸ ÑÐ°Ð¹Ñ‚ Ñƒ Ð½ÐµÐ·Ð°ÐºÐ¾Ð½Ð½Ð¸Ñ… Ñ†Ñ–Ð»ÑÑ…, Ð½Ð°Ð¼Ð°Ð³Ð°Ñ‚Ð¸ÑÑ Ð¿Ð¾Ñ€ÑƒÑˆÐ¸Ñ‚Ð¸ Ð¹Ð¾Ð³Ð¾ Ð±ÐµÐ·Ð¿ÐµÐºÑƒ Ñ‡Ð¸ Ð·Ð°Ð²Ð°Ð¶Ð°Ñ‚Ð¸ Ñ€Ð¾Ð±Ð¾Ñ‚Ñ–.'],
            ['h' => '5. Ð—Ð°ÑÑ‚Ð¾ÑÐ¾Ð²Ð½Ðµ Ð¿Ñ€Ð°Ð²Ð¾', 'p' => 'Ð”Ð¾ Ñ†Ð¸Ñ… Ð¿Ñ€Ð°Ð²Ð¸Ð» Ð·Ð°ÑÑ‚Ð¾ÑÐ¾Ð²ÑƒÑ”Ñ‚ÑŒÑÑ Ð¿Ñ€Ð°Ð²Ð¾ Ð›Ð¸Ñ‚Ð¾Ð²ÑÑŒÐºÐ¾Ñ— Ð ÐµÑÐ¿ÑƒÐ±Ð»Ñ–ÐºÐ¸. Ð¡Ð¿Ð¾Ñ€Ð¸ Ð²Ð¸Ñ€Ñ–ÑˆÑƒÑŽÑ‚ÑŒÑÑ ÑˆÐ»ÑÑ…Ð¾Ð¼ Ð¿ÐµÑ€ÐµÐ³Ð¾Ð²Ð¾Ñ€Ñ–Ð², Ð° Ð² Ñ€Ð°Ð·Ñ– Ð½ÐµÐ´Ð¾ÑÑÐ³Ð½ÐµÐ½Ð½Ñ Ð·Ð³Ð¾Ð´Ð¸ â€” Ñƒ ÑÑƒÐ´Ð°Ñ… Ð›Ð¸Ñ‚Ð¾Ð²ÑÑŒÐºÐ¾Ñ— Ð ÐµÑÐ¿ÑƒÐ±Ð»Ñ–ÐºÐ¸.'],
        ],
        'part2_title' => 'II. ÐŸÑ€Ð°Ð²Ð¸Ð»Ð° Ð½Ð°Ð´Ð°Ð½Ð½Ñ Ð¿Ð¾ÑÐ»ÑƒÐ³ Ð· Ð½Ð°Ð²Ñ‡Ð°Ð½Ð½Ñ Ð²Ð¾Ð´Ñ–Ð½Ð½ÑŽ',
        'part2' => [
            ['h' => '1. Ð ÐµÑ”ÑÑ‚Ñ€Ð°Ñ†Ñ–Ñ Ð½Ð° ÐºÑƒÑ€Ñ', 'p' => 'Ð ÐµÑ”ÑÑ‚Ñ€Ð°Ñ†Ñ–Ñ Ð·Ð´Ñ–Ð¹ÑÐ½ÑŽÑ”Ñ‚ÑŒÑÑ Ñ‡ÐµÑ€ÐµÐ· Ñ„Ð¾Ñ€Ð¼Ñƒ Ð½Ð° ÑÐ°Ð¹Ñ‚Ñ–, ÐµÐ». Ð¿Ð¾ÑˆÑ‚Ñƒ Ð°Ð±Ð¾ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½. Ð”Ð¾Ð³Ð¾Ð²Ñ–Ñ€ Ð¿Ñ€Ð¾ Ð½Ð°Ð´Ð°Ð½Ð½Ñ Ð¿Ð¾ÑÐ»ÑƒÐ³ Ð²Ð²Ð°Ð¶Ð°Ñ”Ñ‚ÑŒÑÑ ÑƒÐºÐ»Ð°Ð´ÐµÐ½Ð¸Ð¼ Ð· Ð¼Ð¾Ð¼ÐµÐ½Ñ‚Ñƒ Ð¿Ñ–Ð´Ñ‚Ð²ÐµÑ€Ð´Ð¶ÐµÐ½Ð½Ñ Ñ€ÐµÑ”ÑÑ‚Ñ€Ð°Ñ†Ñ–Ñ—.'],
            ['h' => '2. ÐŸÐ¾Ñ€ÑÐ´Ð¾Ðº Ð¾Ð¿Ð»Ð°Ñ‚Ð¸', 'p' => 'Ð’Ð°Ñ€Ñ‚Ñ–ÑÑ‚ÑŒ ÐºÑƒÑ€ÑÑƒ Ð²ÐºÐ°Ð·Ð°Ð½Ð° Ð½Ð° ÑÐ°Ð¹Ñ‚Ñ– Ñ– Ð¼Ð¾Ð¶Ðµ Ð¾Ð¿Ð»Ð°Ñ‡ÑƒÐ²Ð°Ñ‚Ð¸ÑÑŒ Ñ‡Ð°ÑÑ‚Ð¸Ð½Ð°Ð¼Ð¸ Ð°Ð±Ð¾ Ð¾Ð´Ñ€Ð°Ð·Ñƒ â€” ÐºÐ¾Ð½ÐºÑ€ÐµÑ‚Ð½Ð¸Ð¹ Ð¿Ð¾Ñ€ÑÐ´Ð¾Ðº ÑƒÐ·Ð³Ð¾Ð´Ð¶ÑƒÑ”Ñ‚ÑŒÑÑ Ð¿Ñ–Ð´ Ñ‡Ð°Ñ Ñ€ÐµÑ”ÑÑ‚Ñ€Ð°Ñ†Ñ–Ñ—.'],
            ['h' => '3. Ð¡ÐºÐ°ÑÑƒÐ²Ð°Ð½Ð½Ñ Ñ‚Ð° Ð¿ÐµÑ€ÐµÐ½ÐµÑÐµÐ½Ð½Ñ', 'p' => 'Ð£Ñ‡ÐµÐ½ÑŒ Ð¼Ð¾Ð¶Ðµ ÑÐºÐ°ÑÑƒÐ²Ð°Ñ‚Ð¸ Ð°Ð±Ð¾ Ð¿ÐµÑ€ÐµÐ½ÐµÑÑ‚Ð¸ Ð·Ð°Ð½ÑÑ‚Ñ‚Ñ, Ð¿Ð¾Ð¿ÐµÑ€ÐµÐ´Ð½ÑŒÐ¾ Ð¿Ð¾Ð²Ñ–Ð´Ð¾Ð¼Ð¸Ð²ÑˆÐ¸ Ñ–Ð½ÑÑ‚Ñ€ÑƒÐºÑ‚Ð¾Ñ€Ð° Ñ‡Ð¸ Ð°Ð´Ð¼Ñ–Ð½Ñ–ÑÑ‚Ñ€Ð°Ñ†Ñ–ÑŽ ÑˆÐºÐ¾Ð»Ð¸. Ð‘ÐµÐ· ÑÐ²Ð¾Ñ”Ñ‡Ð°ÑÐ½Ð¾Ð³Ð¾ Ð¿Ð¾Ð²Ñ–Ð´Ð¾Ð¼Ð»ÐµÐ½Ð½Ñ Ð·Ð°Ð½ÑÑ‚Ñ‚Ñ Ð¼Ð¾Ð¶Ðµ Ð²Ð²Ð°Ð¶Ð°Ñ‚Ð¸ÑÑ Ñ‚Ð°ÐºÐ¸Ð¼, Ñ‰Ð¾ Ð²Ñ–Ð´Ð±ÑƒÐ»Ð¾ÑÑ.'],
            ['h' => '4. Ð’Ñ–Ð´Ð¿Ð¾Ð²Ñ–Ð´Ð°Ð»ÑŒÐ½Ñ–ÑÑ‚ÑŒ ÑÑ‚Ð¾Ñ€Ñ–Ð½', 'p' => SITE_NAME . ' Ð·Ð¾Ð±Ð¾Ð²\'ÑÐ·ÑƒÑ”Ñ‚ÑŒÑÑ Ð½Ð°Ð´Ð°Ð²Ð°Ñ‚Ð¸ ÑÐºÑ–ÑÐ½Ñ– Ð·Ð°Ð½ÑÑ‚Ñ‚Ñ Ð· Ð²Ð¾Ð´Ñ–Ð½Ð½Ñ Ð²Ñ–Ð´Ð¿Ð¾Ð²Ñ–Ð´Ð½Ð¾ Ð´Ð¾ Ñ‡Ð¸Ð½Ð½Ð¾Ð³Ð¾ Ð·Ð°ÐºÐ¾Ð½Ð¾Ð´Ð°Ð²ÑÑ‚Ð²Ð°. Ð£Ñ‡ÐµÐ½ÑŒ Ð·Ð¾Ð±Ð¾Ð²\'ÑÐ·ÑƒÑ”Ñ‚ÑŒÑÑ Ð´Ð¾Ñ‚Ñ€Ð¸Ð¼ÑƒÐ²Ð°Ñ‚Ð¸ÑÑŒ Ð¿Ñ€Ð°Ð²Ð¸Ð» Ð±ÐµÐ·Ð¿ÐµÐºÐ¸ Ñ€ÑƒÑ…Ñƒ Ñ‚Ð° Ð²ÐºÐ°Ð·Ñ–Ð²Ð¾Ðº Ñ–Ð½ÑÑ‚Ñ€ÑƒÐºÑ‚Ð¾Ñ€Ð° Ð¿Ñ–Ð´ Ñ‡Ð°Ñ Ð·Ð°Ð½ÑÑ‚ÑŒ.'],
            ['h' => '5. Ð Ð¾Ð·Ð³Ð»ÑÐ´ ÑÐºÐ°Ñ€Ð³', 'p' => 'Ð¡ÐºÐ°Ñ€Ð³Ð¸ Ñ‰Ð¾Ð´Ð¾ ÑÐºÐ¾ÑÑ‚Ñ– Ð¿Ð¾ÑÐ»ÑƒÐ³ Ð¿Ñ€Ð¾ÑÐ¸Ð¼Ð¾ Ð½Ð°Ð´ÑÐ¸Ð»Ð°Ñ‚Ð¸ Ð½Ð° ÐµÐ». Ð¿Ð¾ÑˆÑ‚Ñƒ ' . $s['email'] . '. Ð£ Ñ€Ð°Ð·Ñ– Ð½ÐµÐ¼Ð¾Ð¶Ð»Ð¸Ð²Ð¾ÑÑ‚Ñ– Ð²Ð¸Ñ€Ñ–ÑˆÐ¸Ñ‚Ð¸ ÑÐ¿Ñ–Ñ€, ÑÐ¿Ð¾Ð¶Ð¸Ð²Ð°Ñ‡ Ð¼Ð¾Ð¶Ðµ Ð·Ð²ÐµÑ€Ð½ÑƒÑ‚Ð¸ÑÑ Ð´Ð¾ Ð”ÐµÑ€Ð¶Ð°Ð²Ð½Ð¾Ñ— ÑÐ»ÑƒÐ¶Ð±Ð¸ Ð·Ð°Ñ…Ð¸ÑÑ‚Ñƒ Ð¿Ñ€Ð°Ð² ÑÐ¿Ð¾Ð¶Ð¸Ð²Ð°Ñ‡Ñ–Ð² Ð›Ð¸Ñ‚Ð²Ð¸ (www.vvtat.lt) Ð°Ð±Ð¾ Ð´Ð¾ ÑÑƒÐ´Ñƒ.'],
        ],
        'contact_note' => 'Ð— Ð¿Ð¸Ñ‚Ð°Ð½Ð½ÑÐ¼Ð¸ Ð·Ð²ÐµÑ€Ñ‚Ð°Ð¹Ñ‚ÐµÑÑŒ: ' . $s['email'] . ', ' . $s['phone1'],
    ],
    'en' => [
        'html_lang' => 'en', 'home_url' => '/en/', 'home_label' => 'Back to home',
        'title' => 'Terms of Use and Service Provision Rules â€” ' . SITE_NAME,
        'h1' => 'Terms of Use and Service Provision Rules',
        'updated' => 'Last updated: 2026-07-27',
        'part1_title' => 'I. Website Terms of Use',
        'part1' => [
            ['h' => '1. General provisions', 'p' => 'These terms govern the use of the autoteises.lt website. By using the website, you agree to these terms.'],
            ['h' => '2. Intellectual property', 'p' => 'All website content (text, logo, design) belongs to ' . SITE_NAME . ' or is used lawfully. Copying without consent is prohibited.'],
            ['h' => '3. Limitation of liability', 'p' => 'We strive to keep the information on the website accurate, but we do not guarantee absolute accuracy. For specific conditions, please confirm directly with us.'],
            ['h' => '4. Prohibited actions', 'p' => 'It is prohibited to use the website for unlawful purposes, attempt to breach its security, or disrupt its operation.'],
            ['h' => '5. Governing law', 'p' => 'These terms are governed by the laws of the Republic of Lithuania. Disputes are resolved through negotiation, and failing that, in the courts of the Republic of Lithuania.'],
        ],
        'part2_title' => 'II. Driving Course Service Provision Rules',
        'part2' => [
            ['h' => '1. Course registration', 'p' => 'Registration is done via the website form, email, or phone. The service agreement is considered concluded once registration is confirmed.'],
            ['h' => '2. Payment terms', 'p' => 'The course price is stated on the website and may be paid in installments or in full â€” the specific arrangement is agreed upon at registration.'],
            ['h' => '3. Cancellation and rescheduling', 'p' => 'A student may cancel or reschedule a lesson by notifying the instructor or school administration in advance. Without timely notice, the lesson may be considered as having taken place.'],
            ['h' => '4. Responsibilities of the parties', 'p' => SITE_NAME . ' undertakes to provide quality driving lessons in accordance with applicable law. The student undertakes to follow road safety rules and the instructor\'s directions during lessons.'],
            ['h' => '5. Complaints handling', 'p' => 'Please submit complaints about service quality to ' . $s['email'] . '. If a dispute cannot be resolved, the consumer may contact the State Consumer Rights Protection Authority of Lithuania (www.vvtat.lt) or the courts.'],
        ],
        'contact_note' => 'For questions, please contact: ' . $s['email'] . ', ' . $s['phone1'],
    ],
    'ru' => [
        'html_lang' => 'ru', 'home_url' => '/ru/', 'home_label' => 'Ð’ÐµÑ€Ð½ÑƒÑ‚ÑŒÑÑ Ð½Ð° Ð³Ð»Ð°Ð²Ð½ÑƒÑŽ',
        'title' => 'ÐŸÑ€Ð°Ð²Ð¸Ð»Ð° Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ð½Ð¸Ñ Ð¸ Ð¿Ñ€ÐµÐ´Ð¾ÑÑ‚Ð°Ð²Ð»ÐµÐ½Ð¸Ñ ÑƒÑÐ»ÑƒÐ³ â€” ' . SITE_NAME,
        'h1' => 'ÐŸÑ€Ð°Ð²Ð¸Ð»Ð° Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ð½Ð¸Ñ ÑÐ°Ð¹Ñ‚Ð¾Ð¼ Ð¸ Ð¿Ñ€ÐµÐ´Ð¾ÑÑ‚Ð°Ð²Ð»ÐµÐ½Ð¸Ñ ÑƒÑÐ»ÑƒÐ³',
        'updated' => 'ÐŸÐ¾ÑÐ»ÐµÐ´Ð½ÐµÐµ Ð¾Ð±Ð½Ð¾Ð²Ð»ÐµÐ½Ð¸Ðµ: 27.07.2026',
        'part1_title' => 'I. ÐŸÑ€Ð°Ð²Ð¸Ð»Ð° Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ð½Ð¸Ñ ÑÐ°Ð¹Ñ‚Ð¾Ð¼',
        'part1' => [
            ['h' => '1. ÐžÐ±Ñ‰Ð¸Ðµ Ð¿Ð¾Ð»Ð¾Ð¶ÐµÐ½Ð¸Ñ', 'p' => 'Ð­Ñ‚Ð¸ Ð¿Ñ€Ð°Ð²Ð¸Ð»Ð° Ñ€ÐµÐ³ÑƒÐ»Ð¸Ñ€ÑƒÑŽÑ‚ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ð½Ð¸Ðµ ÑÐ°Ð¹Ñ‚Ð° autoteises.lt. Ð˜ÑÐ¿Ð¾Ð»ÑŒÐ·ÑƒÑ ÑÐ°Ð¹Ñ‚, Ð²Ñ‹ ÑÐ¾Ð³Ð»Ð°ÑˆÐ°ÐµÑ‚ÐµÑÑŒ Ñ ÑÑ‚Ð¸Ð¼Ð¸ ÑƒÑÐ»Ð¾Ð²Ð¸ÑÐ¼Ð¸.'],
            ['h' => '2. Ð˜Ð½Ñ‚ÐµÐ»Ð»ÐµÐºÑ‚ÑƒÐ°Ð»ÑŒÐ½Ð°Ñ ÑÐ¾Ð±ÑÑ‚Ð²ÐµÐ½Ð½Ð¾ÑÑ‚ÑŒ', 'p' => 'Ð’ÐµÑÑŒ ÐºÐ¾Ð½Ñ‚ÐµÐ½Ñ‚ ÑÐ°Ð¹Ñ‚Ð° (Ñ‚ÐµÐºÑÑ‚Ñ‹, Ð»Ð¾Ð³Ð¾Ñ‚Ð¸Ð¿, Ð´Ð¸Ð·Ð°Ð¹Ð½) Ð¿Ñ€Ð¸Ð½Ð°Ð´Ð»ÐµÐ¶Ð¸Ñ‚ ' . SITE_NAME . ' Ð¸Ð»Ð¸ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·ÑƒÐµÑ‚ÑÑ Ð·Ð°ÐºÐ¾Ð½Ð½Ð¾. ÐšÐ¾Ð¿Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ Ð±ÐµÐ· ÑÐ¾Ð³Ð»Ð°ÑÐ¸Ñ Ð·Ð°Ð¿Ñ€ÐµÑ‰ÐµÐ½Ð¾.'],
            ['h' => '3. ÐžÐ³Ñ€Ð°Ð½Ð¸Ñ‡ÐµÐ½Ð¸Ðµ Ð¾Ñ‚Ð²ÐµÑ‚ÑÑ‚Ð²ÐµÐ½Ð½Ð¾ÑÑ‚Ð¸', 'p' => 'ÐœÑ‹ ÑÑ‚Ñ€ÐµÐ¼Ð¸Ð¼ÑÑ, Ñ‡Ñ‚Ð¾Ð±Ñ‹ Ð¸Ð½Ñ„Ð¾Ñ€Ð¼Ð°Ñ†Ð¸Ñ Ð½Ð° ÑÐ°Ð¹Ñ‚Ðµ Ð±Ñ‹Ð»Ð° Ñ‚Ð¾Ñ‡Ð½Ð¾Ð¹, Ð¾Ð´Ð½Ð°ÐºÐ¾ Ð½Ðµ Ð³Ð°Ñ€Ð°Ð½Ñ‚Ð¸Ñ€ÑƒÐµÐ¼ ÐµÑ‘ Ð°Ð±ÑÐ¾Ð»ÑŽÑ‚Ð½Ð¾Ð¹ Ñ‚Ð¾Ñ‡Ð½Ð¾ÑÑ‚Ð¸. ÐŸÐ¾ ÐºÐ¾Ð½ÐºÑ€ÐµÑ‚Ð½Ñ‹Ð¼ ÑƒÑÐ»Ð¾Ð²Ð¸ÑÐ¼ Ñ€ÐµÐºÐ¾Ð¼ÐµÐ½Ð´ÑƒÐµÐ¼ ÑƒÑ‚Ð¾Ñ‡Ð½ÑÑ‚ÑŒ Ð½Ð°Ð¿Ñ€ÑÐ¼ÑƒÑŽ.'],
            ['h' => '4. Ð—Ð°Ð¿Ñ€ÐµÑ‰Ñ‘Ð½Ð½Ñ‹Ðµ Ð´ÐµÐ¹ÑÑ‚Ð²Ð¸Ñ', 'p' => 'Ð—Ð°Ð¿Ñ€ÐµÑ‰ÐµÐ½Ð¾ Ð¸ÑÐ¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÑŒ ÑÐ°Ð¹Ñ‚ Ð² Ð½ÐµÐ·Ð°ÐºÐ¾Ð½Ð½Ñ‹Ñ… Ñ†ÐµÐ»ÑÑ…, Ð¿Ñ‹Ñ‚Ð°Ñ‚ÑŒÑÑ Ð½Ð°Ñ€ÑƒÑˆÐ¸Ñ‚ÑŒ ÐµÐ³Ð¾ Ð±ÐµÐ·Ð¾Ð¿Ð°ÑÐ½Ð¾ÑÑ‚ÑŒ Ð¸Ð»Ð¸ Ð¼ÐµÑˆÐ°Ñ‚ÑŒ Ñ€Ð°Ð±Ð¾Ñ‚Ðµ.'],
            ['h' => '5. ÐŸÑ€Ð¸Ð¼ÐµÐ½Ð¸Ð¼Ð¾Ðµ Ð¿Ñ€Ð°Ð²Ð¾', 'p' => 'Ðš ÑÑ‚Ð¸Ð¼ Ð¿Ñ€Ð°Ð²Ð¸Ð»Ð°Ð¼ Ð¿Ñ€Ð¸Ð¼ÐµÐ½ÑÐµÑ‚ÑÑ Ð¿Ñ€Ð°Ð²Ð¾ Ð›Ð¸Ñ‚Ð¾Ð²ÑÐºÐ¾Ð¹ Ð ÐµÑÐ¿ÑƒÐ±Ð»Ð¸ÐºÐ¸. Ð¡Ð¿Ð¾Ñ€Ñ‹ Ñ€ÐµÑˆÐ°ÑŽÑ‚ÑÑ Ð¿ÑƒÑ‚Ñ‘Ð¼ Ð¿ÐµÑ€ÐµÐ³Ð¾Ð²Ð¾Ñ€Ð¾Ð², Ð° Ð¿Ñ€Ð¸ Ð½ÐµÐ´Ð¾ÑÑ‚Ð¸Ð¶ÐµÐ½Ð¸Ð¸ ÑÐ¾Ð³Ð»Ð°ÑÐ¸Ñ â€” Ð² ÑÑƒÐ´Ð°Ñ… Ð›Ð¸Ñ‚Ð¾Ð²ÑÐºÐ¾Ð¹ Ð ÐµÑÐ¿ÑƒÐ±Ð»Ð¸ÐºÐ¸.'],
        ],
        'part2_title' => 'II. ÐŸÑ€Ð°Ð²Ð¸Ð»Ð° Ð¿Ñ€ÐµÐ´Ð¾ÑÑ‚Ð°Ð²Ð»ÐµÐ½Ð¸Ñ ÑƒÑÐ»ÑƒÐ³ Ð¿Ð¾ Ð¾Ð±ÑƒÑ‡ÐµÐ½Ð¸ÑŽ Ð²Ð¾Ð¶Ð´ÐµÐ½Ð¸ÑŽ',
        'part2' => [
            ['h' => '1. Ð ÐµÐ³Ð¸ÑÑ‚Ñ€Ð°Ñ†Ð¸Ñ Ð½Ð° ÐºÑƒÑ€Ñ', 'p' => 'Ð ÐµÐ³Ð¸ÑÑ‚Ñ€Ð°Ñ†Ð¸Ñ Ð¾ÑÑƒÑ‰ÐµÑÑ‚Ð²Ð»ÑÐµÑ‚ÑÑ Ñ‡ÐµÑ€ÐµÐ· Ñ„Ð¾Ñ€Ð¼Ñƒ Ð½Ð° ÑÐ°Ð¹Ñ‚Ðµ, ÑÐ». Ð¿Ð¾Ñ‡Ñ‚Ñƒ Ð¸Ð»Ð¸ Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½. Ð”Ð¾Ð³Ð¾Ð²Ð¾Ñ€ Ð¾Ð± Ð¾ÐºÐ°Ð·Ð°Ð½Ð¸Ð¸ ÑƒÑÐ»ÑƒÐ³ ÑÑ‡Ð¸Ñ‚Ð°ÐµÑ‚ÑÑ Ð·Ð°ÐºÐ»ÑŽÑ‡Ñ‘Ð½Ð½Ñ‹Ð¼ Ñ Ð¼Ð¾Ð¼ÐµÐ½Ñ‚Ð° Ð¿Ð¾Ð´Ñ‚Ð²ÐµÑ€Ð¶Ð´ÐµÐ½Ð¸Ñ Ñ€ÐµÐ³Ð¸ÑÑ‚Ñ€Ð°Ñ†Ð¸Ð¸.'],
            ['h' => '2. ÐŸÐ¾Ñ€ÑÐ´Ð¾Ðº Ð¾Ð¿Ð»Ð°Ñ‚Ñ‹', 'p' => 'Ð¡Ñ‚Ð¾Ð¸Ð¼Ð¾ÑÑ‚ÑŒ ÐºÑƒÑ€ÑÐ° ÑƒÐºÐ°Ð·Ð°Ð½Ð° Ð½Ð° ÑÐ°Ð¹Ñ‚Ðµ Ð¸ Ð¼Ð¾Ð¶ÐµÑ‚ Ð¾Ð¿Ð»Ð°Ñ‡Ð¸Ð²Ð°Ñ‚ÑŒÑÑ Ñ‡Ð°ÑÑ‚ÑÐ¼Ð¸ Ð¸Ð»Ð¸ ÑÑ€Ð°Ð·Ñƒ â€” ÐºÐ¾Ð½ÐºÑ€ÐµÑ‚Ð½Ñ‹Ð¹ Ð¿Ð¾Ñ€ÑÐ´Ð¾Ðº ÑÐ¾Ð³Ð»Ð°ÑÐ¾Ð²Ñ‹Ð²Ð°ÐµÑ‚ÑÑ Ð¿Ñ€Ð¸ Ñ€ÐµÐ³Ð¸ÑÑ‚Ñ€Ð°Ñ†Ð¸Ð¸.'],
            ['h' => '3. ÐžÑ‚Ð¼ÐµÐ½Ð° Ð¸ Ð¿ÐµÑ€ÐµÐ½Ð¾Ñ', 'p' => 'Ð£Ñ‡ÐµÐ½Ð¸Ðº Ð¼Ð¾Ð¶ÐµÑ‚ Ð¾Ñ‚Ð¼ÐµÐ½Ð¸Ñ‚ÑŒ Ð¸Ð»Ð¸ Ð¿ÐµÑ€ÐµÐ½ÐµÑÑ‚Ð¸ Ð·Ð°Ð½ÑÑ‚Ð¸Ðµ, Ð·Ð°Ñ€Ð°Ð½ÐµÐµ ÑƒÐ²ÐµÐ´Ð¾Ð¼Ð¸Ð² Ð¸Ð½ÑÑ‚Ñ€ÑƒÐºÑ‚Ð¾Ñ€Ð° Ð¸Ð»Ð¸ Ð°Ð´Ð¼Ð¸Ð½Ð¸ÑÑ‚Ñ€Ð°Ñ†Ð¸ÑŽ ÑˆÐºÐ¾Ð»Ñ‹. Ð‘ÐµÐ· ÑÐ²Ð¾ÐµÐ²Ñ€ÐµÐ¼ÐµÐ½Ð½Ð¾Ð³Ð¾ ÑƒÐ²ÐµÐ´Ð¾Ð¼Ð»ÐµÐ½Ð¸Ñ Ð·Ð°Ð½ÑÑ‚Ð¸Ðµ Ð¼Ð¾Ð¶ÐµÑ‚ ÑÑ‡Ð¸Ñ‚Ð°Ñ‚ÑŒÑÑ ÑÐ¾ÑÑ‚Ð¾ÑÐ²ÑˆÐ¸Ð¼ÑÑ.'],
            ['h' => '4. ÐžÑ‚Ð²ÐµÑ‚ÑÑ‚Ð²ÐµÐ½Ð½Ð¾ÑÑ‚ÑŒ ÑÑ‚Ð¾Ñ€Ð¾Ð½', 'p' => SITE_NAME . ' Ð¾Ð±ÑÐ·ÑƒÐµÑ‚ÑÑ Ð¿Ñ€ÐµÐ´Ð¾ÑÑ‚Ð°Ð²Ð»ÑÑ‚ÑŒ ÐºÐ°Ñ‡ÐµÑÑ‚Ð²ÐµÐ½Ð½Ñ‹Ðµ Ð·Ð°Ð½ÑÑ‚Ð¸Ñ Ð¿Ð¾ Ð²Ð¾Ð¶Ð´ÐµÐ½Ð¸ÑŽ Ð² ÑÐ¾Ð¾Ñ‚Ð²ÐµÑ‚ÑÑ‚Ð²Ð¸Ð¸ Ñ Ð´ÐµÐ¹ÑÑ‚Ð²ÑƒÑŽÑ‰Ð¸Ð¼ Ð·Ð°ÐºÐ¾Ð½Ð¾Ð´Ð°Ñ‚ÐµÐ»ÑŒÑÑ‚Ð²Ð¾Ð¼. Ð£Ñ‡ÐµÐ½Ð¸Ðº Ð¾Ð±ÑÐ·ÑƒÐµÑ‚ÑÑ ÑÐ¾Ð±Ð»ÑŽÐ´Ð°Ñ‚ÑŒ Ð¿Ñ€Ð°Ð²Ð¸Ð»Ð° Ð±ÐµÐ·Ð¾Ð¿Ð°ÑÐ½Ð¾ÑÑ‚Ð¸ Ð´Ð²Ð¸Ð¶ÐµÐ½Ð¸Ñ Ð¸ ÑƒÐºÐ°Ð·Ð°Ð½Ð¸Ñ Ð¸Ð½ÑÑ‚Ñ€ÑƒÐºÑ‚Ð¾Ñ€Ð° Ð²Ð¾ Ð²Ñ€ÐµÐ¼Ñ Ð·Ð°Ð½ÑÑ‚Ð¸Ð¹.'],
            ['h' => '5. Ð Ð°ÑÑÐ¼Ð¾Ñ‚Ñ€ÐµÐ½Ð¸Ðµ Ð¶Ð°Ð»Ð¾Ð±', 'p' => 'Ð–Ð°Ð»Ð¾Ð±Ñ‹ Ð¿Ð¾ ÐºÐ°Ñ‡ÐµÑÑ‚Ð²Ñƒ ÑƒÑÐ»ÑƒÐ³ Ð¿Ñ€Ð¾ÑÐ¸Ð¼ Ð½Ð°Ð¿Ñ€Ð°Ð²Ð»ÑÑ‚ÑŒ Ð½Ð° ÑÐ». Ð¿Ð¾Ñ‡Ñ‚Ñƒ ' . $s['email'] . '. Ð’ ÑÐ»ÑƒÑ‡Ð°Ðµ Ð½ÐµÐ²Ð¾Ð·Ð¼Ð¾Ð¶Ð½Ð¾ÑÑ‚Ð¸ Ñ€ÐµÑˆÐ¸Ñ‚ÑŒ ÑÐ¿Ð¾Ñ€, Ð¿Ð¾Ñ‚Ñ€ÐµÐ±Ð¸Ñ‚ÐµÐ»ÑŒ Ð¼Ð¾Ð¶ÐµÑ‚ Ð¾Ð±Ñ€Ð°Ñ‚Ð¸Ñ‚ÑŒÑÑ Ð² Ð“Ð¾ÑÑƒÐ´Ð°Ñ€ÑÑ‚Ð²ÐµÐ½Ð½ÑƒÑŽ ÑÐ»ÑƒÐ¶Ð±Ñƒ Ð·Ð°Ñ‰Ð¸Ñ‚Ñ‹ Ð¿Ñ€Ð°Ð² Ð¿Ð¾Ñ‚Ñ€ÐµÐ±Ð¸Ñ‚ÐµÐ»ÐµÐ¹ Ð›Ð¸Ñ‚Ð²Ñ‹ (www.vvtat.lt) Ð¸Ð»Ð¸ Ð² ÑÑƒÐ´.'],
        ],
        'contact_note' => 'ÐŸÐ¾ Ð²Ð¾Ð¿Ñ€Ð¾ÑÐ°Ð¼ Ð¾Ð±Ñ€Ð°Ñ‰Ð°Ð¹Ñ‚ÐµÑÑŒ: ' . $s['email'] . ', ' . $s['phone1'],
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

  <h2><?= htmlspecialchars($c['part1_title']) ?></h2>
  <?php foreach ($c['part1'] as $sec): ?>
    <h3><?= htmlspecialchars($sec['h']) ?></h3>
    <p><?= htmlspecialchars($sec['p']) ?></p>
  <?php endforeach; ?>

  <hr style="border:none;border-top:1px solid var(--border);margin:36px 0">

  <h2><?= htmlspecialchars($c['part2_title']) ?></h2>
  <?php foreach ($c['part2'] as $sec): ?>
    <h3><?= htmlspecialchars($sec['h']) ?></h3>
    <p><?= htmlspecialchars($sec['p']) ?></p>
  <?php endforeach; ?>

  <p class="muted" style="margin-top:2em;color:var(--text-muted)"><?= htmlspecialchars($c['contact_note']) ?></p>
</main>
<footer class="site-footer">
  <div class="container footer-bottom">
    <span>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME) ?></span>
  </div>
</footer>
</body>
</html>
