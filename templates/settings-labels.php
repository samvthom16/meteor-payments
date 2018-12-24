<?php


	$api = METEOR_API::getInstance();
	$labels = $api->getLabels();
	
	if( isset( $_POST['submit'] ) ){
		
		update_option( 'meteor_labels', $_POST['meteor_labels'] );
		$api->setLabelsDB( $_POST['meteor_labels'] );
		
	}
	
	if( isset( $_GET['reset'] ) && '1' == $_GET['reset'] ){
		update_option( 'meteor_labels', $labels );
		
		_e("<script>location.href='".$url."';</script>");
		
	}
	
	$labels_db = $api->getLabelsDB();
	
?>


<form method="POST">
	<?php foreach( $labels as $key => $value ):?>
	<p>
		<label><?php _e( $value );?></label><br>
		<input type="text" name="meteor_labels[<?php _e( $key );?>]" style="width: 100%; max-width: 400px;" value="<?php _e( $api->get_label( $key ) );?>" />
	</p>
	<?php endforeach;?>
	<div class='submit'>
		<ul>
			<li><input type="submit" name="submit" class="button button-primary" value="Save Settings"></li>
			<li>&nbsp; or &nbsp;</li>
			<li><a href='<?php _e( $url );?>&reset=1' class='button button-secondary'>Reset</a></li>
		</ul>
	</div>
	
</form>

<style>
	.submit li{
		display: inline-block;
	}
</style>