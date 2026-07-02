<?php
/**
 * Single post template. Replaces BlogDetails.jsx
 */

get_header();
?>

<main class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-12 py-12">

	<div class="lg:col-span-2">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'flex flex-col' ); ?>>

				<h1 class="text-3xl md:text-5xl font-serif mb-4 leading-tight">
					<?php the_title(); ?>
				</h1>

				<div class="text-sm text-neutral-500 uppercase tracking-wide mb-8">
					<?php echo esc_html( get_the_date() ); ?> &middot; <?php the_author(); ?>
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="w-full mb-8">
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto object-cover' ) ); ?>
					</div>
				<?php endif; ?>

				<div>
					<?php the_content(); ?>
				</div>

			</article>
		<?php endwhile; ?>
	</div>

	<aside class="lg:col-span-1 flex flex-col gap-10">

		<!-- Advanced Ads placeholder -->
		<div class="w-full flex justify-center">
			<?php
			if ( function_exists( 'the_ad' ) ) {
				the_ad( 'sidebar-single' ); // Replace slug/ID with your Advanced Ads placement.
			} else {
				echo '<!-- Advanced Ads placeholder: sidebar-single -->';
			}
			?>
		</div>

		<!-- Trending posts -->
		<div>
			<h2 class="text-lg font-serif uppercase tracking-wide mb-4 border-b border-neutral-200 pb-2">
				<?php esc_html_e( 'Trending', 'sassy-strides' ); ?>
			</h2>

			<?php
			$trending_query = new WP_Query(
				array(
					'posts_per_page' => 5,
					'post_status'    => 'publish',
					'orderby'        => 'date', // TODO: swap for a "views" meta_key once a views/analytics plugin is connected.
					'order'          => 'DESC',
					'post__not_in'   => array( get_the_ID() ),
				)
			);
			?>

			<ul class="flex flex-col gap-4">
				<?php if ( $trending_query->have_posts() ) : ?>
					<?php while ( $trending_query->have_posts() ) : $trending_query->the_post(); ?>
						<li class="flex gap-4 items-start">
							<a href="<?php the_permalink(); ?>" class="flex-shrink-0 w-16 h-16 overflow-hidden">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
								<?php endif; ?>
							</a>
							<a href="<?php the_permalink(); ?>" class="text-sm font-medium leading-snug">
								<?php the_title(); ?>
							</a>
						</li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			</ul>
		</div>

	</aside>

</main>

<?php
get_footer();
