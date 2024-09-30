<?php

namespace UnderstrapRealEstate;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Theme
{
	public function run(): void
	{
		new Property();
		new City();

		add_action('after_setup_theme', [$this, 'theme_setup']);
		add_action('wp_enqueue_scripts', [$this, 'scripts'], 999);
	}

	public function theme_setup(): void
	{
        // remove <p></p> tag from contact form fields
        add_filter('wpcf7_autop_or_not', '__return_false');

        add_theme_support( 'menus' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'custom-logo' );

        add_post_type_support( 'page', 'excerpt' );
	}

	public function scripts(): void
	{
        wp_enqueue_style('understrap-real-estate-main', THEME_DIRECTORY_URI . '/assets/css/main.css', [], THEME_VERSION);
        wp_enqueue_script('understrap-real-estate-main', THEME_DIRECTORY_URI . '/assets/js/main.js', ['jquery'], THEME_VERSION, true);
	}
}
