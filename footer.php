<?php
// Hardcoded — see the matching note in header.php.
$sassystrides_footer_logo_url = 'https://olive-rail-428908.hostingersite.com/wp-content/uploads/2026/07/sassystrides2light_logo-removebg-preview-e1781683275677-2.webp';

$sassystrides_footer_social_links = sassystrides_get_social_links();

$sassystrides_footer_category_links = array(
	array(
		'label' => 'Fashion',
		'slug'  => 'fashion',
	),
	array(
		'label' => 'Beauty',
		'slug'  => 'beauty',
	),
	array(
		'label' => 'Lifestyle',
		'slug'  => 'lifestyle',
	),
	array(
		'label' => 'Trends',
		'slug'  => 'trends',
	),
	array(
		'label' => 'News',
		'slug'  => 'news',
	),
);

$sassystrides_footer_company_links_col_one = array(
	array(
		'label' => 'About Us',
		'path'  => '/about',
	),
	array(
		'label' => 'Privacy Policy',
		'path'  => '/privacy-policy',
	),
	array(
		'label' => 'Terms of Service',
		'path'  => '/terms-of-service',
	),
);

$sassystrides_footer_company_links_col_two = array(
	array(
		'label' => 'Contact',
		'path'  => '/contact',
	),
	array(
		'label' => 'FAQ',
		'path'  => '/faq',
	),
	array(
		'label' => 'Advertise',
		'path'  => '/advertise',
	),
);
?>

<footer class="site-footer">
	<div class="editorial-container site-footer__inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo-link" aria-label="<?php esc_attr_e( 'Sassy Strides homepage', 'sassy-strides' ); ?>">
			<img
				src="<?php echo esc_url( $sassystrides_footer_logo_url ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="site-footer__logo"
				loading="lazy"
				decoding="async"
			>
		</a>

		<p class="site-footer__section-title site-footer__title-follow"><?php esc_html_e( 'Follow Us', 'sassy-strides' ); ?></p>
		<p class="site-footer__section-title site-footer__title-categories"><?php esc_html_e( 'Categories', 'sassy-strides' ); ?></p>
		<p class="site-footer__section-title site-footer__title-company"><?php esc_html_e( 'Company', 'sassy-strides' ); ?></p>

		<div class="site-footer__social">
			<?php foreach ( $sassystrides_footer_social_links as $sassystrides_social_link ) : ?>
				<a
					href="<?php echo esc_url( $sassystrides_social_link['href'] ); ?>"
					class="site-footer__social-link"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php echo esc_attr( $sassystrides_social_link['label'] ); ?>"
				>
					<?php if ( 'Instagram' === $sassystrides_social_link['label'] ) : ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
							<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
							<line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
						</svg>
					<?php elseif ( 'Facebook' === $sassystrides_social_link['label'] ) : ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
						</svg>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>

		<nav class="site-footer__categories" aria-label="<?php esc_attr_e( 'Footer categories', 'sassy-strides' ); ?>">
			<ul class="site-footer__category-list">
				<?php foreach ( $sassystrides_footer_category_links as $sassystrides_category_link ) : ?>
					<li>
						<a href="<?php echo esc_url( sassystrides_get_category_url( $sassystrides_category_link['slug'] ) ); ?>" class="site-footer__category-link">
							<?php echo esc_html( $sassystrides_category_link['label'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<div class="site-footer__company">
			<div class="site-footer__company-columns">
				<ul class="site-footer__company-list">
					<?php foreach ( $sassystrides_footer_company_links_col_one as $sassystrides_company_link ) : ?>
						<li>
							<a href="<?php echo esc_url( home_url( $sassystrides_company_link['path'] ) ); ?>" class="site-footer__company-link">
								<?php echo esc_html( $sassystrides_company_link['label'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<ul class="site-footer__company-list">
					<?php foreach ( $sassystrides_footer_company_links_col_two as $sassystrides_company_link ) : ?>
						<li>
							<a href="<?php echo esc_url( home_url( $sassystrides_company_link['path'] ) ); ?>" class="site-footer__company-link">
								<?php echo esc_html( $sassystrides_company_link['label'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

	</div>

	<div class="site-footer__copyright">
		<?php esc_html_e( '© Copyright @ 2026 SassyStrides By Web Affino LLC', 'sassy-strides' ); ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
