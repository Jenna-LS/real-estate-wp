<?php

use UnderstrapRealEstate\Property;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$count = $args['count'] ?? null;
$property_query = Property::get_properties($count, $args['city']);
if ($property_query->have_posts()) {
    ?>
    <?php if (isset($args['title']) && !empty($args['title'])) { ?>
        <div class="mt-4"></div>
        <h3><?php echo $args['title']; ?></h3>
    <?php } ?>
    <div class="row">
        <?php
            while ($property_query->have_posts()) {
                $property_query->the_post();
                ?>
                <div class="col-4">
                    <?php get_template_part('template-parts/property-card'); ?>
                </div>
                <?php
            }

            wp_reset_postdata();
        ?>
    </div>
<?php }
