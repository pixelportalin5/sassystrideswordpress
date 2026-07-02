<?php
/**
 * Front page template. Replaces Home.jsx
 */

get_header();
?>

<main class="max-w-7xl mx-auto px-6">

	<!-- Hero: latest post tagged 'hero-featured' -->
	<?php
	$hero_query = new WP_Query(
		array(
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'tag'            => 'hero-featured',
		)
	);
	?>

	<?php if ( $hero_query->have_posts() ) : ?>
		<?php while ( $hero_query->have_posts() ) : $hero_query->the_post(); ?>
			<section class="relative w-full h-[70vh] mb-16">
				<a href="<?php the_permalink(); ?>" class="block w-full h-full relative overflow-hidden">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
					<?php endif; ?>
					<div class="absolute inset-0 bg-black/30 flex flex-col justify-end p-10">
						<span class="text-white text-xs uppercase tracking-widest mb-2">
							<?php echo esc_html( get_the_category()[0]->name ?? '' ); ?>
						</span>
						<h1 class="text-white text-4xl md:text-6xl font-serif max-w-3xl">
							<?php the_title(); ?>
						</h1>
					</div>
				</a>
			</section>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>

	<!-- Promo ticker -->
	<?php get_template_part( 'template-parts/promo-ticker' ); ?>

	<!-- Featured Stories -->
	<section class="mb-16">
		<h2 class="text-2xl font-serif mb-8 uppercase tracking-wide">
			<?php esc_html_e( 'Featured Stories', 'sassy-strides' ); ?>
		</h2>

		<?php
		$featured_query = new WP_Query(
			array(
				'posts_per_page' => 4,
				'post_status'    => 'publish',
				'ignore_sticky_posts' => true,
			)
		);
		?>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
			<?php if ( $featured_query->have_posts() ) : ?>
				<?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
					<article class="flex flex-col">
						<a href="<?php the_permalink(); ?>" class="block aspect-[4/5] overflow-hidden mb-4">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
							<?php endif; ?>
						</a>
						<h3 class="font-serif text-lg leading-snug mb-2">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<p class="text-sm text-neutral-600">
							<?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?>
						</p>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</section>

	<!-- Homepage Advanced Ads slot -->
	<div class="w-full flex justify-center my-16">
		<?php
		if ( function_exists( 'the_ad' ) ) {
			the_ad( 'homepage-leaderboard' ); // Replace slug/ID with your Advanced Ads placement.
		} else {
			echo '<!-- Advanced Ads placeholder: homepage-leaderboard -->';
		}
		?>
	</div>

</main>

<?php
get_footer();
