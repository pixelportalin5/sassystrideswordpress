<?php
/**
 * Front page template. Replaces Home.jsx
 *
 * Note: the SPA's `loading` / `HomeSkeleton` state and the dev-only
 * `fetchMeta` fallback-source banner have no WordPress equivalent
 * (server-rendered Loop, no client fetch) and are intentionally omitted.
 */

get_header();

$sassystrides_has_any_posts = (bool) wp_count_posts()->publish;
?>

<?php if ( ! $sassystrides_has_any_posts ) : ?>

	<!-- Empty state: mirrors Home.jsx's "!loading && !posts.length" branch -->
	<div class="min-h-screen bg-ivory">
		<main class="editorial-container grid min-h-[70vh] place-items-center text-center">
			<div class="max-w-2xl px-6">
				<p class="micro-label mb-4 text-bronze"><?php esc_html_e( 'Editorial Desk', 'sassy-strides' ); ?></p>
				<h1 class="serif-title text-6xl leading-none text-espresso">
					<?php esc_html_e( 'Stories are temporarily unavailable.', 'sassy-strides' ); ?>
				</h1>
				<p class="mt-5 text-taupe">
					<?php esc_html_e( 'Please check back shortly.', 'sassy-strides' ); ?>
				</p>
			</div>
		</main>
	</div>

