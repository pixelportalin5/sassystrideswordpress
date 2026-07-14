<?php
/**
 * Template Name: FAQ
 *
 * Auto-selected by WordPress for the page with slug "faq" (matches the
 * /faq route in the React app). Ported from FaqPage.jsx + FaqAccordion.jsx
 * + data/faqContent.js.
 *
 * The open/close accordion behaviour (originally React useState) is
 * handled by assets/js/faq-accordion.js, enqueued in functions.php only
 * on this page. The EditorialReveal fade-in-on-scroll wrappers from the
 * React version are dropped for simplicity.
 */

$sassystrides_faq_categories = array(
	array(
		'id'    => 'general',
		'title' => __( 'General', 'sassy-strides' ),
		'items' => array(
			array(
				'id'       => 'what-is-sassy-strides',
				'question' => __( 'What is Sassy Strides?', 'sassy-strides' ),
				'answer'   => __( 'Sassy Strides is a luxury digital fashion magazine covering fashion, beauty, lifestyle, trends, and culture. We publish editorial stories, style guides, and cultural commentary designed for readers who appreciate refined aesthetics and thoughtful curation.', 'sassy-strides' ),
			),
			array(
				'id'       => 'content-frequency',
				'question' => __( 'How often is content published?', 'sassy-strides' ),
				'answer'   => __( 'We publish fresh editorial content throughout the week, with new fashion features, beauty edits, and lifestyle stories added regularly. Follow us on social media or subscribe to our newsletter to stay current with the latest from The Edit.', 'sassy-strides' ),
			),
			array(
				'id'       => 'submit-story-ideas',
				'question' => __( 'Can I submit story ideas?', 'sassy-strides' ),
				'answer'   => __( 'Yes. We welcome thoughtful pitches from readers, stylists, and creatives. Visit our Contact page to share your idea with a brief summary, relevant links, and your contact details. While we cannot respond to every submission, our editorial team reviews all inquiries.', 'sassy-strides' ),
			),
		),
	),
	array(
		'id'    => 'content',
		'title' => __( 'Content', 'sassy-strides' ),
		'items' => array(
			array(
				'id'       => 'find-by-category',
				'question' => __( 'How do I find articles by category?', 'sassy-strides' ),
				'answer'   => __( 'Use the main navigation to explore Fashion, Beauty, Lifestyle, Trends, and News. Each category page features curated stories, subcategory filters, and editorial highlights so you can browse the topics that matter most to you.', 'sassy-strides' ),
			),
			array(
				'id'       => 'sponsored-content',
				'question' => __( 'Do you publish sponsored content?', 'sassy-strides' ),
				'answer'   => __( 'Sassy Strides may feature sponsored stories, brand partnerships, and advertising placements that align with our editorial voice. Sponsored content is created to feel native to our magazine aesthetic and is presented with transparency for our readers.', 'sassy-strides' ),
			),
		),
	),
	array(
		'id'    => 'partnerships',
		'title' => __( 'Partnerships', 'sassy-strides' ),
		'items' => array(
			array(
				'id'       => 'brand-collaborations',
				'question' => __( 'How can brands collaborate with Sassy Strides?', 'sassy-strides' ),
				'answer'   => __( 'Brands can partner with Sassy Strides through banner advertising, sponsored editorials, product features, and custom campaigns. Visit our Advertise page to learn about media offerings, or contact our team to discuss a tailored collaboration.', 'sassy-strides' ),
			),
			array(
				'id'       => 'contact-editorial',
				'question' => __( 'How do I contact the editorial team?', 'sassy-strides' ),
				'answer'   => __( 'For press inquiries, partnerships, and editorial questions, reach out through our Contact page or email hello@sassystrides.com. Please include your name, organization, and a concise summary of your request so we can respond efficiently.', 'sassy-strides' ),
			),
		),
	),
	array(
		'id'    => 'newsletter',
		'title' => __( 'Newsletter', 'sassy-strides' ),
		'items' => array(
			array(
				'id'       => 'subscribe',
				'question' => __( 'How do I subscribe?', 'sassy-strides' ),
				'answer'   => __( 'You can subscribe to the Sassy Strides newsletter through sign-up forms across our website, including the homepage and footer. Enter your email address to receive curated style edits, beauty highlights, and exclusive editorial features in your inbox.', 'sassy-strides' ),
			),
			array(
				'id'       => 'unsubscribe',
				'question' => __( 'Can I unsubscribe anytime?', 'sassy-strides' ),
				'answer'   => __( 'Absolutely. Every newsletter includes an unsubscribe link at the bottom of the email. You may also contact hello@sassystrides.com to update your preferences or remove your address from our mailing list at any time.', 'sassy-strides' ),
			),
		),
	),
);

