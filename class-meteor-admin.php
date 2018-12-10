<?php

class METEOR_ADMIN extends METEOR_BASE{
	
	function __construct(){
		
		add_action( 'admin_menu', function(){
			
			add_submenu_page(
				'options-general.php',
				__('Meteor Settings', 'meteor'),
				__('Meteor Settings', 'meteor'),
				'manage_options',
				'settings',
				array( $this, 'settings_page' )
			);
			
			
		});
		
	}
	
	function getSettingsOptions(){
		return array(
			'stripeSecret' 			=> 'Secret Key',
			'stripePublishable'		=> 'Publishable Key'
		);
	}
	
	function settings_page(){
		include "templates/settings.php";
	}
	
}

METEOR_ADMIN::getInstance();