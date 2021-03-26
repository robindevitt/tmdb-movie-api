# TMDB Movie API

### About this plugin
This is a demo feature I have developed that integrates with the [The Movie Database (TMDb) API](https://developers.themoviedb.org/3/getting-started/introduction).

### Prerequisites
* [Node.js](https://nodejs.org/en/)
* [NPM](https://www.npmjs.com/)
* [SASS KNOWLEDGE](https://sass-lang.com/)

### Project Setup
1. [Download a copy of the plugin](https://github.com/robindevitt/tmdb-movie-api/archive/refs/heads/main.zip)
2. Navigate to your WordPress admin area and add a new plugin
3. Upload the file you just downloaded
4. Activate the Plugin
5. Navigate to "TMDB Movie API" in the left hand menu
6. Add your API Key into the input and click save changes.

### TMDB Movie API Key 
If you need na API Key, please follow the documentation [here](https://developers.themoviedb.org/3/getting-started/introduction)

### Built in Shortcodes
1. ```[movies_output]``` - This will display all daily trending terms.
    - Once this shortcode is added to the desired page, navigating to the page will show all the daily trending items.
    - Pagination at the bottom allows you to navigate through the respective pages as well as define a page to navigate to.
    - Clicking on a "card" open a pop-up which shows more info as well as the option to add it as a favorite.
2. ```[movies_favorites]``` - Once a user a favorited a movie, this shortcode can be used to display their favorites.
    - Once this shortcode is added to the desired page, should a user have any items marked as a favorite, they will appear here.
3. ```[contact_me]``` - Displays a contact form and contact details

### SASS Terminal Setup
- Open the Plugin folder in Terminal
- CD Into assets/css folder
- Type into your terminal : sass --watch sass/tmdb-plugin-style.sass:tmdb-plugin-style.css
- Watch the magic as it compiles your style sheet on save.
- To compress the css use the following : sass --watch sass/tmdb-plugin-style.sass:tmdb-plugin-style.css --style compressed
- Watch the magic as it compiles your style sheet on save.
- CTRL + C to stop