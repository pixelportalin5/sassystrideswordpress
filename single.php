<?php
/**
 * Single post template. Merges BlogDetails.jsx (outer layout) and
 * ArticleContentWithAds.jsx (in-article ad injection).
 * Dynamic ad lookup is title-based so the template no longer depends on
 * Advanced Ads placement IDs.
 *
 * Note: the SPA's ArticleSkeleton / isError / "Article not found" branches
 * have no WordPress equivalent here — a nonexistent slug never reaches
 * single.php (WordPress routes it to 404.php instead).
 */

get_header();

while ( have_posts() ) :
	the_post();

	$sassystrides_post_id      = get_the_ID();
	$sassystrides_categories   = get_the_category();
	$sassystrides_primary_cat  = ! empty( $sassystrides_categories ) ? $sassystrides_categories[0] : null;
	$sassystrides_category_url = $sassystrides_primary_cat ? get_category_link( $sassystrides_primary_cat ) : sassystrides_get_category_url( 'fashion' );
	$sassystrides_category_name = $sassystrides_primary_cat ? $sassystrides_primary_cat->name : __( 'Editorial', 'sassy-strides' );

	$sassystrides_reading_time = sassystrides_get_reading_time( get_the_content() );

	$sassystrides_tags = get_the_tags();
	if ( empty( $sassystrides_tags ) ) {
		$sassystrides_tags = array( $sassystrides_category_name );
	} else {
		$sassystrides_tags = wp_list_pluck( $sassystrides_tags, 'name' );
	}

	// Same 3-post "related" fetch reused for the sidebar, prev/next nav, and the bottom grid.
	$sassystrides_related_query = new WP_Query(
		array(
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'post__not_in'        => array( $sassystrides_post_id ),
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'category__in'        => $sassystrides_primary_cat ? array( $sassystrides_primary_cat->term_id ) : array(),
		)
	);
	$sassystrides_related_posts   = $sassystrides_related_query->posts;
	$sassystrides_previous_post   = isset( $sassystrides_related_posts[0] ) ? $sassystrides_related_posts[0] : null;
	$sassystrides_next_post       = isset( $sassystrides_related_posts[1] ) ? $sassystrides_related_posts[1] : null;

	$sassystrides_is_rss_feed_article = $sassystrides_primary_cat && 'rss-feeds' === $sassystrides_primary_cat->slug;

	// Article body ad injection (ArticleContentWithAds.jsx).
	$sassystrides_article_html   = sassystrides_normalize_article_html( get_the_content() );
	$sassystrides_article_blocks = sassystrides_split_article_blocks( $sassystrides_article_html );
	?>

	<div class="min-h-screen bg-ivory text-ink<?php echo $sassystrides_is_rss_feed_article ? ' blog-detail--rss-feed' : ''; ?>">
		<main class="space-y-7 pb-10">

			<div class="editorial-container pt-4">
				<!-- AdSlot page="category" slot={5} variant="category-inline" -->
				<section class="editorial-ad editorial-ad--inline editorial-container" aria-label="<?php esc_attr_e( 'Sponsored placement', 'sassy-strides' ); ?>">
					<aside class="ad-banner ad-banner--horizontal" aria-label="<?php esc_attr_e( 'Advertisement', 'sassy-strides' ); ?>">
						<div class="ad-banner__frame">
							<?php render_dynamic_ad( 'post-top' ); ?>
						</div>
					</aside>
				</section>
			</div>

			<div class="editorial-container">
				<nav class="flex flex-wrap items-center gap-2 border-y border-ink/10 py-3 text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-taupe">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="transition hover:text-bronze"><?php esc_html_e( 'Home', 'sassy-strides' ); ?></a>
					<span>/</span>
					<a href="<?php echo esc_url( $sassystrides_category_url ); ?>" class="transition hover:text-bronze"><?php echo esc_html( $sassystrides_category_name ); ?></a>
					<span>/</span>
					<span><?php esc_html_e( 'Article', 'sassy-strides' ); ?></span>
					<span>/</span>
					<span class="line-clamp-1 text-espresso"><?php the_title(); ?></span>
				</nav>
			</div>

			<section class="editorial-container grid gap-8 lg:grid-cols-[minmax(0,0.7fr)_minmax(280px,0.3fr)]">
				<article class="min-w-0">
					<header class="border border-ink/10 bg-paper-grain px-6 py-8 sm:px-10 lg:px-14 lg:py-12">
						<p class="micro-label text-bronze"><?php echo esc_html( $sassystrides_category_name ); ?></p>
						<h1 class="serif-title blog-detail__title mt-5 max-w-5xl font-semibold leading-[1.12] text-espresso">
							<?php the_title(); ?>
						</h1>

						<div class="mt-8 grid gap-5 border-y border-ink/10 py-5 xl:grid-cols-[1fr_auto] xl:items-center">
							<div class="flex flex-wrap items-center gap-4">
								<img
									src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=240&q=80"
									alt="Sassy Strides"
									class="h-14 w-14 rounded-full object-cover saturate-[0.85]"
									loading="lazy"
									decoding="async"
								>
								<div>
									<p class="text-sm font-semibold text-espresso"><?php esc_html_e( 'By Sassy Strides', 'sassy-strides' ); ?></p>
									<p class="mt-1 text-[0.62rem] uppercase tracking-[0.18em] text-taupe">
										<?php
										printf(
											/* translators: %d: reading time in minutes */
											esc_html__( '%d Min Read', 'sassy-strides' ),
											(int) $sassystrides_reading_time
										);
										?>
									</p>
								</div>
							</div>

							<!-- ShareButtons -->
							<div class="flex flex-wrap items-center gap-2">
								<button type="button" aria-label="<?php esc_attr_e( 'Share article', 'sassy-strides' ); ?>" class="grid h-10 w-10 place-items-center border border-ink/10 bg-porcelain transition hover:bg-espresso hover:text-porcelain">
									<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<circle cx="18" cy="5" r="3" /><circle cx="6" cy="12" r="3" /><circle cx="18" cy="19" r="3" />
										<line x1="8.59" x2="15.42" y1="13.51" y2="17.49" /><line x1="15.41" x2="8.59" y1="6.51" y2="10.49" />
									</svg>
								</button>
								<button type="button" aria-label="<?php esc_attr_e( 'Send article', 'sassy-strides' ); ?>" class="grid h-10 w-10 place-items-center border border-ink/10 bg-porcelain transition hover:bg-espresso hover:text-porcelain">
									<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
										<path d="m21.854 2.147-10.94 10.939" />
									</svg>
								</button>
								<button type="button" aria-label="<?php esc_attr_e( 'Email article', 'sassy-strides' ); ?>" class="grid h-10 w-10 place-items-center border border-ink/10 bg-porcelain transition hover:bg-espresso hover:text-porcelain">
									<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<rect width="20" height="16" x="2" y="4" rx="2" />
										<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
									</svg>
								</button>
								<button type="button" aria-label="<?php esc_attr_e( 'Copy link', 'sassy-strides' ); ?>" class="grid h-10 w-10 place-items-center border border-ink/10 bg-porcelain transition hover:bg-espresso hover:text-porcelain">
									<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<path d="M9 17H7A5 5 0 0 1 7 7h2" /><path d="M15 7h2a5 5 0 1 1 0 10h-2" /><line x1="8" x2="16" y1="12" y2="12" />
									</svg>
								</button>
								<?php // TODO: wire up Web Share API / mailto: / clipboard JS for these four buttons. ?>
							</div>
						</div>
					</header>

					<figure class="overflow-hidden border-x border-b border-ink/10 bg-champagne">
						<?php
						the_post_thumbnail(
							'full',
							array(
								'class'   => 'article-hero-image w-full object-cover object-center saturate-[0.82]',
								'loading' => 'eager',
							)
						);
						?>
					</figure>

					<section class="border-x border-b border-ink/10 bg-porcelain px-6 py-8 shadow-soft sm:px-10 lg:px-16">

						<!-- ArticleContentWithAds -->
						<?php if ( ! empty( $sassystrides_article_blocks ) ) : ?>
							<div class="article-content">
								<?php foreach ( $sassystrides_article_blocks as $sassystrides_block_index => $sassystrides_block_html ) : ?>
									<div class="article-content__block"><?php echo $sassystrides_block_html; ?></div>
									<?php if ( 0 === $sassystrides_block_index && count( $sassystrides_article_blocks ) > 1 ) : ?>
										<section class="editorial-ad editorial-ad--inline editorial-container" aria-label="<?php esc_attr_e( 'Sponsored placement', 'sassy-strides' ); ?>">
											<aside class="ad-banner ad-banner--horizontal" aria-label="<?php esc_attr_e( 'Advertisement', 'sassy-strides' ); ?>">
												<div class="ad-banner__frame">
													<?php render_dynamic_ad( 'post-middle' ); ?>
												</div>
											</aside>
										</section>
									<?php endif; ?>
									<?php if ( 3 === $sassystrides_block_index && count( $sassystrides_article_blocks ) > 4 ) : ?>
										<section class="editorial-ad editorial-ad--inline editorial-container" aria-label="<?php esc_attr_e( 'Sponsored placement', 'sassy-strides' ); ?>">
											<aside class="ad-banner ad-banner--horizontal" aria-label="<?php esc_attr_e( 'Advertisement', 'sassy-strides' ); ?>">
												<div class="ad-banner__frame">
													<?php render_dynamic_ad( 'post-bottom' ); ?>
												</div>
											</aside>
										</section>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<div class="mt-10 border-t border-ink/10 pt-7">
							<p class="micro-label mb-4 text-espresso"><?php esc_html_e( 'Tags', 'sassy-strides' ); ?></p>
							<div class="flex flex-wrap gap-2">
								<?php foreach ( $sassystrides_tags as $sassystrides_tag ) : ?>
									<span class="border border-ink/10 bg-parchment px-4 py-2 text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-taupe">
										<?php echo esc_html( wp_strip_all_tags( $sassystrides_tag ) ); ?>
									</span>
								<?php endforeach; ?>
							</div>
						</div>
					</section>
				</article>

				<!-- Sidebar -->
				<aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">

					<!-- AdSlot page="category" slot={2} variant="category-medium" -->
					<div class="featured-page-ad featured-page-ad--medium">
						<?php render_dynamic_ad( 'post-bottom' ); ?>
					</div>

					<!-- TrendingWidget posts={relatedPosts} -->
					<?php if ( ! empty( $sassystrides_related_posts ) ) : ?>
						<section class="border border-ink/10 bg-porcelain p-5">
							<h3 class="trending-widget__heading micro-label text-espresso"><?php esc_html_e( 'Trending Now', 'sassy-strides' ); ?></h3>
							<div class="trending-widget__list">
								<?php foreach ( $sassystrides_related_posts as $sassystrides_trend_index => $sassystrides_trend_post ) : ?>
									<?php
									global $post;
									$post = $sassystrides_trend_post;
									setup_postdata( $post );
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
												<?php echo esc_html( str_pad( (string) ( $sassystrides_trend_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>
											</p>
											<h4 class="trending-widget__title serif-title text-espresso transition group-hover:text-bronze"><?php the_title(); ?></h4>
										</div>
									</a>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</section>
					<?php endif; ?>
				</aside>
			</section>

			<!-- PostNavCard previous / next -->
			<section class="editorial-container grid gap-4 border-y border-ink/10 py-7 md:grid-cols-2">
				<?php if ( $sassystrides_previous_post ) : ?>
					<?php
					global $post;
					$post = $sassystrides_previous_post;
					setup_postdata( $post );
					?>
					<a href="<?php the_permalink(); ?>" class="group grid items-center gap-2 border border-ink/10 bg-porcelain p-2 transition hover:bg-parchment sm:grid-cols-[58px_1fr]">
						<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-14 w-14 shrink-0 object-cover saturate-[0.75] transition group-hover:saturate-100', 'loading' => 'lazy' ) ); ?>
						<span class="flex min-w-0 flex-col justify-center">
							<span class="mb-1 inline-flex items-center gap-1 text-[0.52rem] font-semibold uppercase tracking-[0.18em] text-taupe">
								<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<path d="m12 19-7-7 7-7" /><path d="M19 12H5" />
								</svg>
								<?php esc_html_e( 'Previous Post', 'sassy-strides' ); ?>
							</span>
							<span class="serif-title line-clamp-2 text-base leading-tight text-espresso transition group-hover:text-bronze"><?php the_title(); ?></span>
						</span>
					</a>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<div class="hidden md:block"></div>
				<?php endif; ?>

				<?php if ( $sassystrides_next_post ) : ?>
					<?php
					global $post;
					$post = $sassystrides_next_post;
					setup_postdata( $post );
					?>
					<a href="<?php the_permalink(); ?>" class="group grid items-center gap-2 border border-ink/10 bg-porcelain p-2 transition hover:bg-parchment sm:grid-cols-[1fr_58px] sm:text-right">
						<span class="flex min-w-0 flex-col justify-center">
							<span class="mb-1 inline-flex items-center justify-end gap-1 text-[0.52rem] font-semibold uppercase tracking-[0.18em] text-taupe">
								<?php esc_html_e( 'Next Post', 'sassy-strides' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<path d="M5 12h14" /><path d="m12 5 7 7-7 7" />
								</svg>
							</span>
							<span class="serif-title line-clamp-2 text-base leading-tight text-espresso transition group-hover:text-bronze"><?php the_title(); ?></span>
						</span>
						<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-14 w-14 shrink-0 object-cover saturate-[0.75] transition group-hover:saturate-100', 'loading' => 'lazy' ) ); ?>
					</a>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<div class="hidden md:block"></div>
				<?php endif; ?>
			</section>

			<!-- Related Posts (PostFeedWithAds renders 0 ads for exactly 3 items at interval 3, so it's a plain grid) -->
			<?php if ( ! empty( $sassystrides_related_posts ) ) : ?>
				<section class="editorial-container py-4">
					<div class="mb-5 flex items-center justify-between border-b border-ink/10 pb-3">
						<h2 class="micro-label text-espresso"><?php esc_html_e( 'Related Posts', 'sassy-strides' ); ?></h2>
						<span class="text-[0.62rem] font-semibold uppercase tracking-[0.2em] text-taupe">
							<?php
							printf(
								/* translators: %s: category name */
								esc_html__( 'From %s', 'sassy-strides' ),
								esc_html( $sassystrides_category_name )
							);
							?>
						</span>
					</div>
					<div class="grid gap-5 md:grid-cols-3">
						<?php foreach ( $sassystrides_related_posts as $sassystrides_related_index => $sassystrides_related_post ) : ?>
							<?php
							global $post;
							$post = $sassystrides_related_post;
							setup_postdata( $post );
							get_template_part(
								'template-parts/editorial-article-card',
								null,
								array(
									'variant'       => 'compact',
									'index'         => $sassystrides_related_index + 6,
									'show_excerpt'  => false,
									'show_category' => true,
									'full_title'    => false,
								)
							);
							?>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</section>
			<?php endif; ?>

		</main>
	</div>

<?php endwhile; ?>

<?php
get_footer();
