<?php
/*
Template Name: Empty Seat page layout
Template Post Type: empty-seat
*/
get_header();

if (
	! function_exists('elementor_theme_do_location')
	||
	! elementor_theme_do_location('single')
) {
	get_template_part('template-parts/single-empty-seat');
	
}

get_footer();
