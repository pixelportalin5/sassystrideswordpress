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
	$value = (string) $value;
	$value = preg_replace( '/<[^>]+>/', ' ', $value );
	$value = str_replace( '&nbsp;', ' ', $value );
	$value = str_replace( '&amp;', '&', $value );
	$value = preg_replace( '/\s+/', ' ', $value );
	return trim( $value );
}

/**
 * Mirrors resolveCategorySlug(): the post's primary category if it's one
 * of the 5 top-level slugs, else 'fashion'.
 */
function sassystrides_resolve_post_category_slug( $post_id ) {
	static $cache = array();

	if ( isset( $cache[ $post_id ] ) ) {
		return $cache[ $post_id ];
	}

	$slug = 'fashion';

	// wp_get_post_categories() rather than get_the_category() — the latter
	// re-sorts a post's categories alphabetically, so a post assigned to
	// both e.g. "Beauty" and a leftover "Uncategorized"/import category
	// could have the *wrong* one land at index 0. This checks every
	// assigned category and takes the first one that's actually one of
	// our 5 top-level slugs, in whichever order WordPress assigned them.
	$sassystrides_terms = wp_get_post_categories( $post_id, array( 'fields' => 'all' ) );

	if ( ! empty( $sassystrides_terms ) && ! is_wp_error( $sassystrides_terms ) ) {
		foreach ( $sassystrides_terms as $sassystrides_term ) {
			$candidate = sassystrides_slugify( $sassystrides_term->slug );
			if ( in_array( $candidate, array( 'fashion', 'beauty', 'lifestyle', 'trends', 'news' ), true ) ) {
				$slug = $candidate;
				break;
			}
		}
	}

	$cache[ $post_id ] = $slug;
	return $slug;
}

/**
 * Mirrors resolveSubcategorySlug(): a direct tag match wins, otherwise the
 * subcategory whose keywords appear most often across the post's title,
 * excerpt, content, category, and tags — falling back to the parent's
 * first subcategory, exactly like the SPA.
 */
function sassystrides_resolve_post_subcategory_slug( $post_id, $category_slug ) {
	static $cache = array();
	$cache_key = $post_id . ':' . $category_slug;

	if ( isset( $cache[ $cache_key ] ) ) {
		return $cache[ $cache_key ];
	}

	$subcategories = sassystrides_get_subcategories_by_parent( $category_slug );

	if ( empty( $subcategories ) ) {
		return $cache[ $cache_key ] = 'clothing'; // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition
	}

	$tags      = get_the_tags( $post_id );
	$tag_slugs = array();
	$tag_names = array();

	if ( $tags ) {
		foreach ( $tags as $sassystrides_tag ) {
			$tag_slugs[] = sassystrides_slugify( $sassystrides_tag->slug );
			$tag_names[] = sassystrides_slugify( $sassystrides_tag->name );
		}
	}

	foreach ( $subcategories as $sassystrides_sub ) {
		if ( in_array( $sassystrides_sub['slug'], $tag_slugs, true ) ) {
			return $cache[ $cache_key ] = $sassystrides_sub['slug']; // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition
		}
	}

	foreach ( $subcategories as $sassystrides_sub ) {
		if ( in_array( sassystrides_slugify( $sassystrides_sub['name'] ), $tag_names, true ) ) {
			return $cache[ $cache_key ] = $sassystrides_sub['slug']; // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition
		}
	}

	$haystack_parts = array(
		get_the_title( $post_id ),
		get_the_excerpt( $post_id ),
		(string) get_post_field( 'post_content', $post_id ),
	);

	foreach ( get_the_category( $post_id ) as $sassystrides_cat ) {
		$haystack_parts[] = $sassystrides_cat->name;
	}
	foreach ( $tag_names as $sassystrides_tag_name ) {
		$haystack_parts[] = $sassystrides_tag_name;
	}
	foreach ( $tag_slugs as $sassystrides_tag_slug ) {
		$haystack_parts[] = $sassystrides_tag_slug;
	}

	$search_text = sassystrides_slugify( implode( ' ', array_map( 'sassystrides_to_plain_text', $haystack_parts ) ) );

	$best_match = $subcategories[0];
	$best_score = 0;

	foreach ( $subcategories as $sassystrides_sub ) {
		$score = 0;
		foreach ( $sassystrides_sub['keywords'] as $sassystrides_keyword ) {
			$token = sassystrides_slugify( $sassystrides_keyword );
			if ( '' !== $token && false !== strpos( $search_text, $token ) ) {
				++$score;
			}
		}
		if ( $score > $best_score ) {
			$best_match = $sassystrides_sub;
			$best_score = $score;
		}
	}

	return $cache[ $cache_key ] = $best_match['slug']; // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition
}

