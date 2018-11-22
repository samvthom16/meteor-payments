<?php

	
	
	class METEOR_API extends METEOR_BASE{
		
		var $shortcode;
		
		var $stripe;
		
		function __construct(){
			
			$this->setShortcode( 'meteor_api' );
			
			add_shortcode( $this->getShortcode(), array( $this, 'main_shortcode' ), 100 );
			
			/** TO LOAD THE ASSETS - SCRIPTS AND STYLES */
			//add_action('the_posts', array( $this, 'assets') );
			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
			
			add_action( 'wp_ajax_meteor_process_form', array( $this, 'ajaxProcessForm' ) );
			add_action( 'wp_ajax_nopriv_meteor_process_form', array( $this, 'ajaxProcessForm' ) );
			
			require_once( 'class-meteor-stripe.php' );
			$this->setStripeAPI( METEOR_STRIPE::getInstance() );
			
		}
		
		/* GETTER AND SETTER FUNCTIONS */
		function getShortcode(){ return $this->shortcode; }
		function setShortcode( $shortcode ){ $this->shortcode = $shortcode;}
		
		function getStripeAPI(){ return $this->stripe; }
		function setStripeAPI( $stripe ){ $this->stripe = $stripe; }
		/* GETTER AND SETTER FUNCTIONS */
		
		function ajaxProcessForm(){
			
			$data = array();
			
			array_push( $data, 'stripe-check' );
			
			if( 'stripe' == $_POST['API'] ){
				
				array_push( $data, 'stripe-check' );
				
				if( isset( $_POST['stripeToken'] ) && !empty( $_POST['stripeToken'] ) && isset( $_POST['meteor-stripe'] ) && wp_verify_nonce( $_POST['meteor-stripe'], 'save' ) ){
					$data = $this->getStripeAPI()->processForm( $_POST );
				}
				
			}
			
			print_r( wp_json_encode( $data ) );
			
			wp_die();
		}
		
		
		function form_field( $field ){
			
			$field_class = isset( $field['class'] ) ? $field['class'] : '';
	
			_e("<div class='".$field_class."'>");
				
				// DISPLAY THE FIELD LABEL
				if( isset( $field['label'] ) ){ _e( "<label>".$field['label']."</label>" ); }
				
				/*
				* CHECK IF INLINE FIELDS EXISTS, IF YES THEN DISPLAY THEM BY CALLING THE FUNCTION RECURSIVELY
				*/
				if( isset( $field['fields'] ) && isset( $field['fields_class'] ) ){ 
					
					_e("<div class='".$field['fields_class']."'>");
					
					foreach( $field['fields'] as $inline_field ){
						$this->form_field( $inline_field );
					}
					
					_e("</div>");
					
				}
				
				/*
				* DISPLAY THE FIELD BASED ON ITS TYPE
				*/
				if( isset( $field['type'] ) ){
					
					$tag = '';
					
					// FOR CHECKBOXES WRAP UP THE ELEMENTS TAGS WITH THE INLINE LABEL
					if( in_array( $field['type'], array( 'checkbox' ) ) && isset( $field['inline_label'] ) ){
						$tag .= "<label class='inline-label'>";
					}
					
					// TAG BEGIN
					if( in_array( $field['type'], array( 'email', 'number', 'text', 'checkbox', 'hidden' ) ) ){
						$tag .= "<input type='".$field['type']."'";
					}
					elseif( $field['type'] == 'dropdown' ){
						$tag .= "<select";
					}
					elseif( $field['type'] == 'submit' ){
						$tag .= "<button type='submit'";
					}
					
					foreach( $field as $attr => $val ){
						if( in_array( $attr, array( 'placeholder', 'size', 'class', 'name', 'value') ) ){
							$tag .= " $attr='$val'";
						}
					}
					
					// CLOSE THE INPUT TAG
					if( in_array( $field['type'], array( 'email', 'number', 'text', 'checkbox', 'hidden' ) ) ){
						$tag .= " />";
					}
					elseif( $field['type'] == 'dropdown' ){
						$tag .= ">";
						if( isset( $field['options'] ) ){
							foreach( $field['options'] as $opt_val => $opt_label ){
								$tag .= "<option value='".$opt_val."'>".$opt_label."</option>";
							}
						}
						$tag .= "</select>";
					}
					
					if( in_array( $field['type'], array( 'submit', 'button' ) ) ){
						
						$tag .= ">";
						
						if( isset( $field['html'] ) ){
							$tag .= $field['html'];
						}
						
						$tag .= "</button>";
					}
					
						
					if( in_array( $field['type'], array( 'checkbox' ) ) && isset( $field['inline_label'] ) ){
						$tag .= "&nbsp;".$field['inline_label']."</label>";
					}
					elseif( isset( $field['inline_label'] ) ){
						$tag .= "<label class='inline-label'>".$field['inline_label']."</label>";
					}
					
					_e( $tag );
					
				}
			
			_e("</div>");
			
		}
		
		function form( $form ){
			
			foreach( $form as $field ){
				$this->form_field( $field );
			}
		}
		
		/* ENQUEUE SCRIPTS AND STYLES ONLY IF THE SHORTCODE IS PRESENT IN THE PAGE CONTENT */
		function assets( /*$posts*/ ){
			
			//if( $this->has_shortcode( $posts ) ){
				
				//wp_die();
				
				wp_enqueue_script( 'jquery' );
				
				wp_enqueue_script( 'stripe', 'https://js.stripe.com/v2/', array('jquery'), '1.0.0', true);
				
				
				$uri = plugin_dir_url( __FILE__ );
				
				wp_enqueue_script( 'meteor-api', $uri.'assets/scripts/main.js', array('jquery'), '1.0.6', true);
				
				wp_localize_script( 'meteor-api', 'meteor_settings', array(
					'key'	=> $this->getStripeAPI()->getStripeKeys()['publishable']
				));
				
			//}
			
			//return $posts;
		}
		
		
		
		
		/* EXECUTE SHORTCODE */
		function main_shortcode( $atts ){
			
			$atts = shortcode_atts( array(
				'name' => 'Donation Form'
			), $atts, $this->getShortcode() );
			
			ob_start();
			
			$error_flag = false;
			
			/*
			* CHECK IF STRIPE TOKEN IS PRESENT THEN EXECUTE THE PROCESSING WITH THE API
			*/
			if( isset( $_POST['stripeToken'] ) && !empty( $_POST['stripeToken'] ) && isset( $_POST['meteor-stripe'] ) && wp_verify_nonce( $_POST['meteor-stripe'], 'save' ) ){
				
				$error_flag = $this->getStripeAPI()->processForm( $_POST );
			}
			
			include( 'templates/stripe.php' );
			
			return ob_get_clean();
			
		}
		
		function has_shortcode( $posts ){
			$found = false;
			if ( !empty($posts) ){
				foreach ($posts as $post) {
					if ( has_shortcode($post->post_content, $this->getShortcode() ) ){
						$found = true;
						break;
					}
				}	
			}
			return $found;
		}
		
	}
	
	
	METEOR_API::getInstance();