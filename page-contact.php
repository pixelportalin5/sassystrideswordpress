<?php
/**
 * Template Name: Contact
 *
 * Empty shell — the actual markup now lives in this Page's content field
 * in wp-admin, pasted as a Custom HTML block. The HTML that used to be
 * hardcoded here (ported from ContactPage.jsx) is saved in
 * page-codes-backup.txt for reference/copy-paste.
 *
 * The backed-up markup includes the literal [forminator_form id="1669"]
 * shortcode — that still renders correctly from a Custom HTML block
 * because the_content() runs the whole page through do_shortcode().
 */

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
