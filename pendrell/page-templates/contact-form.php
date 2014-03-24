<?php
/**
 * Template Name: Contact form
 *
 * Description: Use this page template for your contact form.
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

// Based on: https://github.com/bueltge/WP-Contact-Form-Template
// Additional references:
// * http://trevordavis.net/blog/wordpress-jquery-contact-form-without-a-plugin/
// * http://code.tutsplus.com/articles/creating-a-simple-contact-form-for-simple-needs--wp-27893
// * http://premium.wpmudev.org/blog/how-to-build-your-own-wordpress-contact-form-and-why/

// Process form if input field has been set
if ( isset( $_POST['submit'] ) && wp_verify_nonce( $_POST['contact_form_nonce'], 'form_submit' ) ) {

  // Output form values for debugging
  //if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
  //  var_dump( $_POST );

  $spam    = filter_var( trim( $_POST['spamcheck'] ), FILTER_SANITIZE_STRING);
  $from    = filter_var( trim( strip_tags( $_POST['from'] ) ), FILTER_SANITIZE_STRING);
  $email   = trim( $_POST['email'] );
  $subject = filter_var( trim( $_POST['subject'] ), FILTER_SANITIZE_STRING);
  //$message = filter_var( trim( $_POST['text'] ), FILTER_SANITIZE_STRING);

  // Allow html in message
  $message = wp_kses_post( $_POST['text'] );

  if ( isset( $_POST['cc'] ) )
    $cc = intval( $_POST['cc'] );
  else
    $cc = FALSE;

  // Check for spam input field
  if ( ! empty( $spam ) ) {
    $spam_error = TRUE;
    $has_error  = TRUE;
  }

  // Check sender name, string
  if ( empty( $from ) ) {
    $has_error  = TRUE;
  }

  // Check for mail and filter
  // alternative to filter_var a regex via preg_match( $filter, $email )
  // $filter = "/^([a-z0-9äöü]+[-_\\.a-z0-9äöü]*)@[a-z0-9äöü]+([-_\.]?[a-z0-9äöü])+\.[a-z]{2,4}$/i"
  // $filter = "/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i"
  if ( empty( $email ) ) {
    $email_error = TRUE; // __( 'Please enter your email address.', 'pendrell' );
    $has_error   = TRUE;
  } else if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
    $email_error = TRUE; // __( 'Please enter a valid email address.', 'pendrell' );
    $has_error   = TRUE;
  }

  if ( empty( $subject ) ) {
    $subject_error = TRUE; // __( 'Please enter a subject.', 'pendrell' );
    $has_error     = TRUE;
  }

  if ( empty( $message ) ) {
    $message_error = TRUE; // __( 'Please enter a message.', 'pendrell' );
    $has_error     = TRUE;
  }

  if ( ! isset( $has_error ) ) {

    // get IP
    if ( isset( $_SERVER ) ) {
      if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip_addr = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip_addr = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
        $ip_addr = getenv( 'HTTP_X_FORWARDED_FOR' );
      } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
        $ip_addr = getenv( 'HTTP_CLIENT_IP' );
      } else {
        $ip_addr = getenv( 'REMOTE_ADDR' );
      }
    }
    $ip_addr = filter_var( $ip_addr, FILTER_VALIDATE_IP );

    // use mail address from WP Admin
    $email_to = get_option( 'admin_email' );
    $subject  = $subject . ' ' . __( 'via', 'pendrell' ) . ' ' . get_option( 'blogname' );
    $body     = $message . "\n\n" .
                __( 'From:', 'pendrell' ) . ' ' . $from . "\n" .
                __( 'Email:', 'pendrell' ) . ' ' . $email . "\n" .
                __( 'IP:', 'pendrell' ) . ' ' . $ip_addr . "\n";
    $headers  = 'From: ' . $from . ' <' . $email . '>' . "\r\n";
    // Check for CC and include sender mail to reply
    if ( $cc )
      $headers .= 'Reply-To: ' . $email;

    // Send mail via wp mail function
    wp_mail( $email_to, $subject, $body, $headers );

    // Check for CC and send to sender
    if ( $cc ) {
      wp_mail( $email, __( 'CC:', 'pendrell' ) . ' ' . $subject, $body, $headers );
    }

    // successfully mail shipping
    $email_sent = TRUE;
  }
}

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
        <div class="comment-respond">
          <h3><?php _e( 'Contact form', 'pendrell' ); ?></h3>
          <form action="<?php the_permalink(); ?>" method="post" id="contact" class="comment-form">
            <?php
            if ( isset( $email_sent ) ) {
              echo '<p class="success">' . __( 'Your message has been sent! Thank you for making contact.', 'pendrell' ) . '</p>';
            } elseif ( isset( $spam_error ) ) {
              echo '<p class="alert">' . __( 'The spam protection field needs to be empty.', 'pendrell' ) . '</p>';
            } elseif ( isset( $has_error ) ) {
              echo '<p class="alert">' . __( 'Something is wrong. Please fix it!', 'pendrell' ) . '</p>';
            } else {
              echo '<p>' . __( 'Required fields are marked <span class="required">*</span>', 'pendrell' ) . '</p>';
            } ?>

            <p>
              <label for="from"><?php _e( 'Name', 'pendrell' ); ?> <span class="required">*</span></label>
              <input type="text" id="from" name="from" placeholder="<?php esc_attr_e( 'Your name', 'pendrell' ); ?>" value="<?php if ( isset( $from ) && ! isset( $email_sent ) ) echo esc_attr( $from ); ?>"<?php if ( isset( $from_error ) ) { echo ' class="alert-field"'; } ?> />
            </p>

            <p>
              <label for="email"><?php _e( 'Email', 'pendrell' ); ?> <span class="required">*</span></label>
              <input type="text" placeholder="<?php esc_attr_e( 'your@email.com', 'pendrell' ); ?>" id="email" name="email" value="<?php if ( isset( $email ) && ! isset( $email_sent ) ) echo esc_attr( $email ); ?>"<?php if ( isset( $email_error ) ) { echo ' class="alert-field"'; } ?> />
            </p>

            <p class="comment-notes">
              <label for="subject"><?php _e( 'Subject', 'pendrell' ); ?> <span class="required">*</span></label>
              <input type="text" placeholder="<?php _e( 'What is this about?', 'pendrell' ); ?>" id="subject" name="subject" value="<?php if ( isset( $subject ) && ! isset( $email_sent ) ) echo esc_attr( $subject ); ?>"<?php if ( isset( $subject_error ) ) { echo ' class="alert-field"'; } ?> />
            </p>

            <p>
              <label for="text"><?php _e( 'Message', 'pendrell' ); ?></label>
              <textarea id="text" name="text" placeholder="<?php esc_attr_e( 'Your message&#x0085;', 'pendrell' ); ?>"<?php if ( isset( $message_error ) ) { echo ' class="alert-field"'; } ?>><?php if ( isset( $message ) && ! isset( $email_sent ) ) echo esc_textarea( $message ); ?></textarea>
            </p>

            <p>
              <input type="checkbox" id="cc" name="cc" value="1" <?php if ( isset( $cc ) ) checked( '1', intval($cc) ); ?> />
              <label for="cc" style="display: inline;"><?php _e( 'Send a copy to yourself', 'pendrell' ); ?></label>
            </p>

            <p style="display: none !important;">
              <label for="text">
                <?php _e( 'Spam protection', 'pendrell' ); ?>
              </label>
              <input name="spamcheck" class="spamcheck" type="text" />
            </p>

            <p class="form-submit">
              <input class="submit" type="submit" name="submit" value="<?php esc_attr_e( 'Send message', 'pendrell' ); ?>" />
            </p>
            <?php wp_nonce_field( 'form_submit', 'contact_form_nonce' ) ?>
          </form>
        </div>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>