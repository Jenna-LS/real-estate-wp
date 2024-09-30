<?php

use UnderstrapRealEstate\Theme;

define('THEME_VERSION',  '1.0.0');

define('THEME_TEXTDOMAIN',  'understrap-real-estate');

define('THEME_DIRECTORY',  get_stylesheet_directory());

define('THEME_DIRECTORY_URI',  get_stylesheet_directory_uri());

// TODO: implement autoloader
include_once THEME_DIRECTORY . '/inc/Theme.php';
include_once THEME_DIRECTORY . '/inc/Property.php';
include_once THEME_DIRECTORY . '/inc/PropertyForm.php';
include_once THEME_DIRECTORY . '/inc/City.php';

(new Theme)->run();
