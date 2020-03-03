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
			"secret"      => get_option('stripeSecret'),
			"publishable" => get_option('stripePublishable')

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

	function createSubscription($customer, $planInfo) {
		try {

			$plan = $this->createPlan( $planInfo );

			$args = array(
				"customer" => $customer->id,
				"items" => [[ "plan" => $plan->id]],
				"expand" => ["latest_invoice.payment_intent"],
			);

			$subs = \Stripe\Subscription::create($args);

		} catch( Exception $e ) {
			print_r($e->getMessage());
		}

		return $subs;
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
		$data = (array) $data;

		try{

			// ADD DECIMAL VALUE
			$data['Amount'] = $data['Amount']."00";

			/*
			*	THIS HAS TO BE REFACTORED TO BE ADDED THROUGH ACTION HOOKS
			*/
			$booleanData = array(
				'ReadUkGiftAidAgreement',
				//'HasAgreedToUkGiftAid',
				'IsIntlEmailOptIn',
				'IsIntlMailOptIn',
				'IsIntlPhoneOptIn'
			);
			foreach( $booleanData as $booleanSlug ){
				if( isset( $data[$booleanSlug] ) && $data[$booleanSlug] ){
					$data[$booleanSlug] = true;
				}
				else{
					$data[$booleanSlug] = false;
				}
			}
			/*
			*	THIS HAS TO BE REFACTORED TO BE ADDED THROUGH ACTION HOOKS
			*/


			// BASIC CUSTOMER INFO
			$customerInfo = array(
				'email' 		=> $data['Email'],
				'description'	=> $data['FirstName']." ".$data['LastName'],
				/*'source'  		=> $data['stripeToken'],*/
				'metadata'		=> array()
			);


			if( (isset($data['payment_method_id']) && $data['payment_method_id']) && (isset( $data['Recurring'] ) && $data['Recurring'])) {

				$customerInfo['payment_method'] = $data['payment_method_id'];

				$customerInfo['invoice_settings'] = [ "default_payment_method" => $data['payment_method_id']
						];

			}


			// ADD METADATA TO CUSTOMER INFO
			$customerInfo['metadata'] = $this->addMetaData( $data, array(
				'SourceCode',
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
				//'HasAgreedToUkGiftAid'
			) );

			// GET CUSTOMER BY EMAIL
			$customer = $this->getCustomer( $data['Email'] );
			if( ! $customer ){
				//	CUSTOMER DOES NOT EXIST SO ADD TO STRIPE
				$customer = $this->createCustomer( $customerInfo );
			}
			//else{
				$customer->metadata = $customerInfo['metadata'];
				$customer->save();
			//}



			if( isset( $data['Recurring'] ) && $data['Recurring'] ){
				$planInfo = array(
					"amount"	=> $data['Amount'],
					"interval"	=> "month",
					"currency"	=> $data['Currency'],
					"product"	=> array(
						"name"	=> $data['FormName']." - ".$data['FirstName']." ".$data['LastName'],
						"type" 	=> "service"
					)
				);
				//$this->createPlan( $planInfo );
				//$this->createSubscription($customer, $planInfo);
			}

			// BASIC CHARGE INFO
			$chargeInfo = array(
				'customer' 		=> $customer->id,
				'amount'   		=> $data['Amount'],
				'currency' 		=> $data['Currency'],
				'description' 	=> $data['FormName'],
				'confirmation_method' => 'manual',
				'confirm' 		=> true,
				'metadata' 		=> array()
			);

			// ADD METADATA TO BASIC CHARGE INFO
			$chargeInfo['metadata'] = $this->addMetaData( $data, array(
				'SourceCode',
				'FirstName',
				'LastName',
				'IsIntlEmailOptIn',
				'IsIntlMailOptIn',
				'IsIntlPhoneOptIn',
				//'AddressLine1',
				//'AddressLine2',
				//'AddressCity',
				//'AddressState',
				//'AddressZip',
				//'AddressCountry',
				//'Email',
				//'Phone',
				//'ReadUkGiftAidAgreement',
			) );



			$intent = null;
			try {
				if (isset($data['payment_method_id'])) {

					if(isset( $data['Recurring'] ) && $data['Recurring']) {
						//https://stripe.com/docs/billing/migration/strong-customer-authentication#scenario-1

						$subs = $this->createSubscription($customer, $planInfo);

						$subs_status = $subs->status;
						$intent_status = $subs->latest_invoice->payment_intent->status;

						if($subs_status == 'active') {

							echo json_encode([
								"type" => "subscription",
								"status" => "success",
								"message" => "Thank you for your donation."
							]);

						} elseif( $subs_status == 'incomplete' && $intent_status == 'requires_payment_method' ) {

							echo json_encode([
								"type" => "subscription",
								"status" => "failed",
								"message" => "Requires Payment Method."
							]);

						} else if( $subs_status == 'incomplete' && $intent_status == 'requires_action' ) {

							echo json_encode([
								'type' => 'subscription',
								'status' => 'requires_action',
								'payment_intent_client_secret' => $subs->latest_invoice->payment_intent->client_secret
							]);
						}

						wp_die();

					} else {
						$chargeInfo['payment_method'] = $data['payment_method_id'];
				        $intent = \Stripe\PaymentIntent::create( $chargeInfo );
					}

				}

				if (isset($data['payment_intent_id'])) {
				  $intent = \Stripe\PaymentIntent::retrieve(
			        $data['payment_intent_id']
			      );
			      $intent->confirm();

				}

				$this->generatePaymentResponse($intent);

			} catch (\Stripe\Error\Base $e) {
				# Display error on client
				return [
			  		'error' => $e->getMessage()
				];
			}



		}catch( Exception $e ){

			return array( 'status' => 0, 'message' => $e->getMessage() );

		}

	}


	 function generatePaymentResponse($intent) {
		# Note that if your API version is before 2019-02-11, 'requires_action'
		# appears as 'requires_source_action'.
		if ($intent->status == 'requires_action' &&
			$intent->next_action->type == 'use_stripe_sdk') {


			# Tell the client to handle the action
			echo json_encode([
				'requires_action' => true,
				'payment_intent_client_secret' => $intent->client_secret
			]);
		} else if ($intent->status == 'succeeded') {
			# The payment didn’t need any additional actions and completed!
			# Handle post-payment fulfillment

			echo json_encode([
				"success" => true,
				"message" => "Thank you for your donation."
			]);
		} else {
		# Invalid status
		http_response_code(500);
		echo json_encode(['error' => 'Invalid PaymentIntent status']);
		}
  }

}
