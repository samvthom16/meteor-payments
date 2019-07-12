(function ($) {
	
	$.fn.meteor_slides = function(){
	
		return this.each(function() {
			
			var $el 		= jQuery( this );
				
			// GET THE TOTAL NUMBER OF SLIDES
			function totalSlides(){
				return parseInt( $el.find('.meteor-slide').length );
			}
			
			// GET CURRENT SLIDE IN THE LIST OF SLIDES THAT IS ACTIVE
			function getCurrentSlide(){
				return $el.find('.meteor-slide.active');
			}
			
			// FIND THE NEXT SLIDE TO THE ONE THAT IS CURRENTLY ACTIVE
			function getNextSlide(){
				var $currentSlide 		= getCurrentSlide(),
					currentSlideNumber 	= parseInt( $currentSlide.data('slide') ),
					nextSlideNumber 	= currentSlideNumber + 1;
				
				if( nextSlideNumber >= totalSlides() ){ nextSlideNumber = 0; }
				
				return $el.find( '[data-slide~=' + nextSlideNumber + ']' );
			}
			
			// FIND THE PREVIOUS SLIDE TO THE ONE THAT IS CURRENTLY ACTIVE
			function getPreviousSlide(){
				var $currentSlide 		= getCurrentSlide(),
					currentSlideNumber 	= parseInt( $currentSlide.data('slide') ),
					prevSlideNumber 	= currentSlideNumber - 1;
					
				if( prevSlideNumber < 0 ){ prevSlideNumber = totalSlides() - 1; }	
				
				return $el.find( '[data-slide~=' + prevSlideNumber + ']' );
			}
			
			// INITIALIZE
			function init(){
				
				$el.find('.meteor-slide').each( function( i, slide ){
					
					var $slide = jQuery( slide );
					$slide.attr( 'data-slide', i );
					
				});
			}
			
			/*
			*	TRANSITION OF SLIDE FROM CURRENT TO NEXT
			* 	SCROLL THE BODY TO THE TOP OF THE SLIDE
			*/ 
			function slideTransition( $currentSlide, $nextSlide ){
				
				$currentSlide.removeClass('active');
				$nextSlide.addClass('active');
				
				$([document.documentElement, document.body]).animate({
					scrollTop: $el.offset().top - 100
				}, 1000);
			}
			
			$el.find('[data-behaviour~=meteor-slide-next]').click( function( ev ){
				
				ev.preventDefault();
				
				var $slide 		= getCurrentSlide(),
					$nextSlide	= getNextSlide();
				
				$slide.trigger('meteor:beforeNextTransition');
				
				if( $slide.data( 'slide-disable' ) != '1' ){
					slideTransition( $slide, $nextSlide );
				}
				
			});
			
			$el.find('[data-behaviour~=meteor-slide-prev]').click( function( ev ){
				
				ev.preventDefault();
				
				var $slide 		= getCurrentSlide(),
					$prevSlide	= getPreviousSlide();
				
				slideTransition( $slide, $prevSlide );
				
			});
			
			init();
			
			
		});

	};
	
	
	
	$.fn.meteor_stripe = function( options ){
		
		return this.each(function(){
			
			var $form 	= $( this ),
				$submit	= $form.find('[type~=submit]'),
				$errors = $form.find('.payment-errors');
			
			
			// clientSecret to complete payment
			var clientSecret;
			
			// paymentIntent id --- using to update
			var paymentIntent;
			
			//paymentIntent URL
			var intentUrl = $('#card-element').data('url');

			//initializePaymentIntent(intentUrl);			

			function initializePaymentIntent(url) {

				// create initial payment intent, get client secret
				$.getJSON( url,function( data ) {
			    	
			    	clientSecret = data.client_secret;
			    	paymentIntent= data.id;
			    	
			   		console.log(clientSecret);console.log(paymentIntent);
			  
			  });

			}

				


			function updatePaymentIntent( url, intentID, params ) {
				$.getJSON( intentUrl,{
					"id":intentID,
					"amount":9999,
					"currency":"USD"
				},function( data ) {
			      console.log(data);
			    });	
			}

			

			// SET YOUR PUBLISHABLE KEY
			//Stripe.setPublishableKey( meteor_settings['key'] );
			var stripe = Stripe( meteor_settings['key'] );
			
			//mount card element to dom
			var elements = stripe.elements();
			var cardElement = elements.create('card');
			cardElement.mount('#card-element');


			

			$form.on('meteor:beforeNextTransition', function( ev ){
				
				var $slide = jQuery( ev.target ),
					flag = formCheck( $slide );
				
				$slide.data('slide-disable', '1');
				
				if( flag ){
					$slide.data('slide-disable', '0');
				}
				
			});



			
			function formCheck( $el ) {
				
				// HIDE ERRORS FIELD TO SHOW ONLY IF THERE IS AN ERROR MESSAGE
				$errors.hide();
				
				var flag 	= true,
					fields 	= $el.find(".field-required").serializeArray();
				
				$.each( fields, function( i, field ){
					if( !field.value ){
						showError( "You have missed some required fields." ); 		
						flag = false; 
					}
				});
				
				return flag;
			}
			
			
			/*
			* DISPLAYS THE ERROR MESSAGE
			*/
			function showError( message ){
				if(message.success){
					$errors.html( message.success );	
				} else {
					$errors.html( message.error );	
				}
				// display the errors on the form
				
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
			
			
			
			function saveForm(){

				stripe.createPaymentMethod('card', cardElement, {
				  
				    billing_details: {name: 'John Doe'}
				  
				  }).then(function(result) {
				  	
				  	if (result.error) {
				      // Show error in payment form
				    } else {
				    	var postdata  = $form.serializeArray().reduce(function(obj, item) {
										    obj[item.name] = item.value;
										    return obj;
										}, {});;

				      	postdata['payment_method_id'] =  result.paymentMethod.id ;


				      // Otherwise send paymentMethod.id to your server (see Step 2)
				      fetch($form.data('url'), {
				      	method: 'POST',
				        headers: { 'Content-Type': 'application/json' },
				        body: JSON.stringify( postdata )
				      }).then(function(result) {
				        // Handle server response (see Step 3)
				        console.log('callling method handleServerResponse');
				        
				        result.json().then(function(json) {
				          handleServerResponse(json);
				        })
				      });
				    }
				  });

				
				
				
			}

			function handleServerResponse(response) {
				  console.log(response);
				  if (response.error) {
				    // Show error from server on payment form
				  } else if (response.requires_action) {
				    // Use Stripe.js to handle required card action
				    stripe.handleCardAction(
				      response.payment_intent_client_secret
				    ).then(function(result) {
				      if (result.error) {
				        // Show error in payment form
				        console.log("error", result);
				      } else {
				        var postdata  = $form.serializeArray().reduce(function(obj, item) {
										    obj[item.name] = item.value;
										    return obj;
										}, {});;

				      	postdata['payment_intent_id'] =  result.paymentIntent.id ;


				        // The card action has been handled
				        // The PaymentIntent can be confirmed again on the server
				       
				        fetch($form.data('url'), {
				          
				          method: 'POST',
				          headers: { 'Content-Type': 'application/json' },
				          body: JSON.stringify(postdata)

				        }).then(function(confirmResult) {
				          
				          return confirmResult.json();
				        
				        }).then(handleServerResponse);
				      }
				    });
				  } else {
				  	console.log(response);

					//console.log(result);
				    if( response ){
							showError( response );
									
						}
						
						// HIDE THE FIELDS AFTER FORM HAS BEEN PROCESSED
						$form.find('.meteor-slide').hide();
						
						stopLoading();
						
						// SCROLL THE DOCUMENT TO THE TOP OF THE FORM AFTER THE SLIDES HAVE BEEN HIDDEN
						$([document.documentElement, document.body]).animate({
							scrollTop: $form.offset().top - 100
						}, 1000);

				  }
				}
			
			$form.submit( function( ev ){
				console.log('form submitted');
				var	flag 	= formCheck( $form );
				
				if( flag ){
					
					showLoading();
				
					// create single-use token to charge the user
					//Stripe.createToken( getStripeParams(), stripeResponse );

					saveForm();
				}
				
				return false;
				
			});
			
			
		});
	};
		
		
	$.fn.meteor_amount = function(){
		
		return this.each(function(){
			
			var $el 		= $( this ),
				$form		= $el.closest('form'),
				$label		= $el.find('.label-amount'),
				$choices	= $el.find('input[name=AmountChoices]'),
				$customAmt	= $el.find('input[name=AmountCustom]'),
				$currency	= $el.find('[name=Currency]'),
				$amount		= $el.find('input[name=Amount]');
			
			function getAmount(){
				
				var amount 			= 0,
					choice_value 	= $el.find('input[name=AmountChoices]:checked').val();
					
				amount = parseInt( choice_value );
				
				if( choice_value == 'Other' ){
					amount = parseInt( $customAmt.val() );
				}
				
				if( !Number.isInteger( amount ) ){
					amount = 0;
				}
				
				return amount;
				
			}
			
			
			
			function formChanged(){
				
				var choice_value 	= $el.find('input[name=AmountChoices]:checked').val(),
					amount 			= getAmount(),
					currency		= $currency.val();
				
				// SHOW CUSTOM AMOUNT FIELD BASED ON THE AMOUNT CHOICES
				if( choice_value == 'Other' ){
					$el.find('.form-custom-amount').show();
				}
				else{
					$el.find('.form-custom-amount').hide();
				}
				
				// UPDATE AMOUNT LABEL
				$label.html( amount + ' ' + currency );
					
				// UPDATE MAIN AMOUNT IN THE HIDDEN FIELD
				$amount.val( amount );
				
			}
			
			$form.change( function(){
				
				formChanged();
				
			});
			
			formChanged();
			
		});
	};
	
	
	$.fn.meteor_conditional_display = function(){
		
		return this.each(function(){
			
			var $el 		= $( this ),
				$form		= $el.closest('form'),
				state		= $el.attr('data-state');
				
			function checkState(){
				
				// FORM NAME AND VALUE ARE SEPERATED BY =
				var items = state.split('=');
				
				if( items.length > 1 ){
					
					var $field = $form.find('[name='+items[0]+']');
					
					if( $field.val() == items[1] ){
						$el.show();
					}
					else{
						$el.hide();
					}
					
					
				}
				
			}
			
			$form.change( function(){
				checkState();
			});
			
			checkState();
			
		});
		
	};
	
}(jQuery));

jQuery(document).ready(function(){
	
	jQuery( 'form[data-behaviour~=meteor-stripe-form]' ).meteor_stripe();
	
	jQuery( 'form[data-behaviour~=meteor-slides]' ).meteor_slides();
	
	jQuery( 'form[data-behaviour~=meteor-stripe-form] .fields-amount' ).meteor_amount();
	
	jQuery( 'form[data-behaviour~=meteor-stripe-form] [data-behaviour~=conditional-display]' ).meteor_conditional_display();
	
});