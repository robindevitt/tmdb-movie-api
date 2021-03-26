<?php 

wp_enqueue_style( 'tmdb-movie-style', plugin_dir_url( __FILE__ ) . '../../assets/css/tmdb-plugin-style.min.css', null, '1.0', false );
wp_enqueue_script( 'tmdb-movie-script', plugin_dir_url( __FILE__ ) . '../../assets/js/movie-scripts.js', null, '1.0', false );

require( '_tmdb-api.php' );
$movie = new TMDD_Movie_Connect();

echo $movie->display_favorites();
       

add_action( 'wp_footer', 'tmdb_footer_scripts_fav' );
function tmdb_footer_scripts_fav(){ 
    ?>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script>

        jQuery(document).ready(function(){
            jQuery( "input.addToFavorites" ).change( function( e ) {
                e.preventDefault(); 
                var movieid = jQuery( this ).attr( "movieid" );
                jQuery.ajax ( {
                    type: "post",
                    dataType : "json",
                    url: '<?php echo admin_url( "admin-ajax.php" ) ;?>',
                    data: {
                        "action": "assign_favorite",
                        "movieid": movieid,
                    },
                    success: function( data ) {
                        if ( data.indexOf( "ERROR" ) !== -1 ) {
                            alert( "Please try again later." );
                        } else {
                            jQuery( this ).val( data );
                        }
                    }
                } );
            });
        });
    </script>
<?php }

?>


