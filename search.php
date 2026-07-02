<?php
/**
 * Search results template.
 */

get_header();
?>

<main class="max-w-7xl mx-auto px-6 py-12">

	<h1 class="text-2xl md:text-3xl font-serif mb-10">
		<?php
		printf(
			/* translators: %s: search query. */
			esc_html__( 'Search Results for: %s', 'sassy-strides' ),
			'<span class="italic">' . get_search_query() . '</span>'
		);
		?>
	</h1>

	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'flex flex-col' ); ?>>
					<a href="<?php the_permalink(); ?>" class="block aspect-[4/5] overflow-hidden mb-4">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
						<?php endif; ?>
					</a>
					<h2 class="font-serif text-xl leading-snug mb-2">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<p class="text-sm text-neutral-600">
						<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
					</p>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p class="col-span-full text-center text-neutral-500">
				<?php esc_html_e( 'No results found. Try a different search.', 'sassy-strides' ); ?>
			</p>
		<?php endif; ?>
	</div>

	<?php the_posts_pagination(); ?>

</main>

<?php
get_footer();
