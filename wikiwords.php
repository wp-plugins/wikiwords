<?php
/**
 * Plugin Name: WikiWords
 * Description: Scans your pages for CamelCaseWords, or WikiWords, and links them to appropriate internal pages.
 * Version: 1.0
 * Authors: Chris Rudzki
 * Author URI: http://chris.ink/goods/
 * License: GPLv2
 */


function identify_wikiwords($content) {

	// Regex pattern matching CamelCaseWords
	$search = '/\b[A-Z][a-zA-Z]*(?:[a-z][a-zA-Z]*[A-Z]|[A-Z][a-zA-Z]*[a-z])[a-zA-Z]*\b/';
	
	preg_match_all($search, $content, $matches);

	return $matches;

}


function replace_wikiwords($content) {
	$matches = identify_wikiwords($content);
	foreach ($matches[0] as $match) {
		$pagematch = get_page_by_title( $match );
		if ($pagematch) {
			$replace = '<a href="/' . $match . '">' . $match . '</a>';
		}
		else {
			$replace = '<a href="/wp-admin/post-new.php?post_type=page&post_title=' . $match . '">' . $match . '</a><sup>?</sup>';
		} 
		$content = str_replace( $match, $replace, $content );
		
	}	
	return $content;
}

add_filter('the_content', 'replace_wikiwords');
