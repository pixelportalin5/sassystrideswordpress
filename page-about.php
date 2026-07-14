<?php
/**
 * Template Name: About
 *
 * Auto-selected by WordPress for the page with slug "about" (matches
 * the /about route in the React app). Ported from AboutPage.jsx.
 */

$sassystrides_about_pillars = array(
	array(
		'icon'        => 'sparkles',
		'label'       => __( 'Inspire', 'sassy-strides' ),
		'description' => __( 'We celebrate confident self-expression through runway notes, street style, and stories that spark fresh ideas for the modern wardrobe.', 'sassy-strides' ),
	),
	array(
		'icon'        => 'gem',
		'label'       => __( 'Elevate', 'sassy-strides' ),
		'description' => __( 'Every feature is edited with a luxury lens — refined photography, thoughtful curation, and culture-forward storytelling across fashion and beauty.', 'sassy-strides' ),
	),
	array(
		'icon'        => 'trending-up',
		'label'       => __( 'Empower', 'sassy-strides' ),
		'description' => __( 'Sassy Strides gives readers practical style intelligence — from office essentials to red-carpet moments — so they can dress with intention every day.', 'sassy-strides' ),
	),
);

$sassystrides_about_coverage = array(
	array( 'label' => __( 'Fashion', 'sassy-strides' ), 'slug' => 'fashion' ),
	array( 'label' => __( 'Beauty', 'sassy-strides' ), 'slug' => 'beauty' ),
	array( 'label' => __( 'Lifestyle', 'sassy-strides' ), 'slug' => 'lifestyle' ),
	array( 'label' => __( 'Trends', 'sassy-strides' ), 'slug' => 'trends' ),
	array( 'label' => __( 'News', 'sassy-strides' ), 'slug' => 'news' ),
);

get_header();
?>

<div class="min-h-screen bg-ivory text-ink">

	<main class="static-page static-page--about pb-16">

		<section class="static-page__hero editorial-container">
			<span class="static-page__badge">
				<span class="static-page__badge-dot" aria-hidden="true"></span>
				<?php esc_html_e( 'Our Story', 'sassy-strides' ); ?>
			</span>
			<h1 class="static-page__title serif-title">
				<?php esc_html_e( 'About', 'sassy-strides' ); ?> <span class="static-page__title-accent"><?php esc_html_e( 'Sassy Strides.', 'sassy-strides' ); ?></span>
			</h1>
			<p class="static-page__lead">
				<?php esc_html_e( 'Sassy Strides is a refined digital fashion magazine for elevated style, contemporary beauty, and editorial culture.', 'sassy-strides' ); ?>
			</p>
		</section>

		<section class="editorial-container static-page__intro">
			<div class="static-page__intro-copy">
				<p class="micro-label text-bronze"><?php esc_html_e( 'The Editorial Desk', 'sassy-strides' ); ?></p>
				<p class="static-page__intro-text">
					<?php esc_html_e( 'We publish considered fashion journalism for readers who care about craft, quality, and the details that define personal style. From runway recaps and beauty edits to lifestyle features and industry news, Sassy Strides is built for women who want their feed to feel as polished as their closet.', 'sassy-strides' ); ?>
				</p>
			</div>
			<div class="static-page__intro-panel">
				<p class="serif-title static-page__intro-quote">
					&ldquo;<?php esc_html_e( 'Style is a language — and we help you speak it fluently.', 'sassy-strides' ); ?>&rdquo;
				</p>
			</div>
		</section>

		<section class="editorial-container static-page__cards static-page__cards--about">
			<?php foreach ( $sassystrides_about_pillars as $sassystrides_pillar ) : ?>
				<article class="static-page__card">
					<div class="static-page__card-icon" aria-hidden="true">
						<?php if ( 'sparkles' === $sassystrides_pillar['icon'] ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z" />
								<path d="M20 3v4" />
								<path d="M22 5h-4" />
								<path d="M4 17v2" />
								<path d="M5 18H3" />
							</svg>
						<?php elseif ( 'gem' === $sassystrides_pillar['icon'] ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M6 3h12l4 6-10 13L2 9Z" />
								<path d="M11 3 8 9l4 13 4-13-3-6" />
								<path d="M2 9h20" />
							</svg>
						<?php else : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
								<polyline points="16 7 22 7 22 13" />
							</svg>
						<?php endif; ?>
					</div>
					<h2 class="static-page__card-title"><?php echo esc_html( $sassystrides_pillar['label'] ); ?></h2>
					<p class="static-page__card-copy"><?php echo esc_html( $sassystrides_pillar['description'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</section>

		<section class="editorial-container static-page__coverage">
			<div class="static-page__coverage-head">
				<h2 class="micro-label text-espresso"><?php esc_html_e( 'What We Cover', 'sassy-strides' ); ?></h2>
				<p class="static-page__coverage-lead">
					<?php esc_html_e( 'Explore the categories that shape our editorial calendar.', 'sassy-strides' ); ?>
				</p>
			</div>
			<div class="static-page__coverage-grid">
				<?php foreach ( $sassystrides_about_coverage as $sassystrides_coverage_item ) : ?>
					<a href="<?php echo esc_url( sassystrides_get_category_url( $sassystrides_coverage_item['slug'] ) ); ?>" class="static-page__coverage-link group">
						<span class="serif-title text-2xl uppercase leading-none text-espresso transition group-hover:text-bronze">
							<?php echo esc_html( $sassystrides_coverage_item['label'] ); ?>
						</span>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-bronze opacity-0 transition group-hover:opacity-100" aria-hidden="true">
							<path d="M7 7h10v10" />
							<path d="M7 17 17 7" />
						</svg>
					</a>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="editorial-container static-page__cta-wrap">
			<div class="static-page__cta static-page__cta--about">
				<h2 class="static-page__cta-title serif-title"><?php esc_html_e( 'Join the conversation.', 'sassy-strides' ); ?></h2>
				<p class="static-page__cta-copy">
					<?php esc_html_e( 'Discover the latest stories, follow us on social, or reach out about partnerships and press inquiries.', 'sassy-strides' ); ?>
				</p>
				<div class="static-page__cta-actions">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-cta btn-cta--primary">
						<?php esc_html_e( 'Explore Stories', 'sassy-strides' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/advertise' ) ); ?>" class="btn-cta btn-cta--primary">
						<?php esc_html_e( 'Advertise With Us', 'sassy-strides' ); ?>
					</a>
				</div>
			</div>
		</section>

	</main>

</div>

<?php get_footer(); ?>
