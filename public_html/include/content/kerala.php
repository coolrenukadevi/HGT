<?php
/**
 * Kerala destination content (used by /tours/kerala and Kerala package pages).
 *
 * Sources: Holiday Guru Travel's own Kerala itineraries (routes, distances,
 * inclusions) and well-established destination facts. Rules that change often
 * (visas, permits, registrations) are described generally and confirmed per booking.
 * STATUS 'review': published; owner/travel team to review wording.
 * Last updated: 2026-10-08 (destination text refreshed for the new packages; owner review pending).
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'name' => 'Kerala',
    'image' => 'assets/img/destinations/kerala.jpg',
    'map_query' => 'Munnar, Kerala',
    'related' => array('south-india', 'goa', 'maldives', 'kashmir'),
    'frequent' => array(
        array('4-day Munnar Alleppey itinerary', '/munnar-alleppey-tour-package'),
        array('5-day Munnar, Thekkady and Alleppey', '/munnar-thekkady-alleppey'),
    ),
    'intro' => 'Kerala tour packages combine the tea hills of Munnar, the Periyar forests at Thekkady and the Alleppey and Kumarakom backwaters (some with a houseboat night), with Kovalam, Varkala, Kanyakumari or Wayanad on other trips. Our Kerala itineraries run 3 to 10 days from Cochin, Trivandrum or Kozhikode.',
    'why' => array(
        array(
            'Backwater houseboats',
            'Several of our Kerala packages include a night on a houseboat in Alleppey, with meals on board; on the others one can be added on request.',
        ),
        array('Tea hills', 'Munnar’s rolling tea estates, viewpoints and waterfalls.'),
        array('Wildlife and spices', 'Periyar lake and forests at Thekkady, and spice plantation walks.'),
        array('Beaches', 'Kovalam’s crescent beaches and sunrise at Kanyakumari on the longer itineraries.'),
    ),
    'best_time' => array(
        array(
            'September – March',
            'Cool season',
            'The most pleasant weather and the main holiday season; December–January is peak.',
        ),
        array('April – May', 'Summer', 'Hot and humid on the coast; Munnar stays pleasant.'),
        array('June – August', 'Monsoon', 'Heavy rain and lush green scenery; quieter and good value.'),
    ),
    'best_time_answer' => 'September to March is the best time for a Kerala tour; the monsoon (June–August) is green and quieter.',
    'days_answer' => 'Three to four days covers Munnar or the Alleppey backwaters; 5–6 days combines Munnar, Thekkady and the backwaters; allow 7–10 days to add Kovalam, Varkala, Trivandrum or Kanyakumari.',
    'days_rows' => array(
        array('4 days', 'Munnar and Alleppey houseboat', 'munnar-alleppey-tour-package'),
        array('4 days', 'Munnar and Thekkady', 'munnar-thekkady-tour-package'),
        array('5 days', 'Munnar, Thekkady and Alleppey houseboat', 'munnar-thekkady-alleppey'),
        array('6 days', 'Cochin, Munnar, Thekkady and Alleppey', 'kerala-cochin-munnar-thekkady-alleppey'),
        array(
            '7 days',
            'Munnar, Thekkady, Alleppey, Kovalam and Trivandrum',
            'kerala-munnar-thekkady-alleppey-kovalam-trivandrum',
        ),
    ),
    'cost_answer' => 'The cost of a Kerala tour depends on the number of nights, hotel and houseboat category, and the season. We quote each trip for your dates.',
    'cost_factors' => array(
        'Houseboat category and whether it is private or shared',
        'Hotel category (standard / 3-star equivalent, or deluxe on some packages)',
        'Season — December–January and holidays are peak',
        'Number of travellers sharing a room and vehicle',
        'Flights or trains to Cochin or Trivandrum',
        'GST where a package lists it as extra',
    ),
    'places' => array(
        array(
            'Munnar',
            'Tea estates, Mattupetty Dam and Eravikulam National Park; about 135 km (3–4 hours) from Cochin.',
        ),
        array('Thekkady', 'Periyar lake and forest, spice plantations and cultural shows.'),
        array('Alleppey', 'Backwater houseboat cruises; about 190 km from Munnar and 155 km from Thekkady.'),
        array('Kovalam', 'Lighthouse, Hawah and Ashoka beaches; about 165 km from Alleppey.'),
        array('Cochin', 'Fort Kochi, the Chinese fishing nets and the start or end of most tours.'),
    ),
    'things' => array(
        'Spend a night on a houseboat in the Alleppey backwaters',
        'Walk through Munnar’s tea estates',
        'Take a boat ride on Periyar lake at Thekkady',
        'Watch the Chinese fishing nets at Fort Kochi',
        'Relax on Kovalam’s beaches',
        'See sunrise at Kanyakumari on the longer itineraries',
    ),
    'stay' => 'Standard / 3-star equivalent or deluxe hotels as listed on each package, plus one night on a houseboat with all meals on board where the package includes it.',
    'transport' => 'Packages start at Cochin, Trivandrum or Kozhikode, with pick-up from the railway station, bus stand or airport and transfers and sightseeing as specified on each package.',
    'who_title' => 'Who Kerala suits',
    'who' => array(
        array('Families', 'Short drives, houseboats and wildlife boat rides make Kerala easy for all ages.'),
        array('Couples', 'Houseboat evenings and Munnar’s misty hills are classic honeymoon experiences.'),
        array('Wellness', 'Kerala is known for Ayurveda; ask us to add treatments at your hotel.'),
    ),
    'tips' => array(
        'Houseboat cruises follow set timings — check-in around midday and check-out the next morning.',
        'Carry light cotton clothes and a rain layer; Munnar evenings are cool.',
        'Book houseboats early for December–January.',
        'Periyar boat rides depend on availability; the driver follows what is open that day.',
    ),
    'faqs' => array(
        array(
            'What is included in Kerala tour packages?',
            '<p>Hotel stays as per the itinerary, a houseboat night with meals on board where listed, breakfast at hotels, transfers and sightseeing as specified (by private cab on some packages), and applicable taxes where listed. Each package lists its inclusions and exclusions.</p>',
        ),
        array(
            'How many days are enough for Kerala?',
            '<p>Four to five days for Munnar, Thekkady and Alleppey; seven days to add Kovalam.</p>',
        ),
        array(
            'Is the houseboat included?',
            '<p>Yes, in most of our Kerala packages — each package page shows it under inclusions.</p>',
        ),
        array(
            'What is the best time to visit Kerala?',
            '<p>September to March; the monsoon (June–August) is green and quieter.</p>',
        ),
        array(
            'Where do Kerala tours start?',
            '<p>Most start at Cochin; beach itineraries start at Trivandrum.</p>',
        ),
        array(
            'Can Kerala packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Kerala">Tell us what you need</a>.</p>',
        ),
    ),
);
