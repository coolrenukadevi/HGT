<?php
/**
 * Rajasthan destination content (used by /tours/rajasthan, the Rajasthan travel guide and Rajasthan package pages).
 *
 * Sources: Holiday Guru Travel's own Rajasthan itineraries (routes, distances, inclusions) and well-established
 * destination facts. Rules that change often (park permits, opening days) are described generally and confirmed
 * per booking. STATUS 'review': published; owner/travel team to review wording.
 * Added: 2026-10-08 (domestic expansion batch 4). No destination photo yet: image left blank for the owner.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'name' => 'Rajasthan',
    'image' => '',
    'map_query' => 'Rajasthan, India',
    'related' => array('goa', 'himachal', 'uttarakhand', 'kashmir'),
    'frequent' => array(
        array('Jaipur, Jodhpur and Udaipur in 7 days', '/rajasthan-jaipur-jodhpur-udaipur'),
        array('Royal Rajasthan with Jaisalmer', '/rajasthan-jaipur-jodhpur-jaisalmer-udaipur'),
    ),
    'intro' => 'Rajasthan tour packages cover the royal cities of Jaipur, Jodhpur and Udaipur, the desert city of Jaisalmer with the Sam sand dunes, and Pushkar, Mount Abu, Chittorgarh, Kumbhalgarh, Bikaner and the Ranthambore tiger reserve. Our Rajasthan itineraries run 3 to 9 days, with standard / 3-star equivalent hotels and breakfast.',
    'why' => array(
        array('Forts and palaces', 'Amber, Mehrangarh, Jaisalmer, Chittorgarh and Kumbhalgarh — several of them on the UNESCO World Heritage list.'),
        array('Desert and dunes', 'Sunset on the Sam sand dunes and the golden lanes of Jaisalmer Fort.'),
        array('Lakes and temples', 'Udaipur\'s Lake Pichola, Pushkar\'s ghats, the Dilwara and Ranakpur Jain temples and the Ajmer Dargah.'),
        array('Wildlife', 'Tiger safaris in Ranthambore, one of the best-known tiger reserves in India.'),
    ),
    'best_time' => array(
        array('October – March', 'Best season', 'Pleasant days and cool nights; the best time for the desert and the forts.'),
        array('April – June', 'Summer', 'Very hot, especially in the desert; Mount Abu stays milder.'),
        array('July – September', 'Monsoon', 'Lighter rain than most of India; Udaipur\'s lakes fill up, and Ranthambore\'s main zones close.'),
    ),
    'best_time_answer' => 'October to March is the best time to visit Rajasthan; summer (April–June) is very hot, especially in the Thar desert.',
    'days_answer' => 'Two to three days covers Jaipur alone; allow 5 days for Jodhpur and Jaisalmer or Udaipur and Mount Abu, 7 days for Jaipur, Jodhpur and Udaipur, and 9 days to add Jaisalmer.',
    'days_rows' => array(
        array('3 days', 'Jaipur: Amber Fort, City Palace and Hawa Mahal', 'rajasthan-jaipur'),
        array('5 days', 'Jodhpur and Jaisalmer with the Sam dunes', 'rajasthan-jodhpur-jaisalmer'),
        array('7 days', 'Jaipur, Jodhpur and Udaipur', 'rajasthan-jaipur-jodhpur-udaipur'),
        array('9 days', 'Jaipur, Jodhpur, Jaisalmer and Udaipur', 'rajasthan-jaipur-jodhpur-jaisalmer-udaipur'),
    ),
    'cost_answer' => 'The cost of a Rajasthan tour depends mainly on the number of nights, the hotel category, the season and the number of travellers. We quote each trip for your dates; flights or trains to Rajasthan are extra.',
    'cost_factors' => array(
        'Hotel category — standard, or heritage and palace hotels on request',
        'Season — October to March is busiest, with Diwali and Christmas highest',
        'Number of travellers sharing a room and vehicle',
        'Optional camel rides, desert safaris, safaris in Ranthambore and cultural shows',
        'Flights or trains to Jaipur, Jodhpur or Udaipur',
    ),
    'places' => array(
        array('Jaipur', 'The Pink City: Amber Fort, the City Palace, Jantar Mantar and Hawa Mahal.'),
        array('Jodhpur', 'The Blue City below Mehrangarh Fort, with the Jaswant Thada and Umaid Bhawan Palace.'),
        array('Jaisalmer', 'A living golden fort, carved havelis and the Sam sand dunes in the Thar desert.'),
        array('Udaipur', 'The city of lakes: the City Palace on Lake Pichola and the Monsoon Palace.'),
        array('Pushkar and Ajmer', 'Pushkar\'s holy lake and Brahma temple, and the Dargah of Khwaja Moinuddin Chishti at Ajmer.'),
        array('Mount Abu', 'Rajasthan\'s only hill station, with the marble Dilwara Jain temples.'),
        array('Chittorgarh and Kumbhalgarh', 'Two great hill forts of Mewar, with the Ranakpur Jain temple nearby.'),
        array('Ranthambore', 'A tiger reserve around an old fort near Sawai Madhopur.'),
    ),
    'things' => array(
        'Explore Amber Fort and the City Palace in Jaipur',
        'Watch sunset over the Sam sand dunes near Jaisalmer',
        'Take a boat on Lake Pichola in Udaipur (paid locally)',
        'Walk the ramparts of Mehrangarh Fort in Jodhpur',
        'See the carved marble of the Dilwara and Ranakpur temples',
        'Go on a tiger safari in Ranthambore (permits paid locally)',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast as listed on each package; heritage and palace hotels can be quoted on request.',
    'transport' => 'Tours start in Jaipur, Jodhpur, Udaipur or Bikaner, with pick-up from the airport or railway station and transfers and sightseeing as specified on each package. Distances between the cities are long (about 250–340 km), so travel days are mostly on the road.',
    'who_title' => 'Who Rajasthan suits',
    'who' => array(
        array('Families', 'Forts, palaces and the desert keep children and grandparents interested; travel days are long, so allow rest.'),
        array('Couples', 'Udaipur\'s lakes and Jaisalmer\'s desert sunsets make Rajasthan a favourite for honeymoons.'),
        array('History lovers', 'Forts, palaces, step-wells and temples from a thousand years of Rajput history.'),
    ),
    'tips' => array(
        'Carry light cotton clothes, a hat and sunglasses; desert nights in winter are cold.',
        'Monument entry fees are paid locally; many forts charge extra for cameras.',
        'Dress modestly at temples and the Ajmer Dargah; carry a scarf to cover your head.',
    ),
    'faqs' => array(
        array(
            'What is included in Rajasthan packages?',
            '<p>Standard packages include standard / 3-star equivalent hotels with breakfast, transfers and sightseeing as specified in the itinerary, and travel assistance. Monument entry fees, camel rides, desert safaris and Ranthambore safari permits are paid locally. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array(
            'How many days are enough for Rajasthan?',
            '<p>Seven days covers Jaipur, Jodhpur and Udaipur; allow 9 days to add Jaisalmer and the desert.</p>',
        ),
        array('What is the best time to visit Rajasthan?', '<p>October to March.</p>'),
        array(
            'Are flights or trains included?',
            '<p>No. Rajasthan packages start on arrival in Jaipur, Jodhpur, Udaipur or Bikaner; we can add flights or trains to your quote.</p>',
        ),
        array(
            'Is a desert camp night included?',
            '<p>No. Standard packages stay in hotels in Jaisalmer and visit the Sam dunes for sunset; a desert camp night can be quoted on request.</p>',
        ),
        array(
            'Can Rajasthan packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Rajasthan">Tell us what you need</a>.</p>',
        ),
    ),
);
