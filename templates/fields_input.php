<?php
	
	$tag = "<input type='".$field['type']."'";
	
	foreach( $field as $attr => $val ){
		if( in_array( $attr, array( 'placeholder', 'size', 'class', 'name', 'value') ) ){
			$tag .= " $attr='$val'";
		}
	}
	
	// CLOSE THE INPUT TAG
	$tag .= " />";
	
	if( isset( $field['inline_label'] ) ){
		$tag .= "<label class='inline-label'>".$field['inline_label']."</label>";
	}
	
	echo $tag;