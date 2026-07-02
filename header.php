<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-white text-neutral-900 antialiased' ); ?>>
<?php wp_body_open(); ?>

<header class="w-full border-b border-neutral-200">
	<div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-6">

		<div class="flex-shrink-0">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center">
				<?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<span class="text-2xl font-serif tracking-widest uppercase">
						<?php bloginfo( 'name' ); ?>
					</span>
				<?php endif; ?>
			</a>
		</div>

		<nav class="hidden md:flex items-center" aria-label="<?php esc_attr_e( 'Primary Navigation', 'sassy-strides' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex items-center space-x-8 text-sm uppercase tracking-wide',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<div class="flex items-center space-x-4">
			<button type="button" class="p-2" aria-label="<?php esc_attr_e( 'Search', 'sassy-strides' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
					<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0a7.5 7.5 0 10-10.6-10.6 7.5 7.5 0 0010.6 10.6z" />
				</svg>
			</button>
		</div>

	</div>
</header>
