<?php

namespace UnderstrapRealEstate;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use WP_Query;

class City
{
    CONST POST_TYPE = 'cities';

    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);

        add_shortcode('cities_grid', [$this, 'grid_shortcode']);

        add_filter('the_content', [$this, 'city_content']);
        add_filter('get_the_content', [$this, 'city_content']);
    }

    public function register_post_type(): void
    {
        $labels = [
            'name' => __('Города', THEME_TEXTDOMAIN),
            'singular_name' => __('Город', THEME_TEXTDOMAIN),
            'menu_name' => __('Cities', THEME_TEXTDOMAIN),
            'name_admin_bar' => __('Cities', THEME_TEXTDOMAIN),
            'add_new' => __('Add', THEME_TEXTDOMAIN),
            'add_new_item' => __('Add', THEME_TEXTDOMAIN),
            'new_item' => __('New', THEME_TEXTDOMAIN),
            'edit_item' => __('Edit', THEME_TEXTDOMAIN),
            'view_item' => __('View', THEME_TEXTDOMAIN),
            'all_items' => __('All', THEME_TEXTDOMAIN),
            'search_items' => __('Search', THEME_TEXTDOMAIN),
            'not_found' => __('Not Found', THEME_TEXTDOMAIN),
            'not_found_in_trash' => __('Not Found', THEME_TEXTDOMAIN)
        ];

        $args = [
            'labels' => $labels,
            'description' => '',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => [
                'slug' => self::POST_TYPE,
            ],
            'capability_type' => 'post',
            'capabilities' => [
                'create_posts' => true,
            ],
            'map_meta_cap' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'supports' => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'page-attributes', 'post-formats' ],
            'menu_icon' => 'dashicons-format-aside',
            'can_export' => true,
        ];

        register_post_type(self::POST_TYPE, $args);
    }

    public function grid_shortcode(): mixed
    {
        ob_start();

        get_template_part('template-parts/city-grid');

        $content = ob_get_contents();
    
        ob_get_clean();

        return $content;
    }

    public function city_content($content): mixed
    {
        if (is_singular(self::POST_TYPE)) {
            ob_start();
    
            get_template_part('template-parts/property-grid', null, [
                'city' => get_the_ID(),
                'title' => __('Объекты недвижимости', THEME_TEXTDOMAIN),
                'count' => 10
            ]);
    
            $properties = ob_get_contents();
        
            ob_get_clean();

            return $content . $properties;
        }

        return $content;
    }

    public static function get_cities($count = null): WP_Query
    {
        $per_page = '-1';
        $args = [
            'post_type' => self::POST_TYPE,
            'posts_per_page' => $count ?: $per_page,
            'post_status' => 'publish',
            'update_post_meta_cache' => false,
        ];

        return new WP_Query($args);
    }

    public static function get_cities_array($count = null): array
    {
        $cities_query = self::get_cities($count);
        $cities = [];
        
        if ($cities_query->have_posts()) {
            $cities = array_map(function($city) {
                return [
                    'id' => $city->ID,
                    'name' => $city->post_title
                ];
            }, $cities_query->posts);
        }

        return $cities;
    }
}
