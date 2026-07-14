<?php
/**
 * Template Name: Terms of Service
 *
 * Auto-selected by WordPress for the page with slug "terms-of-service"
 * (matches the /terms-of-service route in the React app). Ported from
 * TermsOfServicePage.jsx + data/termsOfServiceContent.js.
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php
	get_template_part(
		'template-parts/legal-article-layout',
		null,
		array(
			'hero_label'           => __( 'Legal', 'sassy-strides' ),
			'hero_title'           => __( 'Terms of Service', 'sassy-strides' ),
			'hero_subtitle'        => __( 'Guidelines for using Sassy Strides and its content.', 'sassy-strides' ),
			'last_updated_iso'     => '2026-06-22',
			'last_updated_display' => __( 'June 22, 2026', 'sassy-strides' ),
			'breadcrumb'           => array(
				array( 'label' => __( 'Home', 'sassy-strides' ), 'path' => '/' ),
				array( 'label' => __( 'Legal', 'sassy-strides' ), 'path' => '/terms-of-service' ),
				array( 'label' => __( 'Terms of Service', 'sassy-strides' ) ),
			),
			'sections'             => array(
				array(
					'id'         => 'acceptance-of-terms',
					'title'      => __( 'Acceptance of Terms', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'By accessing or using the Sassy Strides website, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any part of these terms, please discontinue use of our site.', 'sassy-strides' ),
						__( 'These terms apply to all visitors, readers, contributors, and partners who interact with Sassy Strides content, features, newsletters, and related digital services.', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'use-of-website',
					'title'      => __( 'Use of Website', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'Sassy Strides grants you a limited, non-exclusive, non-transferable license to access and use our website for personal, non-commercial purposes. You may read, share links to, and enjoy our editorial content in accordance with these terms.', 'sassy-strides' ),
						__( 'You agree not to misuse the site, interfere with its operation, attempt unauthorized access to our systems, scrape content at scale, or use automated tools in a manner that disrupts the experience for other readers.', 'sassy-strides' ),
					),
					'list'       => array(
						__( 'Do not copy, republish, or redistribute content without permission', 'sassy-strides' ),
						__( 'Do not upload malicious code or attempt to breach site security', 'sassy-strides' ),
						__( 'Do not impersonate Sassy Strides staff or misrepresent affiliations', 'sassy-strides' ),
						__( 'Do not use the site for unlawful, harassing, or deceptive activity', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'intellectual-property',
					'title'      => __( 'Intellectual Property', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'All content published on Sassy Strides — including articles, photography, graphics, logos, layouts, headlines, and editorial branding — is owned by Sassy Strides or its licensors and is protected by copyright, trademark, and other intellectual property laws.', 'sassy-strides' ),
						__( 'You may share links to our stories on social media and quote brief excerpts with proper attribution and a link back to the original article. Any other reproduction, distribution, or commercial use requires prior written permission from Sassy Strides.', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'user-responsibilities',
					'title'      => __( 'User Responsibilities', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'When you contact us, subscribe to communications, or submit ideas and feedback, you are responsible for providing accurate information and ensuring that your submissions do not violate the rights of others.', 'sassy-strides' ),
						__( 'You agree not to submit unlawful, defamatory, infringing, or otherwise objectionable material. Sassy Strides reserves the right to remove or disregard submissions that do not align with our editorial standards or legal obligations.', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'third-party-links',
					'title'      => __( 'Third-Party Links', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'Our website may include links to third-party websites, brands, retailers, and social platforms for reader convenience and editorial context. Sassy Strides does not control and is not responsible for the content, policies, or practices of those external sites.', 'sassy-strides' ),
						__( 'Accessing third-party links is at your own discretion. We encourage you to review the terms and privacy policies of any external website you visit from Sassy Strides.', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'limitation-of-liability',
					'title'      => __( 'Limitation of Liability', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'Sassy Strides provides editorial content for informational and inspirational purposes. While we strive for accuracy and quality, we do not warrant that the site will be uninterrupted, error-free, or complete at all times.', 'sassy-strides' ),
						__( 'To the fullest extent permitted by law, Sassy Strides and its team shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the website, reliance on editorial content, or interactions with third-party services linked from our platform.', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'changes-to-terms',
					'title'      => __( 'Changes to Terms', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'We may update these Terms of Service from time to time to reflect changes in our services, legal requirements, or editorial operations. When we make material updates, we will revise the "Last updated" date at the top of this page.', 'sassy-strides' ),
						__( 'Your continued use of Sassy Strides after changes are posted constitutes acceptance of the revised terms. We recommend reviewing this page periodically to stay informed.', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'contact-information',
					'title'      => __( 'Contact Information', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'For questions about these Terms of Service, permissions requests, or legal inquiries, please contact Sassy Strides using the information below.', 'sassy-strides' ),
						__( 'Email: hello@sassystrides.com', 'sassy-strides' ),
						__( 'Visit our Contact page for editorial, partnership, and advertising correspondence.', 'sassy-strides' ),
					),
				),
			),
		)
	);
	?>

<?php endwhile; ?>

<?php get_footer(); ?>
