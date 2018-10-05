<?php
//ADDITIONAL FIELDS FOR NEW TOPICS
// Adds the rpg-fields in the forum. 
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_own_character');
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_characters');
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_date');
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_location');
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_time');
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_offplay');

function rpg_bbp_own_character() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_ingame_meta', true);
    $rpg_charastuff     = '';
    $rpg_charastuff     = get_post_meta(get_the_ID(), 'rpg_charastuff_meta', true);
    $userid             = get_current_user_id();

    /*if (get_the_author_meta( 'ID' )) {
        if ($userid != get_the_author_meta( 'ID' )) {
            $userid = get_the_author_meta( 'ID' );
        }
    }*/
    
    if ($rpg_type == 'ingame' | $rpg_charastuff == 'charastuff' | $rpg_parent_type == 'ingame') {
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_own_character', true);
        $value = rpg_cut_id($value);
        echo '<div class="rpg_information">Charaktere</div>';
        echo '<div class="rpg_feld rpg_bbp_own_character"><label for="rpg_bbp_own_character">Mein Charakter</label><br>';
        echo '<select name="rpg_bbp_own_character">';
        $lze_characters = lze_get_characters_by_user_id($userid);
        usort($lze_characters, 'comparebytitle');
        foreach($lze_characters as $single_character) {
            if ($single_character['title'] != "Automatisch gespeicherter Entwurf") {
                if (lze_character_active($single_character['id']) && $single_character['title'] == $value)  {
                    echo '<option selected>'.$single_character['title'].'</option>';
                } else if (!lze_character_active($single_character['id'])) {
                    echo '<option class="rpg_not_ready" disabled>'.$single_character['title'].'</option>';
                } else {
                    echo '<option>'.$single_character['title'].'</option>';
                }
            }
        }
        echo '</select></div>';
    }
}

function rpg_bbp_characters() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_ingame_meta', true);
    if ($rpg_type == 'ingame' | $rpg_parent_type == 'ingame') {
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_characters', true);
        //formatiert die Values zur korrekten Ausgabe bei Edit
        $valuearray = explode(';', $value);
        $names = array();
        foreach($valuearray as $val) {
            $val = rpg_cut_id($val);
            array_push($names, $val);
        }
        $value = implode(';', $names);
        echo '<div class="rpg_feld rpg_bbp_characters">';
        echo '<label for"rpg_bbp_characters">Welche Charaktere sind sonst noch dabei?</label><br>';
        echo '<input id="rpg_bbp_characters" name="character_input" placeholder="Name eintippen">';
        echo '<div id="rpg_add_button">Klicken! <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
</div>';
        echo '<input id="rpg_collects_characters" name="rpg_bbp_characters" placeholder="Hier nix tippen." value="'.$value.'"></div>';
        $lze_characters = lze_get_characters();
        usort($lze_characters, 'comparebyname');
        $count      = count($lze_characters);
        $counter    = 0;
        echo '<script>jQuery( function() {
        var availableTags = [';
        //erstellt die Charakterliste, den letzten Eintrag ohne Komma
        foreach($lze_characters as $single_character) {
            if (lze_character_active($single_character['id'])) {
                if ($count > $counter) {
                    echo '"'.$single_character['name'].'",';
                    $counter ++;
                } else {
                    echo '"'.$single_character['name'].'"';
                }
            }
        }
        echo '];
        jQuery( "#rpg_bbp_characters" ).autocomplete({
          source: availableTags
          })
        });
    </script>';
    }
}

function rpg_bbp_date() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_ingame_meta', true);
    if ($rpg_type == 'ingame' | $rpg_parent_type == 'ingame') {
        $option_date = get_option('rpg_option_date');
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_date', true);
        echo '<div class="rpg_information">Szeneninformationen</div>';
        echo '<div class="rpg_feld rpg_bbp_date"><label for="rpg_bbp_date">Welches Datum?</label><br>';
        echo '<input id="datepicker" type="text" name="rpg_bbp_date" value="'.$value.'"></div>'; 
        echo '<script>jQuery(document).ready(function() {';
        echo 'jQuery("#datepicker").datepicker({';
        echo 'dayNamesMin: [ "So", "Mo", "Di", "Mi", "Do", "Fr", "Sa" ],
        monthNames: [ "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "December" ],
        nextText: "Später",
        prevText: "Früher",
        dateFormat: "yy/mm/dd",
        defaultDate: "'. $option_date .'",
        firstDay: 1
        });
        });
        </script>';
    }
}

