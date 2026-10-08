<?php
/**
 * Goa destination content (used by /tours/goa and Goa package pages).
 *
 * Sources: Holiday Guru Travel's own Goa itineraries (routes, distances,
 * inclusions) and well-established destination facts. Rules that change often
 * (visas, permits, registrations) are described generally and confirmed per booking.
 * STATUS 'review': published; owner/travel team to review wording.
 * Last updated: 2026-10-08 (destination text refreshed for the new packages; owner review pending).
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'name' => 'Goa',
    'image' => 'assets/img/destinations/goa.jpg',
    'map_query' => 'Goa, India',
    'related' => array('kerala', 'maldives', 'south-india', 'dubai'),
    'frequent' => array(
        array('4-day Delightful Goa itinerary', '/delightful-goa-tour'),
        array('Goa with Dudhsagar jeep safari', '/sun-kissed-goa-escape'),
    ),
    'intro' => 'Goa tour packages are beach holidays of 3 to 6 days with North and South Goa sightseeing, Old Goa and Panaji, hotel stays with breakfast, and airport or station transfers. Some stay in North or South Goa only, one splits the stay between both, and two add a jeep trip to Dudhsagar Falls.',
    'why' => array(
        array(
            'Beaches for every mood',
            'Lively Calangute, Baga and Anjuna in the north; quieter, wider beaches in the south.',
        ),
        array('Portuguese heritage', 'The churches of Old Goa, Fort Aguada and the Latin quarter of Panaji.'),
        array('Easy short break', 'A weekend of 3 days covers the highlights; most packages are 4–5 days.'),
        array('Food and nightlife', 'Goan seafood, beach shacks, markets and evening cruises.'),
    ),
    'best_time' => array(
        array(
            'November – February',
            'Peak season',
            'Dry, sunny and pleasant; Christmas and New Year are the busiest weeks.',
        ),
        array('March – May', 'Summer', 'Hot and humid; good hotel availability.'),
        array(
            'June – September',
            'Monsoon',
            'Heavy rain, green countryside and Dudhsagar Falls in full flow; water sports usually pause.',
        ),
        array('October', 'Shoulder', 'Rain eases and the season begins.'),
    ),
    'best_time_answer' => 'November to February is the best time for a Goa beach holiday; the monsoon (June–September) is green and quieter.',
    'days_answer' => 'Three days covers North Goa and Old Goa; 4–5 days adds South Goa or Dudhsagar Falls; allow 6 days to split the stay between North and South Goa.',
    'days_rows' => array(
        array('4 days', 'North Goa and a relaxed day in South Goa', 'delightful-goa-tour'),
        array('4 days', 'Full-day North and South Goa sightseeing', 'enticing-tour-to-goa'),
        array('4 days', 'Dudhsagar jeep safari and Panaji city tour', 'sun-kissed-goa-escape'),
        array('3 days', 'Weekend: North Goa beaches and Old Goa', 'goa-weekend-getaway'),
        array('6 days', 'Three nights in North Goa, two in South Goa with Palolem', 'goa-leisure-holiday'),
    ),
    'cost_answer' => 'The cost of a Goa package depends mainly on the hotel, the season and the number of travellers. We quote each trip for your dates; flights or trains to Goa are extra.',
    'cost_factors' => array(
        'Hotel category and location (north or south)',
        'Season — Christmas and New Year are highest',
        'Meal plan (breakfast, or breakfast and dinner)',
        'Optional water sports and cruises',
        'Flights or trains to Goa',
        'GST where a package lists it as extra',
    ),
    'places' => array(
        array(
            'North Goa',
            'Calangute, Baga and Anjuna beaches, Fort Aguada and the Saturday and Wednesday markets.',
        ),
        array('South Goa', 'Quieter beaches such as Colva and Palolem.'),
        array('Old Goa', 'The Basilica of Bom Jesus and the Se Cathedral.'),
        array('Panaji', 'Goa’s capital: the Fontainhas Latin quarter and river cruises.'),
        array(
            'Dudhsagar Falls',
            'A tall waterfall on the Goa–Karnataka border, reached by jeep safari; best after the monsoon.',
        ),
    ),
    'things' => array(
        'Spend a day on Calangute and Baga beaches',
        'Visit the churches of Old Goa',
        'Walk through Fontainhas in Panaji',
        'Take the jeep safari to Dudhsagar Falls (on selected packages)',
        'Watch sunset from Fort Aguada',
        'Try Goan seafood at a beach shack',
    ),
    'stay' => 'Hotels as indicated in each itinerary or similar, with daily breakfast — and dinner on some packages. One package uses deluxe rooms on double sharing with resort extras listed on its page.',
    'transport' => 'Packages include arrival and departure transfers from the airport, railway station or bus stand, and sightseeing by coach or cab as listed on each package.',
    'who_title' => 'Who Goa suits',
    'who' => array(
        array('Families', 'Calm South Goa beaches and short sightseeing days suit children and grandparents.'),
        array('Couples', 'Sunsets, beach dinners and heritage walks make Goa a favourite for honeymoons.'),
        array('Friends', 'North Goa’s beaches, markets and nightlife are ideal for groups.'),
    ),
    'tips' => array(
        'Book early for Christmas and New Year, when prices rise sharply.',
        'Sea swimming is not safe during the monsoon; follow lifeguard flags.',
        'Carry sunscreen and light cotton clothes; churches expect covered shoulders.',
    ),
    'faqs' => array(
        array(
            'What is included in Goa packages?',
            '<p>Hotel stays as indicated, daily breakfast (and dinner on some packages), arrival and departure transfers, and the sightseeing listed on each package.</p>',
        ),
        array(
            'How many days are enough for Goa?',
            '<p>Four or five days covers North and South Goa; three days is enough for a weekend, and six days suits a relaxed beach holiday.</p>',
        ),
        array('What is the best time to visit Goa?', '<p>November to February.</p>'),
        array(
            'Are flights included?',
            '<p>No. Goa packages start on arrival; we can add flights or trains to your quote.</p>',
        ),
        array(
            'Are water sports included?',
            '<p>No — they are optional and paid locally, and usually pause during the monsoon.</p>',
        ),
        array(
            'Can Goa packages be customized?',
            '<p>Yes. <a href="/customized-holidays?destination=Goa">Tell us what you need</a>.</p>',
        ),
    ),
);
