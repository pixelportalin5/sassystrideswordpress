<?php
/**
 * Reusable breadcrumb nav, ported from EditorialBreadcrumb.jsx.
 *
 * Expected $args['items']: array of associative arrays with
 * 'label' (string) and optional 'path' (site-relative, e.g. '/faq').
 * The last item (or any item without a 'path') renders as plain text.
 */

$sassystrides_breadcrumb_items = isset( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();
$sassystrides_breadcrumb_count = count( $sassystrides_breadcrumb_items );
?>
<nav class="editorial-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sassy-strides' ); ?>">
	<ol class="editorial-breadcrumb__list">
		<?php foreach ( $sassystrides_breadcrumb_items as $sassystrides_breadcrumb_index => $sassystrides_breadcrumb_item ) : ?>
			<?php $sassystrides_is_last = ( $sassystrides_breadcrumb_index === $sassystrides_breadcrumb_count - 1 ); ?>
			<li class="editorial-breadcrumb__item">
				<?php if ( $sassystrides_breadcrumb_index > 0 ) : ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="editorial-breadcrumb__separator" aria-hidden="true">
						<path d="m9 18 6-6-6-6" />
					</svg>
				<?php endif; ?>

				<?php if ( $sassystrides_is_last || empty( $sassystrides_breadcrumb_item['path'] ) ) : ?>
					<span class="editorial-breadcrumb__current" aria-current="page">
						<?php echo esc_html( $sassystrides_breadcrumb_item['label'] ); ?>
					</span>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( $sassystrides_breadcrumb_item['path'] ) ); ?>" class="editorial-breadcrumb__link">
						<?php echo esc_html( $sassystrides_breadcrumb_item['label'] ); ?>
					</a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
