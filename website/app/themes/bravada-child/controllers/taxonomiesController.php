<?php

add_action('display_taxonomy_grid_images_partial', 'bravada_child_get_taxonomy_grid_images_partial');
add_action('display_children_taxonomy_grid_images_partial', 'bravada_child_display_children_taxonomy_grid_images_partial');

if (!function_exists('bravada_child_get_taxonomy_grid_images_partial')) {

    /**
     * Return a grid of taxonomy cards
     * @param array $children_ids
     * @return void
     */
    function bravada_child_get_taxonomy_grid_images_partial(array $children_ids)
    {
        $gridClass = bravada_child_get_grid_images_class($children_ids);

        ?><div class="<?php echo $gridClass?>"><?php
            foreach ($children_ids as $child_id) {
                $child_taxonomy = get_term_by('term_taxonomy_id', $child_id);
                get_taxonomy_image_card($child_taxonomy);
            }
        ?></div><?php
    }
}

if (!function_exists('get_taxonomy_image_card')) {

    /**
     * Return a taxonomy card
     * @param $child_taxonomy
     * @return void
     */
    function get_taxonomy_image_card($child_taxonomy)
    {
        ?>
            <a class="bravada-child-taxonomy-grid-link" href="<?php echo get_term_link($child_taxonomy) ?>">

                <div class="image-overlay">
                    <p class="text-overlay"><?php echo $child_taxonomy->name ?></p>
                    <img
                        class="bravada-child-taxonomy-image"
                        src="<?php echo z_taxonomy_image_url($child_taxonomy->term_id) ?>"
                    >
                </div>
            </a>
        <?php
    }
}

if (!function_exists('bravada_child_display_children_taxonomy_grid_images_partial')) {

    /**
     * Return the grid images of a taxonomy
     * @param array $images
     * @return void
     */
    function bravada_child_display_children_taxonomy_grid_images_partial (array $images) {

        /*
         * For some reasons if we only have 1 item in the array $images
         * it arrives here as an object
         * So we previously pass it in an array and retrieve the first entry of it
         */

        if (count($images) === 1) {
            $images = $images[0];
        }

        ?><div class="bravada-child-taxonomypage-taxonomy-3-images"><?php
        foreach ($images as $key => $image) {
            $currentImage = $key + 1;
            get_children_taxonomy_images_card($image, $currentImage);
        }
        ?></div>

        <!-- The Modal/Lightbox -->
        <div id="bravada_child_slider_myModal" class="bravada_child_slider_modal">
            <span class="bravada_child_slider_close cursor" onclick="closeModal()">&times;</span>
            <div class="bravada_child_slider_modal-content">

                <?php
                    $totalSlides = count($images);
                    foreach ($images as $key => $image) {
                        $currentImage = $key + 1;
                        get_children_taxonomy_slides_images_card_for_slideshow($image, $currentImage, $totalSlides);
                    }
                ?>

                <!-- Next/previous controls -->
                <a class="bravada_child_slider_prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="bravada_child_slider_next" onclick="plusSlides(1)">&#10095;</a>

                <!-- Caption text -->
                <div class="bravada_child_slider_caption-container">
                    <p id="bravada_child_slider_caption"></p>
                </div>

                <div class="bravada_child_slider_thumbnail">
                    <!-- Thumbnail image controls -->
                    <?php
                    foreach ($images as $key => $image) {
                        $currentImage = $key + 1;
                        get_children_taxonomy_thumbnails_images_card_for_slideshow($image, $currentImage);
                    }
                    ?>
                </div>

            </div>
        </div>

        <script>
            // Open the Modal
            function openModal() {
                document.getElementById("bravada_child_slider_myModal").style.display = "block";
            }

            // Close the Modal
            function closeModal() {
                document.getElementById("bravada_child_slider_myModal").style.display = "none";
            }

            var slideIndex = 1;
            showSlides(slideIndex);

            // Next/previous controls
            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            // Thumbnail image controls
            function currentSlide(n) {
                showSlides(slideIndex = n);
            }

            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName("bravada_child_slider_mySlides");
                var dots = document.getElementsByClassName("bravada_child_slider_demo");
                var captionText = document.getElementById("bravada_child_slider_caption");
                if (n > slides.length) {slideIndex = 1}
                if (n < 1) {slideIndex = slides.length}
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" bravada_child_slider_active", "");
                }
                slides[slideIndex-1].style.display = "block";
                dots[slideIndex-1].className += " bravada_child_slider_active";
                captionText.innerHTML = dots[slideIndex-1].alt;
            }
        </script>

        <?php
    }
}

