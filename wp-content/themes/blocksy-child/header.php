<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blocksy
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <?php do_action('blocksy:head:start') ?>

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
    <?php do_action('blocksy:head:end') ?>
    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1287910935403445');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1287910935403445&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
    <meta name="facebook-domain-verification" content="8gfog71eqlgjt5ltaimwlh6t1bdifd" />
    <script>
        window.ContactUsWidget = {
            config: {
                uuid: "ba516d58-b925-4fb9-ba69-e1b9f4533ba8",
                messaging_url: "https://contact-widget.ooma.com",
                customer_name: "",
            },
        };
    </script>
    <script
        type="text/javascript"
        async
        defer
        src="https://contact-widget.ooma.com/assets/widget.js"></script>

</head>

<?php
ob_start();
blocksy_output_header();
$global_header = ob_get_clean();
?>

<body <?php body_class(); ?> <?php echo blocksy_body_attr() ?>>

    <a class="skip-link show-on-focus" href="<?php echo apply_filters('blocksy:head:skip-to-content:href', '#main') ?>">
        <?php echo __('Skip to content', 'blocksy'); ?>
    </a>

    <?php
    if (function_exists('wp_body_open')) {
        wp_body_open();
    }
    ?>

    <div id="main-container">
        <?php
        do_action('blocksy:header:before');

        echo $global_header;

        do_action('blocksy:header:after');
        do_action('blocksy:content:before');
        ?>

        <main <?php echo blocksy_main_attr() ?>>

            <?php
            do_action('blocksy:content:top');
            blocksy_before_current_template();
            ?>