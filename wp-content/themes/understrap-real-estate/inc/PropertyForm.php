<?php

namespace UnderstrapRealEstate;

use WP_REST_Response;

class PropertyForm
{
    const ROUTE = 'create-property';

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_route']);
        add_action('understrap_real_estate_include_property_form', [$this, 'include_scripts']);

        add_shortcode('property_form', [$this, 'form_shortcode']);
    }

    public function register_route(): void
    {
        register_rest_route(THEME_TEXTDOMAIN . '/v1', '/' . self::ROUTE, [
            'methods' => 'POST',
            'callback' => [$this, 'callback_action'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function include_scripts(): void
    {
        $types = Property::get_types(true);
        $cities = City::get_cities_array();

        wp_enqueue_style('understrap-real-estate-property-form', THEME_DIRECTORY_URI . '/assets/css/property-form.css');
        wp_enqueue_script('understrap-real-estate-property-form', THEME_DIRECTORY_URI . '/assets/js/property-form.js', ['jquery'], null, true);
        wp_localize_script('understrap-real-estate-property-form', 'propertyForm', array(
            'url' => self::get_rest_url(),
            'types' => $types,
            'cities' => $cities
        ));
    }

    public function callback_action(): WP_REST_Response
    {
        $response = [
            'query' => $_POST,
            'status' => false,
            'title' => __('Возникла ошибка при добавлении объекта. Пожалуйста, попробуйте позже.', THEME_TEXTDOMAIN)
        ];

        // Data from get.
        $name = isset($_POST['property-name']) && !empty($_POST['property-name']) ? sanitize_text_field($_POST['property-name']) : '';
        $description = isset($_POST['property-description']) && !empty($_POST['property-description']) ? sanitize_text_field($_POST['property-description']) : '';
        $type = isset($_POST['property-type']) && !empty($_POST['property-type']) ? sanitize_text_field($_POST['property-type']) : '';
        $city = isset($_POST['property-city']) && !empty($_POST['property-city']) ? sanitize_text_field($_POST['property-city']) : '';
        $size = isset($_POST['property-size']) && !empty($_POST['property-size']) ? sanitize_text_field($_POST['property-size']) : '';
        $real_size = isset($_POST['property-real-size']) && !empty($_POST['property-real-size']) ? $_POST['property-real-size'] : '';
        $price = isset($_POST['property-price']) && !empty($_POST['property-price']) ? sanitize_text_field($_POST['property-price']) : '';
        $address = isset($_POST['property-address']) && !empty($_POST['property-address']) ? sanitize_text_field($_POST['property-address']) : '';
        $floor = isset($_POST['property-floor']) && !empty($_POST['property-floor']) ? sanitize_text_field($_POST['property-floor']) : '';

        // If requierd fields are filled.
        if ($name && $description && $type && $city) {

            $property_args = [
                'post_title' => $name,
                'post_content' => '<p>' . $description . '</p>',
                'post_status' => 'publish',
                'post_type' => Property::POST_TYPE
            ];

            $property_id = wp_insert_post($property_args);

            if ($property_id) {
                wp_set_post_terms($property_id, [$type], Property::TYPE_TAXONOMY);
                update_post_meta($property_id, 'property_city', $city);
                
                if ($size) {
                    update_field('size', $size, $property_id);
                }
                
                if ($real_size) {
                    update_field('real_size', $real_size, $property_id);
                }
                
                if ($price) {
                    update_field('price', $price, $property_id);
                }
                
                if ($address) {
                    update_field('address', $address, $property_id);
                }
                
                if ($floor) {
                    update_field('floor', $floor, $property_id);
                }
            }

            $response['status'] = true;
            $response['title'] = __('Объект недвижимости добавлен', THEME_TEXTDOMAIN);
        }

        return new WP_REST_Response($response);
    }

    public function form_shortcode()
    {
        ob_start();

        get_template_part('template-parts/property-form');

        $content = ob_get_contents();
    
        ob_get_clean();

        return $content;
    }

    /**
     * Get create property rest api url.
     *
     * @return string
     */
    public static function get_rest_url(): string
    {
        return rest_url(THEME_TEXTDOMAIN . '/v1/'.self::ROUTE);
    }
}
