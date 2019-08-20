<?php

add_filter( 'meteor-labels', function( $labels ){
  $labels = array(
    'recurring' => array(
      'en'  => 'Pay this amount monthly',
      'de'  => 'Zahlen Sie diesen Betrag monatlich'
    ),
    'email-updates' => array(
      'en'  => 'Yes, please keep me informed by email about your work, your breakthroughs, and how to best support you:',
      'de'  => 'Ja, bitte halten Sie mich per E-Mail über Ihre Arbeit, Ihre Durchbrüche und Ihre beste Unterstützung auf dem Laufenden:'
    ),
    'specific-UK' => array(
      'en'  => 'Only for UK residents',
      'de'  => 'Nur für Einwohner Großbritanniens'
    ),
    'read-UK' => array(
      'en'  => 'Read UK Gift Aid Agreement',
      'de'  => 'Lesen Sie die UK-Geschenkhilfevereinbarung'
    ),
    'phone' => array(
      'en'  => '*Please keep me informed about your work and how to best support you by phone',
      'de'  => '*Bitte halten Sie mich über Ihre Arbeit und Ihre telefonische Unterstützung auf dem Laufenden'
    ),
    'phone_number' => array(
      'en'  => 'Phone Number',
      'de'  => 'Telefonnummer'
    ),
    'updates' => array(
      'en'  => 'Would you like to receive updates by',
      'de'  => 'Möchten Sie Updates von erhalten?'
    ),
    'form-message-below' => array(
      'en'  => 'We treat your personal data with care and in compliance with applicable law. Please visit ADFInternational.org/privacy for a full overview.',
      'de'  => 'Wir behandeln Ihre personenbezogenen Daten sorgfältig und in Übereinstimmung mit den geltenden Gesetzen. Bitte besuchen Sie ADFInternational.org/privacy für eine vollständige Übersicht.'
    ),
    'currency' => array(
      'en'  => 'Currency *',
      'de'  => 'Währung *'
    ),
    'amount_choices' => array(
      'en'  => 'Donation Amount *',
      'de'  => 'Spendenbetrag *'
    ),
    'other' => array(
      'en'  => 'Other',
      'de'  => 'Andere'
    ),
    'custom-amount' => array(
      'en'  => 'Enter Amount',
      'de'  => 'Menge eingeben'
    ),
    'name' => array(
      'en'  => 'Name *',
      'de'  => 'Name *'
    ),
    'firstname' => array(
      'en'  => 'First Name',
      'de'  => 'Vorname'
    ),
    'lastname' => array(
      'en'  => 'Last Name',
      'de'  => 'Nachname'
    ),
    'email' => array(
      'en'  => 'Email *',
      'de'  => 'Email *'
    ),
    'email_addr' => array(
      'en'  => 'Email Address',
      'de'  => 'E-Mail-Addresse'
    ),
    'email_inline_notify' => array(
      'en'  => 'Yes, I want to Subscribe',
      'de'  => 'Ja, ich möchte abonnieren'
    ),
    //Card details page translation
    'card' => array(
      'en'  => 'Card Details *',
      'de'  => 'Kartendetails *'
    ),
    'address' => array(
      'en'  => 'Address',
      'de'  => 'Adresse'
    ),
    'address-line1' => array(
      'en'  => 'Street Address',
      'de'  => 'Straße Address'
    ),
    'address-line2' => array(
      'en'  => 'Address Line 2',
      'de'  => 'Adresszeile 2'
    ),
    'address-city' => array(
      'en'  => 'City',
      'de'  => 'Stadt'
    ),
    'address-state' => array(
      'en'  => 'State / Province / Region',
      'de'  => 'Bundesland / Landkreis / Region'
    ),
    'address-zip' => array(
      'en'  => 'Postal Code / Zip',
      'de'  => 'Postleitzahl'
    ),
    'address-country' => array(
      'en'  => 'Country *',
      'de'  => 'Country *'
    ),
    'mail' => array(
      'en'  => 'Post',
      'de'  => 'Post'
    ),
    'phone_inline' => array(
      'en'  => 'Phone',
      'de'  => 'Telefon'
    ),
    // For previous,next,submit buttons
    'btn_prev' => array(
      'en'  => 'Previous',
      'de'  => 'Bisherige'
    ),
    'btn_next' => array(
      'en'  => 'Next',
      'de'  => 'Nächster'
    ),
    'btn_submit' => array(
      'en'  => 'Submit',
      'de'  => 'einreichen'
    ),
  );
  return $labels;
});
