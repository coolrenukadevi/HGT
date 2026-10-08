<?php
/**
 * Maharashtra destination content (used by /tours/maharashtra, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established destination facts; permits, park seasons,
 * ferry and temple timings change and are confirmed per booking. STATUS 'review': published; owner/travel team to review.
 * Added: 2026-10-08 (domestic expansion phase D2). No Maharashtra photo in the library yet.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'image' => '',
    'name' => 'Maharashtra',
    'map_query' => 'Maharashtra, India',
    'related' => array(
        'goa',
        'gujarat',
        'madhya-pradesh',
        'south-india',
    ),
    'frequent' => array(
        array('Ajanta and Ellora in 4 days', '/maharashtra-aurangabad-ajanta-ellora'),
        array('Mahabaleshwar and Panchgani in 4 days', '/maharashtra-mahabaleshwar-panchgani'),
    ),
    'intro' => 'Maharashtra tour packages cover Mumbai and the Elephanta Caves, the hill stations of Lonavala, Mahabaleshwar and Panchgani, the Ajanta and Ellora caves, Shirdi and Shani Shingnapur, the Jyotirlingas of Trimbakeshwar, Grishneshwar and Bhimashankar, the eight Ashtavinayak temples, Pandharpur, Tuljapur and Akkalkot, Kolhapur, Matheran, Pune, Satara\'s Kaas plateau, Igatpuri and Bhandardara, the coast from Alibaug, Kashid and Ganpatipule to Tarkarli, and Tadoba Tiger Reserve. Our itineraries run 3 to 6 days, with standard / 3-star equivalent hotels and breakfast.',
    'why' => array(
        array('Cave art', 'The painted caves of Ajanta and the Kailasa temple at Ellora, both UNESCO World Heritage Sites.'),
        array('Pilgrimage', 'Shirdi, Trimbakeshwar, Grishneshwar, Bhimashankar and Kolhapur\'s Mahalaxmi.'),
        array('Hills and coast', 'Mahabaleshwar and Lonavala in the Sahyadris, and the Konkan beaches and sea forts.'),
        array('Tigers', 'Tadoba, Maharashtra\'s oldest tiger reserve.'),
    ),
    'best_time' => array(
        array('October – March', 'Best season', 'Pleasant and dry for sightseeing, caves and beaches.'),
        array('April – May', 'Summer', 'Hot on the plains; good for Tadoba and the Alphonso mango season.'),
        array(
            'June – September',
            'Monsoon',
            'The Sahyadri hills are green and full of waterfalls; the coast is rough and boats stop.',
        ),
    ),
    'best_time_answer' => 'October to March is the best time to visit Maharashtra; the monsoon (June to September) is best for the green Sahyadri hills.',
    'days_answer' => 'Three days covers Mumbai, Shirdi, Kolhapur or Lonavala; four days covers Ajanta and Ellora or Mahabaleshwar; allow six for the Jyotirlinga circuit or Mumbai to Pune through the hills.',
    'days_rows' => array(
        array('3 days', 'Mumbai city', 'maharashtra-mumbai-city'),
        array('3 days', 'Shirdi and Shani Shingnapur', 'maharashtra-shirdi-shani-shingnapur'),
        array('4 days', 'Ajanta and Ellora', 'maharashtra-aurangabad-ajanta-ellora'),
        array('4 days', 'Mahabaleshwar and Panchgani', 'maharashtra-mahabaleshwar-panchgani'),
        array('6 days', 'Three Jyotirlingas', 'maharashtra-jyotirlinga-trimbakeshwar-grishneshwar-bhimashankar'),
    ),
    'cost_answer' => 'The cost depends mainly on the number of nights, the hotel category, the season and the number of travellers. Ferries, boats, safaris, darshan passes and entry fees are paid locally; flights or trains are quoted separately.',
    'cost_factors' => array(
        'Hotel category and city (Mumbai costs more)',
        'Season and long weekends',
        'Number of travellers sharing a room and vehicle',
        'Safaris, ferries and boats (optional)',
        'Flights or trains',
    ),
    'places' => array(
        array('Mumbai', 'The Gateway of India, CSMT, Marine Drive and the Elephanta Caves.'),
        array('Ajanta and Ellora', 'Painted Buddhist caves and the rock-cut Kailasa temple.'),
        array('Shirdi and Nashik', 'Sai Baba\'s Shirdi, Panchavati and the Trimbakeshwar Jyotirlinga.'),
        array('Mahabaleshwar and Lonavala', 'Sahyadri hill stations with viewpoints, forts and caves.'),
        array('Konkan coast', 'Ganpatipule, Ratnagiri, Malvan and Tarkarli with the Sindhudurg sea fort.'),
        array('Tadoba', 'Tiger safaris in teak and bamboo forest.'),
    ),
    'things' => array(
        'See the Kailasa temple at Ellora',
        'Walk Marine Drive at sunset',
        'Have darshan at Shirdi',
        'Take the boat to the Sindhudurg sea fort',
        'Watch sunrise at Mahabaleshwar',
        'Go on a tiger safari at Tadoba',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast, as listed on each package; simple stays near Bhimashankar.',
    'transport' => 'Packages include road transfers and sightseeing as specified. Ferries to Elephanta and Sindhudurg, safaris and boats are paid locally; flights or trains (including the Konkan Railway) are quoted separately.',
    'who_title' => 'Who Maharashtra suits',
    'who' => array(
        array('Pilgrims', 'Shirdi, the Jyotirlingas and Kolhapur.'),
        array('Families', 'Mumbai, the hill stations and the Konkan beaches.'),
        array('Heritage lovers', 'Ajanta, Ellora, Elephanta and the sea forts.'),
    ),
    'tips' => array(
        'Ajanta is closed on Mondays and Ellora on Tuesdays.',
        'Elephanta ferries do not run on Mondays or in rough monsoon weather.',
        'Tadoba\'s core zone is closed on Tuesdays.',
        'The ghat roads are slow and misty in the monsoon.',
    ),
    'faqs' => array(
        array(
            'What is included in Maharashtra packages?',
            '<p>Standard packages include standard / 3-star equivalent hotels with breakfast, transfers and sightseeing by road as specified in the itinerary, and travel assistance. Ferries, boats, safaris, darshan passes and entry fees are paid locally or quoted separately. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array('On which days are Ajanta and Ellora closed?', '<p>Ajanta is closed on Mondays and Ellora on Tuesdays.</p>'),
        array(
            'Is the monsoon a good time to visit?',
            '<p>Yes for the hill stations, which are green and full of waterfalls; the coast is rough and boat trips stop.</p>',
        ),
        array('What is the best time to visit?', '<p>October to March.</p>'),
        array(
            'Can the itinerary be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Maharashtra">Tell us what you need</a>.</p>',
        ),
    ),
);