function sassystrides_post_permalink( $post_link, $post ) {
	if ( ! ( $post instanceof WP_Post ) || 'post' !== $post->post_type || 'publish' !== $post->post_status ) {
		return $post_link;
	}

	$category_slug    = sassystrides_resolve_post_category_slug( $post->ID );
	$subcategory_slug = sassystrides_resolve_post_subcategory_slug( $post->ID, $category_slug );

	return home_url( '/' . $category_slug . '/' . $subcategory_slug . '/' . $post->post_name . '/' );
}
add_filter( 'post_link', 'sassystrides_post_permalink', 10, 2 );

/**
 * Resolves incoming /{anything}/{anything}/{postname}/ requests straight
 * to the post by its trailing slug segment — the leading 2 segments are
 * cosmetic (same as the SPA's router, which only reads :slug out of
 * /:categorySlug/:subSlug/:slug and ignores the rest). Registered after
 * the category/subcategory listing rules above so more specific 1- and
 * 2-segment matches (and their /page/N/ pagination) still win first.
 */
function sassystrides_register_post_permalink_rewrites() {
	add_rewrite_rule(
		'^[^/]+/[^/]+/([^/]+)/?$',
		'index.php?post_type=post&name=$matches[1]',
		'top'
	);
}
add_action( 'init', 'sassystrides_register_post_permalink_rewrites' );

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
 * Homepage-only Advanced Ads ID lookup. Mirrors constants/adSlotMappings.js
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
 * Dynamic Advanced Ads title lookup used by category and single templates.
 * The title format is `Parent - Child - Placement`, where the placement is
 * resolved from a placement slug like `cat-top-banner` or `post-middle`.
 */
function sassystrides_get_dynamic_ad_placement_names() {
	return array(
		'cat-top-banner' => 'Top Banner',
		'cat-sidebar-1'  => 'Sidebar 1',
		'cat-sidebar-2'  => 'Sidebar 2',
		'cat-sidebar-3'  => 'Sidebar 3',
		'cat-in-feed-1'  => 'In Feed 1',
		'cat-in-feed-2'  => 'In Feed 2',
		'cat-bottom-1'   => 'Bottom 1',
		'cat-bottom-2'   => 'Bottom 2',
		'cat-bottom-3'   => 'Bottom 3',
		'post-top'       => 'Post Top',
		'post-middle'    => 'Post Middle',
		'post-bottom'    => 'Post Bottom',
	);
}

function sassystrides_get_dynamic_ad_title_map() {
	static $title_map = null;

	if ( null !== $title_map ) {
		return $title_map;
	}

	$cache_key  = 'sassystrides_dynamic_ad_title_map';
	$cached_map = wp_cache_get( $cache_key, 'sassystrides' );

	if ( false !== $cached_map && is_array( $cached_map ) ) {
		$title_map = $cached_map;
		return $title_map;
	}

	$title_map = array();
	$ad_posts  = get_posts(
		array(
			'post_type'              => 'advanced_ads',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'orderby'                => 'title',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'suppress_filters'       => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $ad_posts as $ad_post ) {
		if ( ! ( $ad_post instanceof WP_Post ) ) {
			continue;
		}

		$ad_title = trim( wp_strip_all_tags( $ad_post->post_title ) );
		if ( '' === $ad_title || isset( $title_map[ $ad_title ] ) ) {
			continue;
		}

		$title_map[ $ad_title ] = (int) $ad_post->ID;
	}

	wp_cache_set( $cache_key, $title_map, 'sassystrides' );

	return $title_map;
}

function sassystrides_find_dynamic_ad_id( $ad_title ) {
	$ad_title = trim( wp_strip_all_tags( (string) $ad_title ) );
	if ( '' === $ad_title ) {
		return 0;
	}

	static $lookup_cache = array();

	if ( array_key_exists( $ad_title, $lookup_cache ) ) {
		return $lookup_cache[ $ad_title ];
	}

	$title_map = sassystrides_get_dynamic_ad_title_map();
	$ad_id     = isset( $title_map[ $ad_title ] ) ? (int) $title_map[ $ad_title ] : 0;

	$lookup_cache[ $ad_title ] = $ad_id;

	return $ad_id;
}

function sassystrides_get_category_ad_context() {
	$parent_slug = get_query_var( 'category_name' );
	$child_slug  = get_query_var( 'sassystrides_subcategory' );

	if ( $parent_slug && $child_slug ) {
		$parent_term = get_term_by( 'slug', $parent_slug, 'category' );
		$child_term  = sassystrides_get_subcategory( $parent_slug, $child_slug );

		if ( $parent_term && ! is_wp_error( $parent_term ) && $child_term ) {
			return array(
				'parent_label' => $parent_term->name,
				'child_label'  => $child_term['name'],
			);
		}
	}

	$current_term = get_queried_object();
	if ( ! ( $current_term instanceof WP_Term ) || 'category' !== $current_term->taxonomy ) {
		return null;
	}

	if ( $current_term->parent ) {
		$parent_term = get_term( $current_term->parent, 'category' );

		if ( ! $parent_term || is_wp_error( $parent_term ) ) {
			return null;
		}

		return array(
			'parent_label' => $parent_term->name,
			'child_label'  => $current_term->name,
		);
	}

	return array(
		'parent_label' => $current_term->name,
		'child_label'  => 'All ' . $current_term->name,
	);
}

function sassystrides_get_post_ad_context() {
	$post_id = get_queried_object_id();
	if ( ! $post_id ) {
		return null;
	}

	$categories = get_the_category( $post_id );
	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return null;
	}

	$top_level_category = null;

	foreach ( $categories as $category ) {
		if ( ! ( $category instanceof WP_Term ) ) {
			continue;
		}

		if ( $category->parent ) {
			$parent_term = get_term( $category->parent, 'category' );

			if ( $parent_term && ! is_wp_error( $parent_term ) ) {
				return array(
					'parent_label' => $parent_term->name,
					'child_label'  => $category->name,
				);
			}
		}

		if ( in_array( $category->slug, sassystrides_get_featured_category_slugs(), true ) && ! $top_level_category ) {
			$top_level_category = $category;
		}
	}

	if ( ! $top_level_category ) {
		$top_level_category = $categories[0];
	}

	if ( ! ( $top_level_category instanceof WP_Term ) ) {
		return null;
	}

	return array(
		'parent_label' => $top_level_category->name,
		'child_label'  => 'All ' . $top_level_category->name,
	);
}

