<?php

add_action('display_taxonomy_grid_images_partial', 'bravada_child_get_taxonomy_grid_images_partial');
add_action('display_children_taxonomy_grid_images_partial', 'bravada_child_display_children_taxonomy_grid_images_partial');

if (!function_exists('bravada_child_get_taxonomy_grid_images_partial')) {

    /**
     * Return a grid of taxonomy cards
     *
     * @param array $children_ids
     * @return void
     */
    function bravada_child_get_taxonomy_grid_images_partial(array $children_ids)
    {
        $gridClass = bravada_child_get_grid_images_class($children_ids);

        ?><div class="<?php echo $gridClass?>"><?php
            foreach ($children_ids as $child_id) {
                $child_taxonomy = get_term_by('term_taxonomy_id', $child_id);
                get_taxonomy_image_card($child_taxonomy, $children_ids);
            }
        ?></div><?php
    }
}

if (!function_exists('get_taxonomy_image_card')) {

    /**
     * Return a taxonomy card
     *
     * @param $child_taxonomy
     * @param $children_ids
     * @return void
     */
    function get_taxonomy_image_card($child_taxonomy, $children_ids)
    {
        if (count($children_ids) === 1) {
            $size = 'bravada-child-grid-single-thumb';
        } elseif (count($children_ids) === 2) {
            $size = 'bravada-child-grid-double-thumb';
        } else {
            $size = 'bravada-child-grid-thumb';
        }

        ?>
            <a class="bravada-child-taxonomy-grid-link" href="<?php echo get_term_link($child_taxonomy) ?>">

                <div class="image-overlay">
                    <p class="text-overlay"><?php echo $child_taxonomy->name ?></p>
                    <img
                        class="bravada-child-taxonomy-image"
                        src="<?php echo z_taxonomy_image_url($child_taxonomy->term_id, $size) ?>"
                    >
                </div>
            </a>
        <?php
    }
}

if (!function_exists('bravada_child_display_children_taxonomy_grid_images_partial')) {

    /**
     * Return the grid images of a taxonomy
     * And handle the slideshow config
     *
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
            </div>

            <div class="slideshow-buttons">
                <!-- Boutons Pause et Lecture -->
                <a id="bravada-child-btn-pause" class="bravada-child-btn" onclick="stopSlideshow()">
                    <i class="fa-regular fa-circle-pause"></i>
                </a>

                <a id="bravada-child-btn-play" class="bravada-child-btn" onclick="startSlideshow()">
                    <i class="fa-regular fa-circle-play"></i>
                </a>
            </div>

            <!-- Next/previous controls -->
            <a class="bravada_child_slider_prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="bravada_child_slider_next" onclick="plusSlides(1)">&#10095;</a>

            <!-- Caption text -->
            <div class="bravada_child_slider_caption-container">
                <p id="bravada_child_slider_caption"></p>
            </div>

            <!-- Thumbnail image controls
            <div class="bravada_child_slider_thumbnail">
                 <?php /*
                foreach ($images as $key => $image) {
                    $currentImage = $key + 1;
                    get_children_taxonomy_thumbnails_images_card_for_slideshow($image, $currentImage);
                }
               */ ?>
            </div>-->
        </div>

        <script>
            var slideIndex = 1;
            var pauseButton = document.getElementById("bravada-child-btn-pause");
            var playButton = document.getElementById("bravada-child-btn-play");

            showSlides(slideIndex);

            // Open the Modal
            function openModal() {
                document.getElementById("bravada_child_slider_myModal").style.display = "block";
                startSlideshow(); // Démarrer le slideshow à l'ouverture
            }

            // Close the Modal
            function closeModal() {
                document.getElementById("bravada_child_slider_myModal").style.display = "none";
                stopSlideshow(); // Arrêter le slideshow à la fermeture
            }

            // Next/previous controls
            function plusSlides(n) {
                showSlides(slideIndex += n); // Modification ici
            }

            // Thumbnail image controls
            function currentSlide(n) {
                showSlides(slideIndex = n);
            }

            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName("bravada_child_slider_mySlides");
                // var dots = document.getElementsByClassName("bravada_child_slider_demo");
                var captionText = document.getElementById("bravada_child_slider_caption");
                if (n > slides.length) {slideIndex = 1}
                if (n < 1) {slideIndex = slides.length}
                for (i = 0; i < slides.length; i++) {
                    slides[i].className = slides[i].className.replace(" active", "");
                    slides[i].style.display = "block";
                }
                /* Hide the thumbnail part
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" bravada_child_slider_active", "");
                }*/
                slides[slideIndex-1].className += " active";
                /*dots[slideIndex-1].className += " bravada_child_slider_active";*/
                /*captionText.innerHTML = dots[slideIndex-1].alt;*/
            }

            function startSlideshow() {
                // call function plusSlides() every 4 seconds
                playButton.classList.add("bravada-child-btn-on");
                pauseButton.classList.remove("bravada-child-btn-on");

                slideInterval = setInterval(function() {
                    plusSlides(1);
                }, 4000 // 4 seconds
                );

                // disable buttons for 1 second
                setTimeout(() => {
                        pauseButton.disabled = true;
                        playButton.disabled = true;
                    }, 1000 // 1 seconds
                )

            }

            function stopSlideshow() {
                pauseButton.classList.add("bravada-child-btn-on");
                playButton.classList.remove("bravada-child-btn-on");

                // disable buttons for 1 second
                setTimeout(() => {
                    pauseButton.disabled = true;
                    playButton.disabled = true;
                    }, 1000 // 1 second
                )

                clearInterval(slideInterval);
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
        $image_url = get_bravada_child_image_url($image, 'bravada-child-grid-thumb');
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
     * @param string|null $size
     * @return array|string|string[]|null
     */
    function get_bravada_child_image_url ($image, string $size = null) {
        if ($size) {
            return wp_get_attachment_image_src($image->ID, $size)[0];
        }

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
        $taxonomies_to_hide = [];
        $taxonomy_ids_to_hide = [];

        foreach ($taxonomies_to_hide as $taxonomy_slug) {
            $taxonomy_ids_to_hide[] = get_term_by('slug', $taxonomy_slug, 'attachment_category')->term_taxonomy_id;
        }

        return array_diff($children_ids, $taxonomy_ids_to_hide); // We remove the unwanted taxonomies
    }
}