function rpg_bbp_location() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_ingame_meta', true);
    if ($rpg_type == 'ingame' | $rpg_parent_type == 'ingame') {
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_location', true);
        echo '<div class="rpg_feld rpg_bbp_location"><label for="rpg_bbp_location">Wo spielt ihr?</label><br>';
        echo '<input  type="text" name="rpg_bbp_location" value="'.$value.'"></div>';
    }
}

function rpg_bbp_time() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_ingame_meta', true);
    if ($rpg_type == 'ingame' | $rpg_parent_type == 'ingame') {
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_time', true);
        echo '<div class="rpg_feld rpg_bbp_time"><label for="rpg_bbp_time">Tages- oder Uhrzeit?</label><br>';
        echo '<input type="text" name="rpg_bbp_time" value="'.$value.'"></div>';
    }
}

function rpg_bbp_offplay() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_ingame_meta', true);
    $rpg_charastuff     = get_post_meta(get_the_ID(), 'rpg_charastuff_meta', true);
    $rpg_partner        = get_post_meta(get_the_ID(), 'rpg_partner_meta', true);
    if (!$rpg_type && !$rpg_charastuff && !$rpg_partner) {
        $value = "";
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_offplay', true);
        echo '<div class="rpg_feld rpg_bbp_offplay"><label for"rpg_bbp_offplay">Untertitel</label><br>';
        echo '<input  type="text" name="rpg_bbp_offplay" value="'.$value.'"></div>';
    }
}

//Save and update the values
add_action ( 'bbp_new_topic', 'rpg_save_extra_fields', 10, 1 );
add_action ( 'bbp_edit_topic', 'rpg_save_extra_fields', 10, 1 );

function rpg_save_extra_fields($topic_id=0) {
    $userid = get_current_user_id();
    /*if (get_the_author_meta( 'ID' )) {
        if ($userid != get_the_author_meta( 'ID' )) {
            $userid = get_the_author_meta( 'ID' );
        }
    }*/
        
    //adds value character ID       
    $lze_save_characters = lze_get_characters();
    $id = "";
    foreach($lze_save_characters as $chara) {
        if (isset($_POST['rpg_bbp_own_character'])) {
            if ($chara['name'] == $_POST['rpg_bbp_own_character']) {
                $id = $chara['id']; 
            }
        }
    }
    $savevalue = $_POST['rpg_bbp_own_character'] . ', '.$id;

    $lze_save_characters2 = lze_get_characters();
    $id2 = "";
    $player = array();
    $player2 = array();
    $counter = 0;
    if (isset($_POST['rpg_bbp_characters'])) {
        if (strpos($_POST['rpg_bbp_characters'], ';')) {
            $player = explode('; ', $_POST['rpg_bbp_characters']);
            foreach($lze_save_characters2 as $chara2) {
                if (in_array($chara2['name'], $player)) {
                    //$value = $chara2['name'].', '.$chara2['id'];
                    array_push($player2, $chara2['name'].', '.$chara2['id']);
                } 
            }
            $savevalue2 = implode('; ', $player2);
        } else {
            foreach($lze_save_characters2 as $chara2) {
                if ($chara2['name'] == $_POST['rpg_bbp_characters']) {
                    $id2 = $chara2['id']; 
                }
            }
            $savevalue2 = $_POST['rpg_bbp_characters'] . ', '.$id2;
        }
    }
        
    
    if (isset($_POST) && $_POST['rpg_bbp_own_character']!='')
        update_post_meta( $topic_id, 'rpg_bbp_own_character', $savevalue );
    if (isset($_POST) && $_POST['rpg_bbp_characters']!='')
        update_post_meta( $topic_id, 'rpg_bbp_characters', $savevalue2 );
    if (isset($_POST) && $_POST['rpg_bbp_date']!='')
        update_post_meta( $topic_id, 'rpg_bbp_date', $_POST['rpg_bbp_date'] );
    if (isset($_POST) && $_POST['rpg_bbp_time']!='')
        update_post_meta( $topic_id, 'rpg_bbp_time', $_POST['rpg_bbp_time'] );
    if (isset($_POST) && $_POST['rpg_bbp_location']!='')
        update_post_meta( $topic_id, 'rpg_bbp_location', $_POST['rpg_bbp_location'] );
    if (isset($_POST) && $_POST['rpg_bbp_offplay']!='')
        update_post_meta( $topic_id, 'rpg_bbp_offplay', $_POST['rpg_bbp_offplay'] );
}

