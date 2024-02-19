<?php

get_header();
$current_taxonomy = get_queried_object();
$is_current_taxonomy_parent_travel = false;

if ($current_taxonomy->parent !== 0) {
    $current_taxonomy_parent = get_term_by('term_taxonomy_id', $current_taxonomy->parent);
    $is_current_taxonomy_parent_travel = $current_taxonomy_parent->slug === 'bcrampon-travels';
}

// Get the current taxonomy
$taxonomy_id = $current_taxonomy->term_id;

// Get children of current taxonomy
$args = array(
    'child_of' => $taxonomy_id,
    'taxonomy' => 'attachment_category',
    'hide_empty' => 0,
    'hierarchical' => true,
    'depth'  => 1,
);
$children_taxonomies = get_terms( $args );
$children_ids = [];
foreach ($children_taxonomies as $children_taxonomy) {
    if ($children_taxonomy->parent === $current_taxonomy->term_taxonomy_id)
    $children_ids[] = $children_taxonomy->term_taxonomy_id;
}

/*
 * Those taxonomies are empty
 * We hide them temporarily
 */
$taxonomies_to_hide = [
        'bcrampon-travels-central-america',
        'bcrampon-travels-south-america',
        'bcrampon-nature-cityscapes',
];
$taxonomy_ids_to_hide = [];

foreach ($taxonomies_to_hide as $taxonomy_slug) {
    $taxonomy_ids_to_hide[] = get_term_by('slug', $taxonomy_slug, 'attachment_category')->term_taxonomy_id;
}
$children_ids = array_diff($children_ids, $taxonomy_ids_to_hide); // We remove the unwanted taxonomies

?>
    <?php
        if (
            $current_taxonomy->parent === 0 ||
            $is_current_taxonomy_parent_travel
        ):
    ?>

    <div id="bravada-child-taxonomy-title"><?php echo $current_taxonomy->name . " - " .  $current_taxonomy->count . " images" ?></div>
    <div id="bravada-child-taxonomy-grid-content">
        <?php
            /*
             * Get the grid partial with the images
             * for the children of the current taxonomy
             */
            do_action( 'display_taxonomy_grid_images_partial', $children_ids);
        ?>
    </div>
    <?php else:

    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'posts_per_page' => -1, // get all the posts, if not we are limited at 10
        'orderby' => 'desc',
        'tax_query' => array(
            array(
                'taxonomy' => 'attachment_category',
                'field' => 'term_taxonomy_id',
                'terms' => [$current_taxonomy->term_taxonomy_id]
            )
        )
    );
    $query_images = new WP_Query( $args );

    /*
     * We always need to send an array
     * If there is only 1 image or less for a given child taxonomy an object is sent
     * So we put it in an array
     */
    $images = $current_taxonomy->count <= 1 ? [$query_images->posts] : $query_images->posts;
    $pluralOrSingular = $current_taxonomy->count > 1 ? "s" : "";

    ?>
        <div id="bravada-child-taxonomy-title"><?php echo $current_taxonomy->name . " - " .  $current_taxonomy->count . " image$pluralOrSingular" ?></div>
        <div id="bravada-child-taxonomy-grid-content">
            <?php do_action('display_children_taxonomy_grid_images_partial', $images); ?>
        </div>

    <?php

    ?>

    <?php endif; ?>

<?php get_footer();
