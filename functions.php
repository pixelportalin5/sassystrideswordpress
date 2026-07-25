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
 * Post permalinks: /{category}/{subcategory}/{postname}/, ported from
 * utils/postRoutes.js's getPostPath() so links generated here match the
 * original SPA's URLs exactly (e.g. /fashion/clothing/my-post-slug). The
 * category/subcategory segments are cosmetic — like the SPA's router,
 * incoming requests are resolved purely by the trailing postname segment
 * (see sassystrides_register_post_permalink_rewrites() below), so a post
 * always resolves correctly even if its resolved category/subcategory
 * ever drifts from what's baked into an already-shared link.
 */
function sassystrides_slugify( $value ) {
	$value = strtolower( trim( (string) $value ) );
	$value = str_replace( '&', 'and', $value );
	$value = preg_replace( '/[^a-z0-9]+/', '-', $value );
	return trim( $value, '-' );
}

function sassystrides_to_plain_text( $value ) {
	$value = (string) $value;
	$value = preg_replace( '/<[^>]+>/', ' ', $value );
	$value = str_replace( '&nbsp;', ' ', $value );
	$value = str_replace( '&amp;', '&', $value );
	$value = preg_replace( '/
