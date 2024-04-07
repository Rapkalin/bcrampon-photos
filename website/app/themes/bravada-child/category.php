<?php

get_header();


// Get the current category
$category = get_category( get_query_var( 'cat' ) );
$cat_id = $category->cat_ID;

// Get children of current category
$children_ids = get_term_children($cat_id, 'category');

if ($category->parent !== 0) {
    $term_category = $category;
    $terms = get_terms([
        'taxonomy' => 'attachment_category'
    ]);

    foreach ($terms as $term) {
        if (strtolower($category->name) === strtolower($term->name)) {
           $term_category = $term;
        }
    }
}

/*
 * Get the grid partial with the images
 * for the children of the current category
 */

?>
    <?php if ($category->parent === 0): ?>
    <div id="bravada-child-category-grid-content">
        <?php do_action( 'display_category_grid_images_partial', $children_ids); ?>
    </div>
    <?php else:

    // dump('$category', $category);
    // dump('$terms', $terms);
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'orderby' => 'asc',
        'tax_query' => array(
            array(
                'taxonomy' => 'attachment_category',
                'field' => 'slug',
                'terms' => [$term_category->slug]
            )
        )
    );
    $query_images = new WP_Query( $args );

    /*
     * We always need to send an array
     * If there is only 1 image or less for a given child category an object is sent
     * So we put it in an array
     */
    $images = $term_category->count <= 1 ? [$query_images->posts] : $query_images->posts;

    ?><div id="bravada-child-category-grid-content"><?php
        do_action('display_children_category_grid_images_partial', $images);
    ?></div><?php

    ?>

    <?php endif; ?>

<?php get_footer();
