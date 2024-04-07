<?php

add_action('get_front_page_category_grid_images_partial', 'bravada_child_get_front_page_category_grid_images_partial');

if (!function_exists('bravada_child_get_front_page_category_grid_images_partial')) {
    function bravada_child_get_front_page_category_grid_images_partial (array $post_categories)
    {
        ?>
            <div class="bravada-child-frontpage-category-images">
                <?php
                    foreach ($post_categories as $key => $post_category) {
                        if (
                            $post_category->slug !== 'uncategorized' &&
                            $post_category->parent === 0 && // We make sure that this is not a child category
                            function_exists('z_taxonomy_image') &&
                            function_exists('get_front_page_category_image_card') &&
                            function_exists('get_front_page_category_image_card')
                        ) {
                            get_front_page_category_image_card($post_category);
                        }
                    }
                ?>
            </div>
        <?php
    }
}

if (!function_exists('get_front_page_category_image_card')) {
    function get_front_page_category_image_card ($post_category)
    {
        ?>
            <a href="<?php echo get_home_url() . "/category/$post_category->slug" ?>">

                <div class="image-overlay">
                    <p class="text-overlay"><?php echo $post_category->name ?></p>
                    <img
                        class="bravada-child-category-image"
                        src="<?php echo z_taxonomy_image_url($post_category->term_id) ?>"
                    >
                </div>
            </a>
        <?php
    }
}