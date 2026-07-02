<?php
/**
 * Static page template (About, Advertise, Contact, Privacy Policy, etc.).
 */

get_header();
?>

<main class="max-w-4xl mx-auto px-6 py-12">

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'flex flex-col' ); ?>>

			<h1 class="text-3xl md:text-5xl font-serif mb-8 leading-tight">
				<?php the_title(); ?>
			</h1>

			<div>
				<?php the_content(); ?>
			</div>

		</article>
	<?php endwhile; ?>

</main>

<?php
get_footer();
