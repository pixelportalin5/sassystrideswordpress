<?php
/**
 * Template Name: Contact
 *
 * Auto-selected by WordPress for the page with slug "contact" (matches
 * the /contact route in the React app). Ported from ContactPage.jsx.
 *
 * Note: the React version lazy-loaded the Forminator form's HTML/JS/CSS
 * via a custom ForminatorEmbed component talking to the REST API. Since
 * this theme runs directly on the WordPress site that hosts the plugin,
 * we can skip all of that and just render the [forminator_form] shortcode
 * natively — Forminator handles its own asset loading and AJAX submission.
 *
 * The EditorialReveal/useRevealOnScroll fade-in-on-scroll treatment from
 * the React version is also dropped here for simplicity — content renders
 * immediately instead of animating in.
 */

$sassystrides_contact_image = 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=80';
$sassystrides_social_links  = sassystrides_get_social_links();

get_header();
?>

<main class="contact-page">
	<section class="contact-page__shell">

		<div class="contact-page__hero">
			<p class="contact-page__eyebrow"><?php esc_html_e( 'Get In Touch', 'sassy-strides' ); ?></p>
			<h1 class="contact-page__hero-title serif-title"><?php esc_html_e( 'Contact Us', 'sassy-strides' ); ?></h1>
			<div class="contact-page__hero-divider" aria-hidden="true"></div>
		</div>

		<div class="contact-page__grid-wrap">
			<div class="contact-page__grid">

				<div class="contact-page__form-col">
					<h2 class="contact-page__section-title serif-title"><?php esc_html_e( 'Say Hello!', 'sassy-strides' ); ?></h2>
					<div class="contact-page__details">
						<p class="contact-page__intro-text">
							<?php esc_html_e( "Whether it's fashion, beauty, lifestyle, partnerships, or editorial inquiries, we'd love to hear from you.", 'sassy-strides' ); ?>
						</p>
					</div>

					<div class="contact-page__forminator-wrap">
						<?php if ( shortcode_exists( 'forminator_form' ) ) : ?>
							<div class="contact-page__forminator">
								<?php echo do_shortcode( '[forminator_form id="' . absint( SASSYSTRIDES_FORMINATOR_FORM_ID ) . '"]' ); ?>
							</div>
						<?php else : ?>
							<p class="contact-page__forminator-status contact-page__forminator-status--error">
								<?php esc_html_e( 'The contact form could not be loaded. Please confirm the Forminator plugin is active and published.', 'sassy-strides' ); ?>
								<?php esc_html_e( 'You can also email', 'sassy-strides' ); ?>
								<a href="mailto:hello@sassystrides.com">hello@sassystrides.com</a>.
							</p>
						<?php endif; ?>
					</div>
				</div>

				<aside class="contact-page__aside">
					<figure class="contact-page__figure group">
						<img
							src="<?php echo esc_url( $sassystrides_contact_image ); ?>"
							alt="<?php esc_attr_e( 'Editorial lifestyle moment with fashion and coffee', 'sassy-strides' ); ?>"
							class="contact-page__image"
							loading="lazy"
							decoding="async"
						>
					</figure>
					<div class="contact-page__social-block">
						<p class="contact-page__social-text"><?php esc_html_e( 'Stay connected with us', 'sassy-strides' ); ?></p>
						<div class="contact-page__social-icons">
							<?php foreach ( $sassystrides_social_links as $sassystrides_social_link ) : ?>
								<a
									href="<?php echo esc_url( $sassystrides_social_link['href'] ); ?>"
									class="contact-page__social-link"
									target="_blank"
									rel="noopener noreferrer"
									aria-label="<?php echo esc_attr( $sassystrides_social_link['label'] ); ?>"
								>
									<?php if ( 'Instagram' === $sassystrides_social_link['label'] ) : ?>
										<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
											<rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
											<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
											<line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
										</svg>
									<?php elseif ( 'Facebook' === $sassystrides_social_link['label'] ) : ?>
										<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
											<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
										</svg>
									<?php endif; ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</aside>

			</div>
		</div>

	</section>
</main>

<?php get_footer(); ?>