get_header();
?>

<div class="min-h-screen bg-ivory text-ink">

	<main class="faq-page">
		<div class="faq-page__shell">

			<?php
			get_template_part(
				'template-parts/editorial-breadcrumb',
				null,
				array(
					'items' => array(
						array( 'label' => __( 'Home', 'sassy-strides' ), 'path' => '/' ),
						array( 'label' => __( 'Help Center', 'sassy-strides' ), 'path' => '/faq' ),
						array( 'label' => __( 'FAQ', 'sassy-strides' ) ),
					),
				)
			);
			?>

			<div class="faq-page__hero">
				<p class="faq-page__eyebrow"><?php esc_html_e( 'Help Center', 'sassy-strides' ); ?></p>
				<h1 class="faq-page__title serif-title"><?php esc_html_e( 'Frequently Asked Questions', 'sassy-strides' ); ?></h1>
				<p class="faq-page__subtitle"><?php esc_html_e( 'Everything you need to know about Sassy Strides.', 'sassy-strides' ); ?></p>
				<div class="faq-page__hero-rule" aria-hidden="true"></div>
			</div>

			<div class="faq-page__content-wrap">
				<div class="faq-accordion">
					<div class="faq-accordion__list">
						<?php foreach ( $sassystrides_faq_categories as $sassystrides_faq_category ) : ?>
							<?php foreach ( $sassystrides_faq_category['items'] as $sassystrides_faq_item ) : ?>
								<?php
								$sassystrides_faq_item_id  = $sassystrides_faq_category['id'] . '-' . $sassystrides_faq_item['id'];
								$sassystrides_faq_panel_id = $sassystrides_faq_item_id . '-panel';
								$sassystrides_faq_button_id = $sassystrides_faq_item_id . '-button';
								?>
								<div class="faq-accordion__item">
									<h3 class="faq-accordion__question-wrap">
										<button
											id="<?php echo esc_attr( $sassystrides_faq_button_id ); ?>"
											type="button"
											class="faq-accordion__trigger"
											aria-expanded="false"
											aria-controls="<?php echo esc_attr( $sassystrides_faq_panel_id ); ?>"
										>
											<span class="faq-accordion__question"><?php echo esc_html( $sassystrides_faq_item['question'] ); ?></span>
											<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="faq-accordion__icon" aria-hidden="true">
												<path d="m6 9 6 6 6-6" />
											</svg>
										</button>
									</h3>
									<div
										id="<?php echo esc_attr( $sassystrides_faq_panel_id ); ?>"
										role="region"
										aria-labelledby="<?php echo esc_attr( $sassystrides_faq_button_id ); ?>"
										class="faq-accordion__panel"
									>
										<div class="faq-accordion__answer">
											<p><?php echo esc_html( $sassystrides_faq_item['answer'] ); ?></p>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="faq-page__cta">
				<p class="faq-page__cta-label"><?php esc_html_e( 'Still have questions?', 'sassy-strides' ); ?></p>
				<h2 class="faq-page__cta-title serif-title"><?php esc_html_e( "We're here to help.", 'sassy-strides' ); ?></h2>
				<p class="faq-page__cta-copy">
					<?php esc_html_e( 'Reach out to our editorial desk for partnerships, press inquiries, or general correspondence.', 'sassy-strides' ); ?>
				</p>
				<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="faq-page__cta-button">
					<?php esc_html_e( 'Contact Us', 'sassy-strides' ); ?>
				</a>
			</div>

		</div>
	</main>

</div>

<?php get_footer(); ?>
