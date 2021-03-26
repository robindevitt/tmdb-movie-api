<?php

add_shortcode( 'movies_output', 'movies_template', 10, 2 );

function movies_template( $output ) {

	include( 'tmdb/movies_template.php' );
	
}

add_shortcode( 'movies_favorites', 'movies_favorites_template', 10, 2 );

function movies_favorites_template( $output ) {

	include( 'tmdb/movies_favorites_template.php' );
	
}

add_shortcode( 'contact_me', 'contact_robin', 10, 2 );

function contact_robin( $output ) {

	include( 'tmdb/contact_template.php' );
	
}

?>