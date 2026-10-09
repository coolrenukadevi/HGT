<?php
/**
 * Vaishno Devi destination content (used by /tours/vaishno-devi, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established facts; yatra rules (registration, helicopter
 * booking) are set by the Shri Mata Vaishno Devi Shrine Board and confirmed per booking.
 * STATUS 'review': published; owner/travel team to review wording. Added: 2026-10-08 (domestic expansion batch 6).
 * No Vaishno Devi photo in the library yet: image left blank for the owner.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'name' => 'Vaishno Devi',
    'image' => '',
    'map_query' => 'Katra, Jammu and Kashmir, India',
    'related' => array('kashmir', 'amarnath', 'himachal', 'char-dham'),
    'frequent' => array(
        array('Vaishno Devi yatra from Jammu in 3 days', '/vaishno-devi-katra'),
        array('Amritsar and Vaishno Devi in 5 days', '/vaishno-devi-amritsar-katra'),
    ),
    'intro' => 'Vaishno Devi tour packages take you to Katra for the 13 km climb to the Holy Cave of Mata Vaishno Devi and the Bhairon temple, with Jammu city sightseeing or extensions to Shiv Khori, Patnitop, Srinagar, Amritsar, Dalhousie and Dharamshala, and the Devi temples of Himachal. Our itineraries run 3 to 7 days from Jammu, Amritsar or Delhi, with standard / 3-star equivalent hotels and breakfast.',
    'why' => array(
        array('The Holy Cave', 'Darshan of the three pindis of Mata Vaishno Devi, one of the most visited shrines in India.'),
        array('Choose your pace', 'Walk, ride a pony, take a palki or book the helicopter to Sanjhichhat.'),
        array('Easy to combine', 'Add Shiv Khori, Patnitop, Srinagar, Amritsar or the Devi temples of Himachal.'),
        array('Planned with rest', 'Nights at Katra before and after the climb.'),
    ),
    'best_time' => array(
        array('March – June', 'Busy season', 'Pleasant weather; the summer holidays are very crowded.'),
        array('July – August', 'Monsoon', 'Rain can make the path slippery; the helicopter may not fly in bad weather.'),
        array('September – November', 'Good season', 'Pleasant days; Navratri brings large crowds.'),
        array('December – February', 'Winter', 'Cold, with snow possible near the Bhawan; fewer pilgrims.'),
    ),
    'best_time_answer' => 'The yatra runs all year. March to June and September to November are the most comfortable; Navratri and summer holidays are very crowded.',
    'days_answer' => 'Three days from Jammu is enough for darshan; allow 4–5 days to add Shiv Khori, Patnitop or Amritsar, and 7 days for Srinagar, Dalhousie and Dharamshala, or the Himachal Devi temples.',
    'days_rows' => array(
        array('3 days', 'Vaishno Devi from Jammu', 'vaishno-devi-katra'),
        array('4 days', 'With the Shiv Khori cave', 'vaishno-devi-katra-shiv-khori'),
        array('5 days', 'With Amritsar\'s Golden Temple', 'vaishno-devi-amritsar-katra'),
        array('7 days', 'With Patnitop and Srinagar', 'vaishno-devi-katra-patnitop-srinagar'),
    ),
    'cost_answer' => 'The cost depends mainly on the hotel category, the season (Navratri and summer holidays are busiest) and the number of travellers. Ponies, palkis, the helicopter, flights and trains are extra.',
    'cost_factors' => array(
        'Hotel category in Katra',
        'Season — Navratri and summer holidays are busiest',
        'Number of travellers sharing a room and vehicle',
        'Pony, palki, battery car, ropeway or helicopter (paid separately)',
        'Flights or trains to Jammu or Katra',
    ),
    'places' => array(
        array('Katra', 'The base town for the yatra, with the registration counters and the start of the path.'),
        array('Vaishno Devi Bhawan', 'The Holy Cave, about 13 km above Katra, and the Bhairon temple above it.'),
        array('Shiv Khori', 'A cave with a natural Shivling near Ransoo, about 80 km from Katra.'),
        array('Patnitop', 'A hill resort in pine forest on the road to Srinagar.'),
        array('Jammu', 'The Raghunath temple, Bahu Fort and the Amar Mahal museum.'),
    ),
    'things' => array(
        'Have darshan at the Holy Cave of Mata Vaishno Devi',
        'Visit the Bhairon temple (ropeway paid locally)',
        'See the natural Shivling in the Shiv Khori cave',
        'Rest in the pine forests of Patnitop',
        'Combine with the Golden Temple in Amritsar',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast as listed on each package, mostly in Katra.',
    'transport' => 'Tours start at Jammu or Amritsar, or by train from Delhi to Katra, with transfers and sightseeing as specified on each package. Jammu–Katra is about 50 km (1.5–2 hours). The climb from Katra is on foot, by pony or palki, or by helicopter to Sanjhichhat.',
    'who_title' => 'Who the yatra suits',
    'who' => array(
        array('Families', 'Children, parents and grandparents; ponies, palkis and the battery car help with the climb.'),
        array('Senior pilgrims', 'The helicopter to Sanjhichhat shortens the walk to about 2.5 km each way.'),
        array('Groups', 'Many pilgrims travel together; book early for Navratri.'),
    ),
    'tips' => array(
        'Carry photo ID; the yatra registration (RFID card) is compulsory and free.',
        'Book helicopter seats early on the Shrine Board portal; they sell out.',
        'Wear comfortable shoes and carry warm clothes; it is cold at the Bhawan at night.',
    ),
    'faqs' => array(
        array(
            'What is included in Vaishno Devi packages?',
            '<p>Standard packages include standard / 3-star equivalent hotels with breakfast, transfers and sightseeing as specified in the itinerary, and travel assistance. Ponies, palkis, the battery car, the Bhairon ropeway and helicopter seats are paid separately. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array('How long is the climb?', '<p>About 13 km each way from Katra to the Bhawan; most pilgrims take 6–8 hours to go up.</p>'),
        array('Is the helicopter included?', '<p>No. Helicopter seats from Katra to Sanjhichhat are booked separately on the Shrine Board portal and sell out early.</p>'),
        array('Is registration needed?', '<p>Yes. Every pilgrim needs a yatra registration (RFID card), free at the Katra counters or online.</p>'),
        array(
            'Can the itinerary be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Vaishno%20Devi">Tell us what you need</a>.</p>',
        ),
    ),
);