//Titelüberschrift
add_action( 'bbp_theme_before_topic_form_title', 'rpg_bbp_scene_title' ); 

function rpg_bbp_scene_title() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_ingame_meta', true);
    if ($rpg_type == 'ingame' | $rpg_parent_type == 'ingame') {
        echo '<div class="rpg_information">Szenentitel</div>';
    }
}

//ADDITIONAL FIELDS FOR NEW POSTINGS
//add the rpg-fields in the forum
add_action ( 'bbp_theme_before_reply_form_content', 'rpg_bbp_own_character_p');

function rpg_bbp_own_character_p() {
    $rpg_parent_id  = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_type       = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $userid         = get_current_user_id();
    if ($rpg_type == 'ingame') {
        $value = get_post_meta( bbp_get_reply_id(), 'rpg_bbp_own_character_p', true);
        $value = rpg_cut_id($value);
        echo '<div class="rpg_feld rpg_bbp_own_character_p"><label for="rpg_bbp_own_character_p">Mein Charakter</label><br>';
        echo '<select name="rpg_bbp_own_character_p">';
        $lze_characters = lze_get_characters_by_user_id($userid);
        usort($lze_characters, 'comparebytitle');
        foreach($lze_characters as $single_character) {
            if ($single_character['title'] != "Automatisch gespeicherter Entwurf") {
                if (lze_character_active($single_character['id']) && $single_character['title'] == $value) {
                    echo '<option selected>'.$single_character['title'].'</option>';
                }
                else if (!lze_character_active($single_character['id'])) {
                    echo '<option class="rpg_not_ready" disabled>'.$single_character['title'].'</option>';
                } else {
                    echo '<option>'.$single_character['title'].'</option>';
                }
            }
        }
        echo '</select></div>';
    }
}

//Save and update the values
add_action ( 'bbp_new_reply', 'rpg_save_extra_fields_p', 10, 1 );
add_action ( 'bbp_edit_reply', 'rpg_save_extra_fields_p', 10, 1 );

function rpg_save_extra_fields_p($post_id=0) {
        //adds value character ID     
    $userid = get_current_user_id();
    $lze_save_characters = lze_get_characters_by_user_id($userid);
    $id = "";
    foreach($lze_save_characters as $chara) {
        if (isset($_POST['rpg_bbp_own_character_p'])) {
            if ($chara['title'] == $_POST['rpg_bbp_own_character_p']) {
                $id = $chara['id']; 
            }
        }
    }
    $savevalue = $_POST['rpg_bbp_own_character_p'] . ', '.$id;
    
    if (isset($_POST) && $_POST['rpg_bbp_own_character_p']!='')
        update_post_meta( $post_id, 'rpg_bbp_own_character_p', $savevalue );
}


function rpg_preview_nocss() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_partner_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_partner_meta', true);
    if ($rpg_type !== 'partner' | $rpg_parent_type !== 'partner') {
        echo '<div id="rpg_preview_show"></div>';
        echo '<div id="rpg_preview_button" class="rpg_button">Vorschau</div>';
        echo '<p class="preview">BBCodes werden in der Vorschau nicht umgewandelt.</p>';
        echo '<style id="rpg_preview_css"></style>';
        ?>
        <script>
            jQuery('#rpg_preview_button').on('click', function() {
                var preContent = jQuery('#bbp_topic_content').val().replace(/\n/g, '<br />');
                jQuery('#rpg_preview_show').html(preContent);   
            });
        </script>
<?php
    }
}

//preview for every field but NOT partner. NoCSS.
//add_action ('bbp_theme_after_topic_form_content', 'rpg_preview_nocss');
?>