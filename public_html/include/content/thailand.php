<?php
/**
 * Thailand destination content (used by /tours/thailand, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established destination facts; visa rules, park closures
 * and tour timings change and are confirmed per booking. STATUS 'review': published; owner/travel team to review.
 * Added: 2026-10-08 (international expansion batch I1). No Thailand photo except Phuket yet.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'name' => 'Thailand',
    'image' => '',
    'map_query' => 'Thailand',
    'related' => array('singapore-malaysia', 'dubai', 'maldives', 'goa'),
    'frequent' => array(
        array('Bangkok and Pattaya in 4 days', '/thailand-bangkok-pattaya'),
        array('Phuket and Krabi in 6 days', '/thailand-phuket-krabi'),
    ),
    'intro' => 'Thailand tour packages cover Bangkok\'s Grand Palace and temples, the beaches of Pattaya, Phuket and Krabi with island trips to Phi Phi and the Four Islands, Ayutthaya, and Chiang Mai and Chiang Rai in the north. Our itineraries run 4 to 8 days and are land only, with standard / 3-star hotels and breakfast; flights and visas are quoted separately.',
    'why' => array(
        array('Temples and palaces', 'The Grand Palace, Wat Pho and Wat Arun in Bangkok, the ruins of Ayutthaya and the White Temple in Chiang Rai.'),
        array('Islands and beaches', 'Phi Phi, Phang Nga Bay, the Four Islands of Krabi and Coral Island off Pattaya.'),
        array('Easy from India', 'Direct flights from the major Indian cities to Bangkok and Phuket.'),
        array('Food and markets', 'Street food in Chinatown, floating markets and night bazaars.'),
    ),
    'best_time' => array(
        array('November – February', 'Cool season', 'The best weather for beaches and sightseeing; busiest and highest prices.'),
        array('March – May', 'Hot season', 'Very hot, especially in Bangkok and the north.'),
        array('June – October', 'Rainy season', 'Showers, green countryside and lower prices; some island trips stop on rough-sea days.'),
    ),
    'best_time_answer' => 'November to February is the best time to visit Thailand; the Andaman coast (Phuket and Krabi) is rougher from June to October.',
    'days_answer' => 'Four days covers Bangkok with Pattaya; five days covers Phuket, Krabi or Chiang Mai; allow 6–8 days to combine Bangkok with the beaches or the north.',
    'days_rows' => array(
        array('4 days', 'Bangkok and Pattaya with Coral Island', 'thailand-bangkok-pattaya'),
        array('5 days', 'Phuket with Phi Phi and Phang Nga Bay', 'thailand-phuket-phi-phi'),
        array('6 days', 'Phuket and Krabi', 'thailand-phuket-krabi'),
        array('8 days', 'Bangkok, Pattaya, Krabi and Phuket', 'thailand-bangkok-pattaya-krabi-phuket'),
    ),
    'cost_answer' => 'The cost depends mainly on the number of nights, the hotel category, the season and the number of travellers. Flights, visas, island tours and shows are quoted separately or paid locally.',
    'cost_factors' => array(
        'Hotel category and beach location',
        'Season — November to February is highest',
        'Number of travellers sharing a room and vehicle',
        'Island tours, shows and theme parks (optional)',
        'Flights from India and within Thailand',
    ),
    'places' => array(
        array('Bangkok', 'The Grand Palace, Wat Pho, Wat Arun and the Chao Phraya river, with markets and malls.'),
        array('Pattaya', 'A beach city two hours from Bangkok, with Coral Island and the Sanctuary of Truth.'),
        array('Phuket', 'Thailand\'s largest island, with Patong, Kata and Karon beaches and trips to Phi Phi and Phang Nga Bay.'),
        array('Krabi', 'Limestone cliffs at Ao Nang, the Four Islands and the Hong Islands.'),
        array('Ayutthaya', 'The ruined royal capital, a UNESCO World Heritage Site near Bangkok.'),
        array('Chiang Mai and Chiang Rai', 'Mountain temples, night bazaars, ethical elephant sanctuaries and the White Temple.'),
    ),
    'things' => array(
        'See the Grand Palace and Wat Pho in Bangkok',
        'Take a speedboat to the Phi Phi Islands',
        'Explore the Four Islands of Krabi by longtail boat',
        'Walk among the ruins of Ayutthaya',
        'Visit an ethical elephant sanctuary near Chiang Mai (no riding)',
        'Try street food in Bangkok\'s Chinatown',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast, as listed on each package; beach hotels near Patong, Kata, Karon or Ao Nang.',
    'transport' => 'Packages are land only: airport transfers and sightseeing as specified. Flights from India and within Thailand (for example Bangkok to Phuket or Chiang Mai) are quoted separately. Island trips are by speedboat or longtail boat.',
    'who_title' => 'Who Thailand suits',
    'who' => array(
        array('Couples', 'Beaches, islands and sunsets in Phuket and Krabi.'),
        array('Families', 'Easy flights, theme parks and safe, shallow beaches.'),
        array('Friends', 'Bangkok and Pattaya for shopping, food and nightlife.'),
    ),
    'tips' => array(
        'Temples need covered shoulders and knees; carry a light scarf.',
        'Island tours depend on the sea; some stop on rough days in the monsoon.',
        'Visa rules for Indian passport holders change; we confirm them when you book.',
    ),
    'faqs' => array(
        array(
            'What is included in Thailand packages?',
            '<p>Standard / 3-star hotels with breakfast, airport transfers and the sightseeing listed on each package. Flights, visas, travel insurance, island tours, park fees and shows are not included; we can quote them. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array('Are flights included?', '<p>No. Our Thailand packages are land only; we can quote flights from India and within Thailand.</p>'),
        array('Do Indians need a visa for Thailand?', '<p>The rules for Indian passport holders change from time to time. We confirm the current rules and fees when you book.</p>'),
        array('What is the best time to visit Thailand?', '<p>November to February.</p>'),
        array(
            'Can Thailand packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Thailand">Tell us what you need</a>.</p>',
        ),
    ),
);
