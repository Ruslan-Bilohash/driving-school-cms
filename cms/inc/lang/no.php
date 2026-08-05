<?php
return [
  'html_lang' => 'nb',
  'locale' => 'nb_NO',
  'dir_prefix' => '',
  'self_label' => 'NO',

  'meta_title' => 'Nordisk Trafikkskole — Demo kjøreskole Oslo | Klasse B, A, C, CE, D',
  'meta_description' => 'Nordisk Trafikkskole — demo kjøreskole i Oslo. Kurs for klasse B, A/A1/A2, C, C+CE, C+CE+D, D, CE, BE og førerkortgjenoppretting. Fiktiv BILOHASH-demo.',
  'meta_keywords' => 'kjøreskole Oslo, trafikkskole Norge, førerkort klasse B, motorsykkel A1 A2, lastebil C CE, bilohash demo',
  'og_title' => 'Nordisk Trafikkskole — Demo kjøreskole i Oslo',
  'og_description' => 'Demo kjøreskole i Oslo. Alle klasser B–CE, priser i NOK, flerspråklig. Ikke en ekte skole.',

  'skip_link' => 'Hopp til innhold',

  'nav' => [
    'home' => 'Hjem',
    'about' => 'Om oss',
    'categories' => 'Klasser',
    'prices' => 'Priser',
    'process' => 'Slik funker det',
    'faq' => 'FAQ',
    'news' => 'Nyheter',
    'gallery' => 'Galleri',
    'contact' => 'Kontakt',
    'cta' => 'Meld deg på',
  ],

  'gallery_page' => [
    'title' => 'Fotogalleri',
    'meta_description' => 'Nordisk Trafikkskole fotogalleri — biler, instruktører og opplæring.',
    'empty' => 'Ingen bilder ennå.',
  ],
  'news_page' => [
    'title' => 'Nyheter',
    'meta_description' => 'Nyheter fra Nordisk Trafikkskole — tips og oppdateringer.',
    'empty' => 'Ingen nyheter ennå.',
    'read_more' => 'Les mer',
    'back' => 'Tilbake til nyheter',
  ],

  'hero' => [
    'eyebrow' => 'Demo kjøreskole i Oslo',
    'title_line1' => 'Ta førerkortet',
    'title_line2' => 'uten stress',
    'subtitle' => 'Kurs for klasse B, A/A1/A2, C, C+CE, C+CE+D, D, CE, BE og gjenoppretting av førerkort. Erfarne instruktører, tydelige priser, fleksibel timeplan.',
    'cta_primary' => 'Meld deg på kurs',
    'cta_secondary' => 'Se priser',
    'stats' => [
      ['num' => '15+', 'label' => 'års erfaring'],
      ['num' => '9', 'label' => 'førerkortklasser'],
      ['num' => '1000+', 'label' => 'elever (demo)'],
      ['num' => '7', 'label' => 'kjøretøy i flåten'],
    ],
  ],

  'about' => [
    'eyebrow' => 'Om oss',
    'title' => 'Hvorfor Nordisk Trafikkskole',
    'text' => 'Nordisk Trafikkskole er en demo-kjøreskole i Oslo — fiktiv BILOHASH-portfolio. Hver elev får individuell oppfølging fra teori til eksamen hos Statens vegvesen.',
    'features' => [
      ['icon' => 'shield', 'title' => 'Erfarne instruktører', 'text' => 'Kvalifiserte instruktører med erfaring i alle klasser.'],
      ['icon' => 'coin', 'title' => 'Tydelige priser', 'text' => 'Ingen skjulte gebyrer — samme pris for praksis eller teori + praksis.'],
      ['icon' => 'calendar', 'title' => 'Fleksibel timeplan', 'text' => 'Vi tilpasser timer etter deg — også kveld og helg.'],
      ['icon' => 'refresh', 'title' => 'Gjenoppretting av førerkort', 'text' => 'Hjelp til deg som har mistet førerkortet og vil ha det tilbake.'],
    ],
  ],

  'categories' => [
    'eyebrow' => 'Klasser',
    'title' => 'Kurs for alle klasser',
    'subtitle' => 'Fra motorsykkel til tung bil med henger — velg din klasse.',
    'items' => [
      ['code' => 'B', 'title' => 'Klasse B', 'text' => 'Personbil — mest populært for nybegynnere.', 'icon' => 'car'],
      ['code' => 'A·A1·A2', 'title' => 'Motorsykkel', 'text' => 'Motorsykkelkurs for A1, A2 og A.', 'icon' => 'moto'],
      ['code' => 'C', 'title' => 'Klasse C', 'text' => 'Lastebil — for profesjonell kjøring.', 'icon' => 'truck'],
      ['code' => 'C+CE', 'title' => 'Klasse C+CE', 'text' => 'Lastebil med henger for langtransport.', 'icon' => 'truck-trailer'],
      ['code' => 'C+CE+D', 'title' => 'Klasse C+CE+D', 'text' => 'Full pakke for profesjonell sjåfør.', 'icon' => 'bus-truck'],
      ['code' => 'D', 'title' => 'Klasse D', 'text' => 'Buss — persontransport.', 'icon' => 'bus'],
      ['code' => 'CE', 'title' => 'Klasse CE', 'text' => 'Lastebil med henger for erfarne C-sjåfører.', 'icon' => 'truck-trailer'],
      ['code' => 'BE', 'title' => 'Klasse BE', 'text' => 'Personbil med henger.', 'icon' => 'car-trailer'],
      ['code' => '↺', 'title' => 'Gjenoppretting', 'text' => 'Program for å få tilbake mistet førerkort.', 'icon' => 'refresh'],
    ],
  ],

  'prices' => [
    'eyebrow' => 'Priser',
    'title' => 'Tydelige og ærlige priser',
    'subtitle' => 'Samme pris enten du tar bare praksis eller teori med praksis.',
    'full_course_title' => 'Priser fullt kurs',
    'extra_title' => 'Priser ekstra time',
    'col_category' => 'Klasse',
    'col_price' => 'Pris',
    'full_course' => [
      ['key' => 'B', 'cat' => 'B', 'price' => '18 900 NOK'],
      ['key' => 'A', 'cat' => 'A, A1, A2', 'price' => '12 900 NOK'],
      ['key' => 'C', 'cat' => 'C', 'price' => '24 900 NOK'],
      ['key' => 'CCE', 'cat' => 'C+CE', 'price' => '34 900 NOK'],
      ['key' => 'CCED', 'cat' => 'C+CE+D', 'price' => '44 900 NOK'],
      ['key' => 'D', 'cat' => 'D', 'price' => '27 900 NOK'],
      ['key' => 'CE', 'cat' => 'CE', 'price' => '19 900 NOK'],
      ['key' => 'BE', 'cat' => 'BE', 'price' => '9 900 NOK'],
      ['key' => 'LOST', 'cat' => 'Gjenoppretting', 'price' => '4 900 NOK'],
    ],
    'extra_lessons' => [
      ['key' => 'B', 'cat' => 'B', 'unit' => '(2 und.timer)', 'price' => '890 NOK'],
      ['key' => 'A', 'cat' => 'A, A1, A2', 'unit' => '(2 und.timer)', 'price' => '890 NOK'],
      ['key' => 'CCE', 'cat' => 'C, CE', 'unit' => '(2 und.timer)', 'price' => '1 190 NOK'],
      ['key' => 'D', 'cat' => 'D', 'unit' => '(2 und.timer)', 'price' => '1 490 NOK'],
      ['key' => 'BE', 'cat' => 'BE', 'unit' => '(2 und.timer)', 'price' => '990 NOK'],
    ],
    'note' => '* Demo-priser i NOK. Ikke et ekte tilbud.',
    'cta' => 'Meld deg på nå',
  ],

  'process' => [
    'eyebrow' => 'Slik funker opplæringen',
    'title' => 'Veien til førerkortet',
    'steps' => [
      ['num' => '01', 'title' => 'Meld deg på', 'text' => 'Fyll ut skjema eller ring — vi avklarer gruppe og timeplan.'],
      ['num' => '02', 'title' => 'Teori', 'text' => 'Trafikkregler og teori for trygg kjøring.'],
      ['num' => '03', 'title' => 'Praksis', 'text' => 'Kjøretimer med instruktør i Oslo-området.'],
      ['num' => '04', 'title' => 'Eksamen', 'text' => 'Forberedelse og eksamen hos Statens vegvesen.'],
    ],
  ],

  'manager' => [
    'eyebrow' => 'Ledelse',
    'title' => 'Eier / direktør for kjøreskolen',
    'name' => 'Skoleeier / daglig leder',
    'role' => 'Eier eller direktør for kjøreskolen (demo)',
    'text' => 'Spørsmål om opplæring, grupper eller timeplan — ta kontakt med skolen. Portefølje-demo, ikke en ekte person.',
  ],

  'reviews' => [
    'eyebrow' => 'Anmeldelser',
    'title' => 'Hva elevene sier',
    'text' => 'Ekte anmeldelser kan vises på Google-profilen (demo).',
    'cta' => 'Se anmeldelser på Google',
  ],

  'faq' => [
    'eyebrow' => 'FAQ',
    'title' => 'Ofte stilte spørsmål',
    'items' => [
      ['q' => 'Hvor lang tid tar klasse B?', 'a' => 'Standardkurs tar omtrent 2–3 måneder, avhengig av timeplan.'],
      ['q' => 'Er prisen annerledes for bare praksis?', 'a' => 'Nei. Fullkursprisen er den samme for praksis alene eller teori + praksis.'],
      ['q' => 'Hva koster en ekstra time?', 'a' => 'Fra ca. 890 NOK for klasse B (2 undervisningstimer) — se priser.'],
      ['q' => 'Hjelper dere med gjenoppretting av førerkort?', 'a' => 'Ja, vi har et eget program for gjenoppretting.'],
      ['q' => 'Hvordan melder jeg meg på?', 'a' => 'Fyll ut skjemaet, send e-post eller ring — vi svarer raskt.'],
      ['q' => 'Hvilke språk tilbys?', 'a' => 'Demo støtter norsk, engelsk, svensk, polsk, ukrainsk og russisk.'],
    ],
  ],

  'contact' => [
    'eyebrow' => 'Kontakt',
    'title' => 'Ta kontakt',
    'subtitle' => 'Legg igjen en forespørsel — vi tar kontakt og hjelper deg å velge klasse.',
    'address_label' => 'Adresse',
    'phone_label' => 'Telefon',
    'email_label' => 'E-post',
    'hours_label' => 'Åpningstider',
    'form_name' => 'Fullt navn',
    'form_phone' => 'Telefonnummer',
    'form_email' => 'E-post (valgfritt)',
    'form_category' => 'Velg klasse',
    'form_message' => 'Melding (valgfritt)',
    'form_consent' => 'Jeg godtar at dataene mine brukes for å kontakte meg om denne forespørselen. Les mer: ',
    'form_privacy_link' => 'Personvern',
    'form_submit' => 'Send forespørsel',
    'form_sending' => 'Sender...',
    'form_success' => 'Takk! Forespørselen er mottatt — vi tar kontakt snart.',
    'form_error' => 'Kunne ikke sende. Prøv igjen eller skriv til demo@bilohash.com',
    'form_error_fields' => 'Fyll inn påkrevde felt.',
    'form_recaptcha_required' => 'Bekreft at du ikke er en robot.',
    'map_title' => 'Vår plassering på kartet',
  ],

  'footer' => [
    'about' => 'Nordisk Trafikkskole — demo kjøreskole i Oslo. Kurs for alle klasser og gjenoppretting av førerkort.',
    'nav_title' => 'Lenker',
    'contact_title' => 'Kontakt',
    'legal' => 'Org.nr: DEMO — kun portefølje-demo',
    'privacy' => 'Personvern',
    'terms' => 'Vilkår',
    'rights' => 'Alle rettigheter reservert.',
    'dev_credit' => 'Nettside av <strong>bilohash.com</strong> — profesjonell utvikling',
  ],

  'cat_page' => [
    'requirements' => 'Krav',
    'includes' => 'Inkludert i kurset',
    'breadcrumb_home' => 'Hjem',
  ],

  'callback' => [
    'title' => 'Vil du bli oppringt?',
    'text' => 'Legg igjen navn og telefon — vi ringer deg snart.',
    'name' => 'Navn',
    'phone' => 'Telefon',
    'submit' => 'Ring meg',
    'dismiss' => 'Nei takk',
    'consent' => 'Jeg godtar at dataene mine brukes for kontakt.',
    'success' => 'Takk! Vi tar kontakt snart.',
  ],

  'cookies' => [
    'text' => 'Vi bruker nødvendige informasjonskapsler for at nettstedet skal fungere. Ingen analyse- eller markedsføringscookies.',
    'accept' => 'Forstått',
    'link' => 'Personvern',
  ],

  'chat' => [
    'title' => 'Nordisk Trafikkskole-assistent',
    'greeting' => 'Hei! Hvordan kan jeg hjelpe med førerkortkurs?',
    'placeholder' => 'Skriv en melding...',
    'send' => 'Send',
    'error' => 'Kan ikke svare nå. Kontakt: ',
    'thinking' => 'Skriver...',
  ],

  'back_to_top' => 'Til toppen',
  '404_title' => 'Siden finnes ikke',
  '404_text' => 'Denne siden finnes ikke eller er flyttet.',
  '404_cta' => 'Til forsiden',
  'district_page' => [
    'other_areas' => 'Andre områder i Oslo vi dekker',
  ],
];
