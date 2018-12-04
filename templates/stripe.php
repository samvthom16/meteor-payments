<?php
	
	add_filter( 'meteor_data_currencies', function( $currencies ){
		foreach( $currencies as $slug => $value ){
			$currencies[ $slug ] = $slug;
		}
		return $currencies;
	});
	
	
	$form = array(
		'basic-page' => array(
			/*
			* HIDDEN FIELDS THAT NEEDS TO BE PASSED
			*/
			'hidden'	=> array(
				'fields_class'	=> 'fields-hidden',
				'fields' 	=> array(
					'api'	=> array(
						'type'		=> 'hidden',
						'name'		=> 'API',
						'value'		=> 'stripe'
					),
					'form_name'	=> array(
						'type'		=> 'hidden',
						'name'		=> 'FormName',
						'value'		=> $atts['name']
					),
					'source_id'	=> array(
						'type'			=> 'hidden',
						'name'			=> 'SourceCode',
						'value'			=> $atts['source_id']
					),
				)
			),
			
			'amount'	=> array(
				'container_class'	=> 'form-field',
				'fields_class'	=> 'fields fields-amount',
				'fields' 	=> array(
					'currency'	=> array(
						'type'				=> 'dropdown',
						'name'				=> 'Currency',
						'options'			=> METEOR_DATA::getInstance()->currencies(),
						'label'				=> 'Currency *',
						'container_class'	=> 'form-field',
						'class'				=> 'field-required',
					),
					'amount_choices' => array(
						'label'				=> 'Donation Amount *',
						'container_class'	=> 'form-field',
						'type'				=> 'radio',
						'name'				=> 'AmountChoices',
						'options'			=> array(
							'35'	=> '35',
							'50'	=> '50',
							'75'	=> '75',
							'100'	=> '100',
							'250'	=> '250',
							'Other'	=> 'Other'
						),
						'default'	=> '35'
					),
					'custom-amount'	=> array(
						'container_class'	=> 'form-field form-custom-amount',
						'type'			=> 'number',
						'name'			=> 'AmountCustom',
						'placeholder'	=> 'Enter Amount',
						'size'			=> '50'	,
						'class'			=> 'fields field-required',
					),
					'amount'	=> array(
						'type'	=> 'hidden',
						'name'	=> 'Amount',
						'value'	=> 0
					),
					'label-amount'	=> array(
						'container_class'	=> 'form-field',
						'class'				=> 'label-amount',
						'type'	=> 'label'
					),
				)
			),
			'recurring'	=> array(
				'inline_label' 	=> 'Pay this amount monthly',
				'class'			=> 'form-field',
				'type'			=> 'checkbox',
				'name'			=> 'Recurring',
				'value'			=> '1'
			),
			/*
			'plan'	=> array(
				'class'			=> 'form-field',
				'fields'		=> array(
					'one-time' => array(
						'type'	=> 'radio'
						'name'	=> 'Plan'
						'label'	=> 'One Time'
					)	
				)
			),
			
			/* NAME FIELD WITH INLINE FIELDS - FIRSTNAME AND LASTNAME */
			'name'	=> array(
				'label'				=> 'Name *',
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-cols-2',
				'fields'	=> array(
					'firstname'	=> array(
						'type'			=> 'text',
						'name'			=> 'FirstName',
						'placeholder'	=> 'First Name',
						'size'			=> '50',
						'class'			=> 'fields field-required',
					),
					'lastname'	=> array(
						'type'			=> 'text',
						'name'			=> 'LastName',
						'placeholder'	=> 'Last Name',
						'size'			=> '50',
						'class'			=> 'fields field-required',
					)
				)
			),
			
			'email'	=> array(
				'label'				=> 'Email *',
				'container_class'	=> 'form-field',
				'class'				=> 'form-field field-required',
				'type'				=> 'email',
				'name'				=> 'Email',
				'placeholder'		=> 'Email Address',
				'size'				=> '100'
			),
			
			'email-updates' => array(
				'label'				=> 'Yes, please keep me informed by email about your work, your breakthroughs, and how to best support you:',
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-uk',
				'fields'		=> array(
					'email'	=> array(
						'inline_label' 	=> 'Newsletter (bi-monthly)',
						'class'			=> 'form-field',
						'type'			=> 'checkbox',
						'name'			=> 'IsIntlEmailOptIn',
						'value'			=> '1'
					),
				)
			),
			
		),
		'card-page' => array(
			/*
			* CARD DETAILS: CARD NUMBER, CVC, MONTH & YEAR
			*/
			'card'	=> array(
				'label'				=> 'Card Details *',
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-card',
				'fields'		=> array(
					'card-num'	=> array(
						'type'				=> 'number',
						'placeholder'		=> 'Card Number',
						'container_class'	=> 'card-num',
						'class'				=> 'field-required'
					),
					'card-month'	=> array(
						'type'				=> 'dropdown',
						'container_class'	=> 'card-month',
						'class'				=> 'field-required',
						'inline_label'		=> 'Month',
						'options'			=> METEOR_DATA::getInstance()->months(),
					),
					'card-year'	=> array(
						'type'				=> 'dropdown',
						'container_class'	=> 'card-year',
						'class'				=> 'field-required',
						'inline_label'		=> 'Year',
						'options'			=> METEOR_DATA::getInstance()->years(),
					),
					'card-cvc'	=> array(
						'type'				=> 'number',
						'container_class'	=> 'card-cvc',
						'placeholder'		=> 'CVC',
						'class'				=> 'field-required',
						'inline_label'		=> 'Security Code'
					)
				)
			),
			/*
			* ADDRESS DETAILS: LINE 1 & 2, CITY, STATE, ZIP, COUNTRY
			*/
			'address'	=> array(
				'label'				=> 'Address *',
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-cols-2',
				'fields'		=> array(
					'address-line1'	=> array(
						'type'			=> 'text',
						'placeholder'	=> 'Street Address',
						'class'			=> 'addr-line1 field-required',
						'name'			=> 'AddressLine1'
					),
					'address-line2'	=> array(
						'type'			=> 'text',
						'placeholder'	=> 'Address Line 2',
						'class'			=> 'addr-line2 field-required',
						'name'			=> 'AddressLine2'
					),
					'address-city'	=> array(
						'type'			=> 'text',
						'placeholder'	=> 'City',
						'class'			=> 'addr-city field-required',
						'name'			=> 'AddressCity'
					),
					'address-state'	=> array(
						'type'			=> 'text',
						'placeholder'	=> 'State / Province / Region',
						'class'			=> 'addr-state field-required',
						'name'			=> 'AddressState'
					),
					'address-zip'	=> array(
						'type'			=> 'text',
						'placeholder'	=> 'Postal Code / Zip',
						'class'			=> 'addr-zip field-required',
						'name'			=> 'AddressZip'
					),
					'address-country' => array(
						'type'			=> 'dropdown',
						'name'			=> 'AddressCountry',
						'class'			=> 'addr-country field-required',
						'options'		=> METEOR_DATA::getInstance()->countries()
					),
				)
			),
			
			'specificUK' => array(
				'label'				=> 'Only for UK residents',
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-uk',
				'fields'		=> array(
					'readUK'	=> array(
						'inline_label' 	=> 'Read UK Gift Aid Agreement',
						'class'			=> 'form-field',
						'type'			=> 'checkbox',
						'name'			=> 'ReadUkGiftAidAgreement',
						'value'			=> '1'
					),
					'agreedUK'	=> array(
						'inline_label' 	=> 'Has Agreed To Uk Gift Aid',
						'class'			=> 'form-field',
						'type'			=> 'checkbox',
						'name'			=> 'HasAgreedToUkGiftAid',
						'value'			=> '1'
					),
				)
			),
			
			'phone'	=> array(
				'label'				=> 'Phone',
				'container_class'	=> 'form-field',
				'type'				=> 'text',
				'name'				=> 'Phone',
				'placeholder'		=> 'Phone Number',
				'size'				=> '100',
				'inline_label'		=> '*Please keep me informed about your work and how to best support you by phone'
			),
			
			'updates' => array(
				'label'				=> 'Would you like to receive updates by',
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-uk',
				'fields'		=> array(
					'mail'	=> array(
						'inline_label' 	=> 'Post',
						'class'			=> 'form-field',
						'type'			=> 'checkbox',
						'name'			=> 'IsIntlMailOptIn',
						'value'			=> '1'
					),
					'phone'	=> array(
						'inline_label' 	=> 'Phone',
						'class'			=> 'form-field',
						'type'			=> 'checkbox',
						'name'			=> 'IsIntlPhoneOptIn',
						'value'			=> '1'
					),
				)
			),
			
		)
		
	);
	