if (!function_exists('get_children_taxonomy_images_card')) {

    /**
     * Return an image of the grid
     * @param $image
     * @param int $currentImage
     * @return void
     */
    function get_children_taxonomy_images_card ($image, int $currentImage) {
        $image_url = get_bravada_child_image_url($image);

        ?>
            <div class="image-overlay">
                <img
                    class="bravada-child-children-taxonomy-image"
                    src="<?php echo $image_url ?>"
                    onclick="openModal();currentSlide(<?php echo $currentImage ?>)"
                >
            </div>
        <?php
    }
}

if (!function_exists('get_children_taxonomy_slides_images_card_for_slideshow')) {

    /**
     * Return an image slide for the slideshow
     * @param $image
     * @param int $currentImage
     * @param int $totalSlides
     * @return void
     */
    function get_children_taxonomy_slides_images_card_for_slideshow($image, int $currentImage, int $totalSlides) {
        // images slider
        $image_url = get_bravada_child_image_url($image);

        ?>
            <div class="bravada_child_slider_mySlides">
                <div class="bravada_child_slider_numbertext"><?php echo $currentImage . " / " . $totalSlides ?></div>
                <img class="bravada_child_image_slider"
                     src="<?php echo $image_url ?>" style="width:100%"
                >
            </div>
        <?php
    }
}

if (!function_exists('get_children_taxonomy_thumbnails_images_card_for_slideshow')) {

    /**
     * Return a thumbnail image for the slideshow
     * @param $image
     * @param int $currentImage
     * @return void
     */
    function get_children_taxonomy_thumbnails_images_card_for_slideshow($image, int $currentImage) {
        // images thumbnail
        $image_url = get_bravada_child_image_url($image);

        ?>
            <div class="bravada_child_slider_column">
                <img class="bravada_child_slider_demo"
                     src="<?php echo $image_url ?>"
                     onclick="currentSlide(<?php echo $currentImage ?>)" alt="<?php echo $image->post_title ?>"
                >
            </div>
        <?php
    }
}

if (!function_exists('get_bravada_child_image_url')) {

    /**
     * Return the right URL for a given image
     * @param $image
     * @return array|string|string[]|null
     */
    function get_bravada_child_image_url ($image) {
        return preg_replace('/wp-content/', 'app', $image->guid);
    }
}

if (!function_exists('bravada_child_get_grid_images_class')) {

    /**
     * Returns a string with the right class to use
     * @param $children_ids
     * @return string
     */
    function bravada_child_get_grid_images_class($children_ids) : string {
        if (count($children_ids) === 1) {
            return 'bravada-child-taxonomypage-taxonomy-1-image';
        } elseif (count($children_ids) === 2) {
            return 'bravada-child-taxonomypage-taxonomy-2-images';
        } else {
            return 'bravada-child-taxonomypage-taxonomy-3-images';
        }
    }
}

if (!function_exists('bravada_child_get_children_for_this_taxonomy')) {

    /**
     * Returns an array of term objects
     * @param int $taxonomy_id
     * @return array
     */
    function bravada_child_get_children_for_this_taxonomy(int $taxonomy_id) : array {
        // Get children of current taxonomy
        $args = array(
            'child_of' => $taxonomy_id,
            'taxonomy' => 'attachment_category',
            'hide_empty' => 0,
            'hierarchical' => true,
            'depth'  => 1,
        );

        return get_terms( $args );
    }
}

if (!function_exists('bravada_child_get_children_ids_for_this_taxonomy')) {

    /**
     * Returns an array of ids
     * @param $current_taxonomy
     * @param array $children_taxonomies
     * @return array
     */
    function bravada_child_get_children_ids_for_this_taxonomy($current_taxonomy, array $children_taxonomies) : array {
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

        return array_diff($children_ids, $taxonomy_ids_to_hide); // We remove the unwanted taxonomies
    }
}

