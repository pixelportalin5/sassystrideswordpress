<?php
/**
 * Category archive template. Replaces CategoryPage.jsx
 *
 * Architectural notes (deviations from the literal SPA source):
 * - Subcategories (CategoryHero subnav, CategorySidebar "Browse" list) are
 *   NOT real WordPress taxonomy terms in the original app — they're a
 *   static list with keyword arrays (constants/subcategories.js) used to
 *   filter posts already in the parent category. sassystrides_get_subcategories_by_parent()
 *   in functions.php mirrors that list so these links match the virtual
 *   /parent/subcategory/ routes handled by category-subcategory.php.
 * - The sort dropdown / grid-list toggle are wired to ?sort= and ?view=
 *   query args (server-rendered, no client JS/state) instead of React
 *   useState, since there's no client fetch to re-run.
 * - CategoryThreeCubeAds's 4 faces (front/back/left/right) all show the
 *   *same* ad creative rotating in 3D, matching the React version's
 *   single useAd(adId) call reused across all 4 <CubeFaceContent> renders.
 *   sassystrides_get_ad_html() captures the_ad() output once per cube so
 *   it isn't rendered (and tracked) 4 separate times.
 */

get_header();

$sassystrides_current_term = get_queried_object();
$sassystrides_parent_term  = ( $sassystrides_current_term instanceof WP_Term && $sassystrides_current_term->parent )
	? get_term( $sassystrides_current_term->parent, 'category' )
	: $sassystrides_current_term;

$sassystrides_subcategories = ( $sassystrides_parent_term instanceof WP_Term )
	? sassystrides_get_subcategories_by_parent( $sassystrides_parent_term->slug )
	: array();

$sassystrides_route_slug       = ( $sassystrides_parent_term instanceof WP_Term ) ? $sassystrides_parent_term->slug : '';
$sassystrides_show_featured_ads = sassystrides_is_featured_page( $sassystrides_route_slug );

// Toolbar state via query args (progressive enhancement, no client JS required).
$sassystrides_sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'latest';
$sassystrides_view = isset( $_GET['view'] ) ? sanitize_key( wp_unslash( $_GET['view'] ) ) : 'grid';

if ( ! in_array( $sassystrides_sort, array( 'latest', 'popular', 'oldest' ), true ) ) {
	$sassystrides_sort = 'latest';
}
if ( ! in_array( $sassystrides_view, array( 'grid', 'list' ), true ) ) {
	$sassystrides_view = 'grid';
}

$sassystrides_orderby = 'date';
$sassystrides_order   = 'DESC';
if ( 'oldest' === $sassystrides_sort ) {
	$sassystrides_order = 'ASC';
} elseif ( 'popular' === $sassystrides_sort ) {
	$sassystrides_orderby = 'comment_count';
	$sassystrides_order   = 'DESC';
}

$sassystrides_category_query = new WP_Query(
	array(
		'cat'            => $sassystrides_current_term->term_id,
		'post_status'    => 'publish',
		'posts_per_page' => 20,
		'orderby'        => $sassystrides_orderby,
		'order'          => $sassystrides_order,
	)
);

$sassystrides_hero_thumbnail_id = $sassystrides_category_query->have_posts() ? $sassystrides_category_query->posts[0]->ID : 0;
?>

