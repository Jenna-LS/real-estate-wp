<?php

namespace UnderstrapRealEstate;

use WP_Query;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Property
{
    CONST POST_TYPE = 'properties';
    const TYPE_TAXONOMY = 'property_type';

    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);

        add_shortcode('property_info', [$this, 'info_shortcode']);

        add_action('add_meta_boxes', [$this, 'create_city_metabox']);
        add_action('save_post', [$this, 'save_city_metadata']);
    }

    public function register_post_type(): void
    {
        $labels = [
            'name' => __('Недвижимость', THEME_TEXTDOMAIN),
            'singular_name' => __('Недвижимость', THEME_TEXTDOMAIN),
            'menu_name' => __('Properties', THEME_TEXTDOMAIN),
            'name_admin_bar' => __('Properties', THEME_TEXTDOMAIN),
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

        register_taxonomy(self::TYPE_TAXONOMY, [self::POST_TYPE], [
            'label' => __('Тип', THEME_TEXTDOMAIN),
            'hierarchical' => true,
            'rewrite' => ['slug' => self::TYPE_TAXONOMY],
            'show_admin_column' => true,
            'show_in_rest' => true,
            'public' => true,
            'show_in_menu' => true,
            'sort' => true,
            'labels' => [
                'singular_name' => __('Тип', THEME_TEXTDOMAIN),
                'all_items' => __('Все типы', THEME_TEXTDOMAIN),
                'edit_item' => __('Edit type', THEME_TEXTDOMAIN),
                'view_item' => __('View type', THEME_TEXTDOMAIN),
                'update_item' => __('Update type', THEME_TEXTDOMAIN),
                'add_new_item' => __('Add New type', THEME_TEXTDOMAIN),
                'new_item_name' => __('New type Name', THEME_TEXTDOMAIN),
                'search_items' => __('Search types', THEME_TEXTDOMAIN),
                'popular_items' => __('Popular types', THEME_TEXTDOMAIN),
                'separate_items_with_commas' => __('Separate types with comma', THEME_TEXTDOMAIN),
                'choose_from_most_used' => __('Choose from most used types', THEME_TEXTDOMAIN),
                'not_found' => __('No types found', THEME_TEXTDOMAIN),
            ]
        ]);

        register_taxonomy_for_object_type(self::TYPE_TAXONOMY, self::POST_TYPE);
    }

    public function info_shortcode(): mixed
    {
        $content = '';
        if (is_singular(self::POST_TYPE)) {
            ob_start();

            get_template_part('template-parts/property-meta', null, [
                'show_title' => true,
                'show_city' => true
            ]);

            $content = ob_get_contents();
        
            ob_get_clean();
        }

        return $content;
    }

    public function create_city_metabox(): void
    {
        add_meta_box(
			'property_city',
			'Город',
			array($this, 'display_city_metabox'),
			array(self::POST_TYPE), // для записей и страниц
			'side',
			'high'
		);
    }

    public function display_city_metabox(): void
    {
        $city_query = City::get_cities();
        if ($city_query->have_posts()) {
            $value = self::get_city(get_the_ID());
            ?>
            <select name="property_city" id="property_city">
                <option value="">Выберите город</option>
                <?php while ($city_query->have_posts()) {
                    $city_query->the_post(); ?>
                    <option value="<?php echo get_the_ID(); ?>" <?php selected($value, get_the_ID()); ?>><?php the_title(); ?></option>
                <?php } ?>
            </select>
            <?php
            wp_reset_postdata();
        }
    }

    public function save_city_metadata($post_id): void
    {
        if (!isset( $_POST['property_city']))
            return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
            return;

        $city = sanitize_text_field($_POST['property_city']);

        update_post_meta($post_id, 'property_city', $city);
    }

    public static function get_city($property_id): mixed
    {
        return get_post_meta($property_id, 'property_city', true);
    }

    public static function get_properties($count = null, $city = null): WP_Query
    {
        $per_page = '-1';
        $args = [
            'post_type' => self::POST_TYPE,
            'posts_per_page' => $count ?: $per_page,
            'post_status' => 'publish',
            'update_post_meta_cache' => false,
        ];

        if ($city) {
            $args['meta_query'] = [
                [
                    'key' => 'property_city',
                    'value' => $city,
                    'compare' => 'LIKE',
                ]
            ];
        }

        return new WP_Query($args);
    }

	public static function get_property_description(): mixed
	{ 
		global $post;

		return apply_filters('the_excerpt', $post->post_excerpt);
	}
}
