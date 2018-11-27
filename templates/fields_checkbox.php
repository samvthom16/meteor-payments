<?php
	
	$tag = '';
					
	// FOR CHECKBOXES WRAP UP THE ELEMENTS TAGS WITH THE INLINE LABEL
	if( isset( $field['inline_label'] ) ){
		$tag .= "<label class='inline-label'>";
	}
	
	$tag .= "<input type='".$field['type']."'";
	
	foreach( $field as $attr => $val ){
		if( in_array( $attr, array( 'class', 'name', 'value') ) ){
			$tag .= " $attr='$val'";
		}
	}
	
	$tag .= " />";
	
	if( isset( $field['inline_label'] ) ){
		$tag .= "&nbsp;".$field['inline_label']."</label>";
	}
	
	echo $tag;