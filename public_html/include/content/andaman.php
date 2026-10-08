<?php
/**
 * Andaman Islands destination content (used by /tours/andaman, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established destination facts; permits, park seasons,
 * ferry and temple timings change and are confirmed per booking. STATUS 'review': published; owner/travel team to review.
 * Added: 2026-10-08 (domestic expansion phase D1). No Andaman Islands photo in the library yet.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'image' => '',
    'name' => 'Andaman Islands',
    'map_query' => 'Andaman and Nicobar Islands, India',
    'related' => array(
        'kerala',
        'goa',
        'odisha',
        'maldives',
    ),
    'frequent' => array(
        array('Port Blair and Havelock in 5 days', '/andaman-port-blair-havelock'),
        array('Port Blair, Havelock and Neil in 6 days', '/andaman-port-blair-havelock-neil'),
    ),
    'intro' => 'Andaman tour packages start at Port Blair, with the Cellular Jail, Ross Island and North Bay, and take the ferry to Havelock (Swaraj Dweep) for Radhanagar and Elephant beaches and to Neil (Shaheed Dweep) for its natural rock bridge; longer trips add the limestone caves of Baratang, Long Island, Rangat, Mayabunder and Diglipur in the north. Our itineraries run 4 to 8 days, with standard / 3-star equivalent hotels and breakfast; ferries and boat trips are quoted or paid locally.',
    'why' => array(
        array('Beaches', 'Radhanagar on Havelock, often rated among Asia\'s best, and the quiet sands of Neil and Lalaji Bay.'),
        array(
            'Coral and clear water',
            'Snorkelling at Elephant beach and North Bay, and the Mahatma Gandhi Marine National Park at Wandoor.',
        ),
        array('History', 'The Cellular Jail, where freedom fighters were imprisoned, and the ruins of Ross Island.'),
        array('Forests and caves', 'Mangrove creeks and limestone caves at Baratang, and turtle beaches in the north.'),
    ),
    'best_time' => array(
        array(
            'October – May',
            'Dry season',
            'Calm seas, clear water and the best time for beaches and snorkelling; December to February is busiest.',
        ),
        array('June – September', 'Monsoon', 'Heavy rain and rough seas; some ferries and water sports stop, but prices are lower.'),
    ),
    'best_time_answer' => 'October to May is the best time to visit the Andaman Islands; the monsoon from June to September brings rough seas.',
    'days_answer' => 'Four days covers Port Blair with Havelock or Neil; five or six days covers Port Blair, Havelock and Neil; allow seven or eight for Baratang, Long Island or the north.',
    'days_rows' => array(
        array('4 days', 'Port Blair, Ross Island and North Bay', 'andaman-port-blair-ross-north-bay'),
        array('5 days', 'Port Blair and Havelock', 'andaman-port-blair-havelock'),
        array('6 days', 'Port Blair, Havelock and Neil', 'andaman-port-blair-havelock-neil'),
        array('7 days', 'Havelock, Neil and Baratang', 'andaman-port-blair-havelock-neil-baratang'),
        array('8 days', 'North Andaman to Diglipur', 'andaman-north-diglipur-ross-smith'),
    ),
    'cost_answer' => 'The cost depends mainly on the number of nights, the hotel category and island, the season and the number of travellers. Flights to Port Blair, ferries, boat trips, water sports and entry fees are quoted separately or paid locally.',
    'cost_factors' => array(
        'Hotel category and island (Havelock costs more)',
        'Season — December to February is highest',
        'Ferry class (government or private)',
        'Boat trips and water sports (optional)',
        'Flights to Port Blair',
    ),
    'places' => array(
        array('Port Blair', 'The capital: the Cellular Jail, Corbyn\'s Cove, museums and boats to Ross Island and North Bay.'),
        array('Havelock (Swaraj Dweep)', 'Radhanagar beach, Elephant beach for snorkelling, and Kalapathar.'),
        array('Neil (Shaheed Dweep)', 'A small farming island with the natural rock bridge and Laxmanpur and Bharatpur beaches.'),
        array('Baratang', 'Mangrove creeks, limestone caves and a mud volcano, reached by road from Port Blair.'),
        array('Long Island and Rangat', 'Quiet Middle Andaman, with Lalaji Bay and mangrove walks.'),
        array('Diglipur', 'The far north: the sandbar of Ross and Smith islands and Saddle Peak.'),
    ),
    'things' => array(
        'Watch the sunset at Radhanagar beach',
        'Snorkel at Elephant beach or North Bay',
        'See the light-and-sound show at the Cellular Jail',
        'Walk among the ruins of Ross Island',
        'Take a boat through the Baratang mangroves to the limestone caves',
        'See the natural rock bridge on Neil at low tide',
    ),
    'stay' => 'Standard / 3-star equivalent hotels and beach resorts with breakfast, as listed on each package.',
    'transport' => 'Packages include airport transfers and sightseeing by road. Ferries between Port Blair, Havelock, Neil and Long Island run to fixed timetables and are quoted with the booking; boats to Ross Island, North Bay and Elephant beach are paid locally. Flights to Port Blair are quoted separately.',
    'who_title' => 'Who the Andamans suit',
    'who' => array(
        array('Couples', 'Quiet beaches and sunsets on Havelock and Neil.'),
        array('Families', 'Calm beaches, glass-bottom boats and the Cellular Jail\'s history.'),
        array('Nature lovers', 'Coral reefs, mangroves, caves and turtle beaches.'),
    ),
    'tips' => array(
        'Carry a photo ID for ferries and some islands.',
        'Book ferries early in the high season; we can quote them with the package.',
        'Plastic is restricted in the marine parks; carry a reusable bottle.',
        'Mobile signal and ATMs are limited on the smaller islands.',
    ),
    'faqs' => array(
        array(
            'What is included in Andaman packages?',
            '<p>Standard packages include standard / 3-star equivalent hotels with breakfast, transfers and sightseeing by road as specified in the itinerary, and travel assistance. Ferries, boat trips, water sports and entry fees are paid locally or quoted separately. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array(
            'Are ferries included?',
            '<p>Not in the standard package. Ferry tickets between the islands are quoted with your booking; boats to Ross Island, North Bay and Elephant beach are paid locally.</p>',
        ),
        array(
            'Do Indian visitors need a permit?',
            '<p>No permit is needed for Port Blair, Havelock, Neil and the usual tourist places. Some forest areas need a local entry permit, which we arrange or which is paid locally.</p>',
        ),
        array('What is the best time to visit?', '<p>October to May.</p>'),
        array(
            'Can the itinerary be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Andaman">Tell us what you need</a>.</p>',
        ),
    ),
);
