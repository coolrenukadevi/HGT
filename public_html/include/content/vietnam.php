<?php
/**
 * Vietnam destination content (used by /tours/vietnam, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established destination facts; visa rules, closures
 * and tour timings change and are confirmed per booking. STATUS 'review': published; owner/travel team to review.
 * Added: 2026-10-08 (international expansion batch I2). No Vietnam photo in the library yet.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'image' => '',
    'name' => 'Vietnam',
    'map_query' => 'Vietnam',
    'related' => array(
        'cambodia',
        'thailand',
        'bali',
        'singapore-malaysia',
    ),
    'frequent' => array(
        array('Hanoi and Halong Bay in 4 days', '/vietnam-hanoi-halong-bay'),
        array('Vietnam north to south in 8 days', '/vietnam-north-to-south'),
    ),
    'intro' => 'Vietnam tour packages cover Hanoi and a day cruise on Halong Bay, the mountains of Sapa, Da Nang with the Golden Bridge in the Ba Na Hills, the lantern-lit old town of Hoi An, Ho Chi Minh City with the Cu Chi tunnels and the Mekong Delta, and the island of Phu Quoc. Our itineraries run 4 to 8 days and are land only, with standard / 3-star hotels and breakfast; flights and visas are quoted separately.',
    'why' => array(
        array('Halong Bay', 'Thousands of limestone islands rising from the sea, a UNESCO World Heritage Site.'),
        array('Old towns and history', 'Hanoi\'s Old Quarter, Hoi An\'s ancient town and the Cu Chi tunnels.'),
        array('Mountains and rice terraces', 'Sapa and the Muong Hoa valley, with the cable car up Fansipan.'),
        array('Beaches and islands', 'Da Nang\'s My Khe beach and the island of Phu Quoc.'),
    ),
    'best_time' => array(
        array('February – April', 'Spring', 'Pleasant across most of the country; a good time for a north-to-south trip.'),
        array('May – August', 'Summer', 'Hot and humid; the centre coast is at its best, the north and south get rain.'),
        array(
            'September – January',
            'Autumn and winter',
            'Clear autumn days in the north; storms on the centre coast from October to December; cool winters in Hanoi and Sapa.',
        ),
    ),
    'best_time_answer' => 'February to April suits a trip the length of Vietnam; the weather differs between the north, centre and south, so the best months depend on the route.',
    'days_answer' => 'Four days covers Hanoi with Halong Bay, Ho Chi Minh City with the Mekong, Phu Quoc or Hoi An; allow 6–8 days to add Sapa or to travel from north to south.',
    'days_rows' => array(
        array('4 days', 'Hanoi and Halong Bay', 'vietnam-hanoi-halong-bay'),
        array('4 days', 'Ho Chi Minh City and the Mekong Delta', 'vietnam-ho-chi-minh-mekong'),
        array('5 days', 'Da Nang and Hoi An', 'vietnam-da-nang-hoi-an'),
        array('6 days', 'Hanoi, Halong Bay and Sapa', 'vietnam-hanoi-halong-sapa'),
        array('8 days', 'Vietnam north to south', 'vietnam-north-to-south'),
    ),
    'cost_answer' => 'The cost depends mainly on the number of nights, the hotel category, the season and the number of travellers. Flights from India and within Vietnam, visas, bay cruises, cable cars and entry fees are quoted separately or paid locally.',
    'cost_factors' => array(
        'Hotel category and location',
        'Season and holidays such as Tet (Lunar New Year)',
        'Number of travellers sharing a room and vehicle',
        'Bay cruises, cable cars and boat trips (optional)',
        'Flights from India and within Vietnam',
    ),
    'places' => array(
        array('Hanoi', 'The capital, with the Old Quarter, Hoan Kiem Lake and the Temple of Literature.'),
        array('Halong Bay', 'Limestone islands and caves, visited on a day cruise from Hanoi.'),
        array('Sapa', 'A mountain town of rice terraces and hill villages near Fansipan.'),
        array('Da Nang and Hoi An', 'A beach city with the Ba Na Hills, and a UNESCO-listed old trading town.'),
        array('Ho Chi Minh City', 'The busy south, with the Cu Chi tunnels and the War Remnants Museum.'),
        array('Mekong Delta and Phu Quoc', 'River canals and orchards, and an island of beaches in the Gulf of Thailand.'),
    ),
    'things' => array(
        'Cruise among the islands of Halong Bay',
        'Walk the Golden Bridge in the Ba Na Hills',
        'See Hoi An\'s lanterns at night',
        'Crawl through the Cu Chi tunnels',
        'Take a boat through the Mekong Delta',
        'Ride the cable car up Fansipan',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast, as listed on each package.',
    'transport' => 'Packages are land only: airport transfers and sightseeing as specified. Flights from India and within Vietnam (for example Hanoi to Da Nang or Ho Chi Minh City) are quoted separately. Bay cruises, cable cars and boat trips are paid locally.',
    'who_title' => 'Who Vietnam suits',
    'who' => array(
        array('Couples', 'Halong Bay, Hoi An\'s lanterns and Phu Quoc\'s beaches.'),
        array('Families', 'Easy city tours, the Ba Na Hills and beach days.'),
        array('Friends', 'Street food, history and the length of the country.'),
    ),
    'tips' => array(
        'Carry some Vietnamese dong in cash for markets and street food.',
        'Weather differs between north, centre and south; we plan around it.',
        'Visa rules for Indian passport holders change; we confirm them when you book.',
    ),
    'faqs' => array(
        array(
            'What is included in Vietnam packages?',
            '<p>Standard / 3-star hotels with breakfast, airport transfers and the sightseeing listed on each package. Flights, visas, travel insurance, bay cruises, cable cars and entry fees are not included; we can quote them. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array(
            'Are flights included?',
            '<p>No. Our Vietnam packages are land only; we can quote flights from India and within the country.</p>',
        ),
        array(
            'Do Indians need a visa for Vietnam?',
            '<p>Yes. Indian passport holders need a visa for Vietnam. The rules and fees change from time to time, so we confirm them when you book.</p>',
        ),
        array(
            'What is the best time to visit Vietnam?',
            '<p>February to April for a trip the length of the country; it depends on the route.</p>',
        ),
        array(
            'Can Vietnam packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Vietnam">Tell us what you need</a>.</p>',
        ),
    ),
);
