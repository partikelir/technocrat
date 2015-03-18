<?php // ==== CONTACT FORM ==== //

// This is the back-end component for the contact form page template; see `src/page-templates/contact-form.php` for the front-end

// AJAX request handler
function pendrell_contact_form() {

  // Originally based on: https://github.com/bueltge/WP-Contact-Form-Template
  // Additional references:
  // - http://trevordavis.net/blog/wordpress-jquery-contact-form-without-a-plugin/
  // - http://code.tutsplus.com/articles/creating-a-simple-contact-form-for-simple-needs--wp-27893
  // - http://premium.wpmudev.org/blog/how-to-build-your-own-wordpress-contact-form-and-why/
  // - http://www.problogdesign.com/wordpress/validate-forms-in-wordpress-with-jquery/
  // - https://wordpress.stackexchange.com/questions/147529/contact-form-in-template-with-jquery-validate-and-ajax

  // Logic goes here
  if ( isset( $_POST['submit'] ) && wp_verify_nonce( $_POST['_contact_form_nonce'], 'contact_form' ) ) {

    // Starting conditions
    $email_sent = false;
    $has_error = false;

    // Validate incoming data
    $spam    = filter_var( trim( $_POST['hades'] ), FILTER_SANITIZE_STRING);
    $from    = filter_var( trim( strip_tags( $_POST['from'] ) ), FILTER_SANITIZE_STRING);
    $email   = trim( $_POST['email'] );
    $subject = filter_var( trim( $_POST['subject'] ), FILTER_SANITIZE_STRING);
    $message = wp_kses_post( trim( $_POST['message'] ) ); // Allow html in message; if not, wrap in filter_var( trim( $_POST['message'] ), FILTER_SANITIZE_STRING);

    // Send a copy?
    if ( isset( $_POST['cc'] ) ) {
      $cc = intval( $_POST['cc'] );
    } else {
      $cc = false;
    }

    // Check form data for errors
    if ( empty( $from ) || empty( $email ) || empty( $message ) || !empty( $spam ) ) {
      $has_error = true;
    }

    // We don't really need a subject
    if ( empty( $subject ) )
      $subject = __( 'No subject', 'pendrell' );

    // If no errors came up we can safely proceed
    if ( $has_error === false ) {

      // Get IP of sender
      if ( isset( $_SERVER ) ) {
        if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
          $ip_addr = $_SERVER['HTTP_CF_CONNECTING_IP']; // Cloudflare
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
          $ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
          $ip_addr = $_SERVER['HTTP_CLIENT_IP'];
        } else {
          $ip_addr = $_SERVER['REMOTE_ADDR'];
        }
      }
      $ip_addr = filter_var( $ip_addr, FILTER_VALIDATE_IP );

      // Send to mail address specified in WordPress admin panel
      $email_to = get_option( 'admin_email' );
      $subject  = sprintf( __( '%1$s via %2$s', 'pendrell' ), $subject, get_option( 'blogname' ) );
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

      // Hooray!
      $email_sent = true;
    }

    if ( $has_error === true )
      echo '<div class="alert error" role="alert">' . __( 'Something went wrong. Please try again!', 'pendrell' ) . '</div>';

    if ( $email_sent === true )
      echo '<div class="alert success" role="status">' . __( 'Your message has been sent! Thank you for making contact.', 'pendrell' ) . '</div>';

  } else {

    // Unspecified error
    echo '<div class="alert warning" role="alert">' . __( 'Something went wrong. Please try again!', 'pendrell' ) . '</div>';
  }

  // Complete the request and return whatever we've got
  die();
}



// Checks for admin but works on the front-end as per http://codex.wordpress.org/AJAX_in_Plugins
if ( is_admin() ) {
  add_action( 'wp_ajax_contact_form', 'pendrell_contact_form' );
  add_action( 'wp_ajax_nopriv_contact_form', 'pendrell_contact_form' );
}



// Provision the front-end with the appropriate script variables
function pendrell_contact_form_script_vars( $script_vars = array() ) {

  // Non-destructively merge the script variables array and namespace custom variables specific to the contact form
  if ( is_page_template( 'page-templates/contact-form.php' ) ) {
    $script_vars = array_merge( $script_vars, array(
      'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
      'CF1'           => array(
        'from'          => __( 'Please enter your name.', 'pendrell' ),
        'email'         => __( 'Enter your email.', 'pendrell' ),
        'invalidEmail'  => __( 'Enter a valid email address.', 'pendrell' ),
        'message'       => __( 'Please enter a message.', 'pendrell' ),
        'messageLength' => __( 'This message is too short.', 'pendrell' ),
        'formSent'      => __( 'Your request has been received.', 'pendrell' ),
        'formError'     => __( 'There was an error submitting your request: ', 'pendrell' )
    ) ) );
  }

  return $script_vars;
}
add_filter( 'pendrell_script_vars', 'pendrell_contact_form_script_vars' );
