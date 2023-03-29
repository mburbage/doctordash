<?php
/**
 * Template Name: Hero Template
 * 
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blocksy
 */

 get_header();

 if (
     ! function_exists('elementor_theme_do_location')
     ||
     ! elementor_theme_do_location('hero')
 ) {
     get_template_part('template-parts/hero');
 }
 
 get_footer();

