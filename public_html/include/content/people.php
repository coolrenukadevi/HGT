<?php
/**
 * Leadership and team profiles — to be supplied by the owner.
 * Fill name, role and a 1–2 sentence bio; add the photo file under
 * assets/img/team/ and set 'photo' (e.g. 'assets/img/team/name.jpg').
 * Leave a field empty to show "To be added". Add or remove entries freely.
 */
$blank = array('name' => '', 'role' => '', 'bio' => '', 'photo' => '');
return array(
    // From the owner's company-page pack (2026-09-30). Titles to be confirmed by the owner for the current legal
    // entity; add photos under assets/img/team/ and set 'photo'.
    'leadership' => array(
        array('name' => 'Deepak Singh Rana', 'role' => 'Founder', 'photo' => '',
              'bio' => array('Deepak founded Holiday Guru Travel after years of planning trips for travellers across India and abroad. He still plans itineraries himself.',
                             'He leads sales and supplier relationships, and sets how the team handles every booking: confirm the plan first, keep the traveller informed, and pick up the phone when something goes wrong.')),
        array('name' => 'Laxmi Saxena', 'role' => 'Managing Director', 'photo' => '',
              'bio' => array('Laxmi oversees operations, accounts and compliance for ' . HG_LEGAL_NAME . '. Her focus is making sure every booking is delivered as promised: hotels confirmed, vouchers issued and payments accounted for.',
                             'She also leads hiring and training for the operations team.')),
    ),
    'team' => array($blank, $blank, $blank, $blank, $blank, $blank),
);
