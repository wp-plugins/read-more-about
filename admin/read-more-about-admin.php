<?php 
/**
* Read-more-about-admin.php
*
* Creates the custom fields for the post admin area
*
* The code for the repeatable fields comes from Helen Housandi and can be found here: https://gist.github.com/helenhousandi/1593065. Many thanks for this code.
*
* @author Jacob Martella
* @package Read More About
* @version 1.0
*/
//* Set the array for the posts dropdown
$args = array('numberposts' => -1);
global $posts_array;
$posts_array = [];
$posts = get_posts($args);
foreach($posts as $post) {
	setup_postdata($post);
	$id = get_the_ID();
	$name = get_the_title();
	$posts_array[$id] = $name;
}

//* Set the array for the internal/external dropdown
global $in_ex_array;
$in_ex_array['external'] = __('External', 'read-more-about');
$in_ex_array['internal'] = __('Internal', 'read-more-about');
add_action('admin_init', 'read_more_about_add_meta_boxes');

//* Add the meta box
function read_more_about_add_meta_boxes() {
	add_meta_box( 'read-more-about-meta', __('Related Links', 'read-more-about') , 'read_more_about_meta_box_display', 'post', 'normal', 'default');
}
//* Create the meta box
function read_more_about_meta_box_display() {
	global $post;
	global $posts_array;
	global $in_ex_array;
	$links = get_post_meta($post->ID, 'read_more_links', true);
	wp_nonce_field( 'read_more_about_meta_box_nonce', 'read_more_about_meta_box_nonce' );
  
	echo '<div id="repeatable-fieldset-one" width="100%">';
	
	//* Check for fields already filled out
	if ( $links ) {
	
	//* Loop through each link the user has already entered
	foreach ( $links as $link ) {
	echo '<section class="link-fields">';
		echo '<p>';
			echo '<label for="read_more_about_in_ex">' . __('External/Internal Link', 'read-more-about') . '</label>';
			echo '<select class="read_more_about_in_ex" name="read_more_about_in_ex[]">';
				foreach ($in_ex_array as $key => $name) {
					if ($key == $link['read_more_about_in_ex']) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
				echo '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
				}
			echo '</select>';
		echo '</p>';

		if ($link['read_more_about_in_ex'] == 'internal') {$style = 'style="display:none;"';} else {$style = '';}
		echo '<p class="external-link"' . $style .  '>';
			echo '<label for="read_more_about_link">' . __('External URL', 'read-more-about') . '</label>';
			echo '<input type="text" name="read_more_about_link[]" id="read_more_about_link" value="' . $link['read_more_about_link'] . '" />';
		echo '</p>';

		if ($link['read_more_about_in_ex'] == 'internal') {$style = 'style="display:none;"';} else {$style = '';}
		echo '<p class="external-title"' . $style . '>';
			echo '<label for="read_more_about_external_title">' . __('External URL Title', 'read-more-about') . '</label>';
			echo '<input type="text" name="read_more_about_external_title[]" id="read_more_about_external_title" value="' . $link['read_more_about_external_title'] . '" />';
		echo '</p>';

		if ($link['read_more_about_in_ex'] == 'external') {$style = 'style="display:none;"';} else {$style = '';}
		echo '<p class="internal-link"' . $style . '>';
			echo '<label for="read_more_about_internal_link">' . __('Internal Post', 'read-more-about') . '</label>';
			echo '<select id="read_more_about_internal_link" name="read_more_about_internal_link[]">';
				foreach ($posts_array as $key => $name) {
					if ($key == $link['read_more_about_internal_link']) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					echo '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
				}
			echo '</select>';
		echo '</p>';
		
		echo '<a class="button remove-row" href="#">' . __('Remove Link', 'read-more-about') . '</a>';
	echo '</section>';
	
	} //* End foreach

	} else {
	//* Show a blank set of fields if there are no fields filled in
		echo '<section class="link-fields">';
			echo '<p>';
				echo '<label for="read_more_about_in_ex">' . __('External/Internal Link', 'read-more-about') . '</label>';
				echo '<select class="read_more_about_in_ex" name="read_more_about_in_ex[]">';
					foreach ($in_ex_array as $key => $name) {
						echo '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
					}
				echo '</select>';
			echo '</p>';

			echo '<p class="external-link">';
				echo '<label for="read_more_about_link">' . __('External URL', 'read-more-about') . '</label>';
				echo '<input type="text" name="read_more_about_link[]" id="read_more_about_link" value="" />';
			echo '</p>';

			echo '<p class="external-title">';
				echo '<label for="read_more_about_external_title">' . __('External URL Title', 'read-more-about') . '</label>';
				echo '<input type="text" name="read_more_about_external_title[]" id="read_more_about_external_title" value="" />';
			echo '</p>';

			echo '<p class="internal-link">';
				echo '<label for="read_more_about_internal_link">' . __('Internal Post', 'read-more-about') . '</label>';
				echo '<select id="read_more_about_internal_link" name="read_more_about_internal_link[]">';
					foreach ($posts_array as $key => $name) {
						echo '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
					}
				echo '</select>';
			echo '</p>';

			echo '<a class="button remove-row" href="#">' . __('Remove Link', 'read-more-about') . '</a>';
		
		echo '</section>';
	}
	
	//* Set up a hidden group of fields for the jQuery to grab
	echo '<section class="empty-row screen-reader-text">';
		echo '<p>';
			echo '<label for="read_more_about_in_ex">' . __('External/Internal Link', 'read-more-about') . '</label>';
			echo '<select class="new-field read_more_about_in_ex"  name="read_more_about_in_ex[]" disabled="disabled">';
				foreach ($in_ex_array as $key => $name) {
					if ($key == $link['read_more_about_in_ex']) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
				echo '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
				}
			echo '</select>';
		echo '</p>';

		echo '<p class="external-link" >';
			echo '<label for="read_more_about_link">' .  __('External URL', 'read-more-about') . '</label>';
			echo '<input class="new-field" type="text" name="read_more_about_link[]" id="read_more_about_link" value="" disabled="disabled" />';
		echo '</p>';

		echo '<p class="external-title">';
			echo '<label for="read_more_about_external_title">' . __('External URL Title', 'read-more-about') . '</label>';
			echo '<input class="new-field" type="text" name="read_more_about_external_title[]" id="read_more_about_external_title" value="" disabled="disabled" />';
		echo '</p>';

		echo '<p class="internal-link">';
			echo '<label for="read_more_about_internal_link">' . __('Internal Post', 'read-more-about') . '</label>';
			echo '<select class="new-field" id="read_more_about_internal_link" name="read_more_about_internal_link[]" disabled="disabled">';
				foreach ($posts_array as $key => $name) {
					if ($key == $link['read_more_about_in_ex']) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					echo '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
				}
			echo '</select>';
		echo '</p>';
		  
		echo '<a class="button remove-row" href="#">' . __('Remove Link', 'read-more-about') . '</a>';
	echo '</section>';
	
	echo '</div>';
	echo '<p><a id="add-row" class="button" href="#">' . __('Add Link', 'read-more-about') . '</a></p>';
	
}
add_action('save_post', 'read_more_about_meta_box_save');
function read_more_about_meta_box_save($post_id) {
	global $posts_array;
	global $in_ex_array;
	if ( ! isset( $_POST['read_more_about_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['read_more_about_meta_box_nonce'], 'read_more_about_meta_box_nonce' ) )
		return;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	if (!current_user_can('edit_post', $post_id))
		return;
	
	$old = get_post_meta($post_id, 'read_more_links', true);
	$new = array();
	
	$in_ex = $_POST['read_more_about_in_ex'];
	$ex_link = $_POST['read_more_about_link'];
	$ex_title = $_POST['read_more_about_external_title'];
	$in_link = $_POST['read_more_about_internal_link'];
	$count = $_POST['read_more_about_count'];
	
	$num = count($in_ex);
	
	for ( $i = 0; $i < $num; $i++ ) {

			if (isset($in_ex) && $in_ex != '') {
			
			if (isset($in_ex[$i]) && array_key_exists($in_ex[$i], $in_ex_array)) {
				$new[$i]['read_more_about_in_ex'] = wp_filter_nohtml_kses($in_ex[$i]);
			}

			if(isset($ex_link[$i])) {
        		$new[$i]['read_more_about_link'] = wp_filter_nohtml_kses($ex_link[$i]);
    		}

    		if(isset($ex_title[$i])) {
	    		$new[$i]['read_more_about_external_title'] = stripslashes( strip_tags( $ex_title[$i] ) );
	    	}

    		if (isset($in_link[$i]) && array_key_exists($in_link[$i], $posts_array)) {
				$new[$i]['read_more_about_internal_link'] = wp_filter_nohtml_kses($in_link[$i]);
			}
		}

	}
	if ( !empty( $new ) && $new != $old ) {
		update_post_meta( $post_id, 'read_more_links', $new );
	} elseif ( empty($new) && $old ) {
		delete_post_meta( $post_id, 'read_more_links', $old );
	}
}
?>