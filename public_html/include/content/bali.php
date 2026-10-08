<?php
/**
 * Bali & Indonesia destination content (used by /tours/bali, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established destination facts; visa rules, closures
 * and tour timings change and are confirmed per booking. STATUS 'review': published; owner/travel team to review.
 * Added: 2026-10-08 (international expansion batch I2). No Bali photo in the library yet.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'image' => '',
    'name' => 'Bali',
    'map_query' => 'Bali, Indonesia',
    'related' => array(
        'thailand',
        'singapore-malaysia',
        'vietnam',
        'maldives',
    ),
    'frequent' => array(
        array('Kuta and Ubud in 5 days', '/bali-kuta-ubud'),
        array('Bali with Nusa Penida in 6 days', '/bali-nusa-penida'),
    ),
    'intro' => 'Bali tour packages cover the rice terraces and temples of Ubud, the volcano view at Kintamani, the sea temples of Tanah Lot and Uluwatu, the beaches of Kuta, Seminyak and Nusa Dua, and trips to Nusa Penida and the Gili Islands; one itinerary adds Borobudur and Prambanan on Java. Our itineraries run 4 to 8 days and are land only, with standard / 3-star hotels and breakfast; flights and visas are quoted separately.',
    'why' => array(
        array('Temples by the sea', 'Tanah Lot on its rock and Uluwatu on its cliff, both best at sunset.'),
        array('Rice terraces and hills', 'Tegallalang and Jatiluwih, the Kintamani volcano view and the lake temple at Bedugul.'),
        array('Islands nearby', 'Nusa Penida\'s cliffs and the car-free Gili Islands off Lombok.'),
        array('Hindu culture', 'Balinese temples, offerings and dance, from Tirta Empul to the Kecak at Uluwatu.'),
    ),
    'best_time' => array(
        array('April – October', 'Dry season', 'Sunny days and calm seas; July and August are the busiest.'),
        array(
            'November – March',
            'Wet season',
            'Afternoon showers, green rice fields and lower prices; boat trips may stop on rough days.',
        ),
    ),
    'best_time_answer' => 'April to October, the dry season, is the best time to visit Bali; November to March brings afternoon rain but fewer visitors.',
    'days_answer' => 'Four days covers Ubud with a beach; five days adds Kintamani and the sea temples; allow 6–8 days for Nusa Penida, the Gili Islands, the north coast or Java.',
    'days_rows' => array(
        array('4 days', 'Ubud and Seminyak', 'bali-ubud-seminyak'),
        array('5 days', 'Kuta and Ubud', 'bali-kuta-ubud'),
        array('6 days', 'Bali with Nusa Penida', 'bali-nusa-penida'),
        array('7 days', 'Bali and the Gili Islands', 'bali-gili-islands'),
        array('8 days', 'Ubud, Kintamani and Nusa Dua', 'bali-ubud-kintamani-nusa-dua'),
    ),
    'cost_answer' => 'The cost depends mainly on the number of nights, the hotel category and area, the season and the number of travellers. Flights, visas, the Bali tourist levy, boats and entry fees are quoted separately or paid locally.',
    'cost_factors' => array(
        'Hotel category and area (Ubud, Kuta, Seminyak or Nusa Dua)',
        'Season — July, August and December are highest',
        'Number of travellers sharing a room and vehicle',
        'Boats to Nusa Penida or the Gili Islands (optional)',
        'Flights from India and within Indonesia',
    ),
    'places' => array(
        array('Ubud', 'The cultural heart of Bali, with the Monkey Forest, palace, art market and rice terraces.'),
        array('Kintamani', 'The view across to Mount Batur and its crater lake.'),
        array('Tanah Lot and Uluwatu', 'Bali\'s best-known sea temples, on a rock and a cliff.'),
        array('Kuta, Seminyak and Nusa Dua', 'Beach areas in the south, from lively Kuta to calm Nusa Dua.'),
        array('Nusa Penida', 'An island by fast boat, with Kelingking Beach and Broken Beach.'),
        array('Yogyakarta', 'On Java, the base for the UNESCO temples of Borobudur and Prambanan.'),
    ),
    'things' => array(
        'Walk the Tegallalang rice terraces',
        'Watch sunset at the Uluwatu or Tanah Lot temple',
        'Visit the Tirta Empul water temple',
        'Take a fast boat to Nusa Penida',
        'Snorkel with turtles off the Gili Islands',
        'See Borobudur on Java',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast, as listed on each package, in Ubud and a beach area.',
    'transport' => 'Packages are land only: airport transfers and sightseeing as specified. Flights from India and within Indonesia (for example Yogyakarta to Bali) are quoted separately. Fast boats to Nusa Penida and the Gili Islands are paid locally.',
    'who_title' => 'Who Bali suits',
    'who' => array(
        array('Couples', 'Rice-terrace villas, sea-temple sunsets and beaches.'),
        array('Families', 'Calm beaches at Nusa Dua and easy day trips.'),
        array('Friends', 'Seminyak and Kuta, island boat trips and snorkelling.'),
    ),
    'tips' => array(
        'Temples need a sarong; it is usually lent at the gate.',
        'Bali charges a tourist levy on arrival.',
        'Fast boats depend on the sea and may be cancelled on rough days.',
        'Visa rules for Indian passport holders change; we confirm them when you book.',
    ),
    'faqs' => array(
        array(
            'What is included in Bali packages?',
            '<p>Standard / 3-star hotels with breakfast, airport transfers and the sightseeing listed on each package. Flights, visas, travel insurance, the Bali tourist levy, boats and entry fees are not included; we can quote them. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array(
            'Are flights included?',
            '<p>No. Our Bali packages are land only; we can quote flights from India and within the country.</p>',
        ),
        array(
            'Do Indians need a visa for Bali?',
            '<p>The rules for Indian passport holders change from time to time. We confirm the current rules and fees when you book.</p>',
        ),
        array('What is the best time to visit Bali?', '<p>April to October.</p>'),
        array(
            'Can Bali packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Bali">Tell us what you need</a>.</p>',
        ),
    ),
);
