<?php // ==== SITE ==== //

// == HEADER INTERFACE == //

// Header buttons; add buttons using filter hooks just like with entry content
// @filter: pendrell_header_buttons
function pendrell_header_buttons() {
  $buttons = apply_filters( 'pendrell_header_buttons', '' );
  if ( !empty( $buttons ) )
    echo '<div class="buttons buttons-merge buttons-header">' . $buttons . '</div>';
}
add_action( 'pendrell_header_interface', 'pendrell_header_buttons', 10 );

// Skip to content link
function pendrell_header_skip_to_content( $buttons ) {
  echo '<div class="buttons buttons-skip-link"><a href="#content" class="button skip-link" role="button" rel="nofollow">' . __( 'Skip to content', 'pendrell' ) . '</a></div>';
}
add_action( 'pendrell_header_interface', 'pendrell_header_skip_to_content', 15 ); // Because all these elements are floated right a higher number is displayed first

// Responsive menu toggle
function pendrell_header_responsive_menu() {
  echo '<div class="buttons buttons-menu-toggle"><button id="menu-toggle" class="menu-toggle">' . pendrell_icon_text( 'menu-toggle', __( 'Menu', 'pendrell' ) ) . '</button></div>';
}
add_action( 'pendrell_header_interface', 'pendrell_header_responsive_menu', 5 );



// == HEADER MENU == //

// Responsive search bar; hidden except on small screens
function pendrell_header_search() {
  get_search_form();
}
add_action( 'pendrell_header_interface', 'pendrell_header_search', 25 );

// Responsive menu wrapped in a container to handle the fact that `wp_nav_menu` defaults back to `wp_page_menu` when no menu is specified
function pendrell_header_menu() {
  wp_nav_menu( array( 'theme_location' => 'header', 'menu_id' => 'menu-header', 'menu_class' => 'menu-inline' ) );
}
add_action( 'pendrell_header_navigation', 'pendrell_header_menu', 20 );



// == FOOTER MENU == //

// Generic footer menu
function pendrell_footer_menu() {
  wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'menu-footer', 'menu_class' => 'menu-inline' ) );
}
add_action( 'pendrell_footer_navigation', 'pendrell_footer_menu', 5 );

// Skip to top link
function pendrell_footer_skip_to_top() {
  echo '<div class="buttons buttons-skip-top"><a href="#page" class="button skip-top" rel="nofollow" role="button">' . pendrell_icon_text( 'top-link', __( 'Top', 'pendrell' ) ) . '</a></div>';
}
add_action( 'pendrell_footer_navigation', 'pendrell_footer_skip_to_top', 20 );
