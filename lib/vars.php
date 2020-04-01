<?php

add_filter( 'meteor-labels', function( $labels ){
  $labels = array(
    'recurring' => array(
      'en'  => 'Pay this amount monthly',
      'de'  => 'Zahlen Sie diesen Betrag monatlich',
      'fr'  => 'Payez ce montant mensuellement',
      'es'  => 'Pague esta cantidad mensualmente'
    ),
    'email-updates' => array(
      'en'  => 'Yes, please keep me informed by email about your work, your breakthroughs, and how to best support you:',
      'de'  => 'Ja, bitte halten Sie mich per E-Mail über Ihre Arbeit, Ihre Durchbrüche und Ihre beste Unterstützung auf dem Laufenden:',
      'fr'  => 'Oui, veuillez me tenir informé par e-mail de votre travail, de vos avancées et de la meilleure façon de vous soutenir:',
      'es'  => 'Sí, manténgame informado por correo electrónico sobre su trabajo, sus avances y la mejor manera de apoyarlo:'
    ),
    'specific-UK' => array(
      'en'  => 'Only for UK residents',
      'de'  => 'Nur für Einwohner Großbritanniens',
      'fr'  => 'Uniquement pour les résidents britanniques',
      'es'  => 'Solo para residentes del Reino Unido'
    ),
    'read-UK' => array(
      'en'  => 'Read UK Gift Aid Agreement',
      'de'  => 'Lesen Sie die UK-Geschenkhilfevereinbarung',
      'fr'  => 'Lire l\'accord sur les cadeaux britanniques',
      'es'  => 'Lea el acuerdo de ayuda de regalo del Reino Unido'
    ),
    'phone' => array(
      'en'  => '*Please keep me informed about your work and how to best support you by phone',
      'de'  => '*Bitte halten Sie mich über Ihre Arbeit und Ihre telefonische Unterstützung auf dem Laufenden',
      'fr'  => '* Veuillez me tenir informé de votre travail et de la meilleure façon de vous soutenir par téléphone',
      'es'  => '* Por favor, manténganme informado sobre su trabajo y la mejor manera de apoyarlo por teléfono'
    ),
    'phone_number' => array(
      'en'  => 'Phone Number',
      'de'  => 'Telefonnummer',
      'fr'  => 'Numéro de téléphone',
      'es'  => 'Número de teléfono'
    ),
    'updates' => array(
      'en'  => 'Would you like to receive updates by',
      'de'  => 'Möchten Sie Updates von erhalten?',
      'fr'  => 'Souhaitez-vous recevoir des mises à jour par',
      'es'  => '¿Desea recibir actualizaciones de'
    ),
    'form-message-below' => array(
      'en'  => 'We treat your personal data with care and in compliance with applicable law. Please visit ADFInternational.org/privacy for a full overview.',
      'de'  => 'Wir behandeln Ihre personenbezogenen Daten sorgfältig und in Übereinstimmung mit den geltenden Gesetzen. Bitte besuchen Sie ADFInternational.org/privacy für eine vollständige Übersicht.',
      'fr'  => 'Nous traitons vos données personnelles avec soin et conformément à la loi applicable. Veuillez visiter ADFInternational.org/privacy pour un aperçu complet.',
      'es'  => 'Tratamos sus datos personales con cuidado y de conformidad con la ley aplicable. Visite ADFInternational.org/privacy para obtener una descripción completa.'
    ),
    'currency' => array(
      'en'  => 'Currency *',
      'de'  => 'Währung *',
      'fr'  => 'Devise *',
      'es'  => 'Moneda *'
    ),
    'amount_choices' => array(
      'en'  => 'Donation Amount *',
      'de'  => 'Spendenbetrag *',
      'fr'  => 'Montant du don *',
      'es'  => 'Monto de donación *'
    ),
    'other' => array(
      'en'  => 'Other',
      'de'  => 'Andere',
      'fr'  => 'Autre',
      'es'  => 'Otro'
    ),
    'custom-amount' => array(
      'en'  => 'Enter Amount',
      'de'  => 'Menge eingeben',
      'fr'  => 'Entrer le montant',
      'es'  => 'Ingresar cantidad'
    ),
    'name' => array(
      'en'  => 'Name *',
      'de'  => 'Name *',
      'fr'  => 'Nom *',
      'es'  => 'Nombre *'
    ),
    'firstname' => array(
      'en'  => 'First Name',
      'de'  => 'Vorname',
      'fr'  => 'Prénom',
      'es'  => 'Nombre de pila'
    ),
    'lastname' => array(
      'en'  => 'Last Name',
      'de'  => 'Nachname',
      'fr'  => 'Nom de famille',
      'es'  => 'Apellido'
    ),
    'email' => array(
      'en'  => 'Email *',
      'de'  => 'Email *',
      'fr'  => 'Email *',
      'es'  => 'Email *'
    ),
    'email_addr' => array(
      'en'  => 'Email Address',
      'de'  => 'E-Mail-Addresse',
      'fr'  => 'Adresse électronique',
      'es'  => 'Dirección de correo electrónico'
    ),
    'email_inline_notify' => array(
      'en'  => 'Yes, I want to Subscribe',
      'de'  => 'Ja, ich möchte abonnieren',
      'fr'  => 'Oui, je veux m\'abonner',
      'es'  => 'Si, quiero suscribirme'
    ),
    //Card details page translation
    'card' => array(
      'en'  => 'Card Details *',
      'de'  => 'Kartendetails *',
      'fr'  => 'Détails de la carte *',
      'es'  => 'Detalles de tarjeta *'
    ),
    'address' => array(
      'en'  => 'Address',
      'de'  => 'Adresse',
      'fr'  => 'Adresse',
      'es'  => 'Habla a'
    ),
    'address-line1' => array(
      'en'  => 'Street Address',
      'de'  => 'Straße Address',
      'fr'  => 'Adresse de rue',
      'es'  => 'Dirección'
    ),
    'address-line2' => array(
      'en'  => 'Address Line 2',
      'de'  => 'Adresszeile 2',
      'fr'  => 'Adresse Ligne 2',
      'es'  => 'Dirección Línea 2'
    ),
    'address-city' => array(
      'en'  => 'City',
      'de'  => 'Stadt',
      'fr'  => 'Ville',
      'es'  => 'Ciudad'
    ),
    'address-state' => array(
      'en'  => 'State / Province / Region',
      'de'  => 'Bundesland / Landkreis / Region',
      'fr'  => 'état / province / région',
      'es'  => 'Estado / Provincia / Región'
    ),
    'address-zip' => array(
      'en'  => 'Postal Code / Zip',
      'de'  => 'Postleitzahl',
      'fr'  => 'Code postal / ZIP',
      'es'  => 'Código Postal / ZIP'
    ),
    'address-country' => array(
      'en'  => 'Country *',
      'de'  => 'Country *',
      'fr'  => 'Pays *',
      'es'  => 'País *'
    ),
    'mail' => array(
      'en'  => 'Post',
      'de'  => 'Post',
      'fr'  => 'Publier',
      'es'  => 'Enviar'
    ),
    'phone_inline' => array(
      'en'  => 'Phone',
      'de'  => 'Telefon',
      'fr'  => 'Téléphone',
      'es'  => 'Teléfono'
    ),
    // For previous,next,submit buttons
    'btn_prev' => array(
      'en'  => 'Previous',
      'de'  => 'Bisherige',
      'fr'  => 'Précédent',
      'es'  => 'Previo'
    ),
    'btn_next' => array(
      'en'  => 'Next',
      'de'  => 'Nächster',
      'fr'  => 'Prochain',
      'es'  => 'Próximo'
    ),
    'btn_submit' => array(
      'en'  => 'Submit',
      'de'  => 'einreichen',
      'fr'  => 'Soumettre',
      'es'  => 'Enviar'
    ),
    'total' => array(
      'en'  => 'Total',
      'de'  => 'Gesamt',
      'fr'  => 'Total',
      'es'  => 'Total'
    )
  );
  return $labels;
});
