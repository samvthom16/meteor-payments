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
	
	function deleteCustomer( $email ){
		$customers = \Stripe\Customer::all( array(
			'limit'	=> 100,
			'email' => $email
		) );
		
		//print_r( $customers );
		
		foreach( $customers->data as $customer ){
			$customer->delete();
		}
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
	function addMetaData( $data, $slugs){
		$finalData = array();
		foreach( $slugs as $slug ){
			if( isset( $data[ $slug ] ) ){
				$finalData[$slug] = $data[ $slug ];
			}
		}
		return $finalData;
	}
	
	function processForm( $data ){
		try{
			
			// ADD DECIMAL VALUE
			$data['Amount'] = $data['Amount']."00";
			
			$data['ReadUkGiftAidAgreement'] = false;
			$data['HasAgreedToUkGiftAid'] = false;
			
			if( 'GB' == $data['AddressCountry'] ){
				
				if( isset( $data['ReadUkGiftAidAgreement'] ) && $data['ReadUkGiftAidAgreement'] ){
					$data['ReadUkGiftAidAgreement'] = true;
				}
				
				if( isset( $data['HasAgreedToUkGiftAid'] ) && $data['HasAgreedToUkGiftAid'] ){
					$data['HasAgreedToUkGiftAid'] = true;
				}
				
			}
			
			// BASIC CUSTOMER INFO
			$customerInfo = array(
				'email' 		=> $data['Email'],
				'description'	=> $data['FirstName']." ".$data['LastName'],
				'source'  		=> $data['stripeToken'],
				'metadata'		=> array()
			);
			
			// ADD METADATA TO CUSTOMER INFO
			$customerInfo['metadata'] = $this->addMetaData( $data, array( 
				'FirstName', 
				'LastName', 
				'AddressLine1', 
				'AddressLine2', 
				'AddressCity', 
				'AddressState', 
				'AddressZip', 
				'AddressCountry',
				'Email',
				'Phone',
				'ReadUkGiftAidAgreement',
				'HasAgreedToUkGiftAid'
			) );
			
			// GET CUSTOMER BY EMAIL
			$customer = $this->getCustomer( $data['Email'] );
			if( ! $customer ){
				//	CUSTOMER DOES NOT EXIST SO ADD TO STRIPE
				$customer = $this->createCustomer( $customerInfo );
			}
			else{
				$customer->metadata = $customerInfo['metadata'];
				$customer->save();
			}
			
			
			
			if( isset( $data['Recurring'] ) && $data['Recurring'] ){
				$planInfo = array(
					'amount'	=> $data['Amount'],
					'interval'	=> 'month',
					'currency'	=> $data['Currency'],
					'product'	=> array(
						'name'	=> $data['FormName'].' - '.$data['FirstName'].' '.$data['LastName']
					)
				);
				$this->createPlan( $planInfo );
			}
			
			// BASIC CHARGE INFO
			$chargeInfo = array(
				'customer' 		=> $customer->id,
				'amount'   		=> $data['Amount'],
				'currency' 		=> $data['Currency'],
				'description' 	=> $data['FormName'],
				'metadata' 		=> array()
			);
			
			// ADD METADATA TO BASIC CHARGE INFO
			$chargeInfo['metadata'] = $this->addMetaData( $data, array( 
				'FirstName', 
				'LastName', 
			) );
					
			// CHARGE THE CARD TO STRIPE
			$chargeJson = \Stripe\Charge::create( $chargeInfo );
					
			if(	$chargeJson['amount_refunded'] == 0  
				&& empty($chargeJson['failure_code'])
				&& $chargeJson['paid'] == 1
				&& $chargeJson['captured'] == 1 ){
				
				return array( 'success' => 1, 'message' => 'Thank you for your donation.');
				
			}
				
		}catch( Exception $e ){
			
			return array( 'success' => 0, 'message' => $e->getMessage() );
			
		}
		
	}
	
}