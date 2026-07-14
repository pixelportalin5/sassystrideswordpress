<?php
/**
 * Template part: Editorial article card. Mirrors EditorialArticleCard.jsx.
 *
 * Must be called from inside a Loop (after the_post()). Pass $args to
 * get_template_part( 'template-parts/editorial-article-card', null, $args ):
 *
 * @param string $args['variant']       'featured' | 'secondary' | 'compact'. Default 'featured'.
 * @param int    $args['index']         1-based grid position; index<=3 loads the image eagerly. Default 1.
 * @param bool   $args['show_excerpt']  Whether to show the excerpt. Default true.
 * @param bool   $args['show_category'] Whether to show the category label. Default true.
 * @param bool   $args['full_title']    Adds the --full-title modifier class. Default false.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sassystrides_variant       = isset( $args['variant'] ) ? $args['variant'] : 'featured';
$sassystrides_index         = isset( $args['index'] ) ? (int) $args['index'] : 1;
$sassystrides_show_excerpt  = isset( $args['show_excerpt'] ) ? $args['show_excerpt'] : true;
$sassystrides_show_category = isset( $args['show_category'] ) ? $args['show_category'] : true;
$sassystrides_full_title    = isset( $args['full_title'] ) ? $args['full_title'] : false;
$sassystrides_is_compact    = 'compact' === $sassystrides_variant;
$sassystrides_image_loading = $sassystrides_index <= 3 ? 'eager' : 'lazy';

$sassystrides_card_class  = 'editorial-article-card editorial-article-card--' . $sassystrides_variant;
$sassystrides_card_class .= $sassystrides_full_title ? ' editorial-article-card--full-title' : '';
$sassystrides_card_class .= ' group';
?>

<article class="<?php echo esc_attr( trim( $sassystrides_card_class ) ); ?>">

	<a href="<?php the_permalink(); ?>" class="editorial-article-card__media-link" aria-label="<?php the_title_attribute(); ?>">
		<div class="editorial-article-card__media">
			<?php
			the_post_thumbnail(
				'medium_large',
				array(
					'class'   => 'editorial-article-card__image',
					'loading' => $sassystrides_image_loading,
				)
			);
			?>
		</div>
	</a>

	<div class="editorial-article-card__body">
		<?php if ( $sassystrides_show_category ) : ?>
			<p class="editorial-article-card__category"><?php echo esc_html( get_the_category()[0]->name ?? '' ); ?></p>
		<?php endif; ?>

		<a href="<?php the_permalink(); ?>" class="editorial-article-card__title-link">
			<h3 class="editorial-article-card__title serif-title"><?php the_title(); ?></h3>
		</a>

		<?php if ( $sassystrides_show_excerpt && ! $sassystrides_is_compact ) : ?>
			<p class="editorial-article-card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<?php endif; ?>
	</div>

</article>
