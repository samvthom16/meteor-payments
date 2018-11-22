(function ($) {
	
	$.fn.meteor_stripe = function( options ){
		
		return this.each(function(){
			
			var $form 	= $( this ),
				$submit	= $form.find('[type~=submit]'),
				$errors = $form.find('.payment-errors');
			
			// HIDE ERRORS FIELD
			
			
			// SET YOUR PUBLISHABLE KEY
			Stripe.setPublishableKey( meteor_settings['key'] );
			
			/*
			* APPEND TOKEN FIELD TO THE FORM
			*/ 
			function addTokenField( token ){
				var $tokenField = $( document.createElement('input') );
				$tokenField.attr('type', 'hidden');
				$tokenField.attr('name', 'stripeToken');
				$tokenField.val( token );
				$tokenField.appendTo( $form );
			}
			
			/*
			* HANDLES STRIPE RESPONSE
			*/ 
			function stripeResponse( status, response ){
				
				if( response.error ){
					
					// enable the submit button
					$submit.removeAttr("disabled");
						
					// display the errors on the form
					$errors.html( response.error.message );
					$errors.show();
					
				} 
				else {
						
					//get token id
					var token = response['id'];
						
					addTokenField( token );
						
					$form.get(0).submit();
				}
			}
			
			function getStripeParams(){
				return {
					number		: $form.find('.card-num input[type=number]').val(),
					cvc			: $form.find('.card-cvc input[type=number]').val(),
					exp_month	: $form.find('.card-month input[type=number]').val(),
					exp_year	: $form.find('.card-year input[type=number]').val()
				}
			}
			
			$form.submit( function(){
				
				// DISABLE THE SUBMIT BUTTON TO PREVENT RESUBMISSION
				$submit.attr( 'disabled', 'disabled' );
				
				// create single-use token to charge the user
				Stripe.createToken( getStripeParams(), stripeResponse );
				
				return false;
				
			});
			
		});
	};
		
}(jQuery));

jQuery(document).ready(function(){
	
	jQuery( 'form[data-behaviour~=meteor-stripe-form]' ).meteor_stripe();
	
});