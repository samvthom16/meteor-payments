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
					exp_month	: $form.find('.card-month select').val(),
					exp_year	: $form.find('.card-year select').val()
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
						
						// HIDE THE FIELDS AFTER FORM HAS BEEN PROCESSED
						$form.find('.meteor-slide').hide();
						
						stopLoading();
						
						// SCROLL THE DOCUMENT TO THE TOP OF THE FORM AFTER THE SLIDES HAVE BEEN HIDDEN
						$([document.documentElement, document.body]).animate({
							scrollTop: $form.offset().top - 100
						}, 1000);
						
					}
				});
				
			}
			
			$form.submit( function( ev ){
				
				var	flag 	= formCheck( $form );
				
				if( flag ){
					
					showLoading();
				
					// create single-use token to charge the user
					Stripe.createToken( getStripeParams(), stripeResponse );
				}
				
				return false;
				
			});
			
			
			
			
			
		});
	};
		
}(jQuery));

jQuery(document).ready(function(){
	
	jQuery( 'form[data-behaviour~=meteor-stripe-form]' ).meteor_stripe();
	
	jQuery( 'form[data-behaviour~=meteor-slides]' ).meteor_slides();
	
});