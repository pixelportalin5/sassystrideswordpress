<?php
/**
 * Sassy Strides theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Forminator form ID used on page-contact.php. Mirrors
 * constants/forminator.js (FORMINTATOR_FORM_ID), which defaulted to the
 * "1669" form already published on the WordPress backend.
 */
if ( ! defined( 'SASSYSTRIDES_FORMINATOR_FORM_ID' ) ) {
	define( 'SASSYSTRIDES_FORMINATOR_FORM_ID', 1669 );
}

/**
 * Theme setup.
 */
function sassystrides_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'sassy-strides' ),
		)
	);
}
add_action( 'after_setup_theme', 'sassystrides_setup' );

/**
 * Renders primary nav items as flat <a> tags (no <li>/<ul> wrapper),
 * matching the original Navbar.jsx structure: a row of `.site-header__nav-link`
 * anchors with an `.is-active` class on the current item.
 */
class SassyStrides_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		// Flat structure only; no dropdown submenus in the original Navbar.
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		// Flat structure only; no dropdown submenus in the original Navbar.
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$is_active = array_intersect( array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ), $classes );

		$link_class = 'site-header__nav-link' . ( $is_active ? ' is-active' : '' );

		$output .= '<a href="' . esc_url( $item->url ) . '" class="' . esc_attr( $link_class ) . '"';
		if ( $is_active ) {
			$output .= ' aria-current="page"';
		}
		$output .= '>' . esc_html( $item->title ) . '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		// Nothing to close; start_el() already emits the full <a>...</a>.
	}
}

/**
 * Resolves a top-level category slug (fashion, beauty, etc.) to its
 * WordPress archive URL. Falls back to a flat home_url()/slug/ path if
 * the term doesn't exist yet, so footer/nav links don't fatal on a
 * fresh install before categories are created.
 */
function sassystrides_get_category_url( $slug ) {
	$term = get_term_by( 'slug', $slug, 'category' );

	if ( $term && ! is_wp_error( $term ) ) {
		return get_category_link( $term );
	}

	return home_url( '/' . $slug . '/' );
}

/**
 * Shared social link list, mirrors constants/social.js. Used by footer.php
 * and page-contact.php so both stay in sync from a single source.
 */
function sassystrides_get_social_links() {
	return array(
		array(
			'label' => 'Instagram',
			'href'  => 'https://www.instagram.com/thesassy_strides/',
		),
		array(
			'label' => 'Facebook',
			'href'  => 'https://www.facebook.com/profile.php?id=61590864083802',
		),
	); // TODO: move to Customizer/ACF options once available.
}

/**
 * Advanced Ads placement ID lookup. Mirrors constants/adSlotMappings.js
 * so `the_ad( sassystrides_get_ad_id( 'homepage', 1 ) )` resolves to the
 * same placement the React <AdSlot page="homepage" slot={1} /> used.
 */
function sassystrides_get_ad_id( $page, $slot ) {
	$homepage_slots = array(
		1  => 1549,
		2  => 1553,
		3  => 1552,
		4  => 1550,
		5  => 1551,
		6  => 1547,
		7  => 1554,
		8  => 1555,
		9  => 1523,
		10 => 1586,
		11 => 1588,
		12 => 1590,
		13 => 1592,
		14 => 1594,
		15 => 1596,
	);

	$category_slots = array(
		1 => 1600,
		2 => 1602,
		3 => 1605,
		4 => 1607,
		5 => 1609,
		6 => 1611,
		7 => 1613,
	);

	$map = ( 'category' === $page ) ? $category_slots : $homepage_slots;

	return isset( $map[ $slot ] ) ? $map[ $slot ] : null;
}

/**
 * Whether a top-level category slug gets the extra Advanced Ads placements
 * (billboard, mid-inline, sidebar). Mirrors constants/featuredPageAds.js's
 * FEATURED_PAGE_SLUGS.
 */
function sassystrides_is_featured_page( $slug ) {
	$featured_slugs = array( 'fashion', 'beauty', 'lifestyle', 'trends' );

	return in_array( $slug, $featured_slugs, true );
}

/**
 * Article body ad injection. Ports utils/normalizeArticleHtml.js and
 * utils/adInjection.js's splitArticleParagraphs()/getArticleAdParagraphIndexes()
 * so single.php can interleave Advanced Ads at the same block positions
 * ArticleContentWithAds.jsx did.
 */

function sassystrides_split_list_item_content( $content ) {
	$trimmed = trim( (string) $content );

	if ( '' === $trimmed ) {
		return null;
	}

	if ( ! preg_match( '/^([\s\S]*?)<br\s*\/?>([\s\S]*)$/i', $trimmed, $matches ) ) {
		return array(
			'heading' => '',
			'body'    => $trimmed,
		);
	}

	$heading = trim( $matches[1] );
	$body    = trim( $matches[2] );

	if ( '' === $heading ) {
		return array(
			'heading' => '',
			'body'    => $trimmed,
		);
	}

	return array(
		'heading' => $heading,
		'body'    => $body,
	);
}

function sassystrides_convert_list_heading( $list_item_html ) {
	$sections = sassystrides_split_list_item_content( $list_item_html );

	if ( null === $sections ) {
		return '';
	}

	if ( '' === $sections['heading'] ) {
		return '<p>' . $sections['body'] . '</p>';
	}

	$heading = '<h2 class="article-section-heading">' . $sections['heading'] . '</h2>';
	$body    = '' !== $sections['body'] ? '<p>' . $sections['body'] . '</p>' : '';

	return $heading . $body;
}

