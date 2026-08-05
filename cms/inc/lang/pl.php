<?php
return [
  'html_lang' => 'pl',
  'locale' => 'pl_PL',
  'dir_prefix' => '/pl',
  'self_label' => 'PL',

  'meta_title' => 'Nordisk Trafikkskole — Demo szkoła jazdy Oslo | Kat. B, A, C, CE, D',
  'meta_description' => 'Nordisk Trafikkskole — demo szkoła jazdy w Oslo. Kursy kategorii B, A/A1/A2, C, CE, D, BE oraz odzyskanie prawa jazdy. Fikcyjne demo BILOHASH.',
  'meta_keywords' => 'szkoła jazdy Oslo, prawo jazdy kategoria B, motocykl A1 A2, ciężarówka C CE, bilohash demo',
  'og_title' => 'Nordisk Trafikkskole — Demo szkoła jazdy w Oslo',
  'og_description' => 'Demo szkoła jazdy w Oslo. Kategorie B–CE, ceny w NOK. To nie jest prawdziwa szkoła.',

  'skip_link' => 'Przejdź do treści',

  'nav' => [
    'home' => 'Start',
    'about' => 'O nas',
    'categories' => 'Kategorie',
    'prices' => 'Cennik',
    'process' => 'Jak to działa',
    'faq' => 'FAQ',
    'news' => 'Aktualności',
    'gallery' => 'Galeria',
    'contact' => 'Kontakt',
    'cta' => 'Zapisz się',
  ],

  'gallery_page' => [
    'title' => 'Galeria zdjęć',
    'meta_description' => 'Galeria Nordisk Trafikkskole — samochody, instruktorzy i nauka jazdy.',
    'empty' => 'Brak zdjęć.',
  ],
  'news_page' => [
    'title' => 'Aktualności',
    'meta_description' => 'Aktualności Nordisk Trafikkskole — porady i informacje.',
    'empty' => 'Brak aktualności.',
    'read_more' => 'Czytaj więcej',
    'back' => 'Wróć do aktualności',
  ],

  'hero' => [
    'eyebrow' => 'Demo szkoła jazdy w Oslo',
    'title_line1' => 'Zrób prawo jazdy',
    'title_line2' => 'bez stresu',
    'subtitle' => 'Kursy kategorii B, A/A1/A2, C, C+CE, C+CE+D, D, CE, BE oraz odzyskanie prawa jazdy. Doświadczeni instruktorzy, jasne ceny, elastyczny grafik.',
    'cta_primary' => 'Zapisz się na kurs',
    'cta_secondary' => 'Zobacz cennik',
    'stats' => [
      ['num' => '15+', 'label' => 'lat doświadczenia'],
      ['num' => '9', 'label' => 'kategorii prawa jazdy'],
      ['num' => '1000+', 'label' => 'kursantów (demo)'],
      ['num' => '7', 'label' => 'pojazdów we flocie'],
    ],
  ],

  'about' => [
    'eyebrow' => 'O nas',
    'title' => 'Dlaczego Nordisk Trafikkskole',
    'text' => 'Nordisk Trafikkskole to demo szkoła jazdy w Oslo — fikcyjne portfolio BILOHASH. Każdy kursant ma indywidualne podejście od teorii do egzaminu w Statens vegvesen.',
    'features' => [
      ['icon' => 'shield', 'title' => 'Doświadczeni instruktorzy', 'text' => 'Wykwalifikowani instruktorzy ze stażem we wszystkich kategoriach.'],
      ['icon' => 'coin', 'title' => 'Jasne ceny', 'text' => 'Bez ukrytych opłat — ta sama cena za samą praktykę lub teorię + praktykę.'],
      ['icon' => 'calendar', 'title' => 'Elastyczny grafik', 'text' => 'Dopasowujemy lekcje do Ciebie — także wieczory i weekendy.'],
      ['icon' => 'refresh', 'title' => 'Odzyskanie prawa jazdy', 'text' => 'Pomoc osobom, które straciły prawo jazdy i chcą je odzyskać.'],
    ],
  ],

  'categories' => [
    'eyebrow' => 'Kategorie',
    'title' => 'Kursy na każdą kategorię',
    'subtitle' => 'Od motocykla po ciężarówkę z przyczepą — wybierz kategorię.',
    'items' => [
      ['code' => 'B', 'title' => 'Kategoria B', 'text' => 'Samochód osobowy — najpopularniejszy wybór dla początkujących.', 'icon' => 'car'],
      ['code' => 'A·A1·A2', 'title' => 'Motocykl', 'text' => 'Kursy motocyklowe A1, A2 i A.', 'icon' => 'moto'],
      ['code' => 'C', 'title' => 'Kategoria C', 'text' => 'Ciężarówka — jazda zawodowa.', 'icon' => 'truck'],
      ['code' => 'C+CE', 'title' => 'Kategoria C+CE', 'text' => 'Ciężarówka z przyczepą na długie trasy.', 'icon' => 'truck-trailer'],
      ['code' => 'C+CE+D', 'title' => 'Kategoria C+CE+D', 'text' => 'Pełny pakiet dla kierowcy zawodowego.', 'icon' => 'bus-truck'],
      ['code' => 'D', 'title' => 'Kategoria D', 'text' => 'Autobus — przewóz osób.', 'icon' => 'bus'],
      ['code' => 'CE', 'title' => 'Kategoria CE', 'text' => 'Ciężarówka z przyczepą dla doświadczonych kierowców C.', 'icon' => 'truck-trailer'],
      ['code' => 'BE', 'title' => 'Kategoria BE', 'text' => 'Samochód osobowy z przyczepą.', 'icon' => 'car-trailer'],
      ['code' => '↺', 'title' => 'Odzyskanie', 'text' => 'Program odzyskania utraconego prawa jazdy.', 'icon' => 'refresh'],
    ],
  ],

  'prices' => [
    'eyebrow' => 'Cennik',
    'title' => 'Jasne i uczciwe ceny',
    'subtitle' => 'Ta sama cena za samą praktykę lub teorię z praktyką.',
    'full_course_title' => 'Ceny pełnego kursu',
    'extra_title' => 'Ceny dodatkowej jazdy',
    'col_category' => 'Kategoria',
    'col_price' => 'Cena',
    'full_course' => [
      ['key' => 'B', 'cat' => 'B', 'price' => '18 900 NOK'],
      ['key' => 'A', 'cat' => 'A, A1, A2', 'price' => '12 900 NOK'],
      ['key' => 'C', 'cat' => 'C', 'price' => '24 900 NOK'],
      ['key' => 'CCE', 'cat' => 'C+CE', 'price' => '34 900 NOK'],
      ['key' => 'CCED', 'cat' => 'C+CE+D', 'price' => '44 900 NOK'],
      ['key' => 'D', 'cat' => 'D', 'price' => '27 900 NOK'],
      ['key' => 'CE', 'cat' => 'CE', 'price' => '19 900 NOK'],
      ['key' => 'BE', 'cat' => 'BE', 'price' => '9 900 NOK'],
      ['key' => 'LOST', 'cat' => 'Odzyskanie', 'price' => '4 900 NOK'],
    ],
    'extra_lessons' => [
      ['key' => 'B', 'cat' => 'B', 'unit' => '(2 godz. lekcyjne)', 'price' => '890 NOK'],
      ['key' => 'A', 'cat' => 'A, A1, A2', 'unit' => '(2 godz. lekcyjne)', 'price' => '890 NOK'],
      ['key' => 'CCE', 'cat' => 'C, CE', 'unit' => '(2 godz. lekcyjne)', 'price' => '1 190 NOK'],
      ['key' => 'D', 'cat' => 'D', 'unit' => '(2 godz. lekcyjne)', 'price' => '1 490 NOK'],
      ['key' => 'BE', 'cat' => 'BE', 'unit' => '(2 godz. lekcyjne)', 'price' => '990 NOK'],
    ],
    'note' => '* Ceny demo w NOK. To nie jest prawdziwa oferta.',
    'cta' => 'Zapisz się teraz',
  ],

  'process' => [
    'eyebrow' => 'Jak wygląda nauka',
    'title' => 'Droga do prawa jazdy',
    'steps' => [
      ['num' => '01', 'title' => 'Zapisz się', 'text' => 'Wypełnij formularz lub zadzwoń — ustalamy grupę i grafik.'],
      ['num' => '02', 'title' => 'Teoria', 'text' => 'Przepisy i wiedza teoretyczna do bezpiecznej jazdy.'],
      ['num' => '03', 'title' => 'Praktyka', 'text' => 'Jazdy z instruktorem w rejonie Oslo.'],
      ['num' => '04', 'title' => 'Egzamin', 'text' => 'Przygotowanie i egzamin w Statens vegvesen.'],
    ],
  ],

  'manager' => [
    'eyebrow' => 'Kierownictwo',
    'title' => 'Właściciel / dyrektor szkoły jazdy',
    'name' => 'Właściciel / dyrektor szkoły',
    'role' => 'Właściciel lub dyrektor szkoły jazdy (demo)',
    'text' => 'Pytania o szkolenie, grupy lub grafik — kontakt do szkoły. Demo portfolio, nie prawdziwa osoba.',
  ],

  'reviews' => [
    'eyebrow' => 'Opinie',
    'title' => 'Co mówią kursanci',
    'text' => 'Prawdziwe opinie mogą być widoczne w profilu Google (demo).',
    'cta' => 'Zobacz opinie w Google',
  ],

  'faq' => [
    'eyebrow' => 'FAQ',
    'title' => 'Często zadawane pytania',
    'items' => [
      ['q' => 'Ile trwa kurs kategorii B?', 'a' => 'Standardowy kurs trwa ok. 2–3 miesięcy, w zależności od grafiku.'],
      ['q' => 'Czy cena jest inna tylko za praktykę?', 'a' => 'Nie. Cena pełnego kursu jest taka sama za samą praktykę lub teorię + praktykę.'],
      ['q' => 'Ile kosztuje dodatkowa jazda?', 'a' => 'Od ok. 890 NOK za kategorię B (2 godz. lekcyjne) — zobacz cennik.'],
      ['q' => 'Pomagacie odzyskać prawo jazdy?', 'a' => 'Tak, mamy dedykowany program odzyskania.'],
      ['q' => 'Jak się zapisać?', 'a' => 'Wypełnij formularz, napisz e-mail lub zadzwoń — odpowiadamy szybko.'],
      ['q' => 'W jakich językach?', 'a' => 'Demo obsługuje norweski, angielski, szwedzki, polski, ukraiński i rosyjski.'],
    ],
  ],

  'contact' => [
    'eyebrow' => 'Kontakt',
    'title' => 'Skontaktuj się',
    'subtitle' => 'Zostaw zgłoszenie — odezwiemy się i pomożemy wybrać kategorię.',
    'address_label' => 'Adres',
    'phone_label' => 'Telefon',
    'email_label' => 'E-mail',
    'hours_label' => 'Godziny otwarcia',
    'form_name' => 'Imię i nazwisko',
    'form_phone' => 'Numer telefonu',
    'form_email' => 'E-mail (opcjonalnie)',
    'form_category' => 'Wybierz kategorię',
    'form_message' => 'Wiadomość (opcjonalnie)',
    'form_consent' => 'Zgadzam się na użycie moich danych w celu kontaktu w sprawie zgłoszenia. Więcej: ',
    'form_privacy_link' => 'Polityka prywatności',
    'form_submit' => 'Wyślij zgłoszenie',
    'form_sending' => 'Wysyłanie...',
    'form_success' => 'Dziękujemy! Zgłoszenie otrzymane — wkrótce się odezwiemy.',
    'form_error' => 'Nie udało się wysłać. Spróbuj ponownie lub napisz na demo@bilohash.com',
    'form_error_fields' => 'Wypełnij wymagane pola.',
    'form_recaptcha_required' => 'Potwierdź, że nie jesteś robotem.',
    'map_title' => 'Nasza lokalizacja na mapie',
  ],

  'footer' => [
    'about' => 'Nordisk Trafikkskole — demo szkoła jazdy w Oslo. Kursy wszystkich kategorii i odzyskanie prawa jazdy.',
    'nav_title' => 'Linki',
    'contact_title' => 'Kontakt',
    'legal' => 'NIP/org: DEMO — tylko demo portfolio',
    'privacy' => 'Polityka prywatności',
    'terms' => 'Regulamin',
    'rights' => 'Wszelkie prawa zastrzeżone.',
    'dev_credit' => 'Strona od <strong>bilohash.com</strong> — profesjonalny development',
  ],

  'cat_page' => [
    'requirements' => 'Wymagania',
    'includes' => 'W cenie kursu',
    'breadcrumb_home' => 'Start',
  ],

  'callback' => [
    'title' => 'Chcesz, żebyśmy oddzwonili?',
    'text' => 'Zostaw imię i telefon — oddzwonimy wkrótce.',
    'name' => 'Imię',
    'phone' => 'Telefon',
    'submit' => 'Zadzwoń do mnie',
    'dismiss' => 'Nie, dziękuję',
    'consent' => 'Zgadzam się na użycie danych do kontaktu.',
    'success' => 'Dziękujemy! Wkrótce się odezwiemy.',
  ],

  'cookies' => [
    'text' => 'Używamy niezbędnych plików cookie, aby strona działała. Bez analityki i marketingu.',
    'accept' => 'Rozumiem',
    'link' => 'Polityka prywatności',
  ],

  'chat' => [
    'title' => 'Asystent Nordisk Trafikkskole',
    'greeting' => 'Cześć! Jak mogę pomóc w sprawie kursów prawa jazdy?',
    'placeholder' => 'Napisz wiadomość...',
    'send' => 'Wyślij',
    'error' => 'Nie możemy teraz odpowiedzieć. Kontakt: ',
    'thinking' => 'Pisze...',
  ],

  'back_to_top' => 'Na górę',
  '404_title' => 'Nie znaleziono strony',
  '404_text' => 'Ta strona nie istnieje lub została przeniesiona.',
  '404_cta' => 'Strona główna',
  'district_page' => [
    'other_areas' => 'Inne dzielnice Oslo, które obsługujemy',
  ],
];
