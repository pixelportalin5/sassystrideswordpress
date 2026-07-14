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
 * Subcategories are NOT real WordPress taxonomy terms — the React app
 * defines them as a static list (constants/subcategories.js) with keyword
 * arrays, and filters posts already in the parent category by matching
 * those keywords against each post's title/excerpt/content/category/tags.
 * Mirrored here verbatim so /fashion/street-style etc. resolve to the same
 * posts the SPA would have shown, without requiring child category terms
 * to exist in wp-admin.
 */
function sassystrides_get_subcategories() {
	return array(
		array( 'parent_slug' => 'fashion', 'slug' => 'clothing', 'name' => __( 'Clothing', 'sassy-strides' ), 'description' => __( 'Discover modern clothing styles, outfit ideas, and wardrobe essentials for every season.', 'sassy-strides' ), 'keywords' => array( 'clothing', 'outfit', 'wardrobe', 'apparel', 'dress', 'wear' ) ),
		array( 'parent_slug' => 'fashion', 'slug' => 'fashion-week', 'name' => __( 'Fashion Week', 'sassy-strides' ), 'description' => __( 'Get runway highlights, designer collections, and global Fashion Week updates.', 'sassy-strides' ), 'keywords' => array( 'fashion week', 'runway', 'designer', 'collection', 'catwalk' ) ),
		array( 'parent_slug' => 'fashion', 'slug' => 'look-of-the-day', 'name' => __( 'Look of the Day', 'sassy-strides' ), 'description' => __( 'Daily fashion inspiration featuring stylish celebrity and influencer outfits.', 'sassy-strides' ), 'keywords' => array( 'look of the day', 'celebrity', 'influencer', 'outfit', 'style' ) ),
		array( 'parent_slug' => 'fashion', 'slug' => 'accessories', 'name' => __( 'Accessories', 'sassy-strides' ), 'description' => __( 'Explore trending bags, jewelry, belts, and fashion accessories to elevate your look.', 'sassy-strides' ), 'keywords' => array( 'accessories', 'bags', 'jewelry', 'belts', 'handbag' ) ),
		array( 'parent_slug' => 'fashion', 'slug' => 'shoes', 'name' => __( 'Shoes', 'sassy-strides' ), 'description' => __( 'Find the latest footwear trends, from sneakers to heels and everyday comfort styles.', 'sassy-strides' ), 'keywords' => array( 'shoes', 'footwear', 'sneakers', 'heels', 'boots' ) ),
		array( 'parent_slug' => 'beauty', 'slug' => 'hair', 'name' => __( 'Hair', 'sassy-strides' ), 'description' => __( 'Haircare tips, hairstyles, and trending hair transformations for every type.', 'sassy-strides' ), 'keywords' => array( 'hair', 'haircare', 'hairstyle', 'salon' ) ),
		array( 'parent_slug' => 'beauty', 'slug' => 'skin', 'name' => __( 'Skin', 'sassy-strides' ), 'description' => __( 'Skincare routines, product guides, and healthy glowing skin solutions.', 'sassy-strides' ), 'keywords' => array( 'skin', 'skincare', 'glow', 'serum', 'moisturizer' ) ),
		array( 'parent_slug' => 'beauty', 'slug' => 'makeup', 'name' => __( 'Makeup', 'sassy-strides' ), 'description' => __( 'Latest makeup trends, tutorials, and product recommendations for all looks.', 'sassy-strides' ), 'keywords' => array( 'makeup', 'cosmetics', 'lipstick', 'foundation', 'beauty' ) ),
		array( 'parent_slug' => 'beauty', 'slug' => 'nails', 'name' => __( 'Nails', 'sassy-strides' ), 'description' => __( 'Nail art ideas, manicure trends, and seasonal nail inspiration.', 'sassy-strides' ), 'keywords' => array( 'nails', 'manicure', 'nail art', 'polish' ) ),
		array( 'parent_slug' => 'beauty', 'slug' => 'fragrance', 'name' => __( 'Fragrance', 'sassy-strides' ), 'description' => __( 'Explore perfumes, scent trends, and fragrance guides for every mood.', 'sassy-strides' ), 'keywords' => array( 'fragrance', 'perfume', 'scent', 'cologne' ) ),
		array( 'parent_slug' => 'lifestyle', 'slug' => 'airport-style', 'name' => __( 'Airport Style', 'sassy-strides' ), 'description' => __( 'Comfortable yet stylish travel outfit ideas for airport looks.', 'sassy-strides' ), 'keywords' => array( 'airport', 'travel', 'flight', 'luggage' ) ),
		array( 'parent_slug' => 'lifestyle', 'slug' => 'office', 'name' => __( 'Office', 'sassy-strides' ), 'description' => __( 'Professional and trendy workwear inspiration for modern office fashion.', 'sassy-strides' ), 'keywords' => array( 'office', 'workwear', 'professional', 'workplace', 'corporate' ) ),
		array( 'parent_slug' => 'lifestyle', 'slug' => 'street-style', 'name' => __( 'Street Style', 'sassy-strides' ), 'description' => __( 'Real-world fashion inspiration from global street style trends.', 'sassy-strides' ), 'keywords' => array( 'street style', 'streetwear', 'urban' ) ),
		array( 'parent_slug' => 'lifestyle', 'slug' => 'holiday', 'name' => __( 'Holiday', 'sassy-strides' ), 'description' => __( 'Vacation outfit ideas and travel fashion inspiration for every destination.', 'sassy-strides' ), 'keywords' => array( 'holiday', 'vacation', 'resort', 'getaway', 'travel' ) ),
		array( 'parent_slug' => 'lifestyle', 'slug' => 'party', 'name' => __( 'Party', 'sassy-strides' ), 'description' => __( 'Glamorous party looks and styling ideas for special occasions.', 'sassy-strides' ), 'keywords' => array( 'party', 'evening', 'celebration', 'gala', 'occasion' ) ),
		array( 'parent_slug' => 'trends', 'slug' => 'spring', 'name' => __( 'Spring', 'sassy-strides' ), 'description' => __( 'Fresh spring fashion, colors, and style inspiration.', 'sassy-strides' ), 'keywords' => array( 'spring', 'seasonal' ) ),
		array( 'parent_slug' => 'trends', 'slug' => 'summer', 'name' => __( 'Summer', 'sassy-strides' ), 'description' => __( 'Lightweight, trendy summer outfits and seasonal beauty looks.', 'sassy-strides' ), 'keywords' => array( 'summer', 'seasonal' ) ),
		array( 'parent_slug' => 'trends', 'slug' => 'autumn', 'name' => __( 'Autumn', 'sassy-strides' ), 'description' => __( 'Warm, stylish autumn fashion and cozy seasonal trends.', 'sassy-strides' ), 'keywords' => array( 'autumn', 'fall', 'seasonal' ) ),
		array( 'parent_slug' => 'trends', 'slug' => 'winter', 'name' => __( 'Winter', 'sassy-strides' ), 'description' => __( 'Elegant winter outfits, layering ideas, and cold-weather style tips.', 'sassy-strides' ), 'keywords' => array( 'winter', 'layering', 'seasonal' ) ),
		array( 'parent_slug' => 'news', 'slug' => 'awards-events', 'name' => __( 'Awards & Events', 'sassy-strides' ), 'description' => __( 'Coverage of fashion awards, red carpet events, and industry shows.', 'sassy-strides' ), 'keywords' => array( 'awards', 'events', 'red carpet', 'gala', 'ceremony' ) ),
		array( 'parent_slug' => 'news', 'slug' => 'entertainment', 'name' => __( 'Entertainment', 'sassy-strides' ), 'description' => __( 'Celebrity news, lifestyle updates, and pop culture stories.', 'sassy-strides' ), 'keywords' => array( 'entertainment', 'celebrity', 'pop culture' ) ),
	);
}

