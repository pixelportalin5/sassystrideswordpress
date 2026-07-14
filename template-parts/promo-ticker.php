<?php
/**
 * Template part: Promo ticker. Ported from PromoTicker.jsx — a looping
 * marquee of partner logos/offers. The track is duplicated into two
 * identical groups (the second aria-hidden) so the CSS marquee animation
 * (already compiled into style.css) can loop seamlessly.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sassystrides_promo_items = array(
	array(
		'brand'       => 'Decathlon',
		'offer'       => 'Summer Sale Flat 30% Off',
		'href'        => 'https://www.decathlon.in/',
		'logo'        => 'https://olive-rail-428908.hostingersite.com/wp-content/uploads/2026/07/decathlon.png',
		'logo_alt'    => 'Decathlon logo',
		'logo_width'  => 180,
		'logo_height' => 180,
	),
	array(
		'brand'       => 'DU',
		'offer'       => 'Exclusive Discounts on iPhone 17',
		'href'        => 'https://www.du.ae/',
		'logo'        => 'https://olive-rail-428908.hostingersite.com/wp-content/uploads/2026/07/du.png',
		'logo_alt'    => 'DU logo',
		'logo_width'  => 128,
		'logo_height' => 128,
	),
	array(
		'brand'       => 'Bath & Body Works',
		'offer'       => 'Best Seller Body Care at ₹999',
		'href'        => 'https://www.bathandbodyworks.in/',
		'logo'        => 'https://olive-rail-428908.hostingersite.com/wp-content/uploads/2026/07/bath-body-works.svg',
		'logo_alt'    => 'Bath & Body Works logo',
		'logo_width'  => 220,
		'logo_height' => 48,
	),
);

$sassystrides_render_promo_group = function ( $items, $aria_hidden = false ) {
	?>
	<div class="promo-ticker__group"<?php echo $aria_hidden ? ' aria-hidden="true"' : ''; ?>>
		<?php foreach ( $items as $sassystrides_promo_item ) : ?>
			<a
				class="promo-ticker__item promo-ticker__link"
				href="<?php echo esc_url( $sassystrides_promo_item['href'] ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				aria-label="<?php echo esc_attr( $sassystrides_promo_item['brand'] . ': ' . $sassystrides_promo_item['offer'] ); ?>"
			>
				<span class="promo-ticker__logo">
					<img
						src="<?php echo esc_url( $sassystrides_promo_item['logo'] ); ?>"
						alt="<?php echo esc_attr( $sassystrides_promo_item['logo_alt'] ); ?>"
						width="<?php echo esc_attr( $sassystrides_promo_item['logo_width'] ); ?>"
						height="<?php echo esc_attr( $sassystrides_promo_item['logo_height'] ); ?>"
						loading="eager"
						decoding="async"
						class="promo-ticker__logo-image"
					>
				</span>
				<span class="promo-ticker__brand"><?php echo esc_html( $sassystrides_promo_item['brand'] ); ?></span>
				<span class="promo-ticker__divider" aria-hidden="true">—</span>
				<span class="promo-ticker__badge"><?php echo esc_html( $sassystrides_promo_item['offer'] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
};
?>

<section class="promo-ticker editorial-container" aria-label="<?php esc_attr_e( 'Partner promotions', 'sassy-strides' ); ?>">
	<div class="promo-ticker__shell">
		<div class="promo-ticker__viewport">
			<div class="promo-ticker__track">
				<?php $sassystrides_render_promo_group( $sassystrides_promo_items ); ?>
				<?php $sassystrides_render_promo_group( $sassystrides_promo_items, true ); ?>
			</div>
		</div>
	</div>
</section>
