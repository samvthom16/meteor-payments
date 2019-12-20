<?php

	$tag = "<select";

	foreach( $field as $attr => $val ){
		if( in_array( $attr, array( 'class', 'name', 'value') ) ){
			$tag .= " $attr='$val'";
		}
	}

	$tag .= ">";
	if( isset( $field['options'] ) ){
		foreach( $field['options'] as $opt_val => $opt_label ){

			$tag .= "<option value='".$opt_val."'";

			if( isset( $field[ 'default' ] ) && $field[ 'default' ] == $opt_val ){
				$tag .= " selected='selected'";
			}

			$tag .= ">".$opt_label."</option>";

		}
	}

	$tag .= "</select>";

	if( isset( $field['inline_label'] ) ){
		$tag .= "<label class='inline-label'>".$field['inline_label']."</label>";
	}

	echo $tag;
