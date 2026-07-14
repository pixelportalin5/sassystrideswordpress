<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Hardcoded rather than read from get_theme_mod( 'custom_logo' ) — a
// different logo was previously set via Customizer > Site Identity and
// kept overriding this URL. Set/replace the Customizer logo if you want
// that to take priority again instead.
$sassystrides_logo_url = 'https://olive-rail-428908.hostingersite.com/wp-content/uploads/2026/07/sassystrides2light_logo-removebg-preview-e1781683275677-2.webp';

$sassystrides_social_links = array(
	array(
		'label' => 'Instagram',
		'href'  => 'https://www.instagram.com/thesassy_strides/',
	),
	array(
		'label' => 'Facebook',
		'href'  => 'https://www.facebook.com/profile.php?id=61590864083802',
	),
); // TODO: move to Customizer/ACF options once available (mirrors constants/social.js).
?>

<header class="site-header sticky top-0 z-50 border-b border-ink/10 bg-ivory/94 backdrop-blur-xl">
	<div class="site-header__top editorial-container">
		<div class="site-header__top-spacer" aria-hidden="true"></div>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__brand" aria-label="<?php esc_attr_e( 'Sassy Strides homepage', 'sassy-strides' ); ?>">
			<img
				src="<?php echo esc_url( $sassystrides_logo_url ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				class="site-header__logo-image"
				loading="eager"
				decoding="async"
				fetchpriority="high"
			>
		</a>

		<div class="site-header__top-actions">

			<div class="site-header__search" role="search">
				<button
					type="button"
					class="site-header__search-toggle"
					aria-label="<?php esc_attr_e( 'Open search', 'sassy-strides' ); ?>"
					aria-expanded="false"
				>
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="11" cy="11" r="8" />
						<path d="m21 21-4.3-4.3" />
					</svg>
				</button>

				<div class="site-header__search-panel">
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<label class="sr-only" for="site-header-search-input">
							<?php esc_html_e( 'Search stories', 'sassy-strides' ); ?>
						</label>
						<input
							id="site-header-search-input"
							type="search"
							name="s"
							value="<?php echo esc_attr( get_search_query() ); ?>"
							placeholder="<?php esc_attr_e( 'Search stories', 'sassy-strides' ); ?>"
							class="site-header__search-input"
							autocomplete="off"
							role="combobox"
							aria-autocomplete="list"
							aria-expanded="false"
							aria-controls="site-header-search-suggestions"
						>
					</form>
					<ul id="site-header-search-suggestions" class="site-header__search-suggestions" role="listbox" aria-label="<?php esc_attr_e( 'Story suggestions', 'sassy-strides' ); ?>" hidden></ul>
				</div>
			</div>

			<div class="site-header__social">
				<?php foreach ( $sassystrides_social_links as $sassystrides_social_link ) : ?>
					<a
						href="<?php echo esc_url( $sassystrides_social_link['href'] ); ?>"
						class="site-header__social-link"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( $sassystrides_social_link['label'] ); ?>"
					>
						<?php if ( 'Instagram' === $sassystrides_social_link['label'] ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
								<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
								<line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
							</svg>
						<?php elseif ( 'Facebook' === $sassystrides_social_link['label'] ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
							</svg>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>

		</div>
	</div>

	<nav class="site-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'sassy-strides' ); ?>">
		<div class="site-header__nav-inner editorial-container">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'walker'         => new SassyStrides_Nav_Walker(),
					'fallback_cb'    => false,
				)
			);
			?>
		</div>
	</nav>
</header>
