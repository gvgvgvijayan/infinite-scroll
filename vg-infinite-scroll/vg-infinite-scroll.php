<?php
defined('ABSPATH') or die('No direct access please!');

/*
Plugin Name: VG Infinite Scroll
Description: Show the posts with infinite scroll along with load more button
Version: 1.0
Author: Vijayan G
Author URI: www.vijayan.in
*/


/**
 * Show posts with infinite scroll
 * 
 * This plugin is used to add a feature which show the posts with infinite scroll
 */
class VG_Infinite_Scroll
{

    /**
     * Callback method for shortcode which render on page load
     */
    public static function get_custom_posts($atts)
    {


        $attributes = shortcode_atts(array(
            'category_name' => 'post-formats',
            'posts_per_page' => 5,
        ), $atts);

        set_transient('get_custom_posts_att', $attributes, DAY_IN_SECONDS);
        $output = self::posts_ui($attributes);

        return '<div id="vg-infinite-container"><div class="vg-main-wrapper vg-container">' . $output . '</div>
        <div class="vg-load-more-wrapper"><div class="vg-infinite load-more">next</div></div></div>';
    }

    private static function posts_ui($att)
    {
        set_query_var('att', $att);

        ob_start();
        load_template(plugin_dir_path(__FILE__) . 'templates/vg-posts-content.php');
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Fetch remaining posts
     * 
     * @since 1.0
     */
    public static function fetch_remaining_posts()
    {
        $att = get_transient('get_custom_posts_att');
        $att['offset'] = filter_input(INPUT_POST, 'offset');

        echo self::posts_ui($att);
        die;
    }


    /**
     * Include plugin JS & CSS
     * 
     * @since 1.0
     */
    public static function add_assets()
    {
        wp_enqueue_script('vg-infinite-js', plugin_dir_url(__FILE__) . 'assets/js/main.js');
        wp_enqueue_style('vg-infinite-css', plugin_dir_url(__FILE__) .  'assets/css/main.css');
        wp_localize_script('vg-infinite-js', 'fetch_remaining_post', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}

add_shortcode('vg_ajax_posts', array('VG_Infinite_Scroll', 'get_custom_posts'));
add_action('wp_enqueue_scripts', array('VG_Infinite_Scroll', 'add_assets'));
add_action('wp_ajax_fetch_remaining_posts', array('VG_Infinite_Scroll', 'fetch_remaining_posts'));
add_action('wp_ajax_nopriv_fetch_remaining_posts', array('VG_Infinite_Scroll', 'fetch_remaining_posts'));
