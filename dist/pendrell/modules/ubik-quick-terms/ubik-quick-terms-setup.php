<?php // ==== QUICK TERMS ==== //

// Add a term description field to the quick edit box!
// This is not as easy as it looks since there's no hook for default term fields
// To get around this fact we create a hidden column and populate that with the raw description (to allow for HTML, Markdown, shortcodes, etc.)
// We then use jQuery to pull content into the quick edit description textarea
// After saving the description is immediately updated
// By default this applies to categories and tags; use the 'ubik_term_description_taxonomies' to add your own like so:

// function ubik_your_taxonomy_quick_edit( $taxonomies ) {
//   $taxonomies[] = 'your-taxonomy';
//   return $taxonomies;
// }
// add_filter( 'ubik_term_description_taxonomies', 'ubik_your_taxonomy_quick_edit' );

// This code is adapted from: https://wordpress.stackexchange.com/questions/139663/add-description-to-taxonomy-quick-edit
// Full credit goes to G.M. @ http://gm.zoomlab.it/
// For a walk-through of this code check out: http://synapticism.com/adding-term-descriptions-to-the-quick-edit-box-in-wordpress/

// Add term descriptions to the quick edit box
function ubik_quick_terms_description_edit( $column, $screen, $taxonomy = '' ) {

  // Check to see if we're in the right place; this function is called on any view where a custom column is registered (including views for which the $taxonomy is undefined)
  if ( empty( $taxonomy ) || $screen !== 'edit-tags' || $column !== '_description' )
    return;

  // Fetch the target taxonomy and ensure the current user can edit terms
  $tax = get_taxonomy( $taxonomy );
  if ( ! current_user_can( $tax->cap->edit_terms ) )
    return;

  // Output the necessary HTML and JavaScript...
  ?><fieldset>
    <div class="inline-edit-col">
    <label>
      <span class="title"><?php _e( 'Description', 'ubik' ); ?></span>
      <span class="input-text-wrap">
      <textarea name="description" rows="3" class="ptitle"></textarea>
      </span>
    </label>
    </div>
  </fieldset>
  <script>
  jQuery('#the-list').on('click', 'a.editinline', function(){
    var now = jQuery(this).closest('tr').find('td.column-_description').text();
    jQuery(':input[name="description"]').text(now);
  });
  </script><?php
  // The jQuery snippet above finds the contents of the hidden column and copies this to the inline description field after the user clicks on the quick edit link
}
add_action( 'quick_edit_custom_box', 'ubik_quick_terms_description_edit', 10, 3 );



// Save the inline term description
function ubik_quick_terms_description_save( $term_id ) {
  $tax = get_taxonomy( $_REQUEST['taxonomy'] );
  if (
    current_filter() === 'edited_' . $tax->name
    && current_user_can( $tax->cap->edit_terms )
  ) {
    $description = filter_input( INPUT_POST, 'description', FILTER_SANITIZE_STRING );
    remove_action( current_filter(), __FUNCTION__ ); // Removing action to avoid recursion
    wp_update_term( $term_id, $tax->name, array( 'description' => $description ) );
  }
}



// Create a custom column to trigger the `quick_box_custom_column` action
function ubik_quick_terms_hidden_column( $columns ) {
  $columns['_description'] = '';
  return $columns;
}



// Hide the custom column from view
function ubik_quick_terms_hidden_column_visibility( $columns ) {
  $columns[] = '_description';
  return $columns;
}



// Fill our hidden column with the raw term description; this will be pulled into the quick edit box with jQuery
function ubik_quick_terms_hidden_column_contents( $_, $column_name, $term_id ) {
  if ( $column_name === '_description' ) {

    // Get current screen, if available
    $screen = get_current_screen();

    // Set the taxonomy from the current screen; if this is unavailable, try the request (after saving the quick edit box `get_current_screen` returns `null`)
    if ( !empty( $screen ) ) {
      $taxonomy = $screen->taxonomy;
    } elseif ( !empty( $_REQUEST['taxonomy'] ) ) {
      $taxonomy = sanitize_text_field( $_REQUEST['taxonomy'] );
    } else {
      $taxonomy = '';
    }

    // Output the raw term description if there is one to be found
    if ( !empty( $term_id ) && !empty( $taxonomy ) ) {
      $term = get_term( $term_id, $taxonomy );
      if ( !empty( $term ) )
        echo $term->description;
    }
  }
}



// Initialize quick edit functionality
function ubik_quick_terms_init() {

  // Filter this to add or subtract taxonomies
  $ubik_term_description_taxonomies = apply_filters( 'ubik_quick_terms_taxonomies', array( 'category', 'post_tag' ) );

  // Setup all the necessary actions and filters
  foreach ( $ubik_term_description_taxonomies as $tax ) {
    add_action( "edited_{$tax}", 'ubik_quick_terms_description_save' );
    add_filter( "manage_edit-{$tax}_columns", 'ubik_quick_terms_hidden_column' );
    add_filter( "manage_{$tax}_custom_column", 'ubik_quick_terms_hidden_column_contents', 10, 3 );
    add_filter( "get_user_option_manageedit-{$tax}columnshidden", 'ubik_quick_terms_hidden_column_visibility' );
  }
}
add_action( 'init', 'ubik_quick_terms_init' );
