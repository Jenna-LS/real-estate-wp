<?php

use UnderstrapRealEstate\City;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$city_query = City::get_cities();

if ($city_query->have_posts()) {
?>
    <?php while ($city_query->have_posts()) {
        $city_query->the_post(); ?>
            <div class="container mb-4">
                <h2 class="mb-3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php get_template_part('template-parts/property-grid', null, [
                        'city' => get_the_ID()
                    ]); ?>
            </div>
    <?php } ?>
    <?php wp_reset_postdata(); ?>
<?php }
