<?php
/**
 * Varanasi (Kashi) and Ayodhya destination content (used by /tours/kashi-ayodhya, its travel guide and package pages).
 * Sources: Holiday Guru Travel's own itineraries and well-established facts; temple timings and security rules change
 * and are confirmed per booking. STATUS 'review': published; owner/travel team to review wording.
 * Added: 2026-10-08 (domestic expansion batch 5; extended in batch 6).
 */
return array(
    'status' => 'review',
    'reviewed' => '2026-10-08',
    'name' => 'Varanasi & Ayodhya',
    'image' => 'assets/img/packages/varanasi-ganga-aarti.jpg',
    'map_query' => 'Varanasi, India',
    'related' => array('golden-triangle', 'char-dham', 'uttarakhand', 'rajasthan'),
    'frequent' => array(
        array('Varanasi (Kashi) in 3 days', '/varanasi-kashi-tour'),
        array('Ayodhya Ram Mandir in 3 days', '/ayodhya-ram-mandir-tour'),
    ),
    'intro' => 'Varanasi and Ayodhya tour packages cover Kashi\'s ghats on the Ganga — the evening aarti at Dashashwamedh Ghat, a sunrise boat and darshan at Kashi Vishwanath — Sarnath, where the Buddha first taught, Ayodhya\'s Shri Ram Janmabhoomi temple and Saryu aarti, and the Triveni Sangam at Prayagraj, with Chitrakoot and Shringverpur, Chunar and the Vindhyachal Shakti Peeth, Naimisharanya, Gorakhpur, the Nawabi city of Lucknow, and Bodhgaya and Nalanda in Bihar. Our itineraries run 3 to 7 days, with standard / 3-star equivalent hotels and breakfast.',
    'why' => array(
        array('The ghats of Kashi', 'The Ganga aarti every evening and the ghats at sunrise, seen from a boat.'),
        array('Two great pilgrimages', 'Kashi Vishwanath, one of the twelve Jyotirlingas, and the Shri Ram Janmabhoomi temple in Ayodhya.'),
        array('Sarnath', 'The Dhamek Stupa and the Lion Capital of Ashoka, where the Buddha gave his first sermon.'),
        array('Short trips', 'Three to four days covers each city; both have airports and railway stations.'),
    ),
    'best_time' => array(
        array('October – March', 'Best season', 'Pleasant days; Dev Deepawali in November lights up the ghats.'),
        array('April – June', 'Summer', 'Very hot; plan temple visits early in the morning.'),
        array('July – September', 'Monsoon', 'The Ganga rises; boat rides and the aarti location can change.'),
    ),
    'best_time_answer' => 'October to March is the best time to visit Varanasi and Ayodhya; summer is very hot, and in the monsoon the Ganga rises over the lower ghats.',
    'days_answer' => 'Three days covers Varanasi or Ayodhya; 4–5 days adds Sarnath, or combines Varanasi and Ayodhya; allow 6–7 days to add Prayagraj, Chitrakoot, Naimisharanya, Lucknow or Bodhgaya.',
    'days_rows' => array(
        array('3 days', 'Varanasi: aarti, sunrise boat and Kashi Vishwanath', 'varanasi-kashi-tour'),
        array('4 days', 'Varanasi with Sarnath and Ramnagar Fort', 'varanasi-sarnath'),
        array('3 days', 'Ayodhya: Ram Janmabhoomi, Hanuman Garhi and the Saryu', 'ayodhya-ram-mandir-tour'),
        array('6 days', 'Prayagraj, Varanasi and Ayodhya', 'prayagraj-varanasi-ayodhya'),
        array('7 days', 'Ram Van Gaman: Varanasi, Prayagraj, Chitrakoot and Ayodhya', 'varanasi-prayagraj-chitrakoot-ayodhya'),
    ),
    'cost_answer' => 'The cost depends mainly on the hotel category, the season (festivals are busiest) and the number of travellers. We quote each trip for your dates; flights or trains to Varanasi or Ayodhya are extra.',
    'cost_factors' => array(
        'Hotel category — standard, or ghat-side hotels on request',
        'Festival dates such as Dev Deepawali and Diwali',
        'Number of travellers sharing a room and vehicle',
        'Boat rides and entry fees (paid locally)',
        'Flights or trains to Varanasi or Ayodhya',
    ),
    'places' => array(
        array('Varanasi', 'The ghats of the Ganga, the evening aarti at Dashashwamedh Ghat and the Kashi Vishwanath temple.'),
        array('Sarnath', 'The Dhamek Stupa and the museum with the Lion Capital of Ashoka (closed on Fridays).'),
        array('Ayodhya', 'The Shri Ram Janmabhoomi temple, Hanuman Garhi, Kanak Bhawan and the Saryu ghats.'),
        array('Prayagraj', 'The Triveni Sangam of the Ganga, Yamuna and Saraswati, and the Bade Hanuman temple.'),
        array('Chitrakoot', 'Ramghat on the Mandakini and the Kamadgiri parikrama.'),
        array('Bodhgaya', 'The Mahabodhi Temple and the Bodhi tree, a UNESCO World Heritage Site.'),
    ),
    'things' => array(
        'Watch the Ganga aarti at Dashashwamedh Ghat',
        'Take a sunrise boat along the ghats (paid locally)',
        'Have darshan at Kashi Vishwanath',
        'Visit the Dhamek Stupa at Sarnath',
        'Have darshan at the Shri Ram Janmabhoomi temple in Ayodhya',
        'Attend the Saryu aarti at Ram Ki Paidi',
    ),
    'stay' => 'Standard / 3-star equivalent hotels with breakfast as listed on each package.',
    'transport' => 'Tours start at Varanasi or Ayodhya airport or railway station, with transfers and sightseeing as specified on each package. The old lanes near the ghats are reached on foot or by cycle-rickshaw.',
    'who_title' => 'Who these trips suit',
    'who' => array(
        array('Pilgrims', 'Darshan at Kashi Vishwanath and the Ram Janmabhoomi temple, and the Ganga and Saryu aartis.'),
        array('Families', 'Short trips with plenty of walking; plan for crowds on festival days.'),
        array('Culture lovers', 'Music, silk weaving and the old lanes of the oldest living city in India.'),
    ),
    'tips' => array(
        'Mobile phones and bags are not allowed inside Kashi Vishwanath or the Ram Janmabhoomi temple; lockers are available.',
        'Dress modestly for temples; carry little cash and a small bag.',
        'Festival days are very crowded; book early for Dev Deepawali and Diwali.',
    ),
    'faqs' => array(
        array(
            'What is included in these packages?',
            '<p>Standard packages include standard / 3-star equivalent hotels with breakfast, transfers and sightseeing as specified in the itinerary, and travel assistance. Boat rides, entry fees and any special darshan or puja are paid locally. Each package page lists its exact inclusions and exclusions.</p>',
        ),
        array('How many days are enough for Varanasi?', '<p>Three days; four days adds Sarnath and Ramnagar Fort.</p>'),
        array('Can I take my phone into the temples?', '<p>No — mobile phones and bags are not allowed inside Kashi Vishwanath or the Shri Ram Janmabhoomi temple. Lockers are available near the entrances.</p>'),
        array('What is the best time to visit?', '<p>October to March; Dev Deepawali in November is special but very crowded.</p>'),
        array(
            'Can I combine Varanasi and Ayodhya?',
            '<p>Yes — see <a href="/varanasi-ayodhya">Varanasi and Ayodhya</a> or <a href="/prayagraj-varanasi-ayodhya">Prayagraj, Varanasi and Ayodhya</a>, or <a href="/customized-holidays?destination=Varanasi%20and%20Ayodhya">tell us your dates</a>.</p>',
        ),
    ),
);
