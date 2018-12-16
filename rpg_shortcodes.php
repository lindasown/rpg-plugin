<?php
// collects the avatar-person for the shortcode.
function lze_get_character_data() {
    global $wpdb;
    $lze_men    = lze_get_post_id('Mann');
    $lze_women  = lze_get_post_id('Frau');
    //collects all avatar-persons including the post-id
    $lze_data   = $wpdb->get_results("SELECT meta_value, post_id FROM wp_postmeta WHERE meta_key = 'lze_Avatarperson'");
    //sorts the whole list alphabetical
    sort($lze_data);
    echo '<h2>Männliche Avatarpersonen</h2><ul>';
    foreach ($lze_data as $single_data) {
        //checks if a value is available and if the avatar person is for a male character.
        if ($single_data->meta_value && in_array($single_data->post_id, $lze_men) && lze_character_active($single_data->post_id))  {
            echo '<li>';
            //echoes value and connected character.
            echo $single_data->meta_value;
            echo ' (<a href="'.get_permalink($single_data->post_id).'" target="_blank">' . get_the_title($single_data->post_id) . '</a>)';
            echo '</li>';
        }
    }
    echo '</ul><h2>Weibliche Avatarpersonen</h2><ul>';
    foreach ($lze_data as $single_data) {
        //checks if a value is available and if the avatar person is for a female character.
        if ($single_data->meta_value && in_array($single_data->post_id, $lze_women) && lze_character_active($single_data->post_id))  {
            echo '<li>';
            //echoes value and connected character.
            echo $single_data->meta_value;
            echo ' (<a href="'.get_permalink($single_data->post_id).'" target="_blank">' . get_the_title($single_data->post_id) . '</a>)';
            echo '</li>';
        }
    }
    echo '</ul>';
}
add_shortcode( 'lze_get_avatar_list', 'lze_get_character_data' );
    
//shortcode for active inplay scenes by user
function lze_get_inplay_scenes($userid) {
    if (is_user_logged_in()){
        if (!$userid) {
            $userid = get_current_user_id();
        }
        //$userid     = get_current_user_id();
        echo '<h3 id="rpg_scenes_drop">Meine Szenen <i class="fa fa-caret-down" aria-hidden="true"></i>
</h3>';
        echo '<ul id="rpg_scenes_drop_content">';
        global $wpdb;
        $characters         = lze_get_characters_by_user_id($userid);
        if (!empty($characters)) {
            $char_array         = array();
            foreach ($characters as $chara) {
                $data = $chara['title'].', '.$chara['id'];
                array_push($char_array, $data);
            }
            if ($char_array) {
                get_scenes($char_array);
            }
        } else {
            echo '<li>Es sind noch keine Charaktere registriert. Auch nicht von dir.</li>';
            echo '</ul>';
        }

    }
}
add_shortcode( 'inplay-scenes', 'lze_get_inplay_scenes' );

//shortcode for counting all characters
function lze_count_characters() {
    $characters = lze_get_characters();
    $counter    = 0;
    foreach ($characters as $single_data) {
        if (lze_character_active($single_data['id'])) {
            $counter++;
        }
    }
    return $counter;
}            
add_shortcode( 'Count', 'lze_count_characters' );

//last character registered
function lze_last_character() {
    $characters = lze_get_characters();
    usort($characters, "cmp");
	return end($characters)['name'];
}
add_shortcode( 'Neuster_Charakter', 'lze_last_character' );

//list of characters,sorted by group
function lze_group_list( $atts ) {
	$atts = shortcode_atts(
		array(
			'gruppe' => '',
		), $atts, 'gruppenliste' );
	lze_characterlist_by_group( $atts, false );  
}
add_shortcode( 'gruppenliste', 'lze_group_list' );

//list of wanted characters
function lze_wanted_list( $atts ) {
	$atts = shortcode_atts(
		array(
			'geschlecht' => '',
            'gesinnung' => '',
		), $atts, 'gruppenliste' );
    
    global $wpdb;
    $list = lze_get_s_wanted();
    $wanted         = array();
    foreach($list as $single_data) {
        $id = $single_data['id'];
        if (has_term($atts['geschlecht'], 'Geschlecht', $id)) {
            $shorttext = "";
            $wanted[$single_data['id']] = array();
            $wanted[$single_data['id']]['id'] = $single_data['id'];
            $wanted[$single_data['id']]['name'] = $single_data['name'];
            $bild = $wpdb->get_results("SELECT meta_value FROM wp_postmeta WHERE post_id = '".$single_data['id']."' AND meta_key = 'singlewanted_pic'");
            foreach($bild as $onepic) {
                $pic = $onepic->meta_value;
            }
            $wanted[$single_data['id']]['bild'] = $pic;
            if(has_term('Gut', 'Gesinnung', $id)) {
                $mindset = 'Gut';
            } else if (has_term('Neutral', 'Gesinnung', $id)) {
                $mindset = 'Neutral';
            } else {
                $mindset = "Böse";
            }
            
        } 
    }
    echo '<h2>'.$atts['geschlecht'].'</h2>';
    foreach ($wanted as $single_data) {
            echo '<div class="wantedlist besetzt"><img src="'.$single_data['bild'].'"><br><p>'.$single_data['name'].'</p><div class="description"><a href="'.get_permalink($single_data['id']).'" target="_blank">Link zum Gesuch</a>';
            if (has_term('reserviert', 'Frei', $single_data['id'])) {
                echo "<br><b>RESERVIERT</b>";
            }
            echo '</div></div>';
        } 
}
add_shortcode( 'wanted', 'lze_wanted_list' );

