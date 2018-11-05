<?php
	
	
	
	
	$form_fields = array(
		/* NAME FIELD WITH INLINE FIELDS - FIRSTNAME AND LASTNAME */
		'name'	=> array(
			'label'			=> 'Name',
			'fields_class'	=> 'fields fields-cols-2',
			'fields'		=> array(
				'firstname'	=> array(
					'type'			=> 'text',
					'name'			=> 'firstname',
					'placeholder'	=> 'First Name',
					'size'			=> '50'
				),
				'lastname'	=> array(
					'type'			=> 'text',
					'name'			=> 'lastname',
					'placeholder'	=> 'Last Name',
					'size'			=> '50'
				)
			)
		),
		
		'email'	=> array(
			'label'			=> 'Email',
			'fields_class'	=> 'fields',
			'fields'		=> array(
				'email'	=> array(
					'type'			=> 'email',
					'name'			=> 'email',
					'placeholder'	=> 'Email Address',
					'size'			=> '100'
				),
			)
		),
		
		/*
		* CARD DETAILS: CARD NUMBER, CVC, MONTH & YEAR
		*/
		'card'	=> array(
			'label'			=> 'Card Details',
			'fields_class'	=> 'fields fields-card',
			'fields'		=> array(
				'card-num'	=> array(
					'type'			=> 'number',
					'placeholder'	=> 'Card Number',
					'field_class'	=> 'card-num'
				),
				'card-month'	=> array(
					'type'			=> 'number',
					'placeholder'	=> 'MM',
					'size'			=> '2',
					'field_class'	=> 'card-month',
					'label'			=> 'Month'
				),
				'card-year'	=> array(
					'type'			=> 'number',
					'placeholder'	=> 'YY',
					'size'			=> '2',
					'field_class'	=> 'card-year',
					'label'			=> 'Year'
				),
				'card-cvc'	=> array(
					'type'			=> 'number',
					'placeholder'	=> 'CVC',
					'field_class'	=> 'card-cvc'
				)
			)
		),
		
	);
	
?>




<!-- stripe payment form -->
<form method="POST" data-behaviour='meteor-stripe-form'>
	<h1>Charge $55 with Stripe</h1>
	<!-- display errors returned by createToken -->
	<div class="payment-errors"></div>

	
<?php
	
	wp_nonce_field( 'meteor-stripe' );
	
	foreach( $form_fields as $form ){
		
		_e("<div class='form-field'>");
			
			_e("<label>".$form['label']."</label>");
			
			_e("<div class='".$form['fields_class']."'>");
			
			foreach( $form['fields'] as $field ){
				
				$field_class = isset( $field['field_class'] ) ? $field['field_class'] : 'field';
				
				_e("<div class='$field_class'>");
				
				switch( $field['type'] ){
					case 'email':
					case 'number':
					case 'text':
						$tag = "<input";
						break;
					
				}
				
				foreach( $field as $attr => $val ){
					if( in_array( $attr, array('type', 'placeholder', 'size', 'class') ) ){
						$tag .= " $attr='$val'";
					}
					
				}
				
				$tag .= " />";
				
				_e( $tag );
				
				if( isset( $field['label'] ) ){
					_e( "<label class='inline-label'>".$field['label']."</label>" );
				}
				
				_e("</div>");
				
			}
			
			_e("</div>");
			
			
		_e("</div>");
	}
		
?>
    
    <button type="submit" id="payBtn">Submit Payment</button>
</form>
<style>
	form[data-behaviour~=meteor-stripe-form]{
		max-width: 800px;
		margin-left: auto;
		margin-right: auto;
	}
	
	form[data-behaviour~=meteor-stripe-form] .payment-errors{
		border: red solid 1px;
		color: #000;
		background: #fafafa;
		padding: 20px;
		margin-top: 20px;
		margin-bottom: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] .form-field{
		margin-bottom: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] input{
		width: 100%;
		padding: 5px;
	}
	
	form[data-behaviour~=meteor-stripe-form] .fields-cols-2{
		display: grid;
		grid-template-columns: 1fr 1fr;
		grid-gap: 20px;
	}
	
	form[data-behaviour~=meteor-stripe-form] .fields-card{
		display: grid;
		grid-template-areas: 'number number number number'
			'month year blank cvc';
		grid-template-columns: 50px 50px 1fr 100px;
		grid-gap: 20px;
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