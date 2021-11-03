<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package demaxin_test
 */
$footer_text =  esc_html__( ' &copy;', 'demaxin_test' ) . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . 'com' . ' ' .
    esc_html__( 'Made with ❤️ by Nikolai Nasibullin', 'demaxin_test' );

?>

	<footer id="colophon" class="site-footer">
		<div class="site-info">
            <span class="demaxin-copyright"><?php echo wp_kses_post( $footer_text ); ?></span>
        </div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
