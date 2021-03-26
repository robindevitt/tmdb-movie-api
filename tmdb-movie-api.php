<?php 
/**
    * Plugin Name: TMDB Movie API
    * Description: TMDB Movia API Showcase.
    * Version: 1.0.0
    * Author: Robin Devitt
    * Author URI : https://github.com/robindevitt/
    * License: GNU General Public License v2.0
    * License URI: https://www.gnu.org/licenses/gpl-2.0.html
    * Text Domain: tmdb-movie-api
    *
    */

class TMDBMovieAPI {
	private $tmdb_movie_api_options;

	public function __construct() {

        add_action( 'admin_menu', array( $this, 'tmdb_movie_api_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'tmdb_movie_api_page_init' ) );

        add_action( 'wp_ajax_assign_favorite',        array( $this, 'ajax__add_favorite') );
        add_action( 'wp_ajax_nopriv_assign_favorite', array( $this, 'ajax__add_favorite') );

        require_once plugin_dir_path( __FILE__ ) . 'shortcode/movies.php';

	}

    function ajax__add_favorite(){
        
        if ( is_user_logged_in() ) {

            $user_id    = get_current_user_id();
            $usermovies = json_decode( get_user_meta( $user_id, 'tmdb_user_favorites', true ) );
            $allMovies  = array();
            if( $usermovies )
                $allMovies = array_merge( $allMovies, $usermovies );

            if( in_array( $_POST[ 'movieid' ], $allMovies ) ){

                $key = array_search( $_POST[ 'movieid' ] , $allMovies );
                if (false !== $key)
                    unset( $allMovies[$key] );

                $usermovies = json_encode( $allMovies );
                update_user_meta( $user_id, 'tmdb_user_favorites', $usermovies );
                        
            } else {

                $allMovies[]   = $_POST[ 'movieid' ];

                $usermovies = json_encode( $allMovies );
                update_user_meta( $user_id, 'tmdb_user_favorites', $usermovies );
            
            }
    
            
        }

    }

    /**
     * 
     * Create Plugin Page
     * 
     */
	public function tmdb_movie_api_add_plugin_page() {
		add_menu_page(
			'TMDB Movie API',                                   // page_title
			'TMDB Movie API',                                   // menu_title
			'manage_options',                                   // capability
			'tmdb-movie-api',                                   // menu_slug
			array( $this, 'tmdb_movie_api_create_admin_page' ), // function
			'dashicons-editor-video',                           // icon_url
			2                                                   // position
		);
	}

    /**
     * 
     * Create admin page with settings
     * 
     */
	public function tmdb_movie_api_create_admin_page() {
		$this->tmdb_movie_api_options = get_option( 'tmdb_movie_api_option_name' ); ?>

		<div class="wrap">
			<h2>TMDB Movie API</h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'tmdb_movie_api_option_group' );
					do_settings_sections( 'tmdb-movie-api-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

    /**
     * 
     * Plugin page init
     * 
     */
	public function tmdb_movie_api_page_init() {

		register_setting(
			'tmdb_movie_api_option_group',              // option_group
			'tmdb_movie_api_option_name',               // option_name
			array( $this, 'tmdb_movie_api_sanitize' )   // sanitize_callback
		);

		add_settings_section(
			'tmdb_movie_api_setting_section',               // id
			'Settings',                                     // title
			array( $this, 'tmdb_movie_api_section_info' ),  // callback
			'tmdb-movie-api-admin'                          // page
		);

		add_settings_field(
			'tmbd_api_key',                     // id
			'API Key',                          // title
			array( $this, 'api_key_callback' ), // callback
			'tmdb-movie-api-admin',             // page
			'tmdb_movie_api_setting_section'    // section
		);
	}

    /**
     * 
     * Plugin setting sanitize
     * 
     */
	public function tmdb_movie_api_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['tmbd_api_key'] ) ) {
			$sanitary_values['tmbd_api_key'] = sanitize_text_field( $input['tmbd_api_key'] );
		}

		return $sanitary_values;
	}

    /**
     * 
     * Plugin section info
     * 
     */
	public function tmdb_movie_api_section_info() {
		printf(
            '<p>If you need to setup an account for your API key, please <a target="_blank" href="https://developers.themoviedb.org/3/getting-started/introduction">Read more here</a></p>'
        );
	}

    /**
     * 
     * Callback
     * 
     */
	public function api_key_callback() {
		printf(
			'<input class="regular-text" type="text" name="tmdb_movie_api_option_name[tmbd_api_key]" id="tmbd_api_key" value="%s">',
			isset( $this->tmdb_movie_api_options['tmbd_api_key'] ) ? esc_attr( $this->tmdb_movie_api_options['tmbd_api_key']) : ''
		);
	}

}

$tmdb_movie_api = new TMDBMovieAPI();
