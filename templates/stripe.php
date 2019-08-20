<?php
	$lang = $atts['lang'];
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
						'label'				=> $labels['currency'][$lang],
						'container_class'	=> 'form-field',
						'class'				=> 'field-required',
					),
					'amount_choices' => array(
						'label'				=> $labels['amount_choices'][$lang],
						'container_class'	=> 'form-field',
						'type'				=> 'radio',
						'name'				=> 'AmountChoices',
						'options'			=> array(
							'35'	=> '35',
							'50'	=> '50',
							'75'	=> '75',
							'100'	=> '100',
							'250'	=> '250',
							'Other'	=> $labels['other'][$lang]
						),
						'default'	=> '35'
					),
					'custom-amount'	=> array(
						'container_class'	=> 'form-field form-custom-amount',
						'type'			=> 'number',
						'name'			=> 'AmountCustom',
						'placeholder'	=> $labels['custom-amount'][$lang],
						'size'			=> '50'	,
						'class'			=> 'fields',
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
				'inline_label' 	=> $labels['recurring'][$lang],
				'class'			=> 'form-field',
				'type'			=> 'checkbox',
				'name'			=> 'Recurring',
				'value'			=> '1'
			),
			/* NAME FIELD WITH INLINE FIELDS - FIRSTNAME AND LASTNAME */
			'name'	=> array(
				'label'				=> $labels['name'][$lang],
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-cols-2',
				'fields'	=> array(
					'firstname'	=> array(
						'type'			=> 'text',
						'name'			=> 'FirstName',
						'placeholder'	=> $labels['firstname'][$lang],
						'size'			=> '50',
						'class'			=> 'fields field-required',
					),
					'lastname'	=> array(
						'type'			=> 'text',
						'name'			=> 'LastName',
						'placeholder'	=> $labels['lastname'][$lang],
						'size'			=> '50',
						'class'			=> 'fields field-required',
					)
				)
			),

			'email'	=> array(
				'label'				=> $labels['email'][$lang],
				'container_class'	=> 'form-field',
				'class'				=> 'form-field field-required',
				'type'				=> 'email',
				'name'				=> 'Email',
				'placeholder'		=> $labels['email_addr'][$lang],
				'size'				=> '100'
			),

			'email-updates' => array(
				'label'				=> $labels['email-updates'][$lang],
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-uk',
				'fields'		=> array(
					'email'	=> array(
						'inline_label' 	=> $labels['email_inline_notify'][$lang],
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
				'label'				=> $labels['card'][$lang],
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-card',
				'fields'		=> array(
					'card-num'	=> array(
						'type'				=> 'stripe-card',
					),
				)
			),
			/*
			* ADDRESS DETAILS: LINE 1 & 2, CITY, STATE, ZIP, COUNTRY
			*/
			'address'	=> array(
				'label'				=> $labels['address'][$lang],
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-cols-2',
				'fields'		=> array(
					'address-line1'	=> array(
						'type'			=> 'text',
						'placeholder'	=> $labels['address-line1'][$lang],
						'class'			=> 'addr-line1',
						'name'			=> 'AddressLine1'
					),
					'address-line2'	=> array(
						'type'			=> 'text',
						'placeholder'	=> $labels['address-line2'][$lang],
						'class'			=> 'addr-line2',
						'name'			=> 'AddressLine2'
					),
					'address-city'	=> array(
						'type'			=> 'text',
						'placeholder'	=> $labels['address-city'][$lang],
						'class'			=> 'addr-city',
						'name'			=> 'AddressCity'
					),
					'address-state'	=> array(
						'type'			=> 'text',
						'placeholder'	=> $labels['address-state'][$lang],
						'class'			=> 'addr-state',
						'name'			=> 'AddressState'
					),
					'address-zip'	=> array(
						'type'			=> 'text',
						'placeholder'	=> $labels['address-zip'][$lang],
						'inline_label' 	=> $labels['address-zip'][$lang],
						'class'			=> 'addr-zip',
						'name'			=> 'AddressZip'
					),
					'address-country' => array(
						'type'			=> 'dropdown',
						'name'			=> 'AddressCountry',
						'inline_label' 	=> $labels['address-country'][$lang],
						'class'			=> 'addr-country field-required',
						'options'		=> METEOR_DATA::getInstance()->countries()
					),
				)
			),

			'specificUK' => array(
				'label'				=> $labels['specific-UK'][$lang],
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-uk',
				'behaviour'			=> 'conditional-display',
				'state'				=> 'AddressCountry=GB',
				'fields'		=> array(
					'readUK'	=> array(
						'inline_label' 	=> $labels['read-UK'][$lang],
						'class'			=> 'form-field',
						'type'			=> 'checkbox',
						'name'			=> 'ReadUkGiftAidAgreement',
						'value'			=> '1'
					),
					/*
					'agreedUK'	=> array(
						'inline_label' 	=> $this->get_label( 'agreed-UK' ),
						'class'			=> 'form-field',
						'type'			=> 'checkbox',
						'name'			=> 'HasAgreedToUkGiftAid',
						'value'			=> '1'
					),
					*/
				)
			),

			'phone'	=> array(
				'label'				=> $labels['phone_inline'][$lang],
				'container_class'	=> 'form-field',
				'type'				=> 'text',
				'name'				=> 'Phone',
				'placeholder'		=> $labels['phone_number'][$lang],
				'size'				=> '100',
				'inline_label'		=> $labels['phone'][$lang]
			),

			'updates' => array(
				'label'				=> $labels['updates'][$lang],
				'container_class'	=> 'form-field',
				'fields_class'		=> 'fields fields-uk',
				'fields'		=> array(
					'mail'	=> array(
						'inline_label' 	=> $labels['mail'][$lang],
						'class'			=> 'form-field',
						'type'			=> 'checkbox',
						'name'			=> 'IsIntlMailOptIn',
						'value'			=> '1'
					),
					'phone'	=> array(
						'inline_label' 	=> $labels['phone_inline'][$lang],
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

		$this->form( $form, $lang );

	?>
	<div class="payment-errors"><?php if( $error_flag ) _e( $error_flag );?></div>
	<div style="margin-top: 30px;font-style:italic;"><?php echo $labels['form-message-below'][$lang];?></div>
</form>
<style>

	form[data-behaviour~=meteor-stripe-form] .meteor-loader {
		display: none;
	    border: 2px solid #000;
	    border-radius: 50%;
	    border-top: 2px solid #efefef;
	    width: 12px;
	    height: 12px;
		-webkit-animation: spin 2s linear infinite; /* Safari */
		animation: spin 2s linear infinite;
	}
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
		/* display					: grid;
		grid-template-areas		: 'number number number number'
			'month year cvc blank';
		grid-template-columns	: 90px 90px 100px 1fr;
		grid-gap				: 20px; */
		height: 38px;
		padding: 8px;
	    border: 1px solid #a8a8a8;
	}

	/*
	form[data-behaviour~=meteor-stripe-form] .fields-amount{
		display					: grid;
		grid-template-columns	: 150px 150px;
		grid-gap				: 20px;
	}
	*/

	form[data-behaviour~=meteor-stripe-form] .fields-amount select{
		max-width: 200px;
		display: block;
	}


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
	form[data-behaviour~=meteor-stripe-form] .fields-amount .meteor-radio li{
		position: relative;
	}

	form[data-behaviour~=meteor-stripe-form] .fields-amount .meteor-radio li input[type=radio]{
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		opacity: 0;
		cursor: pointer;
	}

	form[data-behaviour~=meteor-stripe-form] .fields-amount .meteor-radio li label{
		display: inline-block;
		max-width: 100%;
		background: #d7d7d7;
		font-weight: 700;
		padding: 0.35em 0.75em;
		border-radius: 0.25em;
		font-size: 1.1em;
		vertical-align: bottom;

	}

	form[data-behaviour~=meteor-stripe-form] .fields-amount .meteor-radio li input[type=radio]:checked+label{
		background: #ac1d23;
		color: #fff;
	}

	form[data-behaviour~=meteor-stripe-form] .label-amount{
		color: #060;
	}
</style>
