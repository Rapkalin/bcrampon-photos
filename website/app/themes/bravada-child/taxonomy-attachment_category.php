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
$children_taxonomies = bravada_child_get_children_for_this_taxonomy($taxonomy_id);
$children_ids = bravada_child_get_children_ids_for_this_taxonomy($current_taxonomy, $children_taxonomies);


// Plural of Singular for taxonomy title
$pluralOrSingular = count($children_ids) > 1 ? "s" : "";

?>
    <?php
        if (
            $current_taxonomy->parent === 0 ||
            !empty($children_ids)
        ):
    ?>


    <?php
    /*
     * Possibily to add a cover on the page by uncommenting these lines
     */
    ?>
    <!-- <div id="bravada-child-taxonomy-cover" class="bravada-child-image-overlay-cover">
        <span id="bravada-child-taxonomy-title" class="bravada-child-text-overlay-cover"><?php /*echo $current_taxonomy->name . "<br>" .  count($children_ids) . " categorie$pluralOrSingular" */?></span>
        <img
                src="<?php /*echo z_taxonomy_image_url($current_taxonomy->term_id) */?>"
                alt=""
                class="bravada-child-taxonomy-image"
        >

    </div> -->

    <div id="bravada-child-taxonomy-title"><?php echo $current_taxonomy->name . " - " .  count($children_ids) . " categorie$pluralOrSingular" ?></div>
    <?php do_action('bravada_child_breadcrumb') ?>
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
        <?php do_action('bravada_child_breadcrumb') ?>
        <div id="bravada-child-taxonomy-grid-content">
            <?php do_action('display_children_taxonomy_grid_images_partial', array_reverse($images)); ?>
        </div>

    <?php

    ?>

    <?php endif; ?>

<?php get_footer();
