<?php
/**
 * Virtual subcategory template. Replaces SubcategoryPage.jsx.
 *
 * Loaded via the template_include filter in functions.php whenever the
 * sassystrides_subcategory query var is set (see
 * sassystrides_register_subcategory_rewrites()) — i.e. for URLs like
 * /fashion/street-style/. WordPress has no such term to query, so the main
 * query here still resolves to the *parent* category archive; this file
 * fetches that category's posts itself and filters them in PHP by the
 * subcategory's keyword list, exactly like postMatchesKeyword() did in
 * the SPA.
 *
 * Deviation from the SPA: this does not attempt to reproduce the SPA's
 * synthetic /parent/subcategory/postslug permalink for individual posts —
 * article links here use get_permalink(), i.e. whatever permalink
 * structure is actually configured for this WordPress site. That's the
 * correct approach for a real WP install; only the parent/subcategory
 * *listing* URL is a custom rewrite.
 */

get_header();

$sassystrides_parent_slug = get_query_var( 'category_name' );
$sassystrides_sub_slug    = get_query_var( 'sassystrides_subcategory' );
$sassystrides_parent_term = $sassystrides_parent_slug ? get_term_by( 'slug', $sassystrides_parent_slug, 'category' ) : false;
$sassystrides_subcategory = sassystrides_get_subcategory( $sassystrides_parent_slug, $sassystrides_sub_slug );

if ( ! $sassystrides_subcategory || ! $sassystrides_parent_term ) :
	?>
	<main class="editorial-container grid min-h-[60vh] place-items-center text-center">
		<div>
			<p class="micro-label mb-4 text-bronze"><?php bloginfo( 'name' ); ?></p>
			<h1 class="serif-title text-5xl leading-none text-espresso"><?php esc_html_e( 'Section not found.', 'sassy-strides' ); ?></h1>
			<a href="<?php echo esc_url( sassystrides_get_category_url( $sassystrides_parent_slug ? $sassystrides_parent_slug : 'fashion' ) ); ?>" class="btn-cta btn-cta--primary mt-8">
				<?php
				printf(
					/* translators: %s: parent category name */
					esc_html__( 'Back to %s', 'sassy-strides' ),
					esc_html( $sassystrides_parent_term instanceof WP_Term ? $sassystrides_parent_term->name : ucfirst( (string) $sassystrides_parent_slug ) )
				);
				?>
			</a>
		</div>
	</main>
	<?php
	get_footer();
	return;
endif;

$sassystrides_sibling_subcategories = sassystrides_get_subcategories_by_parent( $sassystrides_parent_slug );
$sassystrides_show_featured_ads     = sassystrides_is_featured_page( $sassystrides_parent_slug );

// Toolbar state via query args (progressive enhancement, no client JS required).
$sassystrides_sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'latest';
$sassystrides_view = isset( $_GET['view'] ) ? sanitize_key( wp_unslash( $_GET['view'] ) ) : 'grid';

if ( ! in_array( $sassystrides_sort, array( 'latest', 'popular', 'oldest' ), true ) ) {
	$sassystrides_sort = 'latest';
}
if ( ! in_array( $sassystrides_view, array( 'grid', 'list' ), true ) ) {
	$sassystrides_view = 'grid';
}

