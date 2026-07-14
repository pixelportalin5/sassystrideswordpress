<?php
/**
 * Template Name: About
 *
 * Empty shell — the actual markup now lives in this Page's content field
 * in wp-admin, pasted as a Custom HTML block. The HTML that used to be
 * hardcoded here (ported from AboutPage.jsx) is saved in
 * page-codes-backup.txt for reference/copy-paste.
 */

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
