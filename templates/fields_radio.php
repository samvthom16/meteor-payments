<ul class="meteor-list meteor-radio">
	<?php if( isset( $field['options'] ) ):foreach( $field['options'] as $opt_val => $opt_label ):?>
	<li>
		<input type="radio" name="<?php _e( $field['name'] );?>" <?php if( $field['default'] == $opt_val ){ _e("checked='checked'"); }?> value="<?php _e( $opt_val );?>" />
		<label><?php _e( $opt_label );?></label>
	</li>
	<?php endforeach; endif;?>	
</ul>