<?php else : ?>

	<?php
	/*
	 * The `min-h-screen bg-ivory text-ink` wrapper below matches Home.jsx's
	 * root div. It only spans the front-page content (between header.php and
	 * footer.php) since <header>/<footer> now live in their own templates.
	 */
	?>
	<div class="min-h-screen bg-ivory text-ink">

		<main class="homepage-magazine">

			<?php
			// Prefer a post tagged 'hero-featured' for editorial control; if
			// none exists yet, fall back to the single latest published post
			// (Home.jsx / HeroSection.jsx's real default: hero = posts[0]),
			// so the hero never renders blank on a fresh site.
			$sassystrides_hero_query = new WP_Query(
				array(
					'posts_per_page' => 1,
					'post_status'    => 'publish',
					'tag'            => 'hero-featured',
				)
			);

			if ( ! $sassystrides_hero_query->have_posts() ) {
				$sassystrides_hero_query = new WP_Query(
					array(
						'posts_per_page'      => 1,
						'post_status'         => 'publish',
						'orderby'             => 'date',
						'order'               => 'DESC',
						'ignore_sticky_posts' => true,
					)
				);
			}
			?>

			<?php if ( $sassystrides_hero_query->have_posts() ) : ?>

				<?php $sassystrides_hero_query->the_post(); ?>
				<?php $sassystrides_hero_id = get_the_ID(); ?>

				<section class="hero-section editorial-container grid border-x border-b border-ink/10 bg-paper-grain">
					<div class="hero-section__content border-b border-ink/10 px-6 sm:px-8 lg:border-b-0 lg:border-r lg:px-7 xl:px-9">
						<p class="micro-label mb-4 text-bronze sm:mb-5"><?php esc_html_e( 'Inspire. Elevate. Empower.', 'sassy-strides' ); ?></p>
						<a href="<?php the_permalink(); ?>" class="hero-section__headline-link">
							<h1 class="hero-section__headline serif-title font-semibold text-espresso"><?php the_title(); ?></h1>
						</a>
						<p class="hero-section__excerpt text-taupe"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
						<div class="hero-section__actions">
							<a href="<?php the_permalink(); ?>" class="btn-cta btn-cta--primary"><?php esc_html_e( 'Explore Story', 'sassy-strides' ); ?></a>
							<a href="#featured" class="btn-cta btn-cta--secondary"><?php esc_html_e( 'View Issues', 'sassy-strides' ); ?></a>
						</div>
					</div>

					<a href="<?php the_permalink(); ?>" class="hero-section__image hero-section__image-container group border-b border-ink/10 lg:border-b-0 lg:border-r">
						<?php the_post_thumbnail( 'large', array( 'class' => 'hero-section__image-el saturate-[0.82] transition duration-700 group-hover:scale-[1.02] group-hover:saturate-100' ) ); ?>
					</a>

					<aside class="hero-section__sidebar hero-section__sidebar-column bg-porcelain/70">
						<div class="hero-section__sidebar-header px-5 py-5 sm:px-6 sm:py-5">
							<p class="micro-label text-bronze"><?php esc_html_e( 'Latest Stories', 'sassy-strides' ); ?></p>
						</div>
						<div class="hero-section__stories-list divide-y divide-ink/10">
							<?php
							$sassystrides_hero_sidebar_query = new WP_Query(
								array(
									'posts_per_page' => 3,
									'post_status'    => 'publish',
									'orderby'        => 'date',
									'order'          => 'DESC',
									'post__not_in'   => array( $sassystrides_hero_id ),
									'ignore_sticky_posts' => true,
								)
							);
							$sassystrides_story_index = 0;
							?>
							<?php while ( $sassystrides_hero_sidebar_query->have_posts() ) : $sassystrides_hero_sidebar_query->the_post(); ?>
								<?php ++$sassystrides_story_index; ?>
								<a href="<?php the_permalink(); ?>" class="hero-section__story group px-5 py-4 transition hover:bg-parchment/70 sm:px-6 sm:py-5">
									<span class="hero-section__story-index serif-title"><?php echo esc_html( str_pad( (string) $sassystrides_story_index, 2, '0', STR_PAD_LEFT ) ); ?></span>
									<span class="hero-section__story-copy">
										<span class="micro-label mb-2 block text-taupe"><?php echo esc_html( get_the_category()[0]->name ?? '' ); ?></span>
										<span class="hero-section__story-title serif-title text-espresso transition group-hover:text-bronze"><?php the_title(); ?></span>
									</span>
								</a>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</div>
						<div class="hero-section__view-all-wrap">
							<a href="#featured" class="hero-section__view-all btn-cta btn-cta--secondary">
								<?php esc_html_e( 'View All Stories', 'sassy-strides' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<path d="M7 7h10v10" />
									<path d="M7 17 17 7" />
								</svg>
							</a>
						</div>
					</aside>
				</section>

				<?php wp_reset_postdata(); ?>

			<?php else : ?>

				<!-- HeroSection's own empty state: unreachable in practice now that
				     the query above falls back to the latest post, but kept as a
				     defensive fallback matching HeroSection.jsx's `if (!hero)` branch. -->
				<section class="hero-section editorial-container grid min-h-[300px] place-items-center border-x border-b border-ink/10 bg-paper-grain lg:min-h-0">
					<p class="micro-label text-taupe"><?php esc_html_e( 'Loading Editorial Stories', 'sassy-strides' ); ?></p>
				</section>
				<?php $sassystrides_hero_id = 0; ?>

			<?php endif; ?>

			<!-- AdSlot page="homepage" slot={1} variant="hero-billboard" -->
			<div class="homepage-ad-hero-billboard editorial-container">
				<?php
				$sassystrides_ad_id_1 = sassystrides_get_ad_id( 'homepage', 1 );
				if ( function_exists( 'the_ad' ) && $sassystrides_ad_id_1 ) {
					the_ad( $sassystrides_ad_id_1 );
				} else {
					echo '<!-- Advanced Ads placeholder: homepage slot 1 (ad ID 1549) -->';
				}
				?>
			</div>

			<?php get_template_part( 'template-parts/promo-ticker' ); ?>

			<?php
			/*
			 * HomepageCategorySection( posts.slice(3, 14), categories ) + CategoryGrid.
			 * Each tile now queries its own category directly via WP_Query instead
			 * of searching a shared posts array for a matching categoryName.
			 */
			$sassystrides_category_tiles = array(
				array(
					'name'        => 'Fashion',
					'slug'        => 'fashion',
					'description' => 'Runway, Street Style & Luxury Looks',
				),
				array(
					'name'        => 'Trends',
					'slug'        => 'trends',
					'description' => "What's New, What's Next",
				),
				array(
					'name'        => 'Beauty',
					'slug'        => 'beauty',
					'description' => 'Expert Tips, Reviews & New Trends',
				),
				array(
					'name'        => 'News',
					'slug'        => 'news',
					'description' => 'Events, Celebrities & Industry Updates',
				),
				array(
					'name'        => 'Lifestyle',
					'slug'        => 'lifestyle',
					'description' => 'Style for Every Part of Your Life',
				),
			);
			?>
			<section class="homepage-category-section editorial-container">
				<div class="homepage-category-section__top">
					<div class="homepage-category-section__main">

						<div class="homepage-category-grid">
							<?php foreach ( $sassystrides_category_tiles as $sassystrides_tile ) : ?>
								<?php
								$sassystrides_tile_posts = get_posts(
									array(
										'category_name'  => $sassystrides_tile['slug'],
										'posts_per_page' => 1,
										'post_status'    => 'publish',
										'orderby'        => 'date',
										'order'          => 'DESC',
									)
								);
								$sassystrides_tile_post = ! empty( $sassystrides_tile_posts ) ? $sassystrides_tile_posts[0] : null;
								?>
								<a href="<?php echo esc_url( sassystrides_get_category_url( $sassystrides_tile['slug'] ) ); ?>" class="homepage-category-grid__tile group">
									<?php if ( $sassystrides_tile_post && has_post_thumbnail( $sassystrides_tile_post->ID ) ) : ?>
										<?php
										echo get_the_post_thumbnail(
											$sassystrides_tile_post->ID,
											'medium_large',
											array(
												'class'   => 'homepage-category-grid__image',
												'loading' => 'lazy',
											)
										);
										?>
									<?php endif; ?>
									<div class="homepage-category-grid__overlay"></div>
									<div class="homepage-category-grid__content">
										<p class="homepage-category-grid__title serif-title uppercase leading-none text-porcelain"><?php echo esc_html( $sassystrides_tile['name'] ); ?></p>
										<p class="homepage-category-grid__description"><?php echo esc_html( $sassystrides_tile['description'] ); ?></p>
										<span class="homepage-category-grid__cta">
											<?php esc_html_e( 'Explore', 'sassy-strides' ); ?>
											<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
												<path d="M7 7h10v10" />
												<path d="M7 17 17 7" />
											</svg>
										</span>
									</div>
								</a>
							<?php endforeach; ?>
						</div>

						<div class="homepage-category-section__billboard">
							<!-- LazyAdSlot page="homepage" slot={2} variant="inline-billboard" -->
							<div class="lazy-ad-slot">
								<div class="homepage-ad-inline-billboard">
									<?php
									$sassystrides_ad_id_2 = sassystrides_get_ad_id( 'homepage', 2 );
									if ( function_exists( 'the_ad' ) && $sassystrides_ad_id_2 ) {
										the_ad( $sassystrides_ad_id_2 );
									} else {
										echo '<!-- Advanced Ads placeholder: homepage slot 2 (ad ID 1553) -->';
									}
									?>
								</div>
							</div>
						</div>
					</div>

					<aside class="homepage-category-section__sidebar" aria-label="<?php esc_attr_e( 'Sidebar advertisements', 'sassy-strides' ); ?>">
						<!-- LazyAdSlot page="homepage" slot={6} variant="sidebar-skyscraper" -->
						<div class="lazy-ad-slot">
							<div class="homepage-ad-sidebar-skyscraper">
								<?php
								$sassystrides_ad_id_6 = sassystrides_get_ad_id( 'homepage', 6 );
								if ( function_exists( 'the_ad' ) && $sassystrides_ad_id_6 ) {
									the_ad( $sassystrides_ad_id_6 );
								} else {
									echo '<!-- Advanced Ads placeholder: homepage slot 6 (ad ID 1547) -->';
								}
								?>
							</div>
						</div>
						<!-- LazyAdSlot page="homepage" slot={7} variant="sidebar-skyscraper" -->
						<div class="lazy-ad-slot">
							<div class="homepage-ad-sidebar-skyscraper">
								<?php
								$sassystrides_ad_id_7 = sassystrides_get_ad_id( 'homepage', 7 );
								if ( function_exists( 'the_ad' ) && $sassystrides_ad_id_7 ) {
									the_ad( $sassystrides_ad_id_7 );
								} else {
									echo '<!-- Advanced Ads placeholder: homepage slot 7 (ad ID 1554) -->';
								}
								?>
							</div>
						</div>
					</aside>
				</div>
			</section>

			<?php
			$sassystrides_featured_query = new WP_Query(
				array(
					'posts_per_page'      => 10,
					'post_status'         => 'publish',
					'orderby'             => 'date',
					'order'               => 'DESC',
					'post__not_in'        => array( $sassystrides_hero_id ),
					'ignore_sticky_posts' => true,
				)
			);
			?>
			<?php if ( $sassystrides_featured_query->have_posts() ) : ?>
				<section id="featured" class="featured-stories">
					<div class="featured-stories__container">
						<header class="featured-stories__header">
							<h2 class="featured-stories__title"><?php esc_html_e( 'Featured Stories', 'sassy-strides' ); ?></h2>
							<a href="<?php echo esc_url( sassystrides_get_category_url( 'fashion' ) ); ?>" class="featured-stories__view-all">
								<?php esc_html_e( 'View All Stories', 'sassy-strides' ); ?> <span aria-hidden="true">→</span>
							</a>
						</header>

						<div class="editorial-magazine-grid editorial-magazine-grid--featured">
							<?php
							$sassystrides_i = 0;
							while ( $sassystrides_featured_query->have_posts() ) :
								$sassystrides_featured_query->the_post();
								++$sassystrides_i;
								get_template_part(
									'template-parts/editorial-article-card',
									null,
									array(
										'variant'       => 'featured',
										'index'         => $sassystrides_i,
										'show_excerpt'  => false,
										'show_category' => false,
										'full_title'    => true,
									)
								);
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<!-- LazyAdSlot page="homepage" slot={3} variant="inline-banner" -->
			<div class="lazy-ad-slot">
				<div class="homepage-ad-inline-banner editorial-container">
					<?php
					$sassystrides_ad_id_3 = sassystrides_get_ad_id( 'homepage', 3 );
					if ( function_exists( 'the_ad' ) && $sassystrides_ad_id_3 ) {
						the_ad( $sassystrides_ad_id_3 );
					} else {
						echo '<!-- Advanced Ads placeholder: homepage slot 3 (ad ID 1552) -->';
					}
					?>
				</div>
			</div>

			<?php
			/*
			 * StyleMoodCarousel( posts.slice(0, 14) ). The 8 "mood" labels are
			 * decorative, not real taxonomy terms, so — matching the original,
			 * which just cycles through a general recent-posts pool rather than
			 * querying per-mood — this pulls one general pool of recent posts
			 * and cycles through it the same way `posts[index % posts.length]` did.
			 */
			$sassystrides_style_moods = array( 'Old Money', 'Quiet Luxury', 'Parisian Chic', 'Streetwear', 'Scandi Minimal', 'Coastal Summer', 'NYC Corporate', 'Clean Girl' );

			$sassystrides_mood_query = new WP_Query(
				array(
					'posts_per_page'      => 8,
					'post_status'         => 'publish',
					'orderby'             => 'date',
					'order'               => 'DESC',
					'post__not_in'        => array( $sassystrides_hero_id ),
					'ignore_sticky_posts' => true,
				)
			);
			$sassystrides_mood_posts      = $sassystrides_mood_query->posts;
			$sassystrides_mood_post_count = count( $sassystrides_mood_posts );
			?>
			<section id="style-mood" class="style-mood-section editorial-container">
				<div class="style-mood-section__header">
					<h2 class="micro-label text-espresso"><?php esc_html_e( 'Browse By Style Mood', 'sassy-strides' ); ?></h2>
					<a href="<?php echo esc_url( sassystrides_get_category_url( 'trends' ) ); ?>" class="style-mood-section__view-all">
						<?php esc_html_e( 'View All', 'sassy-strides' ); ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M7 7h10v10" />
							<path d="M7 17 17 7" />
						</svg>
					</a>
				</div>

				<div class="style-mood-section__track">
					<?php foreach ( $sassystrides_style_moods as $sassystrides_mood_index => $sassystrides_mood_label ) : ?>
						<?php
						$sassystrides_mood_post = $sassystrides_mood_post_count > 0
							? $sassystrides_mood_posts[ $sassystrides_mood_index % $sassystrides_mood_post_count ]
							: null;
						$sassystrides_mood_href = $sassystrides_mood_post ? get_permalink( $sassystrides_mood_post ) : sassystrides_get_category_url( 'trends' );
						?>
						<a href="<?php echo esc_url( $sassystrides_mood_href ); ?>" class="style-mood-section__card group">
							<div class="style-mood-section__image-wrap">
								<?php if ( $sassystrides_mood_post && has_post_thumbnail( $sassystrides_mood_post->ID ) ) : ?>
									<?php
									echo get_the_post_thumbnail(
										$sassystrides_mood_post->ID,
										'medium',
										array(
											'class'   => 'style-mood-section__image',
											'loading' => 'lazy',
										)
									);
									?>
								<?php endif; ?>
							</div>
							<p class="style-mood-section__label"><?php echo esc_html( $sassystrides_mood_label ); ?></p>
						</a>
					<?php endforeach; ?>
				</div>
			</section>

			<!-- LazyAdSlot page="homepage" slot={4} variant="style-mood-sponsor" -->
			<div class="lazy-ad-slot">
				<div class="homepage-ad-style-mood editorial-container">
					<?php
					$sassystrides_ad_id_4 = sassystrides_get_ad_id( 'homepage', 4 );
					if ( function_exists( 'the_ad' ) && $sassystrides_ad_id_4 ) {
						the_ad( $sassystrides_ad_id_4 );
					} else {
						echo '<!-- Advanced Ads placeholder: homepage slot 4 (ad ID 1550) -->';
					}
					?>
				</div>
			</div>

			<?php
			/*
			 * HomepageCategoryDirectory( posts.slice(0, 14) ). Subcategories come
			 * from real WP child categories (same approach as category.php)
			 * instead of the hardcoded SUBCATEGORIES JS array.
			 */
			$sassystrides_directory_parent_slugs = array( 'fashion', 'beauty', 'lifestyle', 'trends', 'news' );
			$sassystrides_directory_labels       = array(
				'fashion'   => 'Fashion',
				'beauty'    => 'Beauty',
				'lifestyle' => 'Lifestyle',
				'trends'    => 'Trends',
				'news'      => 'News',
			);
			?>
			<section class="homepage-category-directory editorial-container" aria-label="<?php esc_attr_e( 'Browse categories', 'sassy-strides' ); ?>">
				<div class="homepage-category-directory__grid">
					<?php foreach ( $sassystrides_directory_parent_slugs as $sassystrides_dir_slug ) : ?>
						<?php
						$sassystrides_dir_parent_term = get_term_by( 'slug', $sassystrides_dir_slug, 'category' );
						$sassystrides_dir_subcats     = $sassystrides_dir_parent_term
							? get_categories(
								array(
									'parent'     => $sassystrides_dir_parent_term->term_id,
									'hide_empty' => false,
								)
							)
							: array();

						$sassystrides_dir_posts = get_posts(
							array(
								'category_name'  => $sassystrides_dir_slug,
								'posts_per_page' => 1,
								'post_status'    => 'publish',
								'orderby'        => 'date',
								'order'          => 'DESC',
							)
						);
						$sassystrides_dir_post = ! empty( $sassystrides_dir_posts ) ? $sassystrides_dir_posts[0] : null;
						?>
						<div class="homepage-category-directory__column">
							<div class="homepage-category-directory__column-main">
								<div class="homepage-category-directory__copy">
									<a href="<?php echo esc_url( sassystrides_get_category_url( $sassystrides_dir_slug ) ); ?>" class="homepage-category-directory__title">
										<?php echo esc_html( $sassystrides_directory_labels[ $sassystrides_dir_slug ] ); ?>
									</a>

									<ul class="homepage-category-directory__links">
										<?php foreach ( $sassystrides_dir_subcats as $sassystrides_dir_sub ) : ?>
											<li>
												<a
													href="<?php echo esc_url( get_category_link( $sassystrides_dir_sub ) ); ?>"
													class="homepage-category-directory__link"
													title="<?php echo esc_attr( $sassystrides_dir_sub->description ); ?>"
												>
													<?php echo esc_html( $sassystrides_dir_sub->name ); ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>

									<a href="<?php echo esc_url( sassystrides_get_category_url( $sassystrides_dir_slug ) ); ?>" class="homepage-category-directory__view-all">
										<?php esc_html_e( 'View All', 'sassy-strides' ); ?>
										<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
											<path d="M7 7h10v10" />
											<path d="M7 17 17 7" />
										</svg>
									</a>
								</div>

								<?php if ( $sassystrides_dir_post ) : ?>
									<a href="<?php echo esc_url( get_permalink( $sassystrides_dir_post ) ); ?>" class="homepage-category-directory__feature">
										<?php
										echo get_the_post_thumbnail(
											$sassystrides_dir_post->ID,
											'thumbnail',
											array(
												'class'   => 'homepage-category-directory__feature-image',
												'loading' => 'lazy',
											)
										);
										?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="homepage-category-directory__banner-row" aria-label="<?php esc_attr_e( 'Directory advertisements', 'sassy-strides' ); ?>">
					<?php foreach ( array( 5, 6 ) as $sassystrides_dir_slot ) : ?>
						<!-- LazyAdSlot page="homepage" slot={<?php echo esc_html( $sassystrides_dir_slot ); ?>} variant="grid-card" -->
						<div class="lazy-ad-slot">
							<div class="grid-ad-card grid-ad-card--bare">
								<?php
								$sassystrides_dir_ad_id = sassystrides_get_ad_id( 'homepage', $sassystrides_dir_slot );
								if ( function_exists( 'the_ad' ) && $sassystrides_dir_ad_id ) {
									the_ad( $sassystrides_dir_ad_id );
								} else {
									echo '<!-- Advanced Ads placeholder: homepage slot ' . esc_html( $sassystrides_dir_slot ) . ' -->';
								}
								?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>

			<?php
			$sassystrides_latest_query = new WP_Query(
				array(
					'posts_per_page'      => 10,
					'post_status'         => 'publish',
					'orderby'             => 'date',
					'order'               => 'DESC',
					'post__not_in'        => array( $sassystrides_hero_id ),
					'ignore_sticky_posts' => true,
				)
			);
			?>
			<?php if ( $sassystrides_latest_query->have_posts() ) : ?>
				<section class="homepage-latest-grid" aria-label="<?php esc_attr_e( 'Latest stories', 'sassy-strides' ); ?>">
					<header class="homepage-latest-grid__header">
						<h2 class="homepage-latest-grid__title"><?php esc_html_e( 'Latest From The Edit', 'sassy-strides' ); ?></h2>
						<a href="<?php echo esc_url( sassystrides_get_category_url( 'fashion' ) ); ?>" class="featured-stories__view-all">
							<?php esc_html_e( 'View All', 'sassy-strides' ); ?> <span aria-hidden="true">→</span>
						</a>
					</header>
					<div class="editorial-magazine-grid editorial-magazine-grid--secondary">
						<?php
						$sassystrides_j = 0;
						while ( $sassystrides_latest_query->have_posts() ) :
							$sassystrides_latest_query->the_post();
							++$sassystrides_j;
							get_template_part(
								'template-parts/editorial-article-card',
								null,
								array(
									'variant'       => 'secondary',
									'index'         => $sassystrides_j,
									'show_excerpt'  => false,
									'show_category' => false,
									'full_title'    => true,
								)
							);
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>

		</main>

		<?php
		/*
		 * HomepageAppendSections( posts ). Composes AppendFashionCities,
		 * AppendSponsoredStory( posts.slice(5, 6) ), AppendInstagramInspo( posts.slice(0, 10) ).
		 * Note: the source imports a separate stylesheet
		 * (styles/homepage-append.css) that isn't part of your 3800-line
		 * index.css — bring that over too if these sections need it.
		 */
		?>
		<div class="hp-append">

			<!-- AppendFashionCities -->
			<section class="hp-append__section hp-append__section--cities" aria-label="<?php esc_attr_e( 'Fashion cities', 'sassy-strides' ); ?>">
				<div class="hp-append__container">
					<h2 class="hp-append__cities-title"><?php esc_html_e( 'Fashion Cities', 'sassy-strides' ); ?></h2>

					<div class="hp-append__cities-row">
						<div class="hp-append__cities-ad hp-append__cities-ad--left" aria-label="<?php esc_attr_e( 'Advertisement', 'sassy-strides' ); ?>">
							<div class="grid-ad-card grid-ad-card--bare">
								<?php
								$sassystrides_cities_ad_left = sassystrides_get_ad_id( 'homepage', 5 );
								if ( function_exists( 'the_ad' ) && $sassystrides_cities_ad_left ) {
									the_ad( $sassystrides_cities_ad_left );
								} else {
									echo '<!-- Advanced Ads placeholder: homepage slot 5 (ad ID 1551) -->';
								}
								?>
							</div>
						</div>

						<div class="hp-append__cities-grid">
							<?php
							$sassystrides_fashion_cities = array(
								array(
									'name'  => 'Paris',
									'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=640&q=80',
								),
								array(
									'name'  => 'Milan',
									'image' => 'https://images.unsplash.com/photo-1513581166391-887a96ddeafd?auto=format&fit=crop&w=640&q=80',
								),
								array(
									'name'  => 'London',
									'image' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=640&q=80',
								),
								array(
									'name'  => 'New York',
									'image' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?auto=format&fit=crop&w=640&q=80',
								),
								array(
									'name'  => 'Los Angeles',
									'image' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?auto=format&fit=crop&w=640&q=80',
								),
							);
							?>
							<?php foreach ( $sassystrides_fashion_cities as $sassystrides_city ) : ?>
								<a href="<?php echo esc_url( sassystrides_get_category_url( 'lifestyle' ) ); ?>" class="hp-append__city-card">
									<img
										src="<?php echo esc_url( $sassystrides_city['image'] ); ?>"
										alt="<?php echo esc_attr( $sassystrides_city['name'] . ' fashion city' ); ?>"
										class="hp-append__city-image"
										loading="lazy"
										decoding="async"
									>
									<span class="hp-append__city-overlay" aria-hidden="true"></span>
									<span class="hp-append__city-name"><?php echo esc_html( $sassystrides_city['name'] ); ?></span>
								</a>
							<?php endforeach; ?>
						</div>

						<div class="hp-append__cities-ad hp-append__cities-ad--right" aria-label="<?php esc_attr_e( 'Advertisement', 'sassy-strides' ); ?>">
							<div class="grid-ad-card grid-ad-card--bare">
								<?php
								$sassystrides_cities_ad_right = sassystrides_get_ad_id( 'homepage', 6 );
								if ( function_exists( 'the_ad' ) && $sassystrides_cities_ad_right ) {
									the_ad( $sassystrides_cities_ad_right );
								} else {
									echo '<!-- Advanced Ads placeholder: homepage slot 6 (ad ID 1547) -->';
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- AppendSponsoredStory( posts.slice(5, 6) ) -->
			<?php
			$sassystrides_sponsored_query = new WP_Query(
				array(
					'posts_per_page'      => 1,
					'offset'              => 5,
					'post_status'         => 'publish',
					'orderby'             => 'date',
					'order'               => 'DESC',
					'post__not_in'        => array( $sassystrides_hero_id ),
					'ignore_sticky_posts' => true,
				)
			);
			$sassystrides_sponsored_post = $sassystrides_sponsored_query->have_posts() ? $sassystrides_sponsored_query->posts[0] : null;
			?>
			<section class="hp-append__sponsored" aria-label="<?php esc_attr_e( 'Sponsored story', 'sassy-strides' ); ?>">
				<div class="hp-append__container">
					<div class="hp-append__sponsored-banner">
						<div class="hp-append__sponsored-layout">
							<div class="hp-append__sponsored-copy">
								<p class="hp-append__sponsored-badge"><?php esc_html_e( 'Sponsored', 'sassy-strides' ); ?></p>
								<?php if ( $sassystrides_sponsored_post ) : ?>
									<a href="<?php echo esc_url( get_permalink( $sassystrides_sponsored_post ) ); ?>" class="hp-append__sponsored-headline">
										<?php echo esc_html( get_the_title( $sassystrides_sponsored_post ) ); ?>
									</a>
									<a href="<?php echo esc_url( get_permalink( $sassystrides_sponsored_post ) ); ?>" class="hp-append__sponsored-cta">
										<?php esc_html_e( 'Read Story', 'sassy-strides' ); ?>
										<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
											<path d="M7 7h10v10" />
											<path d="M7 17 17 7" />
										</svg>
									</a>
								<?php else : ?>
									<p class="hp-append__sponsored-headline"><?php esc_html_e( 'The Quiet Luxury Brands Everyone Is Wearing', 'sassy-strides' ); ?></p>
									<span class="hp-append__sponsored-cta">
										<?php esc_html_e( 'Read Story', 'sassy-strides' ); ?>
										<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
											<path d="M7 7h10v10" />
											<path d="M7 17 17 7" />
										</svg>
									</span>
								<?php endif; ?>
							</div>

							<div class="hp-append__sponsored-image-wrap">
								<?php if ( $sassystrides_sponsored_post ) : ?>
									<a href="<?php echo esc_url( get_permalink( $sassystrides_sponsored_post ) ); ?>">
										<?php
										echo get_the_post_thumbnail(
											$sassystrides_sponsored_post->ID,
											'large',
											array(
												'class'   => 'hp-append__sponsored-image',
												'loading' => 'lazy',
											)
										);
										?>
									</a>
								<?php else : ?>
									<div class="hp-append__sponsored-ad">
										<!-- LazyAdSlot page="homepage" slot={11} variant="inline-banner" -->
										<div class="lazy-ad-slot">
											<div class="homepage-ad-inline-banner editorial-container">
												<?php
												$sassystrides_sponsored_ad_id = sassystrides_get_ad_id( 'homepage', 11 );
												if ( function_exists( 'the_ad' ) && $sassystrides_sponsored_ad_id ) {
													the_ad( $sassystrides_sponsored_ad_id );
												} else {
													echo '<!-- Advanced Ads placeholder: homepage slot 11 (ad ID 1588) -->';
												}
												?>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- AppendInstagramInspo( posts.slice(0, 10) ) -->
			<?php
			$sassystrides_instagram_query = new WP_Query(
				array(
					'posts_per_page'      => 10,
					'post_status'         => 'publish',
					'orderby'             => 'date',
					'order'               => 'DESC',
					'post__not_in'        => array( $sassystrides_hero_id ),
					'ignore_sticky_posts' => true,
				)
			);
			?>
			<?php if ( $sassystrides_instagram_query->have_posts() ) : ?>
				<section class="hp-append__section" aria-label="<?php esc_attr_e( 'Instagram inspiration', 'sassy-strides' ); ?>">
					<div class="hp-append__container">
						<div class="hp-append__instagram-header">
							<h2 class="hp-append__instagram-title"><?php esc_html_e( 'Instagram Inspo', 'sassy-strides' ); ?></h2>
							<span class="hp-append__instagram-handle">@thesassy_strides</span>
						</div>
						<div class="hp-append__instagram-grid">
							<?php
							while ( $sassystrides_instagram_query->have_posts() ) :
								$sassystrides_instagram_query->the_post();
								?>
								<a href="<?php the_permalink(); ?>" class="hp-append__instagram-item group">
									<?php
									the_post_thumbnail(
										'medium',
										array(
											'class'   => 'hp-append__instagram-image',
											'loading' => 'lazy',
										)
									);
									?>
								</a>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</div>
					</div>
				</section>
			<?php endif; ?>

		</div>

		<?php
		/*
		 * LatestNewsSection. Source posts come from the 'rss-feeds' category
		 * (LATEST_NEWS_CATEGORY_SLUG in constants/latestNewsCategory.js).
		 * Note: the source imports a separate stylesheet
		 * (styles/latest-news.css) not part of index.css — bring that over
		 * too if this section needs its own styling.
		 */
		$sassystrides_latest_news_query = new WP_Query(
			array(
				'category_name'  => 'rss-feeds',
				'posts_per_page' => 10,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		?>
		<?php if ( $sassystrides_latest_news_query->have_posts() ) : ?>
			<section class="latest-news" aria-label="<?php esc_attr_e( 'Latest News', 'sassy-strides' ); ?>">
				<div class="latest-news__container editorial-container">
					<header class="latest-news__header">
						<h2 class="latest-news__title"><?php esc_html_e( 'Latest News', 'sassy-strides' ); ?></h2>
					</header>

					<div class="latest-news__grid">
						<?php
						while ( $sassystrides_latest_news_query->have_posts() ) :
							$sassystrides_latest_news_query->the_post();
							?>
							<article class="latest-news-card editorial-article-card editorial-article-card--secondary group">
								<a href="<?php the_permalink(); ?>" class="editorial-article-card__media-link" aria-label="<?php the_title_attribute(); ?>">
									<div class="editorial-article-card__media">
										<?php
										the_post_thumbnail(
											'medium_large',
											array(
												'class'   => 'editorial-article-card__image',
												'loading' => 'lazy',
											)
										);
										?>
									</div>
								</a>

								<div class="editorial-article-card__body latest-news-card__body">
									<?php $sassystrides_news_date = get_the_date( 'M j, Y' ); ?>
									<?php if ( $sassystrides_news_date ) : ?>
										<p class="latest-news-card__date"><?php echo esc_html( $sassystrides_news_date ); ?></p>
									<?php endif; ?>
									<a href="<?php the_permalink(); ?>" class="editorial-article-card__title-link">
										<h3 class="editorial-article-card__title serif-title"><?php the_title(); ?></h3>
									</a>
								</div>
							</article>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

	</div>

<?php endif; ?>

<?php
get_footer();
