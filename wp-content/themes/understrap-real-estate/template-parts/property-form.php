<?php
// include form assets
// TODO: check if we have already included these scripts before
do_action('understrap_real_estate_include_property_form');
?>

<div id="property-form" class="mt-5">
    <h2 class="text-center mb-3"><?php echo __('Добавить объект недвижимости', THEME_TEXTDOMAIN); ?></h2>
    <?php echo do_shortcode('[contact-form-7 title="Property form"]'); ?>
    <div id="property-form-response" class="text-center">
    </div>
</div>
