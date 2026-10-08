<?php
/**
 * Japan destination content (used by /tours/japan, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established destination facts; visa rules, closures
 * and tour timings change and are confirmed per booking. STATUS 'review': published; owner/travel team to review.
 * Added: 2026-10-08 (international expansion batch I2). No Japan photo in the library yet.
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'image' => '',
    'name' => 'Japan',
    'map_query' => 'Japan',
    'related' => array(
        'vietnam',
        'singapore-malaysia',
        'thailand',
        'bali',
    ),
    'frequent' => array(
        array('Tokyo and Mount Fuji in 5 days', '/japan-tokyo-mount-fuji'),
        array('Tokyo, Kyoto and Osaka in 7 days', '/japan-tokyo-kyoto-osaka'),
    ),
    'intro' => 'Japan tour packages cover Tokyo with a day at Mount Fuji, the bullet train to the temples and shrines of Kyoto, the Great Buddha and deer of Nara, Osaka, the hot-spring hills of Hakone, and Hiroshima with the island of Miyajima. Our itineraries run 5 to 8 days and are land only, with standard / 3-star hotels and breakfast; flights, visas and rail tickets are quoted separately.',
    'why' => array(
        array('Temples and shrines', 'Kyoto\'s Fushimi Inari and Kinkaku-ji, Nara\'s Todai-ji and Tokyo\'s Senso-ji.'),
        array('Mount Fuji', 'Lake Kawaguchiko and Hakone, with the mountain in view on clear days.'),
        array('The bullet train', 'The Shinkansen links Tokyo, Kyoto, Osaka and Hiroshima.'),
        array('Modern cities', 'Shibuya, Shinjuku and Osaka\'s Dotonbori.'),
    ),
    'best_time' => array(
        array('March – May', 'Spring', 'Cherry blossom from late March to early April; the busiest and most expensive time.'),
        array('June – August', 'Summer', 'Rainy in June and hot in July and August; the Fuji fifth station road is usually open.'),
        array('September – November', 'Autumn', 'Clear days and autumn colours, especially in Kyoto in November.'),
        array('December – February', 'Winter', 'Cold and clear, with the best Fuji views; quieter cities.'),
    ),
    'best_time_answer' => 'Spring (March to May) for cherry blossom and autumn (October to November) for colours are the best times to visit Japan.',
    'days_answer' => 'Five days covers Tokyo with Mount Fuji, or Kyoto, Nara and Osaka; seven days covers the Golden Route from Tokyo to Osaka; allow eight to add Hakone or Hiroshima.',
    'days_rows' => array(
        array('5 days', 'Tokyo and Mount Fuji', 'japan-tokyo-mount-fuji'),
        array('5 days', 'Osaka, Kyoto and Nara', 'japan-osaka-kyoto-nara'),
        array('7 days', 'Tokyo, Kyoto and Osaka', 'japan-tokyo-kyoto-osaka'),
        array('7 days', 'Kyoto, Hiroshima and Osaka', 'japan-kyoto-hiroshima-osaka'),
        array('8 days', 'Tokyo, Hakone, Kyoto and Osaka', 'japan-tokyo-hakone-kyoto-osaka'),
    ),
    'cost_answer' => 'The cost depends mainly on the number of nights, the hotel category, the season and the number of travellers. Flights, visas, bullet-train tickets or rail passes, cable cars, cruises and entry fees are quoted separately or paid locally.',
    'cost_factors' => array(
        'Hotel category; city hotel rooms are compact',
        'Season — cherry blossom and autumn colours are highest',
        'Number of travellers sharing a room',
        'Bullet-train tickets or a rail pass',
        'Flights from India',
    ),
    'places' => array(
        array('Tokyo', 'Senso-ji in Asakusa, the Meiji shrine, Shibuya and Shinjuku.'),
        array('Mount Fuji and Hakone', 'Lake Kawaguchiko, the Chureito Pagoda, Lake Ashi and Owakudani.'),
        array('Kyoto', 'Fushimi Inari, Kinkaku-ji, Kiyomizu-dera, Arashiyama and Gion.'),
        array('Nara', 'The Great Buddha of Todai-ji and the deer of Nara Park.'),
        array('Osaka', 'Osaka Castle, Dotonbori and Universal Studios Japan.'),
        array('Hiroshima and Miyajima', 'The Peace Memorial and the floating torii of the Itsukushima shrine.'),
    ),
    'things' => array(
        'Walk through the red gates of Fushimi Inari',
        'See Mount Fuji from Lake Kawaguchiko',
        'Ride the Shinkansen bullet train',
        'Feed the deer in Nara Park',
        'Cross the Shibuya crossing',
        'Visit the Peace Memorial in Hiroshima',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast, as listed on each package; Japanese city hotel rooms are compact.',
    'transport' => 'Packages are land only: airport transfers and sightseeing as specified, mostly by train and metro. Flights from India and Shinkansen tickets or rail passes are quoted separately. Cable cars, cruises and entry fees are paid locally.',
    'who_title' => 'Who Japan suits',
    'who' => array(
        array('Couples', 'Kyoto\'s temples, Hakone\'s hot springs and Fuji views.'),
        array('Families', 'Safe, clean cities, the bullet train and theme parks.'),
        array('Friends', 'Tokyo and Osaka for food, shopping and nightlife.'),
    ),
    'tips' => array(
        'Indian passport holders need a visa for Japan; we confirm the current rules when you book.',
        'Carry some yen in cash; smaller shops and temples may not take cards.',
        'Pack light: trains have limited luggage space, and luggage forwarding is available.',
        'Vegetarian food takes some planning; we can suggest restaurants.',
    ),
    'faqs' => array(
        array(
            'What is included in Japan packages?',
            '<p>Standard / 3-star hotels with breakfast, airport transfers and the sightseeing listed on each package. Flights, visas, travel insurance, bullet-train tickets, cable cars and entry fees are not included; we can quote them. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array(
            'Are flights included?',
            '<p>No. Our Japan packages are land only; we can quote flights from India and within the country.</p>',
        ),
        array(
            'Do Indians need a visa for Japan?',
            '<p>Yes. Indian passport holders need a visa for Japan. The rules and fees change from time to time, so we confirm them when you book.</p>',
        ),
        array('What is the best time to visit Japan?', '<p>March to May and October to November.</p>'),
        array(
            'Can Japan packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Japan">Tell us what you need</a>.</p>',
        ),
    ),
);
