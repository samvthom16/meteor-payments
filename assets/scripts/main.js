(function ($) {
	
	$.fn.meteor_stripe = function( options ){
		
		return this.each(function(){
			
			var $form 	= $( this ),
				$submit	= $form.find('[type~=submit]'),
				$errors = $form.find('.payment-errors');
			
			// SET YOUR PUBLISHABLE KEY
			Stripe.setPublishableKey( meteor_settings['key'] );
			
			/*
			* DISPLAYS THE ERROR MESSAGE
			*/
			function showError( message ){
				// display the errors on the form
				$errors.html( message );
				$errors.show();
			}
			
			/*
			*	
			*/
			function showLoading(){
				
				// DISABLE THE SUBMIT BUTTON TO PREVENT RESUBMISSION
				$submit.attr( 'disabled', 'disabled' );
				
				$submit.data( 'text', $submit.html() );
				$submit.html( 'Processing..' );
			}
			
			/*
			*
			*/
			function stopLoading(){
				// enable the submit button
				$submit.removeAttr("disabled");
				
				// REVERT BACK THE ORIGINAL TEXT OF THE BUTTON
				$submit.html( $submit.data( 'text' ) );
			}
			
			
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
					
					stopLoading();
						
					showError( response.error.message );
					
				} 
				else {
						
					//get token id
					addTokenField( response['id'] );
					
					saveForm();
					
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
			
			
			function saveForm(){
				
				// AJAX REQUEST
				jQuery.ajax({
					method: 'POST',
					url: $form.data('url'), 
					dataType: "json", 
					data: $form.serialize(),
					success: function( response ){
						
						if( response.message ){
							
							showError( response.message );
									
						}
						
						stopLoading();
						
					}
				});
				
			}
			
			$form.submit( function(){
				
				
				
				showLoading();
				
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