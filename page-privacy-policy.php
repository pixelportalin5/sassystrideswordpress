<?php
/**
 * Template Name: Privacy Policy
 *
 * Auto-selected by WordPress for the page with slug "privacy-policy"
 * (matches the /privacy-policy route in the React app). Ported from
 * PrivacyPolicyPage.jsx + data/privacyPolicyContent.js.
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
			'hero_title'           => __( 'Privacy Policy', 'sassy-strides' ),
			'hero_subtitle'        => __( 'Learn how Sassy Strides collects, uses, and protects your information.', 'sassy-strides' ),
			'last_updated_iso'     => '2026-06-22',
			'last_updated_display' => __( 'June 22, 2026', 'sassy-strides' ),
			'breadcrumb'           => array(
				array( 'label' => __( 'Home', 'sassy-strides' ), 'path' => '/' ),
				array( 'label' => __( 'Legal', 'sassy-strides' ), 'path' => '/privacy-policy' ),
				array( 'label' => __( 'Privacy Policy', 'sassy-strides' ) ),
			),
			'sections'             => array(
				array(
					'id'         => 'information-we-collect',
					'title'      => __( 'Information We Collect', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'Sassy Strides collects information that helps us deliver a refined editorial experience, respond to inquiries, and understand how readers engage with our fashion, beauty, and lifestyle content.', 'sassy-strides' ),
						__( 'The information we collect may include details you voluntarily provide — such as your name, email address, and message content when you contact us, subscribe to our newsletter, or submit a story idea. We may also collect technical data automatically when you visit our website, including your IP address, browser type, device information, referring pages, and general usage patterns.', 'sassy-strides' ),
					),
					'list'       => array(
						__( 'Contact and inquiry details submitted through our forms', 'sassy-strides' ),
						__( 'Newsletter subscription preferences and email address', 'sassy-strides' ),
						__( 'Comments or correspondence sent to our editorial team', 'sassy-strides' ),
						__( 'Analytics data related to page views, clicks, and session duration', 'sassy-strides' ),
						__( 'Cookie and device identifiers used for site functionality and measurement', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'how-we-use-information',
					'title'      => __( 'How We Use Information', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'We use the information we collect to operate Sassy Strides, publish and improve our editorial content, and communicate with readers who choose to hear from us.', 'sassy-strides' ),
						__( 'This includes responding to partnership and press inquiries, delivering newsletters you have opted into, personalizing your browsing experience where appropriate, and analyzing audience trends to inform our editorial calendar. We do not sell your personal information to third parties.', 'sassy-strides' ),
					),
					'list'       => array(
						__( 'Providing, maintaining, and improving our website and content', 'sassy-strides' ),
						__( 'Sending editorial updates, newsletters, and service-related notices', 'sassy-strides' ),
						__( 'Processing advertising and collaboration inquiries', 'sassy-strides' ),
						__( 'Monitoring site performance, security, and audience engagement', 'sassy-strides' ),
						__( 'Complying with applicable legal obligations', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'cookies-tracking-technologies',
					'title'      => __( 'Cookies & Tracking Technologies', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'Like most modern digital publications, Sassy Strides uses cookies and similar technologies to remember preferences, measure traffic, and support advertising partnerships.', 'sassy-strides' ),
						__( 'Cookies are small text files stored on your device. Some are essential for the site to function; others help us understand which stories resonate with our audience or allow advertising partners to deliver relevant placements. You can manage cookie preferences through your browser settings, though disabling certain cookies may affect site functionality.', 'sassy-strides' ),
					),
					'list'       => array(
						__( 'Essential cookies required for core site features', 'sassy-strides' ),
						__( 'Analytics cookies that help us understand readership patterns', 'sassy-strides' ),
						__( 'Advertising cookies used to measure campaign performance', 'sassy-strides' ),
						__( 'Preference cookies that remember settings during your visit', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'third-party-services',
					'title'      => __( 'Third-Party Services', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'Sassy Strides may integrate trusted third-party services for analytics, advertising, email delivery, social media embeds, and content hosting. These providers may process limited information on our behalf in accordance with their own privacy policies.', 'sassy-strides' ),
						__( 'When you interact with embedded content — such as social media widgets, sponsored placements, or external links — those third parties may collect information directly from you. We encourage you to review the privacy practices of any external service you choose to engage with.', 'sassy-strides' ),
					),
					'list'       => array(
						__( 'Website analytics and performance tools', 'sassy-strides' ),
						__( 'Email marketing and newsletter platforms', 'sassy-strides' ),
						__( 'Advertising networks and sponsorship partners', 'sassy-strides' ),
						__( 'Social media platforms linked from our site', 'sassy-strides' ),
						__( 'Content delivery and security infrastructure providers', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'data-security',
					'title'      => __( 'Data Security', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'We take reasonable administrative, technical, and organizational measures to protect the information we collect against unauthorized access, alteration, disclosure, or destruction.', 'sassy-strides' ),
						__( 'While no online platform can guarantee absolute security, we regularly review our practices and work with reputable service providers to safeguard reader data. If you believe your information has been compromised, please contact us promptly using the details below.', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'your-rights',
					'title'      => __( 'Your Rights', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'Depending on your location, you may have rights regarding your personal information, including the ability to access, correct, delete, or restrict certain processing of your data.', 'sassy-strides' ),
						__( 'You may unsubscribe from marketing emails at any time using the link included in our newsletters. You may also request information about the data we hold or ask us to update inaccurate details by contacting our team. We will respond to legitimate requests in accordance with applicable law.', 'sassy-strides' ),
					),
					'list'       => array(
						__( 'Request access to personal information we maintain about you', 'sassy-strides' ),
						__( 'Ask us to correct or update inaccurate information', 'sassy-strides' ),
						__( 'Request deletion of certain personal data, where applicable', 'sassy-strides' ),
						__( 'Opt out of marketing communications at any time', 'sassy-strides' ),
						__( 'Object to or restrict specific types of data processing', 'sassy-strides' ),
					),
				),
				array(
					'id'         => 'contact-information',
					'title'      => __( 'Contact Information', 'sassy-strides' ),
					'paragraphs' => array(
						__( 'If you have questions about this Privacy Policy or how Sassy Strides handles your information, please reach out to our editorial team. We are happy to assist with privacy-related requests, newsletter preferences, and general inquiries.', 'sassy-strides' ),
						__( 'Email: hello@sassystrides.com', 'sassy-strides' ),
						__( 'You may also visit our Contact page for partnership, press, and editorial correspondence.', 'sassy-strides' ),
					),
				),
			),
		)
	);
	?>

<?php endwhile; ?>

<?php get_footer(); ?>
