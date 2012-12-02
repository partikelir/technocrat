<?php // === PENDRELL ADMIN FUNCTIONS === //

// HTML editor fontstack and fontsize hack; source: http://justintadlock.com/archives/2011/07/06/fixing-wordpress-3-2s-html-editor-font
function pendrell_html_editor_fontstack() {
?>
				<style type="text/css">#wp-content-editor-container textarea#content, #wp_mce_fullscreen { font-size: <?php echo PENDRELL_FONTSIZE_EDITOR; ?>; font-family: <?php echo PENDRELL_FONTSTACK_EDITOR; ?> }</style>
<?php }
add_action( 'admin_head-post.php', 'pendrell_html_editor_fontstack' );
add_action( 'admin_head-post-new.php', 'pendrell_html_editor_fontstack' );
?>