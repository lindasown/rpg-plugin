<?php

//sort topics alphabetical

function rew_date_topic_order( $args ) {
                $args['orderby']='title';
                $args['order']='ASC';  //change to ASC to put oldest at top
                return $args;
}
add_filter('bbp_before_has_topics_parse_args','rew_date_topic_order');

//very unelegant cutting of for user not needed information.
if (is_admin()) {
    add_action( 'admin_footer', 'jquerystuff', 10, 0 );
    function jquerystuff() {
        echo "<script>jQuery('#lze_ava_meta').remove().prependTo('#postbox-container-1');";
        echo "jQuery('#Fertigdiv').remove().prependTo('#postbox-container-1');";
        if (!current_user_can('administrator')) {
            echo "jQuery('#menu-posts-lze_felder').remove();";
            echo "jQuery('#menu-posts').remove();";
            echo "jQuery('#menu-comments').remove();";
            echo "jQuery('#menu-posts').remove();";
            echo "jQuery('#menu-tools').remove();";
            echo "jQuery('#wp-admin-bar-new-content').remove();";
            echo "jQuery('#wp-admin-bar-comments').remove();";
            echo "jQuery('#dashboard-widgets-wrap').remove();";
        }
        echo "</script>";
    }
}

add_filter('private_title_format', 'ntwb_remove_private_title');
function ntwb_remove_private_title($title) {
	return '%s';
}
?>