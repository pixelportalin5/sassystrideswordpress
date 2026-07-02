<?php
/**
 * 404 error template.
 */

get_header();
?>

<main class="max-w-7xl mx-auto px-6 py-24 flex flex-col items-center justify-center text-center">

	<h1 class="text-6xl font-serif mb-4">404</h1>

	<p class="text-lg text-neutral-600 mb-8">
		<?php esc_html_e( 'Not Found. The page you are looking for does not exist.', 'sassy-strides' ); ?>
	</p>

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block px-6 py-3 border border-neutral-900 text-sm uppercase tracking-wide">
		<?php esc_html_e( 'Back to Homepage', 'sassy-strides' ); ?>
	</a>

</main>

<?php
get_footer();
