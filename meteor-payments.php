<?php
/*
	Plugin Name: Meteor Payments
	Plugin URI: https://sputznik.com/
	Description: Stripe Integration. Plugin tailored made for ADF
	Version: 1.0.0
	Author: Sputznik
	Author URI: https://sputznik.com/
*/
	
	if( ! defined( 'ABSPATH' ) ){
		exit;
	}
	
	$inc_files = array(
		'class-meteor-api.php'
	);

	foreach( $inc_files as $inc_file ){
		require_once( $inc_file );
	}