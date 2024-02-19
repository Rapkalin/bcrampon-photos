<?php

add_action('display_category_grid_images_partial', 'bravada_child_get_category_grid_images_partial');
add_action('display_children_category_grid_images_partial', 'bravada_child_display_children_category_grid_images_partial');

if (!function_exists('bravada_child_get_category_grid_images_partial')) {
    function bravada_child_get_category_grid_images_partial(array $children_ids)
    {
        ?><div class="bravada-child-categorypage-category-images"><?php
            foreach ($children_ids as $child_id) {
                $child_category = get_category($child_id);
                get_category_image_card($child_category);
            }
        ?></div><?php
    }
}

if (!function_exists('get_category_image_card')) {
    function get_category_image_card($child_category)
    {
        ?>
            <a class="bravada-child-category-grid-link" href="<?php echo get_home_url() . "/category/$child_category->slug" ?>">

                <div class="image-overlay">
                    <p class="text-overlay"><?php echo $child_category->name ?></p>
                    <img
                        class="bravada-child-category-image"
                        src="<?php echo z_taxonomy_image_url($child_category->term_id) ?>"
                    >
                </div>
            </a>
        <?php
    }
}

if (!function_exists('bravada_child_display_children_category_grid_images_partial')) {
    function bravada_child_display_children_category_grid_images_partial (array $images) {
        ?><div class="bravada-child-categorypage-category-images"><?php
        foreach ($images as $key => $image) {
            $currentImage = $key + 1;
            get_children_category_images_card($image, $currentImage);
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
                        get_children_category_slides_images_card($image, $currentImage, $totalSlides);
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
                    $totalSlides = count($images);
                    foreach ($images as $key => $image) {
                        $currentImage = $key + 1;
                        get_children_category_thumbnails_images_card($image, $currentImage, $totalSlides);
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
                console.log('TRYING TO CLOSE MODAL');
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

if (!function_exists('get_children_category_images_card')) {
    function get_children_category_images_card ($image, int $currentImage) {
        $image_url = get_bravada_child_image_url($image);

        ?>
            <div class="image-overlay">
                <img
                    class="bravada-child-children-category-image"
                    src="<?php echo $image_url ?>"
                    onclick="openModal();currentSlide(<?php echo $currentImage ?>)"
                >
            </div>
        <?php
    }
}


if (!function_exists('get_children_category_images_card')) {
    function get_children_category_images_card ($image, int $currentImage) {
        $image_url = get_bravada_child_image_url($image);

        ?>
            <img
                class="bravada-child-children-category-image"
                src="<?php echo $image_url ?>"
                onclick="openModal();currentSlide(<?php echo $currentImage ?>)"
            >
        <?php
    }
}

if (!function_exists('get_children_category_slides_images_card')) {
    function get_children_category_slides_images_card($image, int $currentImage, int $totalSlides) {
        // images slider
        $image_url = get_bravada_child_image_url($image);

        ?>
            <div class="bravada_child_slider_mySlides">
                <div class="bravada_child_slider_numbertext"><?php echo $currentImage . " / " . $totalSlides ?></div>
                <img class="bravada_child_image_slider"
                     src="<?php echo $image_url ?>" style="width:100%"git diff
                >
            </div>
        <?php
    }
}

if (!function_exists('get_children_category_thumbnails_images_card')) {
    function get_children_category_thumbnails_images_card($image, int $currentImage) {
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
    function get_bravada_child_image_url ($image) {
        return preg_replace('/wp-content/', 'app', $image->guid);
    }
}

