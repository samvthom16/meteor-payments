<?php

	
	
	class METEOR_API{
		
		var $shortcode;
		
		function __construct(){
			
			$this->setShortcode( 'meteor_api' );
			
			add_shortcode( $this->getShortcode(), array( $this, 'main_shortcode' ), 100 );
			
			/** TO LOAD THE ASSETS - SCRIPTS AND STYLES */
			add_action('the_posts', array( $this, 'assets') );
			
		}
		
		function getShortcode(){ return $this->shortcode; }
		function setShortcode( $shortcode ){ $this->shortcode = $shortcode;}
		
		function assets( $posts ){
			
			if( $this->has_shortcode( $posts ) ){
				
				wp_enqueue_script( 'jquery' );
				
				wp_enqueue_script( 'stripe', 'https://js.stripe.com/v2/', array('jquery'), '1.0.0', true);
				
				
				$uri = plugin_dir_url( __FILE__ );
				
				wp_enqueue_script( 'meteor-api', $uri.'assets/scripts/main.js', array('jquery'), '1.0.0', true);
				
				wp_localize_script( 'meteor-api', 'meteor_settings', array(
					'key'	=> $this->getStripeKeys()['publishable']
				));
				
			}
			
			return $posts;
		}
		
		function getStripeKeys(){
			
			return array(
				"secret"      => "sk_test_IYHIHIPQDGqNDUwZdCVj31wO",
				"publishable" => "pk_test_h38rqoEGEDZvy9qb1LtgZy5S"	
					
			);
			
		}
		
		function main_shortcode(){
			
			ob_start();
			
			if( isset( $_POST['stripeToken'] ) && !empty( $_POST['stripeToken'] ) ){
				
				require_once('stripe-php/init.php');
				
				$token  = $_POST['stripeToken'];
				
				//set api key
				$stripeKeys = $this->getStripeKeys();
				
				\Stripe\Stripe::setApiKey( $stripeKeys['secret'] );
				
				//add customer to stripe
				$customer = \Stripe\Customer::create(array(
					'email' 	=> $_POST['email'],
					'source'  	=> $token
				));
				
				$itemName = "Premium Script CodexWorld";
				$itemNumber = "PS123456";
				$itemPrice = 55;
				$currency = "usd";
				
				//charge a credit or a debit card
				$charge = \Stripe\Charge::create(array(
					'customer' 		=> $customer->id,
					'amount'   		=> $itemPrice,
					'currency' 		=> $currency,
					'description' 	=> $itemName,
					'metadata' => array(
						
					)
				));
				
				//retrieve charge details
				$chargeJson = $charge->jsonSerialize();
				
				if(	$chargeJson['amount_refunded'] == 0  
					&& empty($chargeJson['failure_code'])
					&& $chargeJson['paid'] == 1
					&& $chargeJson['captured'] == 1 ){
						
					print_r( $charge );
				}
				
				
				
				
			}
			
			include( 'templates/stripe.php' );
			
			return ob_get_clean();
			
		}
		
		function has_shortcode( $posts ){
			$found = false;
			if ( !empty($posts) ){
				foreach ($posts as $post) {
					if ( has_shortcode($post->post_content, $this->getShortcode() ) ){
						$found = true;
						break;
					}
				}	
			}
			return $found;
		}
		
	}
	
	
	new METEOR_API;