if (!function_exists('bravada_child_get_region_travel_categories')) {
    /**
     * Get all regions
     * Return an array of wp_terms for the corresponding regions
     *
     * @return array|object|stdClass[]|null
     */
    function bravada_child_get_region_travel_categories()
    {
        global $wpdb;

        // Get the parent id corresponding to the category slug: 'bcrampon-travels'
        $parent_category = $wpdb->get_results( "SELECT term_id FROM {$wpdb->prefix}terms WHERE slug = 'bcrampon-travels'");
        $parent_category_term_id = $parent_category[0]->term_id;

        // Get all the region from the parent category Travels (slug: 'bcrampon-travels')
        $media_terms = $wpdb->get_results( "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'attachment_category' AND parent = $parent_category_term_id", OBJECT );

        $term_ids = [];
        foreach ($media_terms as $term) {
            $term_ids[] = $term->term_id;
        }

        $sql_term_ids = implode( ',', $term_ids);
        $region_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}terms WHERE term_id IN ($sql_term_ids)");

        return $region_categories;
    }
}

if (!function_exists('bravada_child_get_country_travel_categories_from_region')) {
    /**
     * Get all countries for a given region
     * Returns an array of wp_terms for the corresponding country
     *
     * @param stdClass $region_category
     * @return array|object|stdClass[]|null
     */
    function bravada_child_get_country_travel_categories_from_region(stdClass $region_category)
    {
        global $wpdb;

        $children_term_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}term_taxonomy WHERE parent = $region_category->term_id" );

        $children_term_ids = [];
        foreach ($children_term_categories as $children_term_category) {
            $children_term_ids[] = $children_term_category->term_id;
        }
        $sql_children_term_ids = implode( ',', $children_term_ids);
        $country_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}terms WHERE term_id IN ($sql_children_term_ids)" );

        return $country_categories;
    }
}

if (!function_exists('bravada_child_get_all_country_travel_categories')) {
    /**
     * Get all countries for each region
     * Return an array of countries
     * Each country contains an array of wp_terms for the corresponding countries
     *
     * @param array $region_categories
     * @return array
     */
    function bravada_child_get_all_country_travel_categories(array $region_categories) : array
    {
        // Get all countries for each region
        $country_categories = [];
        foreach ($region_categories as $region_category) {
            $country_categories[$region_category->name] = bravada_child_get_country_travel_categories_from_region($region_category);
        }

        return $country_categories;
    }
}

if (!function_exists('bravada_child_get_city_travel_categories_from_country')) {
    /**
     * Get all city terms
     * Returns an array of wp_terms for the corresponding cities
     *
     * @param stdClass $country_category
     * @return array|object|stdClass[]|null
     */
    function bravada_child_get_city_travel_categories_from_country(stdClass $country_category) : array
    {
        global $wpdb;

        $children_term_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}term_taxonomy WHERE parent = $country_category->term_id" );

        $children_term_ids = [];
        foreach ($children_term_categories as $children_term_category) {
            $children_term_ids[] = $children_term_category->term_id;
        }
        $sql_children_term_ids = implode( ',', $children_term_ids);
        $city_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}terms WHERE term_id IN ($sql_children_term_ids)" );

        return $city_categories;
    }
}

if (!function_exists('bravada_child_get_all_city_travel_categories_per_region')) {
    /**
     * Get all cities for a given country
     * Returns an array of sorted countries that contains an array of wp_terms for the corresponding cities
     *
     * @param array $country_categories
     * @return array
     */
    function bravada_child_get_all_city_travel_categories_per_region(array $country_categories) : array
    {
        // Get all cities for each country
        $country_cities = [];
        foreach ($country_categories as $country_category) {
            $country_cities[$country_category->name] = bravada_child_get_city_travel_categories_from_country($country_category);
        }


        return $country_cities;
    }
}

if (!function_exists('bravada_child_get_all_sorted_cities')) {
    /**
     * Get all cities and sort them by countries and regions
     * Returns an array of sorted countries per region
     * The array of each country contains an array of wp_terms for the corresponding cities
     *
     * @param array $country_categories
     * @return array
     */
    function bravada_child_get_all_sorted_cities(array $country_categories) : array
    {
        $sorted_cities = [];
        foreach ($country_categories as $key => $country_category) {
            $sorted_cities[$key] = bravada_child_get_all_city_travel_categories_per_region($country_category);
        }

        return $sorted_cities;
    }
}

