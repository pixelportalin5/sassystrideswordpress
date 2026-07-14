<?php
/**
 * Template Name: Advertise
 *
 * Auto-selected by WordPress for the page with slug "advertise" (matches
 * the /advertise route in the React app). Ported from AdvertisePage.jsx.
 */

$sassystrides_offerings = array(
	array(
		'icon'        => 'image',
		'title'       => __( 'Banner Ads', 'sassy-strides' ),
		'description' => __( 'Promote your brand through banner ads with highly visible display placements across our editorial homepage, category pages, and article layouts.', 'sassy-strides' ),
	),
	array(
		'icon'        => 'star',
		'title'       => __( 'Product Features', 'sassy-strides' ),
		'description' => __( 'Showcase your top products, collections, or exclusive offers directly to a style-forward audience actively exploring fashion and beauty content.', 'sassy-strides' ),
	),
	array(
		'icon'        => 'users',
		'title'       => __( 'Collaborations', 'sassy-strides' ),
		'description' => __( 'Partner with Sassy Strides on sponsored stories, brand integrations, and tailored campaigns designed to reach a wider shopping audience.', 'sassy-strides' ),
	),
);

get_header();
?>

<div class="min-h-screen bg-ivory text-ink">

	<main class="static-page static-page--advertise pb-16">

		<section class="static-page__hero editorial-container">
			<span class="static-page__badge">
				<span class="static-page__badge-dot" aria-hidden="true"></span>
				<?php esc_html_e( 'Media & Partnerships', 'sassy-strides' ); ?>
			</span>
			<h1 class="static-page__title serif-title">
				<?php esc_html_e( 'Advertise With', 'sassy-strides' ); ?> <span class="static-page__title-accent"><?php esc_html_e( 'Us.', 'sassy-strides' ); ?></span>
			</h1>
			<p class="static-page__lead">
				<?php esc_html_e( 'Interested in advertising with Sassy Strides? Promote your brand through banner ads, product features, and collaborations to reach a wider shopping audience.', 'sassy-strides' ); ?>
			</p>
		</section>

		<section class="editorial-container static-page__cards">
			<?php foreach ( $sassystrides_offerings as $sassystrides_offering ) : ?>
				<article class="static-page__card">
					<div class="static-page__card-icon" aria-hidden="true">
						<?php if ( 'image' === $sassystrides_offering['icon'] ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
								<circle cx="9" cy="9" r="2" />
								<path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
							</svg>
						<?php elseif ( 'star' === $sassystrides_offering['icon'] ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
							</svg>
						<?php else : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
								<circle cx="9" cy="7" r="4" />
								<path d="M22 21v-2a4 4 0 0 0-3-3.87" />
								<path d="M16 3.13a4 4 0 0 1 0 7.75" />
							</svg>
						<?php endif; ?>
					</div>
					<h2 class="static-page__card-title"><?php echo esc_html( $sassystrides_offering['title'] ); ?></h2>
					<p class="static-page__card-copy"><?php echo esc_html( $sassystrides_offering['description'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</section>

		<section class="editorial-container static-page__cta-wrap">
			<div class="static-page__cta static-page__cta--advertise">
				<h2 class="static-page__cta-title serif-title"><?php esc_html_e( "Let's build a partnership.", 'sassy-strides' ); ?></h2>
				<p class="static-page__cta-copy">
					<?php esc_html_e( 'For inquiries, partnerships, or promotions, please contact our team. We respond to media and advertising requests promptly.', 'sassy-strides' ); ?>
				</p>
				<a href="mailto:affiliate@webaffino.com" class="static-page__cta-button">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<rect width="20" height="16" x="2" y="4" rx="2" />
						<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
					</svg>
					affiliate@webaffino.com
				</a>
			</div>
			<p class="static-page__thanks">
				<?php esc_html_e( 'Thank you for supporting', 'sassy-strides' ); ?> <strong><?php esc_html_e( 'Sassy Strides!', 'sassy-strides' ); ?></strong>
			</p>
		</section>

	</main>

</div>

<?php get_footer(); ?>
