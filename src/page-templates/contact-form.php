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

get_header(); ?>
  <div id="wrap-content" class="wrap-content">
    <div id="content" class="site-content<?php pendrell_content_class(); ?>">
    	<section id="primary" class="content-area">
    		<main id="main" class="site-main" role="main">
    			<?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
              <header class="entry-header">
                <?php pendrell_entry_title(); ?>
              </header>
              <div class="entry-content">
                <?php the_content(); ?>
              </div>
              <div>
                <h3><a name="contact-form"></a><?php _e( 'Contact form', 'pendrell' ); ?></h3>
                <form id="contact-form" method="post" action="">
                  <div><?php _e( 'Required fields are marked <span class="required">*</span>', 'pendrell' ); ?></div>
                  <fieldset>
                    <label for="from"><?php _e( 'Name', 'pendrell' ); ?> <span class="required">*</span></label>
                    <input id="from" name="from" type="text" placeholder="<?php _e( 'Your name', 'pendrell' ); ?>" value="" required="" />
                  </fieldset>
                  <fieldset>
                    <label for="email"><?php _e( 'Email', 'pendrell' ); ?> <span class="required">*</span></label>
                    <input id="email" name="email" type="text" placeholder="<?php esc_attr_e( 'your@email.com', 'pendrell' ); ?>" value="" required="" />
                  </fieldset>
                  <fieldset>
                    <label for="subject"><?php _e( 'Subject', 'pendrell' ); ?></label>
                    <input id="subject" name="subject" type="text" placeholder="<?php _e( 'What is this about?', 'pendrell' ); ?>" value="" />
                  </fieldset>
                  <fieldset>
                    <label for="text"><?php _e( 'Message', 'pendrell' ); ?></label>
                    <textarea id="message" name="message" rows="5" placeholder="<?php esc_attr_e( 'Your message&#x0085;', 'pendrell' ); ?>" required="" ></textarea>
                    <input id="cc" name="cc" type="checkbox" value="1" />
                    <label for="cc" class="checkbox-label"><?php _e( 'Send a copy to yourself', 'pendrell' ); ?></label>
                  </fieldset>
                  <button id="submit" type="submit" name="submit"><?php echo pendrell_icon( 'contact-form-send', __( 'Send message', 'pendrell' ) ); ?></button>
                  <div class="hide">
                    <label for="hades"><?php _e( 'Spam protection; do not fill this', 'pendrell' ); ?></label>
                    <input name="hades" type="text" />
                    <?php wp_nonce_field( 'contact_form', '_contact_form_nonce' ); ?>
                  </div>
                </form>
              </div>
            </article>
    			<?php endwhile; // end of the loop. ?>
    		</main>
    	</section>
    </div>
  </div>
<?php get_footer(); ?>