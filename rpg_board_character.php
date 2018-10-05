<?php
add_action ( 'bbp_theme_before_reply_author_details', 'rpg_character_info');
add_action ('bbp_theme_after_reply_content', 'rpg_signature');

//displays character information in inplay-areas
function rpg_character_info() {
    //if parent is inplay or characterstuff show character information
    $rpg_parent_id  = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_type       = '';
    $rpg_type       = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_charastuff     = '';
    $rpg_charastuff     = get_post_meta($rpg_parent_id, 'rpg_charastuff_meta', true);
    $active = 1;
    if ($rpg_type == 'ingame' || $rpg_charastuff == 'charastuff') {
        global $wpdb;
        //for Character Name
        $post_id        = get_the_ID();
        $image          = "";
        $charactername  = get_post_meta($post_id, 'rpg_bbp_own_character_p', true);
        if (!$charactername) {
            //if no posting but topic, check different metafield.
            $charactername  = get_post_meta($post_id, 'rpg_bbp_own_character', true);
            if (!$charactername) {
                global $wpdb;
                $active = 0;
                //
                $names = $wpdb->get_results("SELECT starter, username FROM wp_posts WHERE ID = '".$post_id."'");
                foreach($names as $name) {
                    if ($name->starter) {
                        $charactername = $name->starter;
                    } else {
                        $charactername = $name->username;
                    }
                }
            }
        }
        $values         = explode(', ', $charactername);
        echo '<span class="rpg_charname">'.$values[0].'</span>';
        //for Character Avatar Picture
        if ($active) {
            $image = lze_get_pic($values[1], 'lze_character_ava');
        } else {
            $image = get_option('rpg_options_dummypic');
        }
        echo '<img src="'.$image.'">';
    }
}

function rpg_signature() {
    //if parent is inplay or characterstuff show character signature
    $rpg_parent_id  = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_type       = '';
    $rpg_type       = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_charastuff     = '';
    $rpg_charastuff     = get_post_meta($rpg_parent_id, 'rpg_charastuff_meta', true);
    if ($rpg_type == 'ingame' || $rpg_charastuff == 'charastuff') {
        $id = get_the_ID();
        $charactername = "";
        if (get_post_meta($id, 'rpg_bbp_own_character_p', true)) {
            //for Character Signatur Picture
            $charactername = get_post_meta($id, 'rpg_bbp_own_character_p', true);       
        } else if (get_post_meta($id, 'rpg_bbp_own_character', true)) {
            $charactername = get_post_meta($id, 'rpg_bbp_own_character', true); 
        }
        //for Character Signatur Picture
        $values         = explode(', ', $charactername);
        $image = lze_get_pic($values[1], 'lze_character_sig', 0);
        if ($image) {
            echo '<div class="rpg_signatur"><img class = "rpg_signatur_pic" src="'.$image.'"></div>';
        }
    }
}
 
?>