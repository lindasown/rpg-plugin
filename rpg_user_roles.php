<?php

//adds specific user role
//built copying snippet, no own code
function add_new_roles( $bbp_roles )
{
    /* Add a role called 'spieler */
    $bbp_roles['bbp_newbie'] = array(
        'name' => 'Spieler',
        'capabilities' => custom_capabilities( 'bbp_newbie' )
        );
 
    return $bbp_roles;
}
 
add_filter( 'bbp_get_dynamic_roles', 'add_new_roles', 1 );
 
function add_role_caps_filter( $caps, $role ) {
    /* Only filter for roles we are interested in! */
    if( $role == 'bbp_newbie' )
        $caps = custom_capabilities( $role );
 
    return $caps;
}
 
add_filter( 'bbp_get_caps_for_role', 'add_role_caps_filter', 10, 2 );
 
function custom_capabilities( $role ) {
    switch ( $role ) {
        /* Capabilities for 'newbie' role */
        case 'bbp_newbie':
            return array(
                // Primary caps
                'spectate'              => true,
                'participate'           => false,
                'moderate'              => false,
                'throttle'              => false,
                'view_trash'            => false,
 
                // Forum caps
                'publish_forums'        => false,
                'edit_forums'           => false,
                'edit_others_forums'    => false,
                'delete_forums'         => false,
                'delete_others_forums'  => false,
                'read_private_forums'   => true,
                'read_hidden_forums'    => false,
 
                // Topic caps
                'publish_topics'        => true,
                'edit_topics'           => true,
                'edit_others_topics'    => false,
                'delete_topics'         => false,
                'delete_others_topics'  => false,
                'read_private_topics'   => false,
 
                // Reply caps
                'publish_replies'       => true,
                'edit_replies'          => true,
                'edit_others_replies'   => false,
                'delete_replies'        => false,
                'delete_others_replies' => false,
                'read_private_replies'  => false,
 
                // Topic tag caps
                'manage_topic_tags'     => false,
                'edit_topic_tags'       => false,
                'delete_topic_tags'     => false,
                'assign_topic_tags'     => false,
            );
 
            break;
 
        default :
            return $role;
    }
}

?>