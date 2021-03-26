<?php wp_enqueue_style( 'tmdb-movie-style', plugin_dir_url( __FILE__ ) . '../../assets/css/tmdb-plugin-style.min.css', null, '1.0', false );?>
<section id="contact-wrapper">

    <div class="half">

        <?php if( empty ( $_POST['contact-email'] ) ){;?>

        <h2>Fill out the below form</h2>

        <form method="post" action="<?php echo the_permalink(); ?>">
            <input  type="text" name="contact-name" id="name" placeholder="Fullname*">
            <input  type="email" name="contact-email" id="Email" placeholder="Email*">
            <input type="tel" name="contact-telephone" id="telephone" placeholder="Phone number">
            <textarea  name="contact-message" id="message" cols="30" rows="3" placeholder="Message*"></textarea>
            <input type="submit" name="contect-send" value="Send">
        </form>

        <?php } else {

            $to = get_option( 'admin_email' );;
            $subject = "New contact lead!";
            $message = filter_var( $_POST['contact-message'], FILTER_SANITIZE_STRING );
            $headers = "From: ".  filter_var( $_POST['contact-name'], FILTER_SANITIZE_STRING ) .'<'. filter_var( $_POST['contact-email'], FILTER_SANITIZE_STRING ).'>';

            $message = mail( $to, $subject, $message, $headers );

            if( $message ){
                echo '<div class="success"> Your message was sent!</div>';
            } else {
                echo '<div class="error"> Your message was not sent!</div>';
            }

        };?>

    </div>

    <div class="half">
        <h2>Details</h2>
        <ul>
            <li><a target="_blank" href="mailto:iamrobindevitt@gmail.com">Drop me a mail</a></li>
            <li><a target="_blank" href="tel:0846972172">Give me a call</a></li>
            <li><a target="_blank" href="https://github.com/robindevitt">GitHub Account</a></li>
            <li><a target="_blank" href="https://instagram.com/robindevitt">Instagram Account</a></li>
            <li><a target="_blank" href="https://www.linkedin.com/in/robindevitt/">LinkedIn Account</a></li>
            <li><a target="_blank" href="https://www.strava.com/athletes/robindevitt">Strava Account</a></li>
        </ul>
    </div>

</section>