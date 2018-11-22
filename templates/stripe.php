<?php
	
	$form = array(
		'form_name'	=> array(
			'class'			=> 'form-field',
			'type'			=> 'hidden',
			'name'			=> 'form_name',
			'value'			=> $atts['name']
		),
		'amount'	=> array(
			'label'			=> 'Donation Amount',
			'class'			=> 'form-field',
			'fields_class'	=> 'fields fields-amount',
			'fields' 	=> array(
				'amount'	=> array(
					'type'			=> 'number',
					'name'			=> 'amount',
					'placeholder'	=> 'Enter Amount',
					'size'			=> '50'	,
					'class'			=> 'fields',
				),
				'currency'	=> array(
					'type'			=> 'dropdown',
					'name'			=> 'currency',
					'options'		=> METEOR_DATA::getInstance()->currencies(),
					'inline_label'	=> 'Currency',
					'class'			=> 'fields',
				)
			)
		),
		'recurring'	=> array(
			'inline_label' 	=> 'Pay this amount monthly',
			'class'			=> 'form-field',
			'type'			=> 'checkbox',
			'name'			=> 'recurring',
			'placeholder'	=> 'Email Address',
			'size'			=> '100'
		),
		/* NAME FIELD WITH INLINE FIELDS - FIRSTNAME AND LASTNAME */
		'name'	=> array(
			'label'			=> 'Name',
			'class'			=> 'form-field cols-2',
			'fields_class'	=> 'fields fields-cols-2',
			'fields'	=> array(
				'firstname'	=> array(
					'type'			=> 'text',
					'name'			=> 'firstname',
					'placeholder'	=> 'First Name',
					'size'			=> '50',
					'class'			=> 'fields',
				),
				'lastname'	=> array(
					'type'			=> 'text',
					'name'			=> 'lastname',
					'placeholder'	=> 'Last Name',
					'size'			=> '50',
					'class'			=> 'fields',
				)
			)
		),
		
		'email'	=> array(
			'label'			=> 'Email',
			'class'			=> 'form-field',
			'type'			=> 'email',
			'name'			=> 'email',
			'placeholder'	=> 'Email Address',
			'size'			=> '100'
		),
		
		/*
		* CARD DETAILS: CARD NUMBER, CVC, MONTH & YEAR
		*/
		'card'	=> array(
			'label'			=> 'Card Details',
			'class'			=> 'form-field',
			'fields_class'	=> 'fields fields-card',
			'fields'		=> array(
				'card-num'	=> array(
					'type'			=> 'number',
					'placeholder'	=> 'Card Number',
					'class'			=> 'card-num'
				),
				'card-month'	=> array(
					'type'			=> 'number',
					'placeholder'	=> 'MM',
					'size'			=> '2',
					'class'			=> 'card-month',
					'inline_label'	=> 'Month'
				),
				'card-year'	=> array(
					'type'			=> 'number',
					'placeholder'	=> 'YY',
					'size'			=> '2',
					'class'			=> 'card-year',
					'inline_label'	=> 'Year'
				),
				'card-cvc'	=> array(
					'type'			=> 'number',
					'placeholder'	=> 'CVC',
					'class'			=> 'card-cvc',
					'inline_label'	=> 'Security Code'
				)
			)
		),
		/*
		* ADDRESS DETAILS: LINE 1 & 2, CITY, STATE, ZIP, COUNTRY
		*/
		'address'	=> array(
			'label'			=> 'Address',
			'class'			=> 'form-field',
			'fields_class'	=> 'fields fields-cols-2',
			'fields'		=> array(
				'address-line1'	=> array(
					'type'			=> 'text',
					'placeholder'	=> 'Street Address',
					'class'			=> 'addr-line1',
					'name'			=> 'address_line1'
				),
				'address-line2'	=> array(
					'type'			=> 'text',
					'placeholder'	=> 'Address Line 2',
					'class'			=> 'addr-line2',
					'name'			=> 'address_line2'
				),
				'address-city'	=> array(
					'type'			=> 'text',
					'placeholder'	=> 'City',
					'class'			=> 'addr-city',
					'name'			=> 'address_city'
				),
				'address-state'	=> array(
					'type'			=> 'text',
					'placeholder'	=> 'State / Province / Region',
					'class'			=> 'addr-state',
					'name'			=> 'address_state'
				),
				'address-zip'	=> array(
					'type'			=> 'text',
					'placeholder'	=> 'Postal Code / Zip',
					'class'			=> 'addr-zip',
					'name'			=> 'address_zip'
				),
				'address-country' => array(
					'type'			=> 'dropdown',
					'name'			=> 'address_country',
					'class'			=> 'addr-country',
					'options'		=> METEOR_DATA::getInstance()->countries()
				),
			)
		),
		
		'phone'	=> array(
			'label'			=> 'Phone',
			'class'			=> 'form-field',
			'type'			=> 'text',
			'name'			=> 'phone',
			'placeholder'	=> 'Phone Number',
			'size'			=> '100',
			'inline_label'	=> '*Please keep me informed about your work and how to best support you by phone'
		),
		
		'submit'	=> array(
			'type'	=> 'submit',
			'class'	=> 'form-field',
			'html'	=> 'Submit Payment'
		)
	);
	
?>

<!-- stripe payment form -->
<form method="POST" data-behaviour='meteor-stripe-form'>
	<!-- display errors returned by createToken -->
	<div class="payment-errors <?php if( ! $error_flag ) _e('hide');?>"><?php if( $error_flag ) _e( $error_flag );?></div>
	<?php
	
		wp_nonce_field( 'save', 'meteor-stripe' );
	
		$this->form( $form );
	
	?>
</form>
<style>
	form[data-behaviour~=meteor-stripe-form]{
		max-width	: 700px;
		margin-left	: auto;
		margin-right: auto;
	}
	
	form[data-behaviour~=meteor-stripe-form] .payment-errors{
		border			: red solid 1px;
		color			: #000;
		background		: #fafafa;
		padding			: 20px;
		margin-top		: 20px;
		margin-bottom	: 20px;
	}
	form[data-behaviour~=meteor-stripe-form] .payment-errors.hide{
		display: none;
	}
	
	
	form[data-behaviour~=meteor-stripe-form] .form-field{
		margin-bottom	: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] input, form[data-behaviour~=meteor-stripe-form] select{
		width	: 100%;
		padding	: 5px;
	}
	form[data-behaviour~=meteor-stripe-form] input[type=checkbox]{
		width: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] .fields-cols-2{
		display					: grid;
		grid-template-columns	: 1fr 1fr;
		grid-gap				: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] .fields-card{
		display					: grid;
		grid-template-areas		: 'number number number number'
			'month year cvc blank';
		grid-template-columns	: 70px 70px 100px 1fr;
		grid-gap				: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] .fields-amount{
		display					: grid;
		grid-template-columns	: 150px 150px;
		grid-gap				: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] .inline-label{
		font-weight: normal;
	}
	
	form[data-behaviour~=meteor-stripe-form] .fields-card .card-num{
		grid-area: number;
	}
	form[data-behaviour~=meteor-stripe-form] .fields-card .card-month{
		grid-area: month;
	}
	form[data-behaviour~=meteor-stripe-form] .fields-card .card-year{
		grid-area: year;
	}
	form[data-behaviour~=meteor-stripe-form] .fields-card .card-cvc{
		grid-area: cvc;
	}
</style>