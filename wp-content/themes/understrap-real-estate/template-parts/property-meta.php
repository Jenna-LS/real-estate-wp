<?php

use UnderstrapRealEstate\Property;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$size = get_field('size', get_the_ID());
$real_size = get_field('real_size', get_the_ID());
$price = get_field('price', get_the_ID());
$address = get_field('address', get_the_ID());
$floor = get_field('floor', get_the_ID());
$city = Property::get_city(get_the_ID());
?>

<?php if (isset($args['show_title']) && $args['show_title']) { ?>
    <h4>Характеристики</h4>
<?php } ?>
<ul class="list-group">
    <?php if ($city && isset($args['show_city']) && $args['show_city']) { ?>
        <li class="list-group-item"><?php echo __('Город:', THEME_TEXTDOMAIN); ?> <?php echo esc_html(get_the_title($city)); ?></li>
    <?php } ?>
    <?php if ($size) { ?>
        <li class="list-group-item"><?php echo __('Площадь:', THEME_TEXTDOMAIN); ?> <?php echo esc_html($size); ?> <?php echo __('м<sup>2</sup>', THEME_TEXTDOMAIN); ?></li>
    <?php } ?>
    <?php if ($real_size) { ?>
        <li class="list-group-item"><?php echo __('Жилая площадь:', THEME_TEXTDOMAIN); ?> <?php echo esc_html($real_size); ?> <?php echo __('м<sup>2</sup>', THEME_TEXTDOMAIN); ?></li>
    <?php } ?>
    <?php if ($price) { ?>
        <li class="list-group-item"><?php echo __('Цена:', THEME_TEXTDOMAIN); ?> <?php echo esc_html(number_format($price, 0, '.', '&thinsp;')); ?> &#8381;</li>
    <?php } ?>
    <?php if ($address) { ?>
        <li class="list-group-item"><?php echo __('Адрес:', THEME_TEXTDOMAIN); ?> <?php echo esc_html($address); ?></li>
    <?php } ?>
    <?php if ($floor) { ?>
        <li class="list-group-item"><?php echo __('Этаж:', THEME_TEXTDOMAIN); ?> <?php echo esc_html($floor); ?></li>
    <?php } ?>
</ul>