function sassystrides_strip_inline_alignment( $html ) {
	$html = preg_replace( '/\sstyle="[^"]*"/i', '', $html );

	return preg_replace_callback(
		'/\sclass="([^"]*\bhas-text-align-\w+\b[^"]*)"/i',
		function ( $matches ) {
			$classes = array_filter(
				preg_split( '/\s+/', $matches[1] ),
				function ( $name ) {
					return 0 !== strpos( $name, 'has-text-align-' );
				}
			);
			$cleaned = trim( implode( ' ', $classes ) );

			return $cleaned ? ' class="' . $cleaned . '"' : '';
		},
		$html
	);
}

/**
 * Converts `<ul class="wp-block-list"><li>Heading<br>Body</li></ul>` blocks
 * into a heading + paragraph, and strips inline style/text-align classes.
 */
function sassystrides_normalize_article_html( $html ) {
	$trimmed = trim( (string) $html );

	if ( '' === $trimmed ) {
		return '';
	}

	$without_list_headings = preg_replace_callback(
		'/<(?:ol|ul)[^>]*wp-block-list[^>]*>\s*<li>([\s\S]*?)<\/li>\s*<\/(?:ol|ul)>/i',
		function ( $matches ) {
			return sassystrides_convert_list_heading( $matches[1] );
		},
		$trimmed
	);

	return sassystrides_strip_inline_alignment( $without_list_headings );
}

/**
 * Splits normalized article HTML into top-level block elements (paragraphs,
 * headings, figures, lists...), mirroring splitArticleParagraphs()'s
 * DOMParser branch, with a regex </p>-boundary fallback if DOMDocument
 * is unavailable.
 */
function sassystrides_split_article_blocks( $html ) {
	$html = trim( (string) $html );

	if ( '' === $html ) {
		return array();
	}

	$blocks = array();

	if ( class_exists( 'DOMDocument' ) ) {
		$dom          = new DOMDocument();
		$prev_setting = libxml_use_internal_errors( true );
		$dom->loadHTML( '<?xml encoding="UTF-8"><div>' . $html . '</div>', LIBXML_NOERROR | LIBXML_NOWARNING );
		libxml_clear_errors();
		libxml_use_internal_errors( $prev_setting );

		$wrapper = $dom->getElementsByTagName( 'div' )->item( 0 );

		if ( $wrapper ) {
			foreach ( $wrapper->childNodes as $node ) {
				if ( XML_ELEMENT_NODE !== $node->nodeType ) {
					continue;
				}

				$block_html = trim( $dom->saveHTML( $node ) );

				if ( '' !== $block_html ) {
					$blocks[] = $block_html;
				}
			}
		}
	}

	if ( ! empty( $blocks ) ) {
		return $blocks;
	}

	$segments   = preg_split( '/(?=<\/p>)/i', $html );
	$paragraphs = array();
	$buffer     = '';

	foreach ( $segments as $segment ) {
		$buffer .= $segment;

		if ( preg_match( '/<\/p>/i', $segment ) ) {
			$paragraphs[] = $buffer;
			$buffer       = '';
		}
	}

	if ( '' !== trim( $buffer ) ) {
		$paragraphs[] = $buffer;
	}

	return $paragraphs;
}

/**
 * Advanced Ads placement IDs for in-article banners. Mirrors
 * constants/bannerPlacements.js's ARTICLE_BANNER_IDS.
 */
function sassystrides_get_article_ad_id( $placement ) {
	$map = array(
		'after_fashion'          => 1550,
		'after_featured_stories' => 1553,
	);

	return isset( $map[ $placement ] ) ? $map[ $placement ] : null;
}

/**
 * Which block index each in-article ad should render after. Mirrors
 * ArticleContentWithAds.jsx's ARTICLE_INLINE_ADS + adByParagraph logic:
 * ad 1 after block 1 once there's more than 1 block, ad 2 after block 4
 * once there are more than 4 blocks.
 *
 * @return array<int,int> Block index => Advanced Ads placement ID.
 */
function sassystrides_get_article_ad_placements( $block_count ) {
	$placements = array();

	if ( $block_count > 1 ) {
		$placements[1] = sassystrides_get_article_ad_id( 'after_fashion' );
	}

	if ( $block_count > 4 ) {
		$placements[4] = sassystrides_get_article_ad_id( 'after_featured_stories' );
	}

	return $placements;
}

/**
 * Words-per-minute reading time estimate. Mirrors getReadingTime() in
 * services/wordpressApi.js (220 wpm, minimum 1 minute).
 */
function sassystrides_get_reading_time( $html ) {
	$text  = wp_strip_all_tags( (string) $html );
	$words = preg_split( '/\s+/', trim( $text ), -1, PREG_SPLIT_NO_EMPTY );

	return max( 1, (int) ceil( count( $words ) / 220 ) );
}

/**
 * Enqueue theme styles.
 */
function sassystrides_scripts() {
	wp_enqueue_style(
		'sassystrides-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);

	if ( is_page( 'faq' ) ) {
		wp_enqueue_script(
			'sassystrides-faq-accordion',
			get_template_directory_uri() . '/assets/js/faq-accordion.js',
			array(),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'sassystrides_scripts' );
