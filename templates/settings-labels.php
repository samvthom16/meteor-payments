<?php


	$api = METEOR_API::getInstance();
	$labels = $api->getLabels();

	if( isset( $_POST['submit'] ) ){

		// echo "<pre>";
		// print_r( $_POST['meteor_labels'] );
		// echo "</pre>";

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
	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row"></th>
				<th scope="row">English</th>
				<th scope="row">German</th>
			</tr>
	<?php foreach( $labels_db as $key => $value ):?>
			<tr>
				<th scope="row"><label><?php _e( $value['en'] );?></label></th>
				<?php foreach ( array( 'en', 'de' ) as $lang ):?>
					<td>
						<input type="text" name="meteor_labels[<?php _e( $key );?>][<?php _e( $lang );?>]" style="width: 100%; max-width: 400px;" value="<?php _e( $api->get_label( $key, $lang ) );?>" />
					</td>
				<?php endforeach;?>
			</tr>
	<?php endforeach;?>
			<tr>
				<td>
					<input type="submit" name="submit" class="button button-primary" value="Save Settings">
					<a href='<?php _e( $url );?>&reset=1' class='button button-secondary'>Reset</a>
				</td>
			</tr>
	 </tbody>
 </table>

</form>


<style>
	.submit li{
		display: inline-block;
	}
</style>
