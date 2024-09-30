<?php

use UnderstrapRealEstate\Property;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<a href="<?php the_permalink(); ?>">
    <div class="card property-card">
        <div class="property-card__img">
            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php the_title(); ?></h5>
            <p class="card-text"><?php echo Property::get_property_description(); ?></p>
        </div>
        <?php get_template_part('template-parts/property-meta'); ?>
    </div>
</a>
