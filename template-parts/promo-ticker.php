<?php
/**
 * Template part: Promo ticker.
 *
 * Static partner-logo strip for now. Replace the <img> src/alt values
 * with real partner logos, or convert to a dynamic loop (ACF repeater,
 * options page, etc.) once the content source is decided.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="w-full py-3 border-y border-neutral-200 mb-16 overflow-hidden">
	<div class="max-w-7xl mx-auto flex flex-wrap justify-center items-center gap-x-12 gap-y-4 px-6">

		<div class="flex-shrink-0">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/partners/partner-1.png' ); ?>" alt="<?php esc_attr_e( 'Partner 1', 'sassy-strides' ); ?>" class="h-6 w-auto grayscale opacity-70">
		</div>

		<div class="flex-shrink-0">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/partners/partner-2.png' ); ?>" alt="<?php esc_attr_e( 'Partner 2', 'sassy-strides' ); ?>" class="h-6 w-auto grayscale opacity-70">
		</div>

		<div class="flex-shrink-0">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/partners/partner-3.png' ); ?>" alt="<?php esc_attr_e( 'Partner 3', 'sassy-strides' ); ?>" class="h-6 w-auto grayscale opacity-70">
		</div>

		<div class="flex-shrink-0">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/partners/partner-4.png' ); ?>" alt="<?php esc_attr_e( 'Partner 4', 'sassy-strides' ); ?>" class="h-6 w-auto grayscale opacity-70">
		</div>

		<div class="flex-shrink-0">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/partners/partner-5.png' ); ?>" alt="<?php esc_attr_e( 'Partner 5', 'sassy-strides' ); ?>" class="h-6 w-auto grayscale opacity-70">
		</div>

	</div>
</div>
