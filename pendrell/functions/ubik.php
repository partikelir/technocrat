<?php // === UBIK === //

function pendrell_is_portfolio() {
  if ( function_exists( 'ubik_is_portfolio' ) ) {
    return ubik_is_portfolio();
  }
  return false;
}

function pendrell_is_place() {
  if ( post_type_exists( 'place' ) ) {
    if ( function_exists( 'ubik_is_place' ) ) {
      return ubik_is_place();
    }
  }
  return false;
}