<div class="min-h-screen bg-ivory text-ink">

	<main class="category-page">

		<!-- CategoryHero -->
		<section class="category-hero editorial-container border border-ink/10 bg-paper-grain">
			<div class="category-hero__grid grid min-h-[150px] lg:min-h-[180px] lg:grid-cols-[0.68fr_1.32fr]">
				<div class="category-hero__copy border-b border-ink/10 bg-porcelain/70 px-5 sm:px-6 lg:border-b-0 lg:border-r lg:pl-6 lg:pr-5">
					<h1 class="serif-title text-6xl font-semibold uppercase leading-[0.84] text-espresso sm:text-7xl lg:text-7xl">
						<?php single_cat_title(); ?>
					</h1>
					<p class="max-w-sm text-sm leading-6 text-ink/78">
						<?php
						$sassystrides_category_desc = category_description();
						if ( $sassystrides_category_desc ) {
							echo wp_kses_post( $sassystrides_category_desc );
						} else {
							printf(
								/* translators: %s: category name */
								esc_html__( 'A curated edit of the newest %s stories from Sassy Strides.', 'sassy-strides' ),
								esc_html( strtolower( single_cat_title( '', false ) ) )
							);
						}
						?>
					</p>
				</div>

				<a href="<?php echo $sassystrides_hero_thumbnail_id ? esc_url( get_permalink( $sassystrides_hero_thumbnail_id ) ) : '#'; ?>" class="category-hero__image group relative min-h-[150px] lg:min-h-[180px]">
					<?php if ( $sassystrides_hero_thumbnail_id && has_post_thumbnail( $sassystrides_hero_thumbnail_id ) ) : ?>
						<?php
						echo get_the_post_thumbnail(
							$sassystrides_hero_thumbnail_id,
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
				<?php $sassystrides_all_is_active = ( $sassystrides_parent_term instanceof WP_Term && $sassystrides_current_term->term_id === $sassystrides_parent_term->term_id ); ?>
				<a
					href="<?php echo esc_url( get_category_link( $sassystrides_parent_term ) ); ?>"
					class="category-subnav__link<?php echo $sassystrides_all_is_active ? ' is-active' : ''; ?>"
					<?php echo $sassystrides_all_is_active ? ' aria-current="page"' : ''; ?>
				>
					<?php
					printf(
						/* translators: %s: parent category name */
						esc_html__( 'All %s', 'sassy-strides' ),
						esc_html( $sassystrides_parent_term instanceof WP_Term ? $sassystrides_parent_term->name : '' )
					);
					?>
				</a>
				<?php foreach ( $sassystrides_subcategories as $sassystrides_subcategory ) : ?>
					<a
						href="<?php echo esc_url( home_url( '/' . $sassystrides_route_slug . '/' . $sassystrides_subcategory['slug'] . '/' ) ); ?>"
						class="category-subnav__link"
					>
						<?php echo esc_html( $sassystrides_subcategory['name'] ); ?>
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
							esc_html( $sassystrides_parent_term instanceof WP_Term ? $sassystrides_parent_term->name : 'Fashion' )
						);
						?>
					</h3>
					<nav class="category-sidebar__nav">
						<?php foreach ( $sassystrides_subcategories as $sassystrides_browse_item ) : ?>
							<a
								href="<?php echo esc_url( home_url( '/' . $sassystrides_route_slug . '/' . $sassystrides_browse_item['slug'] . '/' ) ); ?>"
								class="category-sidebar__link group block border-b border-ink/10 pb-4 transition last:border-0 last:pb-0"
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
						<?php foreach ( sassystrides_get_category_sidebar_ad_ids() as $sassystrides_sidebar_ad_id ) : ?>
							<div class="category-sidebar__ad-slot">
								<?php if ( function_exists( 'the_ad' ) && $sassystrides_sidebar_ad_id ) : ?>
									<?php the_ad( $sassystrides_sidebar_ad_id ); ?>
								<?php else : ?>
									<!-- Advanced Ads placeholder: category sidebar ad <?php echo esc_html( $sassystrides_sidebar_ad_id ); ?> -->
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</aside>

			<!-- CategoryPostGrid -->
			<section id="category-posts" class="min-w-0">
				<h2 class="serif-title text-3xl uppercase leading-none text-espresso sm:text-4xl"><?php single_cat_title(); ?></h2>

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
								(int) $sassystrides_category_query->post_count
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

				<?php if ( $sassystrides_category_query->have_posts() ) : ?>
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
						while ( $sassystrides_category_query->have_posts() ) :
							$sassystrides_category_query->the_post();
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

						<?php endwhile; ?>

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
							<?php esc_html_e( 'No articles found in this category.', 'sassy-strides' ); ?>
						</h3>
					</div>
				<?php endif; ?>
			</section>

			<!-- TrendingWidget -->
			<?php
			$sassystrides_trending_query = new WP_Query(
				array(
					'cat'            => $sassystrides_current_term->term_id,
					'post_status'    => 'publish',
					'posts_per_page' => 5,
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
			?>
			<aside class="category-page__aside lg:col-span-2 xl:col-span-1">
				<div class="category-page__aside-inner sticky top-24">
					<?php if ( $sassystrides_trending_query->have_posts() ) : ?>
						<section class="border border-ink/10 bg-porcelain p-5">
							<h3 class="trending-widget__heading micro-label text-espresso"><?php esc_html_e( 'Trending Now', 'sassy-strides' ); ?></h3>
							<div class="trending-widget__list">
								<?php
								$sassystrides_trending_index = 0;
								while ( $sassystrides_trending_query->have_posts() ) :
									$sassystrides_trending_query->the_post();
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
								<?php endwhile; ?>
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