// Fetch the parent category's posts, then filter to the ones matching
// this subcategory's keywords (mirrors fetchCategoryPostsQuery + the
// client-side filteredPosts memo in SubcategoryPage.jsx).
$sassystrides_parent_posts_query = new WP_Query(
	array(
		'cat'            => $sassystrides_parent_term->term_id,
		'post_status'    => 'publish',
		'posts_per_page' => 100,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

$sassystrides_filtered_posts = array();
foreach ( $sassystrides_parent_posts_query->posts as $sassystrides_candidate_post ) {
	if ( sassystrides_post_matches_keywords( $sassystrides_candidate_post->ID, $sassystrides_subcategory['keywords'] ) ) {
		$sassystrides_filtered_posts[] = $sassystrides_candidate_post;
	}
}

if ( 'oldest' === $sassystrides_sort ) {
	$sassystrides_filtered_posts = array_reverse( $sassystrides_filtered_posts );
} elseif ( 'popular' === $sassystrides_sort ) {
	usort(
		$sassystrides_filtered_posts,
		function ( $sassystrides_a, $sassystrides_b ) {
			return (int) $sassystrides_b->comment_count - (int) $sassystrides_a->comment_count;
		}
	);
}

$sassystrides_hero_post = ! empty( $sassystrides_filtered_posts ) ? $sassystrides_filtered_posts[0] : ( $sassystrides_parent_posts_query->posts[0] ?? null );
?>

<div class="min-h-screen bg-ivory text-ink">

	<main class="category-page">

		<!-- CategoryHero -->
		<section class="category-hero editorial-container border border-ink/10 bg-paper-grain">
			<div class="category-hero__grid grid min-h-[150px] lg:min-h-[180px] lg:grid-cols-[0.68fr_1.32fr]">
				<div class="category-hero__copy border-b border-ink/10 bg-porcelain/70 px-5 sm:px-6 lg:border-b-0 lg:border-r lg:pl-6 lg:pr-5">
					<h1 class="serif-title text-6xl font-semibold uppercase leading-[0.84] text-espresso sm:text-7xl lg:text-7xl">
						<?php echo esc_html( $sassystrides_subcategory['name'] ); ?>
					</h1>
					<p class="max-w-sm text-sm leading-6 text-ink/78">
						<?php echo esc_html( $sassystrides_subcategory['description'] ); ?>
					</p>
				</div>

				<a href="<?php echo $sassystrides_hero_post ? esc_url( get_permalink( $sassystrides_hero_post ) ) : '#'; ?>" class="category-hero__image group relative min-h-[150px] lg:min-h-[180px]">
					<?php if ( $sassystrides_hero_post && has_post_thumbnail( $sassystrides_hero_post ) ) : ?>
						<?php
						echo get_the_post_thumbnail(
							$sassystrides_hero_post,
							'large',
							array(
								'class'   => 'category-hero__image-el absolute inset-0 h-full w-full object-cover object-center saturate-[0.78] transition duration-700 group-hover:scale-[1.02] group-hover:saturate-100',
								'loading' => 'eager',
							)
						);
						?>
					<?php endif; ?>
					<div class="absolute inset-0 bg-gradient-to-r from-espresso/10 via-transparent to-transparent"></div>
				</a>
			</div>

			<nav class="category-subnav flex overflow-x-auto border-t border-ink/10 bg-ivory">
				<a
					href="<?php echo esc_url( sassystrides_get_category_url( $sassystrides_parent_slug ) ); ?>"
					class="category-subnav__link"
				>
					<?php
					printf(
						/* translators: %s: parent category name */
						esc_html__( 'All %s', 'sassy-strides' ),
						esc_html( $sassystrides_parent_term->name )
					);
					?>
				</a>
				<?php foreach ( $sassystrides_sibling_subcategories as $sassystrides_sibling ) : ?>
					<?php $sassystrides_sibling_is_active = ( $sassystrides_sibling['slug'] === $sassystrides_subcategory['slug'] ); ?>
					<a
						href="<?php echo esc_url( home_url( '/' . $sassystrides_parent_slug . '/' . $sassystrides_sibling['slug'] . '/' ) ); ?>"
						class="category-subnav__link<?php echo $sassystrides_sibling_is_active ? ' is-active' : ''; ?>"
						<?php echo $sassystrides_sibling_is_active ? ' aria-current="page"' : ''; ?>
					>
						<?php echo esc_html( $sassystrides_sibling['name'] ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</section>

		<section class="category-page__layout editorial-container lg:grid-cols-[170px_minmax(0,1fr)] xl:grid-cols-[170px_minmax(0,1fr)_228px]">

			<!-- CategorySidebar -->
			<aside class="category-sidebar">
				<section class="border border-ink/10 bg-porcelain p-5">
					<h3 class="micro-label mb-3 text-espresso">
						<?php
						printf(
							/* translators: %s: parent category name */
							esc_html__( 'Browse %s', 'sassy-strides' ),
							esc_html( $sassystrides_parent_term->name )
						);
						?>
					</h3>
					<nav class="category-sidebar__nav">
						<?php foreach ( $sassystrides_sibling_subcategories as $sassystrides_browse_item ) : ?>
							<?php $sassystrides_browse_is_active = ( $sassystrides_browse_item['slug'] === $sassystrides_subcategory['slug'] ); ?>
							<a
								href="<?php echo esc_url( home_url( '/' . $sassystrides_parent_slug . '/' . $sassystrides_browse_item['slug'] . '/' ) ); ?>"
								class="category-sidebar__link group block border-b border-ink/10 pb-4 transition last:border-0 last:pb-0<?php echo $sassystrides_browse_is_active ? ' is-active' : ''; ?>"
								<?php echo $sassystrides_browse_is_active ? ' aria-current="page"' : ''; ?>
							>
								<span class="category-sidebar__link-title block text-[0.62rem] font-semibold uppercase tracking-[0.12em]">
									<?php echo esc_html( $sassystrides_browse_item['name'] ); ?>
								</span>
								<span class="category-sidebar__link-description mt-1 block text-[0.7rem] leading-4">
									<?php echo esc_html( $sassystrides_browse_item['description'] ); ?>
								</span>
							</a>
						<?php endforeach; ?>
					</nav>
				</section>

				<?php if ( $sassystrides_show_featured_ads ) : ?>
					<div class="category-sidebar__ads">
						<?php foreach ( array( 3, 6 ) as $sassystrides_sidebar_slot ) : ?>
							<?php $sassystrides_sidebar_ad_id = sassystrides_get_ad_id( 'category', $sassystrides_sidebar_slot ); ?>
							<div class="category-sidebar__ad-slot">
								<?php if ( function_exists( 'the_ad' ) && $sassystrides_sidebar_ad_id ) : ?>
									<?php the_ad( $sassystrides_sidebar_ad_id ); ?>
								<?php else : ?>
									<!-- Advanced Ads placeholder: category slot <?php echo esc_html( $sassystrides_sidebar_slot ); ?> -->
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</aside>

			<!-- CategoryPostGrid -->
			<section id="category-posts" class="min-w-0">
				<h2 class="serif-title text-3xl uppercase leading-none text-espresso sm:text-4xl"><?php echo esc_html( $sassystrides_subcategory['name'] ); ?></h2>

				<div class="category-posts__toolbar flex flex-col gap-3 border-y border-ink/10 text-[0.58rem] uppercase tracking-[0.16em] text-taupe sm:flex-row sm:items-center sm:justify-between">
					<form method="get" class="flex items-center gap-3">
						<span><?php esc_html_e( 'Sort By:', 'sassy-strides' ); ?></span>
						<?php if ( 'grid' !== $sassystrides_view ) : ?>
							<input type="hidden" name="view" value="<?php echo esc_attr( $sassystrides_view ); ?>">
						<?php endif; ?>
						<select name="sort" onchange="this.form.submit()" class="bg-transparent font-semibold text-espresso outline-none">
							<option value="latest" <?php selected( $sassystrides_sort, 'latest' ); ?>><?php esc_html_e( 'Latest', 'sassy-strides' ); ?></option>
							<option value="popular" <?php selected( $sassystrides_sort, 'popular' ); ?>><?php esc_html_e( 'Popular', 'sassy-strides' ); ?></option>
							<option value="oldest" <?php selected( $sassystrides_sort, 'oldest' ); ?>><?php esc_html_e( 'Oldest', 'sassy-strides' ); ?></option>
						</select>
					</form>
					<div class="flex items-center justify-between gap-4">
						<span>
							<?php
							printf(
								/* translators: %1$d: result count, shown twice as "Showing 1-N of N results" */
								esc_html__( 'Showing 1-%1$d of %1$d results', 'sassy-strides' ),
								count( $sassystrides_filtered_posts )
							);
							?>
						</span>
						<div class="flex items-center gap-1">
							<a
								href="<?php echo esc_url( add_query_arg( array( 'view' => 'grid' ) ) ); ?>"
								aria-label="<?php esc_attr_e( 'Grid view', 'sassy-strides' ); ?>"
								class="category-view-toggle<?php echo 'grid' === $sassystrides_view ? ' is-active' : ''; ?>"
							>
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<rect width="8" height="8" x="3" y="3" rx="2" />
									<rect width="8" height="8" x="13" y="3" rx="2" />
									<rect width="8" height="8" x="3" y="13" rx="2" />
									<rect width="8" height="8" x="13" y="13" rx="2" />
								</svg>
							</a>
							<a
								href="<?php echo esc_url( add_query_arg( array( 'view' => 'list' ) ) ); ?>"
								aria-label="<?php esc_attr_e( 'List view', 'sassy-strides' ); ?>"
								class="category-view-toggle<?php echo 'list' === $sassystrides_view ? ' is-active' : ''; ?>"
							>
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<line x1="8" x2="21" y1="6" y2="6" />
									<line x1="8" x2="21" y1="12" y2="12" />
									<line x1="8" x2="21" y1="18" y2="18" />
									<line x1="3" x2="3.01" y1="6" y2="6" />
									<line x1="3" x2="3.01" y1="12" y2="12" />
									<line x1="3" x2="3.01" y1="18" y2="18" />
								</svg>
							</a>
						</div>
					</div>
				</div>

				<?php if ( ! empty( $sassystrides_filtered_posts ) ) : ?>
					<div class="editorial-magazine-grid editorial-magazine-grid--secondary<?php echo 'list' === $sassystrides_view ? ' editorial-magazine-grid--list' : ''; ?>">

						<?php if ( $sassystrides_show_featured_ads ) : ?>
							<div class="category-page__top-banner">
								<?php
								$sassystrides_billboard_ad_id = sassystrides_get_ad_id( 'category', 4 );
								if ( function_exists( 'the_ad' ) && $sassystrides_billboard_ad_id ) {
									the_ad( $sassystrides_billboard_ad_id );
								} else {
									echo '<!-- Advanced Ads placeholder: category slot 4 (billboard) -->';
								}
								?>
							</div>
						<?php endif; ?>

						<?php
						$sassystrides_card_index = 0;
						foreach ( $sassystrides_filtered_posts as $sassystrides_grid_post ) :
							global $post;
							$post = $sassystrides_grid_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
							setup_postdata( $post );
							++$sassystrides_card_index;
							?>

							<?php if ( 'list' === $sassystrides_view ) : ?>
								<article class="category-post-card category-post-card--list category-post-card--full-title group min-w-0 overflow-hidden border border-ink/10 bg-porcelain transition duration-300 hover:shadow-editorial">
									<a href="<?php the_permalink(); ?>" class="category-post-card--list__media block min-h-36 overflow-hidden bg-champagne sm:min-h-0">
										<?php
										the_post_thumbnail(
											'medium_large',
											array(
												'class'   => 'h-full w-full object-cover object-center transition duration-300 group-hover:scale-[1.03]',
												'loading' => 'lazy',
											)
										);
										?>
									</a>
									<div class="flex min-w-0 flex-col justify-center overflow-hidden px-3 py-3 sm:px-4">
										<a href="<?php the_permalink(); ?>" class="block min-w-0">
											<h3 class="editorial-article-card__title serif-title"><?php the_title(); ?></h3>
										</a>
										<p class="editorial-article-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
									</div>
								</article>
							<?php else : ?>
								<?php
								get_template_part(
									'template-parts/editorial-article-card',
									null,
									array(
										'variant'       => 'secondary',
										'index'         => $sassystrides_card_index,
										'show_excerpt'  => true,
										'show_category' => false,
										'full_title'    => true,
									)
								);
								?>
							<?php endif; ?>

							<?php if ( $sassystrides_show_featured_ads && 4 === $sassystrides_card_index ) : ?>
								<div class="category-page__mid-ad">
									<?php
									$sassystrides_mid_ad_id = sassystrides_get_ad_id( 'category', 5 );
									if ( function_exists( 'the_ad' ) && $sassystrides_mid_ad_id ) {
										the_ad( $sassystrides_mid_ad_id );
									} else {
										echo '<!-- Advanced Ads placeholder: category slot 5 (inline) -->';
									}
									?>
								</div>
							<?php endif; ?>

						<?php endforeach; ?>

						<?php wp_reset_postdata(); ?>

						<?php if ( $sassystrides_show_featured_ads ) : ?>
							<!-- CategoryThreeCubeAds -->
							<div class="category-page__cube-ads">
								<div class="ad-cube-row">
									<?php foreach ( sassystrides_get_category_cube_ad_ids() as $sassystrides_cube_ad_id ) : ?>
										<?php $sassystrides_cube_ad_html = sassystrides_get_ad_html( $sassystrides_cube_ad_id ); ?>
										<?php if ( '' !== $sassystrides_cube_ad_html ) : ?>
											<div class="ad-cube-link" aria-label="<?php esc_attr_e( 'Sponsored offer', 'sassy-strides' ); ?>">
												<div class="ad-cube-scene">
													<div class="ad-cube">
														<div class="ad-cube__face ad-cube__face--front">
															<div class="ad-cube__face-html"><?php echo $sassystrides_cube_ad_html; // phpcs:ignore WordPress.Security.EscapeOutput -- trusted Advanced Ads markup. ?></div>
														</div>
														<div class="ad-cube__face ad-cube__face--back">
															<div class="ad-cube__face-html"><?php echo $sassystrides_cube_ad_html; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
														</div>
														<div class="ad-cube__face ad-cube__face--left">
															<div class="ad-cube__face-html"><?php echo $sassystrides_cube_ad_html; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
														</div>
														<div class="ad-cube__face ad-cube__face--right">
															<div class="ad-cube__face-html"><?php echo $sassystrides_cube_ad_html; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>

					</div>
				<?php else : ?>
					<div class="category-posts__empty border border-ink/10 bg-porcelain p-10 text-center">
						<p class="micro-label text-bronze"><?php esc_html_e( 'No Stories Found', 'sassy-strides' ); ?></p>
						<h3 class="serif-title mt-3 text-4xl leading-none text-espresso">
							<?php
							printf(
								/* translators: %s: subcategory name */
								esc_html__( 'No articles found in %s yet.', 'sassy-strides' ),
								esc_html( $sassystrides_subcategory['name'] )
							);
							?>
						</h3>
					</div>
				<?php endif; ?>
			</section>

			<!-- TrendingWidget -->
			<?php $sassystrides_trending_posts = array_slice( $sassystrides_filtered_posts, 0, 5 ); ?>
			<aside class="category-page__aside lg:col-span-2 xl:col-span-1">
				<div class="category-page__aside-inner sticky top-24">
					<?php if ( ! empty( $sassystrides_trending_posts ) ) : ?>
						<section class="border border-ink/10 bg-porcelain p-5">
							<h3 class="trending-widget__heading micro-label text-espresso"><?php esc_html_e( 'Trending Now', 'sassy-strides' ); ?></h3>
							<div class="trending-widget__list">
								<?php
								$sassystrides_trending_index = 0;
								foreach ( $sassystrides_trending_posts as $sassystrides_trending_post ) :
									global $post;
									$post = $sassystrides_trending_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
									setup_postdata( $post );
									++$sassystrides_trending_index;
									?>
									<a href="<?php the_permalink(); ?>" class="trending-widget__item group grid grid-cols-[60px_1fr] items-center gap-3 border-b border-ink/10 pb-4 last:border-b-0 last:pb-0">
										<div class="trending-widget__thumb">
											<?php
											the_post_thumbnail(
												'thumbnail',
												array(
													'class'   => 'trending-widget__image h-[4.5rem] w-full object-cover object-center saturate-[0.72] transition duration-500 group-hover:saturate-100',
													'loading' => 'lazy',
												)
											);
											?>
										</div>
										<div class="trending-widget__copy min-w-0">
											<p class="trending-widget__index mb-1.5 text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-taupe">
												<?php echo esc_html( str_pad( (string) $sassystrides_trending_index, 2, '0', STR_PAD_LEFT ) ); ?>
											</p>
											<h4 class="trending-widget__title serif-title text-espresso transition group-hover:text-bronze"><?php the_title(); ?></h4>
										</div>
									</a>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</section>
					<?php endif; ?>
				</div>
			</aside>

		</section>

	</main>

</div>

<?php
get_footer();
