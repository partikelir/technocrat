<?php // ==== SIDEBAR ==== //

// Wrap sidebar in a `div` for styling purposes
function pendrell_sidebar_before( $index, $has_widgets ) {
  if ( $has_widgets === true )
    echo '<div class="sidebar ' . $index . '">';
}
function pendrell_sidebar_after( $index, $has_widgets ) {
  if ( $has_widgets === true )
    echo '</div>';
}
add_action( 'dynamic_sidebar_before', 'pendrell_sidebar_before', 10, 2 );
add_action( 'dynamic_sidebar_after', 'pendrell_sidebar_after', 10, 2 );

// Allow for functions to hook into the sidebar and hijack the contents; can also be set to false to not display any sidebar at all
// @filter: pendrell_sidebar
if ( apply_filters( 'pendrell_sidebar', true ) === true ) { ?>
  <div id="wrap-sidebar" class="wrap-sidebar">
    <div id="secondary" class="site-sidebar" role="complementary">
      <?php dynamic_sidebar( 'sidebar-1' ); dynamic_sidebar( 'sidebar-2' ); ?>
    </div>
  </div>
<?php }