?>

<!-- stripe payment form -->
<form method="POST" data-behaviour='meteor-stripe-form meteor-slides' data-url="<?php _e( admin_url('admin-ajax.php')."?action=meteor_process_form" );?>">
	<!-- display errors returned by createToken -->
	<?php
		
		wp_nonce_field( 'save', 'meteor-stripe' );
	
		$this->form( $form );
	
	?>
	<div class="payment-errors"><?php if( $error_flag ) _e( $error_flag );?></div>
</form>
<style>
	form[data-behaviour~=meteor-stripe-form] .meteor-slide{
		display: none;
	}
	form[data-behaviour~=meteor-stripe-form] .meteor-slide.active{
		display: block;
	}
	
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
		display			: none;
	}
	
	
	
	form[data-behaviour~=meteor-stripe-form] .form-field{
		margin-bottom	: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] input, form[data-behaviour~=meteor-stripe-form] select{
		width	: 100%;
		padding	: 5px;
	}
	form[data-behaviour~=meteor-stripe-form] input[type=radio]{
		width: auto;
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
		grid-template-columns	: 90px 90px 100px 1fr;
		grid-gap				: 20px;
	}
	
	/*
	form[data-behaviour~=meteor-stripe-form] .fields-amount{
		display					: grid;
		grid-template-columns	: 150px 150px;
		grid-gap				: 20px;
	}
	*/
	
	form[data-behaviour~=meteor-stripe-form] .fields-uk .form-field{
		margin-bottom: 0;
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
	
	form[data-behaviour~=meteor-stripe-form] .meteor-list{
		list-style: none;
		padding: 0;
		margin: 0;
	}
	form[data-behaviour~=meteor-stripe-form] .meteor-list li{
		display: inline-block;
		margin-right: 20px;
	}
	
	/*
	form[data-behaviour~=meteor-stripe-form] .meteor-radio li input[type=radio]{
		position: absolute;
		opacity: 0;
		
	}
	*/
	form[data-behaviour~=meteor-stripe-form] .meteor-radio li{
		position: relative;
	}
	
	form[data-behaviour~=meteor-stripe-form] .meteor-radio li input[type=radio]{
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		opacity: 0;
		cursor: pointer;
	}
	
	form[data-behaviour~=meteor-stripe-form] .meteor-radio li label{
		display: inline-block;
		max-width: 100%;
		background: #d7d7d7;
		font-weight: 700;
		padding: 0.35em 0.75em;
		border-radius: 0.25em;
		font-size: 1.1em;
		vertical-align: bottom;
		
	}
	
	form[data-behaviour~=meteor-stripe-form] .meteor-radio li input[type=radio]:checked+label{
		background: #ac1d23;
		color: #fff;
	}
	
	form[data-behaviour~=meteor-stripe-form] .label-amount{
		color: #060;
	}
</style>