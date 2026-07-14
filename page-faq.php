<?php
/**
 * Template Name: FAQ
 *
 * Empty shell — the actual markup now lives in this Page's content field
 * in wp-admin, pasted as a Custom HTML block. The HTML that used to be
 * hardcoded here (ported from FaqPage.jsx) is saved in
 * page-codes-backup.txt for reference/copy-paste.
 *
 * assets/js/faq-accordion.js (enqueued below via is_page('faq')) still
 * drives the open/close behavior — it targets .faq-accordion__item /
 * .faq-accordion__trigger by class, regardless of where that markup
 * comes from.
 */

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
