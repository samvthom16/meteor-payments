<?php



	class METEOR_API extends METEOR_BASE{

		var $shortcode;

		var $stripe;

		var $labels;

		var $labels_db;

		function __construct(){

			/*
			* DEFAULT LIST OF LABELS THAT CAN BE UPDATED FROM THE BACKEND
			*/
			$this->setLabels( array(
				'recurring'						=> 'Pay this amount monthly',
				'email-updates'				=> 'Yes, please keep me informed by email about your work, your breakthroughs, and how to best support you: *',
				'specific-UK'					=> 'Only for UK residents',
				'read-UK'							=> 'Read UK Gift Aid Agreement',
				//'agreed-UK'					=> 'Has Agreed To Uk Gift Aid',
				'phone'								=> '*Please keep me informed about your work and how to best support you by phone',
				'updates'							=> 'Would you like to receive updates by',
				'form-message-below'	=> 'We treat your personal data with care and in compliance with applicable law. Please visit ADFInternational.org/privacy for a full overview.'
			) );
			// GET THE CUSTOMISED LABELS FROM THE DB
			$labels_db = get_option('meteor_labels');
			if( !$labels_db ){
				$labels_db = array();
			}
			$this->setLabelsDB( $labels_db );


			// setting the shortcode
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

		function getLabelsDB(){ return $this->labels_db; }
		function setLabelsDB( $labels_db ){ $this->labels_db = $labels_db; }

		function setLabels( $labels ){ $this->labels = $labels;}
		function getLabels(){ return $this->labels; }
		/* GETTER AND SETTER FUNCTIONS */

		// GET ONE LABEL FROM THE CUSTOMISED AND DEFAULT LIST
		function get_label( $key ){

			$labels_db = $this->getLabelsDB();
			$labels = $this->getLabels();

			return isset( $labels_db[$key] ) ? $labels_db[$key] : ( isset( $labels[$key] ) ? $labels[$key] : ''  );


		}

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

			// ADD ATTRIBUTES TO THE FORM FIELD
			$atts = '';
			$atts_array = array(
				'class'				=> 'container_class',
				'data-behaviour'	=> 'behaviour',
				'data-state'		=> 'state'
			);
			foreach( $atts_array as $atts_key => $atts_value ){

				$value = isset( $field[$atts_value] ) ? $field[$atts_value] : '';

				if( $value ){
					$atts .= " ".$atts_key."='".$value."'";
				}
			}

			_e("<div $atts>");

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

					switch( $field['type'] ){
						case 'radio':
							include('templates/fields_radio.php');
							break;

						case 'label':
							include('templates/fields_label.php');
							break;
						case 'checkbox':
							include('templates/fields_checkbox.php');
							break;

						case 'email':
						case 'number':
						case 'text':
						case 'hidden':
							include('templates/fields_input.php');
							break;

						case 'dropdown':
							include('templates/fields_select.php');
							break;
					}

				}

			_e("</div>");

		}

		function form( $form ){

			$i = 0;

			foreach( $form as $page ){

				$slide_class = 'meteor-slide';
				if( !$i ){ $slide_class .= ' active'; }

				_e( "<div class='".$slide_class."'>" );

				foreach( $page as $form_field ){
					$this->form_field( $form_field );
				}

				_e( "<ul class='meteor-list meteor-list-inline'>" );

				// HIDE IN THE FIRST PAGE OF THE FORM
				if( $i ){ _e( "<li><button data-behaviour='meteor-slide-prev'>Previous</button></li>" ); }

				// IN THE LAST FORM, THE TEXT SHOULD CHANGE TO SUBMIT
				if( $i != count( $form ) - 1 ){
					_e( "<li><button data-behaviour='meteor-slide-next'>Next</button></li>" );
				}
				else{
					_e( "<li><button type='submit'>Submit</button></li>" );
				}

				_e( "</ul>" );

				_e( "</div>" );

				$i++;
			}
		}

		/* ENQUEUE SCRIPTS AND STYLES ONLY IF THE SHORTCODE IS PRESENT IN THE PAGE CONTENT */
		function assets( /*$posts*/ ){

			//if( $this->has_shortcode( $posts ) ){

				//wp_die();

				wp_enqueue_script( 'jquery' );

				wp_enqueue_script( 'stripe', 'https://js.stripe.com/v2/', array('jquery'), '1.0.0', true);


				$uri = plugin_dir_url( __FILE__ );

				wp_enqueue_script( 'meteor-api', $uri.'assets/scripts/main.js', array('jquery'), '1.1.4', true);

				wp_localize_script( 'meteor-api', 'meteor_settings', array(
					'key'	=> $this->getStripeAPI()->getStripeKeys()['publishable']
				));

			//}

			//return $posts;
		}




		/* EXECUTE SHORTCODE */
		function main_shortcode( $atts ){

			$atts = shortcode_atts( array(
				'name' 		=> 'Donation Form',
				'source_id'	=> '00000'
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
