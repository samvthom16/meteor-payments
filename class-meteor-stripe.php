<?php
	
class METEOR_STRIPE extends METEOR_BASE{
	
	function __construct(){
		
		require_once('stripe-php/init.php');
		
		//set api key
		$stripeKeys = $this->getStripeKeys();
					
		\Stripe\Stripe::setApiKey( $stripeKeys['secret'] );
		
	}
	
	/* RETURN STRIPE SECRET AND PUBLISHABLE KEYS - TO BE REPLACED BY LIVE WORKING ONES */
	function getStripeKeys(){
			
		return array(
			"secret"      => "sk_test_IYHIHIPQDGqNDUwZdCVj31wO",
			"publishable" => "pk_test_h38rqoEGEDZvy9qb1LtgZy5S"	
					
		);
	}
	
	function createCustomer( $data ){
		$customer = \Stripe\Customer::create( $data );
		return $customer;
	}
	
	function getCustomer( $email ){
		
		$customers = \Stripe\Customer::all( array(
			'limit'	=> 1,
			'email' => $email
		) );
		
		if( isset( $customers->data ) && is_array( $customers->data ) ){
			return $customers->data[0];
		}
		
		return false;
	}
	
	//charge a credit or a debit card
	function createCharge( $data ){
		$charge = \Stripe\Charge::create( $data );
		
		//retrieve charge details
		return $charge->jsonSerialize();
		
	}
	
	function createPlan( $data ){
		$plan = \Stripe\Plan::create( $data );
		return $plan;
	}
	
	/*
	* ADD META DATA TO AN ARRAY IF THE KEYS MATCH FROM THE INPUT DATA
	*/
	function addMetaData( $finalData, $data, $slugs){
		foreach( $slugs as $slug ){
			if( isset( $data[ $slug ] ) ){
				$finalData['metadata'][$slug] = $data[ $slug ];
			}
		}
		return $finalData;
	}
	
	function processForm( $data ){
		try{
			
			// ADD DECIMAL VALUE
			$data['amount'] = $data['amount']."00";
			
			// BASIC CUSTOMER INFO
			$customerInfo = array(
				'email' 		=> $data['email'],
				'description'	=> $data['firstname']." ".$data['lastname'],
				'source'  		=> $data['stripeToken'],
				'metadata'		=> array()
			);
			
			// ADD METADATA TO CUSTOMER INFO
			$customerInfo = $this->addMetaData( $customerInfo, $data, array( 
				'firstname', 
				'lastname', 
				'address_line1', 
				'address_line2', 
				'address_city', 
				'address_state', 
				'address_zip', 
				'address_country',
				'email',
				'phone'
			) );
			
			// GET CUSTOMER BY EMAIL
			$customer = $this->getCustomer( $data['email'] );
			if( ! $customer ){
				//	CUSTOMER DOES NOT EXIST SO ADD TO STRIPE
				$customer = $this->createCustomer( $customerInfo );
			}
			
			
			
			if( isset( $data['recurring'] ) ){
				$planInfo = array(
					'amount'	=> $data['amount'],
					'interval'	=> 'month',
					'currency'	=> $data['currency'],
					'product'	=> array(
						'name'	=> $data['form_name'].' - '.$data['firstname'].' '.$data['lastname']
					)
				);
				$this->createPlan( $planInfo );
			}
			
			// BASIC CHARGE INFO
			$chargeInfo = array(
				'customer' 		=> $customer->id,
				'amount'   		=> $data['amount'],
				'currency' 		=> $data['currency'],
				'description' 	=> $data['form_name'],
				'metadata' 		=> array()
			);
			
			// ADD METADATA TO BASIC CHARGE INFO
			$chargeInfo = $this->addMetaData( $chargeInfo, $data, array( 
				'firstname', 
				'lastname', 
			) );
					
			// CHARGE THE CARD TO STRIPE
			$chargeJson = \Stripe\Charge::create( $chargeInfo );
					
			if(	$chargeJson['amount_refunded'] == 0  
				&& empty($chargeJson['failure_code'])
				&& $chargeJson['paid'] == 1
				&& $chargeJson['captured'] == 1 ){
				
				return 'Thank you for your donation.';
				
			}
				
		}catch( Exception $e ){
			return $e->getMessage();
		}
		
	}
	
}