<?php
/**
 * Shared layout for legal pages, ported from LegalArticleLayout.jsx.
 * Used by page-privacy-policy.php and page-terms-of-service.php.
 *
 * Note: the React version wraps each block in an <EditorialReveal> that
 * fades content in on scroll via IntersectionObserver. That's dropped
 * here for simplicity — everything renders immediately.
 *
 * Expected $args:
 *   'hero_label'          string
 *   'hero_title'          string
 *   'hero_subtitle'       string
 *   'last_updated_iso'    string, e.g. '2026-06-22'
 *   'last_updated_display' string, e.g. 'June 22, 2026'
 *   'breadcrumb'          array of ['label', 'path' (optional)]
 *   'sections'            array of ['id', 'title', 'paragraphs' => [], 'list' => [] (optional)]
 */

$sassystrides_legal_sections = isset( $args['sections'] ) && is_array( $args['sections'] ) ? $args['sections'] : array();
?>
<main class="legal-page">
	<div class="legal-page__shell">
		<?php get_template_part( 'template-parts/editorial-breadcrumb', null, array( 'items' => $args['breadcrumb'] ?? array() ) ); ?>

		<div class="legal-page__hero">
			<p class="legal-page__eyebrow"><?php echo esc_html( $args['hero_label'] ?? '' ); ?></p>
			<h1 class="legal-page__title serif-title"><?php echo esc_html( $args['hero_title'] ?? '' ); ?></h1>
			<p class="legal-page__subtitle"><?php echo esc_html( $args['hero_subtitle'] ?? '' ); ?></p>
			<p class="legal-page__updated">
				<?php esc_html_e( 'Last updated', 'sassy-strides' ); ?>
				<time datetime="<?php echo esc_attr( $args['last_updated_iso'] ?? '' ); ?>">
					<?php echo esc_html( $args['last_updated_display'] ?? '' ); ?>
				</time>
			</p>
			<div class="legal-page__hero-rule" aria-hidden="true"></div>
		</div>

		<div class="legal-page__layout">
			<div class="legal-page__toc-wrap">
				<aside class="legal-page__toc" aria-label="<?php esc_attr_e( 'Table of contents', 'sassy-strides' ); ?>">
					<p class="legal-page__toc-label"><?php esc_html_e( 'On this page', 'sassy-strides' ); ?></p>
					<nav>
						<ol class="legal-page__toc-list">
							<?php foreach ( $sassystrides_legal_sections as $sassystrides_toc_section ) : ?>
								<li>
									<a href="#<?php echo esc_attr( $sassystrides_toc_section['id'] ); ?>" class="legal-page__toc-link">
										<?php echo esc_html( $sassystrides_toc_section['title'] ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ol>
					</nav>
				</aside>
			</div>

			<div class="legal-page__article-wrap">
				<article class="legal-page__article">
					<?php foreach ( $sassystrides_legal_sections as $sassystrides_section_index => $sassystrides_section ) : ?>
						<article id="<?php echo esc_attr( $sassystrides_section['id'] ); ?>" class="legal-page__section" aria-labelledby="<?php echo esc_attr( $sassystrides_section['id'] ); ?>-heading">
							<div class="legal-page__section-marker" aria-hidden="true">
								<?php echo esc_html( str_pad( (string) ( $sassystrides_section_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>
							</div>
							<h2 id="<?php echo esc_attr( $sassystrides_section['id'] ); ?>-heading" class="legal-page__section-title serif-title">
								<?php echo esc_html( $sassystrides_section['title'] ); ?>
							</h2>
							<div class="legal-page__section-body">
								<?php foreach ( (array) ( $sassystrides_section['paragraphs'] ?? array() ) as $sassystrides_paragraph ) : ?>
									<p class="legal-page__paragraph"><?php echo esc_html( $sassystrides_paragraph ); ?></p>
								<?php endforeach; ?>

								<?php if ( ! empty( $sassystrides_section['list'] ) ) : ?>
									<ul class="legal-page__list">
										<?php foreach ( $sassystrides_section['list'] as $sassystrides_list_item ) : ?>
											<li><?php echo esc_html( $sassystrides_list_item ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</article>
			</div>
		</div>
	</div>
</main>
