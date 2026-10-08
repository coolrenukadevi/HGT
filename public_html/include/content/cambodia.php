<?php
/**
 * Cambodia destination content (used by /tours/cambodia, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established destination facts; visa rules, closures
 * and tour timings change and are confirmed per booking. STATUS 'review': published; owner/travel team to review.
 * Added: 2026-10-08 (international expansion batch I2). No Cambodia photo in the library yet.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'image' => '',
    'name' => 'Cambodia',
    'map_query' => 'Cambodia',
    'related' => array(
        'vietnam',
        'thailand',
        'bali',
        'singapore-malaysia',
    ),
    'frequent' => array(
        array('Siem Reap and Angkor in 4 days', '/cambodia-siem-reap-angkor'),
        array('Ho Chi Minh City and Angkor in 7 days', '/vietnam-cambodia-ho-chi-minh-siem-reap'),
    ),
    'intro' => 'Cambodia tour packages cover the temples of Angkor from Siem Reap — Angkor Wat at sunrise, the faces of the Bayon, Ta Prohm and Banteay Srei — the floating villages of the Tonle Sap lake, and the capital, Phnom Penh; one itinerary combines Angkor with Ho Chi Minh City in Vietnam. Our itineraries run 4 to 7 days and are land only, with standard / 3-star hotels and breakfast; flights and visas are quoted separately.',
    'why' => array(
        array('Angkor', 'Angkor Wat and hundreds of other Khmer temples, a UNESCO World Heritage Site.'),
        array('Jungle temples', 'Ta Prohm, with trees growing through its walls.'),
        array('Life on the lake', 'Floating villages on the Tonle Sap, South-East Asia\'s largest lake.'),
        array('History', 'Phnom Penh\'s Royal Palace and its museums of the Khmer Rouge years.'),
    ),
    'best_time' => array(
        array('November – March', 'Cool and dry', 'The best weather for the temples; the busiest time.'),
        array('April – May', 'Hot', 'Very hot; start the temples early.'),
        array('June – October', 'Rainy', 'Green, quieter temples and lower prices; the Tonle Sap is at its highest.'),
    ),
    'best_time_answer' => 'November to March, the cool dry season, is the best time to visit Cambodia.',
    'days_answer' => 'Four days covers Siem Reap and the main temples of Angkor; six days adds Phnom Penh; seven days combines Angkor with Ho Chi Minh City.',
    'days_rows' => array(
        array('4 days', 'Siem Reap and Angkor', 'cambodia-siem-reap-angkor'),
        array('6 days', 'Siem Reap and Phnom Penh', 'cambodia-siem-reap-phnom-penh'),
        array('7 days', 'Ho Chi Minh City and Angkor', 'vietnam-cambodia-ho-chi-minh-siem-reap'),
    ),
    'cost_answer' => 'The cost depends mainly on the number of nights, the hotel category, the season and the number of travellers. Flights, visas, the Angkor Pass, boat trips and entry fees are quoted separately or paid locally.',
    'cost_factors' => array(
        'Hotel category',
        'Season — November to March is highest',
        'Number of travellers sharing a room and vehicle',
        'The Angkor Pass (1-day or 3-day)',
        'Flights from India and between countries',
    ),
    'places' => array(
        array('Angkor Wat', 'The largest temple of Angkor, best at sunrise.'),
        array('Angkor Thom and the Bayon', 'The walled royal city with the giant stone faces of the Bayon.'),
        array('Ta Prohm', 'A temple left to the jungle, with roots over its walls.'),
        array('Banteay Srei', 'A small temple of fine carving in pink sandstone.'),
        array('Tonle Sap', 'A great lake with floating villages near Siem Reap.'),
        array('Phnom Penh', 'The capital, with the Royal Palace, Silver Pagoda and National Museum.'),
    ),
    'things' => array(
        'Watch sunrise over Angkor Wat',
        'See the stone faces of the Bayon',
        'Explore the jungle temple of Ta Prohm',
        'Take a boat to a floating village on the Tonle Sap',
        'Visit the Royal Palace in Phnom Penh',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast, as listed on each package.',
    'transport' => 'Packages are land only: airport transfers and sightseeing as specified; Siem Reap to Phnom Penh is by road. Flights from India and between countries are quoted separately. The Angkor Pass and boat trips are paid locally.',
    'who_title' => 'Who Cambodia suits',
    'who' => array(
        array('History lovers', 'The temples of Angkor and Phnom Penh\'s museums.'),
        array('Couples', 'Sunrise at Angkor Wat and evenings in Siem Reap.'),
        array('Combined trips', 'Easy to add to Vietnam or Thailand.'),
    ),
    'tips' => array(
        'Temples need covered shoulders and knees.',
        'Start the temples early to avoid the midday heat.',
        'Visa rules for Indian passport holders change; we confirm them when you book.',
    ),
    'faqs' => array(
        array(
            'What is included in Cambodia packages?',
            '<p>Standard / 3-star hotels with breakfast, airport transfers and the sightseeing listed on each package. Flights, visas, travel insurance, the Angkor Pass, boat trips and entry fees are not included; we can quote them. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array(
            'Are flights included?',
            '<p>No. Our Cambodia packages are land only; we can quote flights from India and within the country.</p>',
        ),
        array(
            'Do Indians need a visa for Cambodia?',
            '<p>Yes. Indian passport holders need a visa for Cambodia. The rules and fees change from time to time, so we confirm them when you book.</p>',
        ),
        array('What is the best time to visit Cambodia?', '<p>November to March.</p>'),
        array(
            'Can Cambodia packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Cambodia">Tell us what you need</a>.</p>',
        ),
    ),
);
