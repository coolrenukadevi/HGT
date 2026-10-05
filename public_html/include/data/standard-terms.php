<?php
/**
 * Holiday Guru Travel GLOBAL STANDARD (level 1) package content — owner directive, 2026-10-04.
 *
 * Rendering priority (include/package_resolve.php): CRM CUSTOM > PACKAGE OVERRIDE > GLOBAL STANDARD > fallback.
 * Each value here is used only when neither the package nor a CMS/CRM override states its own; only the
 * applicable value is ever shown. Wording is deliberately conservative: nothing here names a hotel, claims a
 * 4-star/5-star/luxury category, or implies flights, trains, visas, insurance, entry tickets or activities.
 */
return array(
    // Package-level default only; never a specific hotel or property.
    'hotel_category' => 'Standard / 3-star equivalent',
    'accommodation' => 'Stay in standard/3-star equivalent accommodation as per the selected package plan.',
    'inclusions' => array(
        'Standard/3-star equivalent accommodation as per package plan. Room category subject to package/quotation.',
        'Breakfast as per package plan. Other meals only where specifically included.',
        'Transfers and sightseeing as specified in the itinerary.',
        'Travel coordination/assistance during the tour.',
        'Applicable taxes where included in the package/quotation.',
    ),
    'exclusions' => array(
        'Airfare/train fare unless specifically included.',
        'Personal expenses.',
        'Meals not specifically mentioned.',
        'Monument/attraction entry fees unless specifically included.',
        'Optional activities.',
        'Camera/video charges where applicable.',
        'Travel insurance.',
        'Visa/permit charges where applicable.',
        'Tips/gratuities.',
        'Anything not specifically mentioned under inclusions.',
        'Expenses caused by delays, weather or circumstances beyond reasonable operational control where applicable.',
    ),
    // Meal plan when a package states none. A package's own MAP / AP / Full Board etc. is always kept.
    'meals' => 'Breakfast',
    // Shown only for itineraries whose source is STANDARD (generated / suggested / non-confirmed routing).
    'suggested_note' => 'This is a suggested day-by-day plan. Timings, the order of sightseeing and hotels are confirmed with your quote.',
);