//displays all wanted groups
function lze_groupwanted_list() {
	global $wpdb;
    $list = $wpdb->get_results("SELECT ID, post_title FROM wp_posts WHERE post_type = 'lze_group_wanted'");
    $wanted         = array();
    foreach($list as $single_data) {
        if($single_data->post_title != "Automatisch gespeicherter Entwurf"){
            $id = $single_data->ID;
            $wanted[$id]['name'] = $single_data->post_title;
            $wanted[$id]['id'] = $id;        
            $picobj = $wpdb->get_results("SELECT meta_value FROM wp_postmeta WHERE post_id = '".$id."' AND meta_key='groupwanted_pic'");
            foreach($picobj as $single_pic){
                $wanted[$id]['bild'] = $single_pic->meta_value;
            }
        }
    }
        
        
    echo '<h2>Groups</h2>';
    foreach ($wanted as $single_data) {
            echo '<div class="wantedlist frei gruppe"><img src="'.$single_data['bild'].'"><br><p>'.$single_data['name'].'</p><div class="description"><a href="'.get_permalink($single_data['id']).'" target="_blank">Link zum Gesuch</a>';
            echo '</div></div>';
        } 
}
add_shortcode( 'wanted-groups', 'lze_groupwanted_list' );

//displays Link to Message Center
//needs buddypress
function lze_get_messagelink() {
    if (is_user_logged_in()){
        $current_user = wp_get_current_user();
        $username = $current_user->user_login;
        if (messages_get_unread_count(get_current_user_id()) > 1) {
            echo '<div class="rpg_message rpg_message_new"><a href="';
            echo  get_home_url().'/members/'.$username.'/messages">Hey, du hast '.messages_get_unread_count(  get_current_user_id() ).' private Nachrichten. :-)</a></div>';
        } else if (messages_get_unread_count(get_current_user_id()) > 0 && messages_get_unread_count(get_current_user_id()) < 2){
            echo '<div class="rpg_message rpg_message_new"><a href="';
            echo  get_home_url().'/members/'.$username.'/messages">Hey, du hast '.messages_get_unread_count(  get_current_user_id() ).' private Nachricht. :-)</a></div>';
        } else {
            echo '<div class="rpg_message rpg_message_new"><a href="';
            echo  get_home_url().'/members/'.$username.'/messages">Keine neue private Nachricht.</a></div>';
        }
    }
}
add_shortcode ( 'messagelink', 'lze_get_messagelink' );

//displays the navigation element to the charlist
function lze_get_charlistlink() {
    echo '<div class="rpg_button"><a href="'.get_home_url().'/meine-charaktere/">Meine Charaktere</a></div>';
}
add_shortcode ( 'charakterliste_link', 'lze_get_charlistlink' );

//displays a list with links to own characters and important links for character generation
function lze_get_character_profilelist() {
    $userid = get_current_user_id();
    $charalist = lze_get_characters_by_user_id($userid);
    echo '<div class="rpg_1-3"><h2>Meine Charaktere</h2>';
    echo '<ul>';
    foreach ($charalist as $single_data) {
        echo '<li class="rpg_button"><a href="';
        echo get_home_url().'/lze_character/'.$single_data['slug'].'">';
        echo $single_data['title'];
        echo '</a></li>';
    }
    echo '</ul></div>';
    echo '<div class="rpg_2-3"><h2>Charakter erstellen</h2><p>Klicke <a href="';
    echo get_home_url().'wp-admin/post-new.php?post_type=lze_character">hier</a> um einen neuen Charakter zu erstellen.</p><p>Erschrick nicht - du wirst ins Backend weitergeleitet. Füll alle Formularfelder aus und klick rechts "fertig" an, wenn du so weit bist. Die Steckbriefkorrektur findet per PN statt.</p>';
}
add_shortcode ( 'charakter-linkliste', 'lze_get_character_profilelist' );

//displays notification if a character has term 'Fertig'
function lze_notification_wob() {
    $chara_list = array();
    $counter = 0;
    if (current_user_can('administrator')) {
        $list = lze_get_characters();
        foreach ($list as $single_data) {
            $id = $single_data['id'];
            $name = $single_data['name'];
            $slug = $single_data['slug'];
            if (has_term('Fertig!', 'Fertig', $id) && !has_term('Charakter angenommen', 'Status', $id)) {
                echo '<div class="rpg_notification">';
                echo '<a href="'.get_home_url().'/lze_character/'.$slug.'">'.$name.'</a> ist fertig zur Korrektur!';
                echo '</div>';
            }
        } 
    }    
}
add_shortcode ( 'charakter-wob', 'lze_notification_wob' );

//add links for creating wanted (just for user with active characters) 
function lze_create_wanted_link() {
    if (lze_user_can_play()) {
        echo '<div class="rpg_createwanted">';
        echo '<a href="'.get_home_url().'/wp-admin/post-new.php?post_type=lze_s_wanted">Einzelgesuch erstellen</a> | <a href="'.get_home_url().'/wp-admin/post-new.php?post_type=lze_group_wanted">Gruppengesuch erstellen</a></div>';
    }
}
add_shortcode ( 'create-wanted', 'lze_create_wanted_link' );

?>