function sassystrides_get_dynamic_ad_context( $placement_slug ) {
	if ( 0 === strpos( $placement_slug, 'post-' ) ) {
		return sassystrides_get_post_ad_context();
	}

	return sassystrides_get_category_ad_context();
}

function render_dynamic_ad( $placement_slug ) {
	$placement_slug = sanitize_key( (string) $placement_slug );
	if ( '' === $placement_slug ) {
		return;
	}

	$placement_names = sassystrides_get_dynamic_ad_placement_names();
	if ( ! isset( $placement_names[ $placement_slug ] ) ) {
		return;
	}

	$context = sassystrides_get_dynamic_ad_context( $placement_slug );
	if ( ! $context || empty( $context['parent_label'] ) || empty( $context['child_label'] ) ) {
		return;
	}

	$ad_title = $context['parent_label'] . ' - ' . $context['child_label'] . ' - ' . $placement_names[ $placement_slug ];
	$ad_id    = sassystrides_find_dynamic_ad_id( $ad_title );

	if ( ! $ad_id || ! function_exists( 'the_ad' ) ) {
		return;
	}

	the_ad( $ad_id );
}

/**
 * Whether a top-level category slug gets the extra Advanced Ads placements.
 * Mirrors constants/featuredPageAds.js's FEATURED_PAGE_SLUGS.
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
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sassystrides_scripts' );

/**
 * Header search AJAX endpoint. Uses admin-ajax.php rather than the REST
 * API — REST routes are more commonly blocked/restricted by security
 * plugins and some hosts, while admin-ajax.php is core WordPress
 * plumbing that's essentially always reachable, logged in or not.
 */
function sassystrides_ajax_search() {
	$sassystrides_query = isset( $_GET['query'] ) ? sanitize_text_field( wp_unslash( $_GET['query'] ) ) : '';

	if ( mb_strlen( $sassystrides_query ) < 2 ) {
		wp_send_json( array() );
	}

	$sassystrides_search_query = new WP_Query(
		array(
			's'                   => $sassystrides_query,
			'post_status'         => 'publish',
			'posts_per_page'      => 8,
			'ignore_sticky_posts' => true,
		)
	);

	$sassystrides_results = array();
	foreach ( $sassystrides_search_query->posts as $sassystrides_result_post ) {
		$sassystrides_results[] = array(
			'id'    => $sassystrides_result_post->ID,
			'title' => get_the_title( $sassystrides_result_post ),
			'link'  => get_permalink( $sassystrides_result_post ),
		);
	}

	wp_reset_postdata();
	wp_send_json( $sassystrides_results );
}
add_action( 'wp_ajax_sassystrides_search', 'sassystrides_ajax_search' );
add_action( 'wp_ajax_nopriv_sassystrides_search', 'sassystrides_ajax_search' );
