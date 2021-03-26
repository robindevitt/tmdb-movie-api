<?php

class TMDD_Movie_Connect {

    /**
	 * @var string Base API URL.
	 */
	const API_URL = 'https://api.themoviedb.org/3/';

    /**
     * Dispaly Movie Favorites
     */
    function display_favorites(){

        $usermovies  = '';
        if ( is_user_logged_in() ) {
            $user_id     = get_current_user_id();
            $usermovies  = json_decode( get_user_meta( $user_id, 'tmdb_user_favorites', true ) );
        }


        if( empty( $usermovies ) ){

            echo '<div>No Favorites to show</div>';
        
        } else {
        
            echo '<section id="movies-wrapper" class="trending">';
                foreach( $usermovies as $movie ){
                    $movieDetails = json_decode( $this->get_single_movie( $movie ) );
                    $this->movie_layout( $movieDetails, (array)$usermovies );
                }
            echo '</section>';
        }
    
    
    }
    /**
     * Display movie posters
     */
    function movie_posters(){

        $getPage = ( isset( $_GET[ 'movies' ] ) ? $_GET[ 'movies' ] : '' );
        
        $movies = $this->get_movies( $getPage, null );

        if( !$movies )
            return '<div class="error">No movies!</div>';
        
        $movies      = json_decode( $movies );
        $usermovies  = array();
        $totalpages  = $movies->total_pages;
        $currentpage = $movies->page;
        $pages       = range( 1, $totalpages );
        if ( is_user_logged_in() ) {
            $user_id     = get_current_user_id();
            $usermovies  = json_decode( get_user_meta( $user_id, 'tmdb_user_favorites', true ) );
        }
        ?>
        <section id="movies-wrapper" class="trending">
            <?php foreach( $movies->results as $movie ){

                $this->movie_layout( $movie, (array)$usermovies );
            
            }?>
            <div class="navigation">
                <div class="previous">
                    <?php if( $currentpage >= 2 ){
                        $previousPage = $currentpage - 1;
                        echo '<a href="'. get_page_link().'?movies='.$previousPage.'"><span class="dashicons dashicons-controls-back"></span> Previous</a>';
                    }?>
                </div>
                <form action="<?php echo get_page_link().'?movies=';?>" method="post" class="theForm">

                    <div class="page-select">
                        <label for="pageSelect">Enter a page number to navigate to</label>
                        <div class="wrap">
                            
                            <input type="number" name="pageSelect" id="pageSelect" max="<?php echo $totalpages;?>" name="page" value="<?php echo $currentpage;?>">
                            <button id="pageSubmit" type="submit">GO</button>
                        </div>

                        <div class="sublabel">Page <?php echo $currentpage;?> of <?php echo $totalpages;?></div>
                        
                    </div>
                
                </form>
                <div class="next">
                    <?php if( $currentpage <= $totalpages ){
                        $nextPage = $currentpage + 1;
                        echo '<a href="'. get_page_link().'?movies='.$nextPage.'">Next <span class="dashicons dashicons-controls-forward"></span></a>';
                    }?>
                </div>
            </div>
        </section>
    <?php }

    /**
     * Movie content layout
     */
    function movie_layout( $movie, $usermovies ){

        $checked = '';
        if( $usermovies )
            $checked = ( in_array( $movie->id, $usermovies ) ? 'checked' : '' );

        // set movie title
        $movietitle     = ( isset( $movie->original_name ) ? $movie->original_name : '' );
        if( empty( $movietitle ) )
            $movietitle = ( isset( $movie->title ) ? $movie->title : '' );
        
        // set movie release date
        $releasedate    = ( isset( $movie->release_date ) ? $movie->release_date : '' );
        if( empty( $releasedate ) )
            $releasedate = ( isset( $movie->first_air_date ) ? $movie->first_air_date : '' );

        echo '<div class="single-movie '. $checked .'" data-movie="'. str_replace( ' ', '', $movietitle ).'">';
            if( isset( $movie->poster_path ) ){ echo '<img loading="lazy" src="https://image.tmdb.org/t/p/original'. $movie->poster_path .'"/>'; }
            echo '<h3 class="movie-title">'. $movietitle .'</h3>';
            echo '<p class="released">First Aired Date: '. $releasedate .'</p>';

            echo '<div class="hover"><span>VIEW </br>MORE INFO</span></div>';
            echo '<div class="movie-lightbox"><div class="inner"><span class="close">Close X</span>';
                if( isset( $movie->poster_path ) ){ echo '<img loading="lazy" src="https://image.tmdb.org/t/p/original'. $movie->poster_path .'"/>'; }
                echo '<div class="content-wrap">';
                    if( $movietitle ){ echo '<h3 class="movie-title">'. $movietitle .'</h3>'; }
                    if( $releasedate ){ echo '<p class="released">First Aired Date: '. $releasedate .'</p>'; }
                    if( isset( $movie->overview ) ){ echo '<p class="description">'. $movie->overview .'</p>';}                        
                echo '<div class="addToFavorites"><input class="addToFavorites" '. $checked .' type="checkbox" movieid="'. $movie->id .'" name="favorite'. $movie->id .'" id="favorite'. $movie->id .'"><label for="favorite'. $movie->id .'"><span class="add">Add to favorites</span><span class="remove">Remove from favorites</span></label></div>';
                echo '</div>';
                
            echo '</div></div>';
            
        echo '</div>';
    }

    /**
     * Get the API key
     */
    static function get_api_key(){

        $api_key = get_option( 'tmdb_movie_api_option_name' );

        if( !$api_key[ 'tmbd_api_key' ] )
            return false;
        
        return $api_key[ 'tmbd_api_key' ];
    }

    /**
     * 
     * Get movies from end point
     * @param $endpoint string
     * @uses $this::API_URL
     * @uses $this::get_api_key()
     * @return error else body
     * 
     */
    function get_movies( $page, $endpoint = null ) {

        if( empty( $endpoint ) )
            $endpoint =  'trending/all/day';

        $pagenumber = '';
        if( $page )
            $pagenumber = '&page='.$page;

        $response = wp_remote_request( $this::API_URL . $endpoint . '?api_key=' . $this::get_api_key() . $pagenumber );
        
        if( $response[ 'response' ]['code'] !== 200 )
            return false;
        
        return $response[ 'body' ];

	}

    /**
     * 
     * Get movvies from end point
     * @param $endpoint string
     * @uses $this::API_URL
     * @uses $this::get_api_key()
     * @return error else body
     * 
     */
    function get_single_movie( $movieid ) {

        $response = wp_remote_request( $this::API_URL . 'movie/'.$movieid.'?api_key=' . $this::get_api_key() );
        
        if( $response[ 'response' ]['code'] !== 200 )
            return false;

        return $response[ 'body' ];

	}

}