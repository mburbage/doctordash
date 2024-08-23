<?php
if (! defined('WP_DEBUG')) {
	die('Direct access forbidden.');
}
add_action('wp_enqueue_scripts', function () {

	wp_enqueue_script('datatablesjquery_js', 'https://code.jquery.com/jquery-3.7.1.js');
	wp_enqueue_script('ajaxfoundation_js', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js');
	wp_enqueue_script('datatables_js', 'https://cdn.datatables.net/2.1.4/js/dataTables.js');
	wp_enqueue_script('foundation_js', 'https://cdn.datatables.net/2.1.4/js/dataTables.foundation.js');
	wp_enqueue_script('child-javascript_js', get_stylesheet_directory_uri() . '/doctordash.js');

	//wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	
	
	/* <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />  
	<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script> */
	//wp_enqueue_style('datatables_css', 'https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css');
	wp_enqueue_style('ajaxfoundation_css', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css');
	wp_enqueue_style('foundation_css', 'https://cdn.datatables.net/2.1.4/css/dataTables.foundation.css');
	
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

	$title = 'Medical Transportation From ' . $_POST["acf"]["field_66a911abbb422"] . ' To ' .  $_POST["acf"]["field_66c74a91e4bd0"] . ' - ' . $date->format('F j Y') . ' ' . $time->format('H:i');

	$where = array('ID' => $post_id);

	$wpdb->update($wpdb->posts, array('post_title' => $title, 'post_name' => $new_slug), $where);
	//}
}

add_action('save_post', 'set_post_title');

function return_empty_seats_info($a) {

	$html = '';

	if ($a):
		$html .= "<ul>";
		foreach ($a as $name => $value):
			$html .= "<li><b>$name</b> $value</li>";
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
		'posts_per_page' => 5,
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
	$html .= '		<th>Date</th>';
	$html .= '		<th>Pickup Time</th>';
	$html .= '		<th>Pickup City</th>';
	$html .= '		<th>Dropoff City</th>';
	$html .= '		<th>Pickup Zip</th>';
	$html .= '		<th>Dropoff Zip</th>';
	$html .= '		<th>Seats</th>';
	$html .= '		<th>Mode</th>';
	$html .= '		<th>Price</th>';
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
		$html .= '</tr>';
	}

	$html .= '	</tbody>';
	$html .= '	</table>';

	return $html;
}
