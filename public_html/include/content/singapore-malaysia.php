<?php
/**
 * Singapore & Malaysia destination content (used by /tours/singapore-malaysia and Singapore & Malaysia package pages).
 *
 * Sources: Holiday Guru Travel's own Singapore & Malaysia itineraries (routes, distances,
 * inclusions) and well-established destination facts. Rules that change often
 * (visas, permits, registrations) are described generally and confirmed per booking.
 * STATUS 'review': published; owner/travel team to review wording.
 * Last reviewed: 2026-09-30.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'name' => 'Singapore & Malaysia',
    'image' => 'assets/img/destinations/singapore-malaysia.jpg',
    'map_query' => 'Singapore',
    'related' => array('dubai', 'maldives', 'goa', 'kerala'),
    'frequent' => array(
        array('5-day Singapore and Kuala Lumpur', '/singapore-and-kuala-lumpur-tour'),
        array('The Magical Tour to Singapore', '/the-magical-tour-to-singapore'),
    ),
    'intro' => 'Singapore and Malaysia tour packages cover Singapore\'s Marina Bay, Gardens by the Bay and Sentosa, with Kuala Lumpur, Genting, Penang and Langkawi in Malaysia, the island of Bintan, and Bali. Some packages name their hotels; the standard land-only packages use standard / 3-star hotels with breakfast. Our itineraries run 4 to 9 days.',
    'why' => array(
        array('Singapore highlights', 'Sentosa, Universal Studios and the Night Safari on selected packages.'),
        array(
            'Two or more countries',
            'Singapore with Kuala Lumpur, and optional Thailand on the longer tours.',
        ),
        array(
            'Islands nearby',
            'Sentosa, Bintan by ferry, Langkawi and Bali are a short trip from Singapore.',
        ),
        array('Great for families', 'Theme parks, safaris and clean, easy cities.'),
    ),
    'best_time' => array(
        array(
            'February – April',
            'Drier months',
            'Generally the driest and sunniest time in Singapore and Malaysia.',
        ),
        array('May – September', 'Warm', 'Hot and humid with short showers; Singapore’s school-holiday events.'),
        array(
            'October – January',
            'Wetter',
            'More rain, especially November–January; year-end festive lights and events.',
        ),
    ),
    'best_time_answer' => 'Singapore and Malaysia can be visited year-round; February to April is usually the driest.',
    'days_answer' => 'Four days covers Singapore; 5–6 days adds Sentosa, Bintan, Langkawi or Kuala Lumpur; allow 7–9 days for Penang, Bali or Thailand.',
    'days_rows' => array(
        array('5 days', 'Singapore with Sentosa and Night Safari', 'the-magical-tour-to-singapore'),
        array(
            '5 days',
            'Singapore and Kuala Lumpur, with Universal Studios',
            'singapore-and-kuala-lumpur-tour',
        ),
        array(
            '9 days',
            'Singapore, Kuala Lumpur, Pattaya and Bangkok',
            'the-best-of-singapore-and-kuala-lumpur-with-pattaya-tour',
        ),
        array(
            '9 days',
            'Singapore, Kuala Lumpur, Phuket and Bangkok',
            'singapore-kuala-lumpur-phuket-bangkok',
        ),
    ),
    'cost_answer' => 'The cost depends on the hotels, the number of countries, the season and the attractions you add. We quote each trip for your dates; flights and visas are extra unless listed.',
    'cost_factors' => array(
        'Hotels named on each package',
        'Number of countries and nights',
        'Attractions such as Universal Studios and the Night Safari',
        'Peak and festival period surcharges',
        'Visas and airfare, not included unless listed',
        'Shared or private sightseeing',
    ),
    'places' => array(
        array(
            'Singapore',
            'Sentosa Island, Universal Studios Singapore, Gardens by the Bay, Marina Bay and the Night Safari.',
        ),
        array(
            'Kuala Lumpur',
            'The Petronas Twin Towers, Batu Caves and the Sunway Lagoon theme park on one itinerary.',
        ),
        array(
            'Pattaya',
            'Beach town on the 9-day Pattaya itinerary; the Pattaya day is at leisure, with sightseeing on your own.',
        ),
        array('Phuket', 'Beaches and the Phi Phi Island tour on the 9-day Phuket itinerary.'),
        array(
            'Bangkok',
            'The last stop on both 9-day tours, reached by road from Pattaya (about 155 km); city sightseeing is on your own or can be added.',
        ),
    ),
    'things' => array(
        'Spend a day at Universal Studios Singapore',
        'Explore Sentosa Island',
        'Take the Night Safari',
        'See the Petronas Twin Towers in Kuala Lumpur',
        'Visit Batu Caves',
        'Take the Phi Phi Island tour from Phuket',
    ),
    'stay' => 'Some packages name their hotels — for example Ibis Styles hotels, or V Lavender and Holiday Inn Express on the 5-day Singapore–Kuala Lumpur tour; the standard packages use standard / 3-star hotels with breakfast.',
    'transport' => 'Return airport transfers are private; sightseeing is on a shared (seat-in-coach) basis unless stated. Some itineraries include a coach transfer from Singapore to Kuala Lumpur.',
    'who_title' => 'Who it suits',
    'who' => array(
        array('Families', 'Theme parks, safaris and easy public transport make Singapore ideal for children.'),
        array('Couples', 'City lights, Sentosa beaches and Thai islands on the longer tours.'),
        array('First trip abroad', 'Safe, well-organised cities with lots to see in a few days.'),
    ),
    'tips' => array(
        'Visa rules differ for Singapore, Malaysia and Thailand and change often; we confirm current requirements with your booking.',
        'Carry an umbrella — short tropical showers are common.',
        'Book Universal Studios and Night Safari dates early in holidays.',
    ),
    'faqs' => array(
        array(
            'What is included in these packages?',
            '<p>Hotel stays with breakfast, airport transfers and sightseeing as listed on each package. Attraction tickets, ferries and flights between countries are optional or quoted separately. Each package lists its inclusions and exclusions.</p>',
        ),
        array(
            'Are flights and visas included?',
            '<p>No — packages list visas and airfare as excluded. We can quote flights and guide you on visas.</p>',
        ),
        array(
            'How many days are enough?',
            '<p>Four to five days for Singapore; six to eight days to add Malaysia, Bintan or Bali.</p>',
        ),
        array('Which hotels are used?', '<p>Packages with named hotels list them on the package page; the standard packages use standard / 3-star hotels or similar.</p>'),
        array(
            'What is the best time to visit?',
            '<p>Year-round; February to April is usually the driest.</p>',
        ),
        array(
            'Can these packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Singapore%20%26%20Malaysia">Tell us what you need</a>.</p>',
        ),
    ),
);
