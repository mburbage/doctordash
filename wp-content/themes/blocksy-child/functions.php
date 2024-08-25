<?php
if (! defined('WP_DEBUG')) {
	die('Direct access forbidden.');
}
add_action('wp_enqueue_scripts', function () {

	wp_enqueue_script('datatablesjquery_js', 'https://code.jquery.com/jquery-3.7.1.js');
	wp_enqueue_script('ajaxfoundation_js', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js');
	wp_enqueue_script('datatables_combined_js', 'https://cdn.datatables.net/v/zf/dt-2.1.4/r-3.0.2/datatables.min.js');
	wp_enqueue_script('foundation_js', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js');
	wp_enqueue_script('child-javascript_js', get_stylesheet_directory_uri() . '/doctordash.js');

	//wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );


	/* <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />  
	<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script> */
	//wp_enqueue_style('datatables_css', 'https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css');
	wp_enqueue_style('ajaxfoundation_css', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css');
	wp_enqueue_style('datatables_combined_css', 'https://cdn.datatables.net/v/zf/dt-2.1.4/r-3.0.2/datatables.min.css');

	wp_enqueue_style('child-style-min', get_stylesheet_directory_uri() . '/style.min.css');
}, 100);

/**
 * Makes all posts in the default category private.
 *
 * @see 'save_post'
 *
 * @param int $post_id The post being saved.
 */
function set_post_title($post_id) {

	if ($post_id == null || empty($_POST))
		return;

	if (!isset($_POST['post_type']) || $_POST['post_type'] != 'empty-seat')
		return;

	if (wp_is_post_revision($post_id))
		$post_id = wp_is_post_revision($post_id);

	global $post;
	if (empty($post))
		$post = get_post($post_id);

	$new_slug = sanitize_title($post->post_title, $post_id);

	if ($new_slug == $post->post_name)
		return; // already set

	//if ($_POST['empty-seat'] != '') {
	global $wpdb; 
	//$date = date('l, d.m.Y', strtotime($_POST['empty-seat']));
	// Load field value.
	$date_string = $_POST["acf"]["field_66a7d48e7f09c"];

	// Create DateTime object from value (formats must match).
	$date = DateTime::createFromFormat('Ymd', $date_string);

	// Load field value.
	$time_string = $_POST["acf"]["field_66a911c0bb423"];

	// Create DateTime object from value (formats must match).
	$time = DateTime::createFromFormat('H:i:s', $time_string);

	$title = 'Discount Free Medical Transportation From ' . $_POST["acf"]["field_66a911abbb422"] . ' To ' .  $_POST["acf"]["field_66c74a91e4bd0"] . ' - ' . $_POST["acf"]["field_66a91f896b626"] . ' - ' . $date->format('F j, Y') . ' ' . $time->format('H:i A');

	$where = array('ID' => $post_id);

	$wpdb->update($wpdb->posts, array('post_title' => $title, 'post_name' => $new_slug), $where);
	//}
}

function set_empty_seat_meta($post_id) {

	if ($post_id == null || empty($_POST))
		return;

	if (!isset($_POST['post_type']) || $_POST['post_type'] != 'empty-seat')
		return;

	if (wp_is_post_revision($post_id))
		$post_id = wp_is_post_revision($post_id);

	$fields = get_fields();

	$html = return_empty_seats_info($fields);

	global $post;
	if (empty($post))
		$post = get_post($post_id);

	$date_string = $_POST["acf"]["field_66a7d48e7f09c"];

	// Create DateTime object from value (formats must match).
	$date = DateTime::createFromFormat('Ymd', $date_string);

	// Load field value.
	$time_string = $_POST["acf"]["field_66a911c0bb423"];

	// Create DateTime object from value (formats must match).
	$time = DateTime::createFromFormat('H:i:s', $time_string);

	$title = 'Discount/Free Medical transportation from ' . $_POST["acf"]["field_66a911abbb422"] . ' to ' .  $_POST["acf"]["field_66c74a91e4bd0"] . ' on ' . $date->format('F j, Y') . ' at ' . $time->format('H:i') . '. For more details click here. Contact Doctor Dash at (919) 390-3320 to reserve your ride today. Terms and conditions apply';

	$where = array('ID' => $post_id);

	global $wpdb;

	$wpdb->update($wpdb->posts, array('post_content' => $html, 'post_excerpt' => $title), $where);

	if ($fields["map"] == '')
		return;

	//$image_id = $fields["map"]["ID"];

	//set_empty_seat_featured_image($post_id, $image_id);

	$google_static_map_id = '5085acd06b8a584';
	$google_static_map_api = 'AIzaSyDHbxSoQE9_WUDbQhA-5VE_px7HMQcGoGA';
	$google_static_map_secret = 'AuaAjp6r5qI3Mp55I4Hp3Oid6nc=';
	$google_static_map_zoom = '10';
	$google_static_map_size = '640x640';
	$google_static_map_scale = '2';
	$google_static_map_center = $_POST["acf"]["field_66a911abbb422"] . ',NC';
	$google_static_map_marker_style = 'size:small%7C';
	$google_static_map_marker_icon1 = 'icon:' . get_stylesheet_directory_uri() . '/images/map_pu.png';
	$google_static_map_marker_icon2 = 'icon:' . get_stylesheet_directory_uri() . '/images/map_do.png';
	$google_static_map_marker_loc1 = 'markers=anchor:center%7C' . $google_static_map_marker_icon1 . '%7C' . $_POST["acf"]["field_66a91255bb425"] . '%7C';
	$google_static_map_marker_loc2 = '&markers=anchor:center%7C' . $google_static_map_marker_icon1 . '%7C' . $_POST["acf"]["field_66c74ad20e5eb"];
	$google_static_map_filename = $_POST["acf"]["field_66a911abbb422"].''.$_POST["acf"]["field_66a91255bb425"] . '-' . $_POST["acf"]["field_66c74a91e4bd0"].''.$_POST["acf"]["field_66c74ad20e5eb"];

	
	$google_static_map_style = 'feature:administrative%2Elocality%7C';
	$google_static_map_style .= 'element:all%7C';
	$google_static_map_style .= 'hue:0x2c2e33%7Csaturation:7%7Clightness:19%7Cvisibility:on';
	$google_static_map_style .= '&style=feature:landscape%7C';
	$google_static_map_style .= 'element:all%7C';
	$google_static_map_style .= 'hue:0xffffff%7Csaturation:%2D100%7Clightness:100%7Cvisibility:simplified';
	$google_static_map_style .= '&style=feature:poi%7C';
	$google_static_map_style .= 'element:geometry%7C';
	$google_static_map_style .= 'hue:0xbbc0c4%7Csaturation:%2D93%7Clightness:31%7Cvisibility:simplified:';
	$google_static_map_style .= '&style=feature:road%7C';
	$google_static_map_style .= 'element:labels%7C';
	$google_static_map_style .= 'hue:0xbbc0c4%7Csaturation:%2D93%7Clightness:31%7Cvisibility:on';
	$google_static_map_style .= '&style=feature:road%2Earterial%7C';
	$google_static_map_style .= 'element:labels%7C';
	$google_static_map_style .= 'hue:0xbbc0c4%7Csaturation:%2D93%7Clightness:31%7Cvisibility:simplified';
	$google_static_map_style .= '&style=feature:road%2Elocal%7C';
	$google_static_map_style .= 'element:geometry%7C';
	$google_static_map_style .= 'hue:0xe9ebed%7Csaturation:%2D90%7Clightness:8%7Cvisibility:simplified';
	$google_static_map_style .= '&style=feature:transit%7C';
	$google_static_map_style .= 'element:all%7C';
	$google_static_map_style .= 'hue:0xe9ebed%7Csaturation:10%7Clightness:69%7Cvisibility:on';
	$google_static_map_style .= '&style=feature:water%7C';
	$google_static_map_style .= 'element:all%7C';
	$google_static_map_style .= 'hue:0xe9ebed%7Csaturation:%2D78%7Clightness:67%7Cvisibility:simplified';


	$map_id = google_map_static_upload_file_by_url( 'https://maps.googleapis.com/maps/api/staticmap?'.$google_static_map_marker_loc1.''.$google_static_map_marker_loc2.'&scale='.$google_static_map_scale.'&size='. $google_static_map_size .'&style=' . $google_static_map_style . '&key=' . $google_static_map_api .'' , $google_static_map_filename );

	set_empty_seat_featured_image($post_id, $map_id);

}

function set_empty_seat_featured_image($post_id, $image_id) {
	set_post_thumbnail($post_id, $image_id);
}

add_action('save_post', 'set_post_title');
add_action('save_post', 'set_empty_seat_meta');

function return_empty_seats_info($a) {

	$html = '';

	if ($a):
		$html .= "<ul>";
		foreach ($a as $name => $value):
			if ($name != 'map') {
				$html .= "<li><b>$name</b> $value</li>";
			}

		endforeach;
		$html .= "</ul>";
	endif;

	return $html;
}

add_shortcode('list_empty_seats', 'list_empty_seats_func');

function list_empty_seats_func($atts) {
	$queryArgs = [
		// todo use your post type here
		'post_type' => 'empty-seat',
		'post_status' => 'publish',
		// todo maximum amount of posts, use -1 to set unlimited
		// todo type of order
		'order' => 'DESC',
		// todo order field
		'orderby' => 'date',
		// todo use your fields
		// 'meta_query' => [
		// 	[
		// 		'key' => 'since',
		// 		'value' => 2010,
		// 		'compare' => '>',
		// 		'type' => 'numeric',
		// 	],
		// ],
	];

	// SQL query will be executed during this line
	$query = new WP_Query($queryArgs);

	// @var WP_Posts[]
	$posts = $query->get_posts();

	$html = '';
	$html .= '<table id="example" class="display" width="100%">';
	$html .= '	<thead>';
	$html .= '		<tr>';
	$html .= '		<th>Trip</th>';
	$html .= '		<th>Date</th>';
	$html .= '		<th>Pickup Time</th>';
	$html .= '		<th>Pickup City</th>';
	$html .= '		<th>Dropoff City</th>';
	$html .= '		<th>Pickup Zip</th>';
	$html .= '		<th>Dropoff Zip</th>';
	$html .= '		<th>Seats</th>';
	$html .= '		<th>Mode</th>';
	$html .= '		<th>Price</th>';
	$html .= '		<th>Details</th>';
	$html .= '		</tr>';
	$html .= '	</thead>';
	$html .= '	<tbody>';

	foreach ($posts as $post) {
		$postId = $post->ID;
		$title = get_the_title($postId);
		$description = get_the_excerpt($postId);
		$since = get_field('since', $postId);
		$fields = get_fields($postId);
		$date = DateTime::createFromFormat('F j, Y', $fields["pickup_date"]);


		$html .= '<tr>';
		$html .= '<td class="date">';
		$html .= $postId;
		$html .= '</td>';
		$html .= '<td class="date">';
		$html .= $date->format('n/j');
		$html .= '</td>';
		$html .= '<td class="time">';
		$html .= $fields["pickup_time"];
		$html .= '</td>';
		$html .= '<td class="city">';
		$html .= $fields["pickup_city"];
		$html .= '</td>';
		$html .= '<td class="city">';
		$html .= $fields["dropoff_city"];
		$html .= '</td>';
		$html .= '<td class="city">';
		$html .= $fields["pickup_zip"];
		$html .= '</td>';
		$html .= '<td class="city">';
		$html .= $fields["dropoff_zip"];
		$html .= '</td>';
		$html .= '<td class="city">';
		$html .= $fields["pickup_available_seats"];
		$html .= '</td>';
		$html .= '<td class="city">';
		$html .= $fields["mode"];
		$html .= '</td>';
		$html .= '<td class="city">';
		$html .= $fields["price"];
		$html .= '</td>';
		$html .= '<td class="city">';
		$html .= '<a href="' . get_post_permalink($postId) . '" >More Info</a>';
		$html .= '</td>';
		$html .= '</tr>';
	}

	$html .= '	</tbody>';
	$html .= '	</table>';

	return $html;
}

/**
 * Upload image from URL programmatically
 *
 */


function google_map_static_upload_file_by_url( $url, $map_filename = null, $title = null, $content = null, $alt = null ) {

	require_once( ABSPATH . "/wp-load.php");
	require_once( ABSPATH . "/wp-admin/includes/image.php");
	require_once( ABSPATH . "/wp-admin/includes/file.php");
	require_once( ABSPATH . "/wp-admin/includes/media.php");
	
	// Download url to a temp file
	$tmp = download_url( $url );
	if ( is_wp_error( $tmp ) ) return false;
	
	// Get the filename and extension ("photo.png" => "photo", "png")
	$filename = pathinfo($url, PATHINFO_FILENAME);
	$extension = pathinfo($url, PATHINFO_EXTENSION);
	
	// An extension is required or else WordPress will reject the upload
	if ( ! $extension ) {
		// Look up mime type, example: "/photo.png" -> "image/png"
		$mime = mime_content_type( $tmp );
		$mime = is_string($mime) ? sanitize_mime_type( $mime ) : false;
		
		// Only allow certain mime types because mime types do not always end in a valid extension (see the .doc example below)
		$mime_extensions = array(
			// mime_type         => extension (no period)
			'text/plain'         => 'txt',
			'text/csv'           => 'csv',
			'application/msword' => 'doc',
			'image/jpg'          => 'jpg',
			'image/jpeg'         => 'jpeg',
			'image/gif'          => 'gif',
			'image/png'          => 'png',
			'video/mp4'          => 'mp4',
		);
		
		if ( isset( $mime_extensions[$mime] ) ) {
			// Use the mapped extension
			$extension = $mime_extensions[$mime];
		}else{
			// Could not identify extension. Clear temp file and abort.
			wp_delete_file($tmp);
			return false;
		}
	}
	
	// Upload by "sideloading": "the same way as an uploaded file is handled by media_handle_upload"
	$args = array(
		'name' => "$map_filename.$extension",
		'tmp_name' => $tmp,
	);
	
	// Post data to override the post title, content, and alt text
	$post_data = array();
	if ( $title )   $post_data['post_title'] = $title;
	if ( $content ) $post_data['post_content'] = $content;
	
	// Do the upload
	$attachment_id = media_handle_sideload( $args, 0, null, $post_data );
	
	// Clear temp file
	wp_delete_file($tmp);
	
	// Error uploading
	if ( is_wp_error($attachment_id) ) return false;
	
	// Save alt text as post meta if provided
	if ( $alt ) {
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
	}
	
	// Success, return attachment ID
	return (int) $attachment_id;
}
