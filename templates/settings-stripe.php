<?php
	
	$settingsOptions = $this->getSettingsOptions();
	
	if( isset( $_POST['submit'] ) ){
			
		foreach( $settingsOptions as $option_slug => $option_title ){
			update_option( $option_slug, $_POST[ $option_slug ] );
		}
	}
	
?>
<form method="POST">
	<?php foreach( $settingsOptions as $option_slug => $option_title ):?>
	<p>
		<label><?php _e( $option_title );?></label><br>
		<input type="text" name="<?php _e( $option_slug );?>" style="width: 100%; max-width: 400px;" value="<?php _e( get_option( $option_slug ) );?>" />
	</p>
	<?php endforeach;?>
	<p class='submit'><input type="submit" name="submit" class="button button-primary" value="Save Settings"><p>
</form>
