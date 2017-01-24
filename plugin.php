<?php
/*
Plugin Name: Anti Youtube Preview
Plugin URI: https://github.com/DatDraggy/no-youtube-preview
Description: This plugin will remove the yotube preview from telegram and similar websites/programms
Version: 0.5
Author: DatDraggy
Author URI: https://www.kieran.pw/
*/


yourls_add_action( 'pre_redirect', 'no_preview_func');

function no_preview_func( $args ) {
	$location  = $args[0];
	$code = $args[1];
	if(substr($location, 0, 23) == "https://www.youtube.com" || substr($location, 0, 16) == "https://youtu.be" ){
		echo '<meta name="description" content="' . $location . '" />
				<meta property="og:title" content="Youtube" />
				<meta property="og:description" content="' . $location . '" />
				<meta http-equiv="refresh" content="1;URL=' . $location . '">';
	}
	else{
		//yourls_do_action( 'pre_redirect', $location, $code );
		$location = yourls_apply_filter( 'redirect_location', $location, $code );
		$code     = yourls_apply_filter( 'redirect_code', $code, $location );
		// Redirect, either properly if possible, or via Javascript otherwise
		if( !headers_sent() ) {
			yourls_status_header( $code );
			header( "Location: $location" );
		} else {
			yourls_redirect_javascript( $location );
		}
	}
	die;
}
