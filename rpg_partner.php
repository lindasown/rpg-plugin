<?php
//Addtional Fields for PARTNERTOPICS!
// Adds the rpg-fields in the forum. 
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_partner_genre');
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_partner_age');
add_action ( 'bbp_theme_before_topic_form_content', 'rpg_bbp_partner_freetext');
add_action ( 'bbp_theme_after_topic_form_content', 'rpg_bbp_partner_css');

function rpg_bbp_partner_genre() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_partner_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_partner_meta', true);
    $dropdown            = array('Real Life', 'Bücher / Filme / Serien', 'Fantasy / Sci-Fi', 'Crossover');
    if ($rpg_type == 'partner' | $rpg_parent_type == 'partner') {
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_partner_genre', true);
        $value = rpg_cut_id($value);
        echo '<div class="rpg_feld rpg_bbp_partner_genre"><label for="rpg_bbp_partner_genre">Genre</label><br>';
        echo '<select name="rpg_bbp_partner_genre">';
        foreach($dropdown as $select) {
            if ($select == $value) {
                echo '<option selected>'.$select.'</option>';
            } else {
                echo '<option>'.$select.'</option>';
            }
        }
        echo '</select></div>';
    }
}

function rpg_bbp_partner_age() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_partner_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_partner_meta', true);
    $dropdown            = array('ohne Altersbegrenzung', 'ab 12 Jahren', 'ab 16 Jahren', 'ab 18 Jahren');
    if ($rpg_type == 'partner' | $rpg_parent_type == 'partner') {
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_partner_age', true);
        echo '<div class="rpg_feld rpg_bbp_partner_age"><label for="rpg_bbp_partner_age">Genre</label><br>';
        echo '<select name="rpg_bbp_partner_age">';
        foreach($dropdown as $select) {
            if ($select == $value) {
                echo '<option selected>'.$select.'</option>';
            } else {
                echo '<option>'.$select.'</option>';
            }
        }
        echo '</select></div>';
    }
}

function rpg_bbp_partner_freetext() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_partner_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_partner_meta', true);
    if ($rpg_type == 'partner' | $rpg_parent_type == 'partner') {
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_partner_freetext', true);
        echo '<div class="rpg_feld rpg_bbp_partner_freetext"><label for="rpg_bbp_partner_freetext">Freies Eingabefeld</label><br>';
        echo '<input  type="text" name="rpg_bbp_partner_freetext" value="'.$value.'"></div>';
    }
}

function rpg_bbp_partner_css() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_partner_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_partner_meta', true);
    if ($rpg_type == 'partner' | $rpg_parent_type == 'partner') {
        $value = get_post_meta( bbp_get_topic_id(), 'rpg_bbp_partner_css', true);
        echo 'CSS<br>';
        if ($value) {
            echo '<textarea class="lze_textarea lze_partner" id="lze_partner_css" name="rpg_bbp_partner_css"/>'.$value.'</textarea><p class="description css">Füg hier, wenn vorhanden, dein CSS ein. <p>';
        } else {
        echo '<textarea class="lze_textarea lze_partner"  id="lze_partner_css" name="rpg_bbp_partner_css" placeholder="Füg hier bitte dein CSS OHNE <style> </style> ein. "/></textarea><p class="description css">Füg hier, wenn vorhanden, dein CSS ein. <p>';
        }
    }
}

//Save and update the values
add_action ( 'bbp_new_topic', 'rpg_save_partner_fields', 10, 1 );
add_action ( 'bbp_edit_topic', 'rpg_save_partner_fields', 10, 1 );

function rpg_save_partner_fields($topic_id=0) {
    if (isset($_POST) && $_POST['rpg_bbp_partner_genre']!='')
        update_post_meta( $topic_id, 'rpg_bbp_partner_genre', $_POST['rpg_bbp_partner_genre'] );
    if (isset($_POST) && $_POST['rpg_bbp_partner_age']!='')
        update_post_meta( $topic_id, 'rpg_bbp_partner_age', $_POST['rpg_bbp_partner_age'] );
    if (isset($_POST) && $_POST['rpg_bbp_partner_freetext']!='')
        update_post_meta( $topic_id, 'rpg_bbp_partner_freetext', $_POST['rpg_bbp_partner_freetext'] );
    if (isset($_POST) && $_POST['rpg_bbp_partner_css']!='')
        update_post_meta( $topic_id, 'rpg_bbp_partner_css', $_POST['rpg_bbp_partner_css'] );
}

//put CSS of Partnerrequests in Footer for corred displaying the partnerrequests
function lze_put_css_in_footer() {
    $topic_id           = bbp_get_topic_id();
    $rpg_parent_id      = wp_get_post_parent_id( $topic_id );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_partner_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_partner_meta', true);
    if ($rpg_type == 'partner' | $rpg_parent_type == 'partner') {
        $styles = get_post_meta($topic_id, 'rpg_bbp_partner_css', true);
        echo '<div id="partnercode" class="hidden">'.$styles.'</div>';
?>
        <script>
            var content = jQuery('#partnercode').text();
            jQuery('<style class="lze_partner"></style>').text(content).appendTo('body');
        </script>
        <?
    }
}
add_action ('bbp_theme_after_reply_form', 'lze_put_css_in_footer');

//preview for partnerboards

add_action ('bbp_theme_after_topic_form_content', 'rpg_preview');

function rpg_preview() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_partner_meta', true);
    $rpg_type           = '';
    $rpg_type           = get_post_meta(get_the_ID(), 'rpg_partner_meta', true);
    if ($rpg_type == 'partner' | $rpg_parent_type == 'partner') {
        echo '<div id="rpg_preview_show"></div>';
        echo '<div id="rpg_preview_button" class="rpg_button">Vorschau</div>';
        echo '<p class="preview">BBCodes werden in der Vorschau nicht umgewandelt.</p>';
        echo '<style id="rpg_preview_css"></style>';
        ?>
        <script>
            jQuery('#rpg_preview_button').on('click', function() {
                var preContent = jQuery('#bbp_topic_content').val().replace(/\n/g, '<br />');
                var preCSS      = jQuery('#lze_partner_css').val();
                jQuery('#rpg_preview_css').html(preCSS);
                jQuery('#rpg_preview_show').html(preContent);   
            });
        </script>
<?php
    }
}



?>