<?php
/*
Plugin Name: Anti Youtube Preview
Plugin URI: https://github.com/DatDraggy/no-youtube-preview
Description: This plugin will remove the yotube preview from telegram and similar websites/programms
Version: 0.5
Author: DatDraggy
Author URI: https://www.kieran.pw/
*/

//Set to false if you want to remove the entire preview. It will look like this https://puu.sh/txQl4/f221c856d0.png
//Default is true. https://puu.sh/txQmo/42720f723a.png
$metapreview = true;

yourls_add_action( 'pre_redirect', 'no_preview_func');

function no_preview_func( $args ) {
	$location  = $args[0];
	$code = $args[1];
	//test for youtube URL, if not true, use usual method
	if(substr($location, 0, 23) == "https://www.youtube.com" || substr($location, 0, 16) == "https://youtu.be" ){
    if ($metapreview){
				//set meta for the minified URL preview
		      echo '<meta name="description" content="' . $location . '" />
				        <meta property="og:title" content="Youtube" />
				        <meta property="og:description" content="' . $location . '" />';
    }
		//set the redirect to 1 sec so the youtube preview doesn't show
		echo '<meta http-equiv="refresh" content="1;URL=' . $location . '">';
	}
	else{
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
