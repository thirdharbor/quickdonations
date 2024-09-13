<?php

/*

Plugin Name: Quick Donations

Description: A plugin to add quick donations in WooCommerce donations.

Version: 1.1.5

Author URI: https://propellex.co

Author: Propellex
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html



Text Domain: quick-donations

*/



if ( ! defined( 'ABSPATH' ) ) {

    exit; // Exit if accessed directly.

}



function enqueue_plugin_styles() {

    wp_enqueue_style('plugin-styles', plugin_dir_url(__FILE__) . 'style.css', array(), '1.1.6');

}

add_action('wp_enqueue_scripts', 'enqueue_plugin_styles');



function enqueue_woocommerce_blocks() {

    if ( class_exists( 'WooCommerce' ) ) {

        // Enqueue WooCommerce Blocks' checkout scripts

        wp_enqueue_script(

            'wc-blocks-checkout',

            plugins_url( '/assets/client/blocks/cart-frontend.js', WC_PLUGIN_FILE ),

            array( 'jquery' ),

            WC_VERSION,

            true

        );

    }

}

add_action( 'wp_enqueue_scripts', 'enqueue_woocommerce_blocks' );



function enqueue_jquery_and_modal_script() {

    // Enqueue jQuery if it's not already included by WordPress

    if (!wp_script_is('jquery', 'enqueued')) {

        wp_enqueue_script('jquery');

    }



    // Enqueue your custom modal script from the plugin's root directory

    wp_enqueue_script(

        'modal-js', // Handle for the script

        plugin_dir_url(__FILE__) . 'modal.js', // Path to the script file in the plugin's root directory

        array('jquery'), // Dependencies (make sure jQuery is loaded first)

        '1.1.4', // Version number (null to not worry about caching)

        true // Load the script in the footer

    );

}

add_action('wp_enqueue_scripts', 'enqueue_jquery_and_modal_script');



function display_campaign_data_grid($atts) {

    // Attributes for the shortcode

    $atts = shortcode_atts(

        array(

            'id' => '', // Campaign ID

        ), $atts, 'campaign_grid'

    );



    // Get campaign ID from attributes

    $campaign_id = intval($atts['id']);



    if (!$campaign_id) {

        return 'No campaign found.';

    }



    // Fetch meta fields associated with the campaign

	$campaign_title = get_the_title($campaign_id);

	$campaign_description = get_post_meta($campaign_id, 'campaign_description', true);

	$image_ids = get_post_meta($campaign_id, 'campaign_level_image');

	// print_r($image_ids);

    // $image_ids_array = explode('|', $image_ids);

    $preset_amounts = get_post_meta($campaign_id, 'pred-amount', true);

	$preset_label = get_post_meta($campaign_id, 'pred-label', true);

    $goal_amount = get_post_meta($campaign_id, 'wc-donation-goal-fixed-amount-field', true);

    $goal_progress = get_post_meta($campaign_id, 'wc-donation-goal-fixed-initial-amount-field', true);

    $recurring = get_post_meta($campaign_id, 'wc-donation-recurring', true);



    // Prepare the grid layout

    $output = '<div class="campaign-grid-wrapper">'; /*opening campaign grid wrapper */



	if (!empty($campaign_description)) {

        $output .= '<div class="campaign-description">'; /*opening campaign description */

        $output .= '<p>' . esc_html($campaign_description) . '</p>';

        $output .= '</div>'; /*closing campaign desc */

    }



    $output .= '<div class="campaign-grid">'; /*opening campaign grid div */



	if (!empty($preset_amounts)) {

        foreach ($preset_amounts as $index => $amount) {

            $label = isset($preset_label[$index]) ? $preset_label[$index] : '';

            $image_id = isset($image_ids[$index]) ? $image_ids[$index] : '';

            $image_url = wp_get_attachment_url($image_id);



            $output .= '<div class="campaign-grid-item">';  /*opening campaign grid item div */



            // Display the image first

            if ($image_url) {

                $output .= '<img class="campaign-image" src="' . esc_url($image_url) . '" alt="' . esc_attr($label) . '">';

            }



            // Display the label

            if ($label) {

                $output .= '<div class="amount-label"><h3>' . esc_html($label) . '</h3></div>';

            }



            // Display the amount

            $output .= '<div class="amount"><h4>£' . esc_html($amount) . '</h4></div>';



            // Display the button

            $output .= '<button class="open-modal" data-campaign-id="' . esc_attr($campaign_id) . '">Donate Now</button>';



            $output .= '</div>'; /*closing campaign grid item div */

        }

    }



    // Include the modal container (initially hidden)

    $output .= '<div id="campaign-modal" class="modal" style="display:none;">'; /*opening modal div */
    $output .= '<span class="close-modal">&times;</span>';

    $output .= '<div class="modal-content">'; /*opening modal content div */

	$output .= '<h3>' . esc_html($campaign_title) . '</h3>';
    $output .= '<p>How you can help</p>';

    $output .= do_shortcode('[wc_woo_donation id="' . esc_attr($campaign_id) . '"]'); // Replace with the actual shortcode for the donation form

    $output .= '</div>'; /*closing modal content div */

    $output .= '</div>'; /*closing modal div */

    $output .= '</div></div>'; /*clsoing campaign grid div */ /*closing campaign grid wrapper div */


    return $output;

}



add_shortcode('campaign_grid', 'display_campaign_data_grid');

function campaign_modal_script() {

    ?>

    <script type="text/javascript">

        document.addEventListener('DOMContentLoaded', function () {

            var modal = document.getElementById('campaign-modal');

            var closeModal = document.querySelector('.close-modal');

            var openModalButtons = document.querySelectorAll('.open-modal');



            openModalButtons.forEach(function(button) {

                button.addEventListener('click', function() {

                    modal.style.display = 'block';

                });

            });



            closeModal.addEventListener('click', function() {

                modal.style.display = 'none';

            });



            window.addEventListener('click', function(event) {

                if (event.target == modal) {

                    modal.style.display = 'none';

                }

            });

        });

    </script>

    <?php

}

add_action('wp_footer', 'campaign_modal_script');





function change_donation_response_to_stay_on_page($response) {

    // Prevent redirection to the cart page by setting cart_url to an empty string

    $response['cart_url'] = '';



    // Prevent redirection to the checkout page by setting checkoutUrl to an empty string

    $response['checkoutUrl'] = '';



    return $response;

}

add_filter('wc_donation_alter_donate_response', 'change_donation_response_to_stay_on_page', 10, 1);



function add_single_monthly_labels_before_shortcode($campaign_id) {
    ?>
    <div class="donation-type-labels">
        <label class="selected-label" id="single-donation-label" style="cursor: pointer;">
            Single Payment</label>
        <label class="donation-type-label" id="monthly-donation-label" style="cursor: pointer;">
            Monthly Payment</label>
    </div>

    <?php
}
add_action('wc_donation_before_shortcode_add_donation', 'add_single_monthly_labels_before_shortcode');

add_action('wc_donation_before_subscription_interval', 'add_single_monthly_dropdown');

function add_single_monthly_dropdown() {
    ?>
    <div class="donation-type-dropdown" style="display: none;">
        <label for="donation_type">Choose Donation Type:</label>
        <select id="donation_type" class="donation-type-label">
            <option value="single" id="single-donation-option">Single Payment</option>
            <option value="monthly" id="monthly-donation-option">Monthly Payment</option>
        </select>
    </div>
    <?php
}