function sassystrides_get_subcategories_by_parent( $parent_slug ) {
	return array_values(
		array_filter(
			sassystrides_get_subcategories(),
			function ( $sassystrides_sub ) use ( $parent_slug ) {
				return $sassystrides_sub['parent_slug'] === $parent_slug;
			}
		)
	);
}

function sassystrides_get_subcategory( $parent_slug, $sub_slug ) {
	foreach ( sassystrides_get_subcategories() as $sassystrides_sub ) {
		if ( $sassystrides_sub['parent_slug'] === $parent_slug && $sassystrides_sub['slug'] === $sub_slug ) {
			return $sassystrides_sub;
		}
	}

	return null;
}

/**
 * Mirrors postMatchesKeyword() in SubcategoryPage.jsx: a post "belongs" to
 * a virtual subcategory if any of its keywords appear in the post's
 * title, excerpt, content, category name, or tag names.
 */
function sassystrides_post_matches_keywords( $post_id, array $keywords ) {
	if ( empty( $keywords ) ) {
		return false;
	}

	$sassystrides_haystack_parts = array(
		get_the_title( $post_id ),
		get_the_excerpt( $post_id ),
		wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ),
	);

	foreach ( get_the_category( $post_id ) as $sassystrides_term ) {
		$sassystrides_haystack_parts[] = $sassystrides_term->name;
	}

	$sassystrides_post_tags = get_the_tags( $post_id );
	if ( $sassystrides_post_tags ) {
		foreach ( $sassystrides_post_tags as $sassystrides_tag ) {
			$sassystrides_haystack_parts[] = $sassystrides_tag->name;
		}
	}

	$sassystrides_haystack = strtolower( wp_strip_all_tags( implode( ' ', $sassystrides_haystack_parts ) ) );

	foreach ( $keywords as $sassystrides_keyword ) {
		if ( '' !== $sassystrides_keyword && false !== strpos( $sassystrides_haystack, strtolower( $sassystrides_keyword ) ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Registers /{parent}/{subcategory}/ rewrite rules for the virtual
 * subcategories above (e.g. /lifestyle/street-style/), routing them to
 * category-subcategory.php via the template_include filter below instead
 * of 404ing. Each parent's subcategory slugs are matched by exact
 * alternation (not a wildcard) so this can't accidentally swallow a real
 * post permalink that happens to share the /parent/something/ shape.
 *
 * Requires a permalinks flush after activation — visit
 * Settings > Permalinks and click "Save Changes" once.
 */
/**
 * Category URLs: /fashion/ instead of /category/fashion/, matching the
 * original React site's routes. The subcategory rewrite rules below were
 * already written assuming this flat shape, but the piece that actually
 * produces/resolves it was missing.
 *
 * 1) category_link strips "/category/" from every generated category URL
 *    (get_category_link(), which sassystrides_get_category_url() and core
 *    template tags both call).
 * 2) A rewrite rule per category routes the resulting flat URL back to the
 *    category archive, since WordPress's own generated rules still expect
 *    the /category/ prefix.
 *
 * Requires a permalinks flush after activation/update — visit
 * Settings > Permalinks and click "Save Changes" once.
 */
function sassystrides_remove_category_base( $link ) {
	return str_replace( '/category/', '/', $link );
}
add_filter( 'category_link', 'sassystrides_remove_category_base' );

function sassystrides_register_flat_category_rewrites() {
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $sassystrides_flat_category ) {
		$sassystrides_slug = preg_quote( $sassystrides_flat_category->slug, '#' );

		add_rewrite_rule(
			'^' . $sassystrides_slug . '/page/([0-9]{1,})/?$',
			'index.php?category_name=' . $sassystrides_flat_category->slug . '&paged=$matches[1]',
			'top'
		);
		add_rewrite_rule(
			'^' . $sassystrides_slug . '/?$',
			'index.php?category_name=' . $sassystrides_flat_category->slug,
			'top'
		);
	}
}
add_action( 'init', 'sassystrides_register_flat_category_rewrites' );

function sassystrides_register_subcategory_rewrites() {
	foreach ( array( 'fashion', 'beauty', 'lifestyle', 'trends', 'news' ) as $sassystrides_parent_slug ) {
		$sassystrides_sub_slugs = wp_list_pluck( sassystrides_get_subcategories_by_parent( $sassystrides_parent_slug ), 'slug' );

		if ( empty( $sassystrides_sub_slugs ) ) {
			continue;
		}

		$sassystrides_pattern = '^' . preg_quote( $sassystrides_parent_slug, '#' ) . '/(' . implode(
			'|',
			array_map(
				function ( $sassystrides_slug ) {
					return preg_quote( $sassystrides_slug, '#' );
				},
				$sassystrides_sub_slugs
			)
		) . ')/?$';

		add_rewrite_rule(
			$sassystrides_pattern,
			'index.php?category_name=' . $sassystrides_parent_slug . '&sassystrides_subcategory=$matches[1]',
			'top'
		);
	}
}
add_action( 'init', 'sassystrides_register_subcategory_rewrites' );

function sassystrides_subcategory_query_vars( $vars ) {
	$vars[] = 'sassystrides_subcategory';
	return $vars;
}
add_filter( 'query_vars', 'sassystrides_subcategory_query_vars' );

function sassystrides_subcategory_template( $template ) {
	if ( get_query_var( 'sassystrides_subcategory' ) ) {
		$sassystrides_custom_template = locate_template( 'category-subcategory.php' );

		if ( $sassystrides_custom_template ) {
			return $sassystrides_custom_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'sassystrides_subcategory_template' );

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
	return in_array( $slug, sassystrides_get_featured_category_slugs(), true );
}

/**
 * The 4 top-level category slugs eligible for the extra Advanced Ads
 * placements and, per site owner's request, the only categories the
 * homepage hero post can be pulled from (News is excluded from both).
 */
function sassystrides_get_featured_category_slugs() {
	return array( 'fashion', 'beauty', 'lifestyle', 'trends' );
}

/**
 * Resolves sassystrides_get_featured_category_slugs() to term IDs, for use
 * with WP_Query's 'category__in'. Cached per-request since it's called on
 * every front-page.php hero query.
 */
function sassystrides_get_featured_category_ids() {
	static $ids = null;

	if ( null !== $ids ) {
		return $ids;
	}

	$ids = array();
	foreach ( sassystrides_get_featured_category_slugs() as $slug ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			$ids[] = $term->term_id;
		}
	}

	return $ids;
}

/**
 * The 3 rotating cube ad unit IDs. Mirrors constants/categoryCubeAds.js's
 * CATEGORY_CUBE_ADS ([the_ad id="2293|2294|2295"]).
 */
function sassystrides_get_category_cube_ad_ids() {
	return array( 2293, 2294, 2295 );
}

/**
 * The 3 category sidebar ad unit IDs, in display order. Mirrors
 * CategorySidebar.jsx's SIDEBAR_AD_IDS = ['1611', '1605', '1600'].
 */
function sassystrides_get_category_sidebar_ad_ids() {
	return array( 1611, 1605, 1600 );
}

/**
 * Renders an Advanced Ads unit once and returns the markup as a string,
 * instead of echoing it directly. CategoryThreeCubeAds.jsx fetches each ad's
 * data once and reuses it across all 4 cube faces (front/back/left/right);
 * capturing the_ad() output here the same way avoids the_ad() being called
 * (and impressions being tracked) 4x per cube.
 */
function sassystrides_get_ad_html( $ad_id ) {
	if ( ! $ad_id || ! function_exists( 'the_ad' ) ) {
		return '';
	}

	ob_start();
	the_ad( $ad_id );
	return trim( (string) ob_get_clean() );
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

	// Header search panel lives in every page's header.php, so this loads sitewide.
	wp_enqueue_script(
		'sassystrides-header-search',
		get_template_directory_uri() . '/assets/js/header-search.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
	wp_localize_script(
		'sassystrides-header-search',
		'sassystridesSearch',
		array(
			'restUrl' => esc_url_raw( rest_url( 'wp/v2/posts' ) ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sassystrides_scripts' );
