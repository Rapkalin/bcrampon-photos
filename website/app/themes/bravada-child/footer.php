<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of #main and all content
 * after. Calls sidebar-footer.php for bottom widgets.
 *
 * @package Bravada
 */

?>
	</div><!-- #main -->

	<footer id="footer" class="cryout" <?php cryout_schema_microdata( 'footer' );?>>
		<?php cryout_master_topfooter_hook(); ?>
		<div id="footer-top">
			<div class="footer-inside">
				<?php cryout_master_footer_hook(); ?>
            </div><!-- #footer-inside -->
		</div><!-- #footer-top -->

        <script defer >
            function copyrightMessage(event) {
                // Prevent default right-click behavior
                event.preventDefault();

                // Display an alert message
                alert('Copyright protected. This image cannot be downloaded.');

                // Return false to avoid default behavior
                return false;
            }
        </script>
	</footer>
</div><!-- site-wrapper -->
	<?php wp_footer(); ?>
</body>
</html>
