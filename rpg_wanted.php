<?php

//Register Single Wanted
add_action( 'init', 'lze_add_single_wanted' );
function lze_add_single_wanted() {  
    $labels = array(
        'name' => _x('Einzelgesuch', 'post type general name'), 
        'singular_name' => _x('Einzelgesuch', 'post type singular name'), 
        'add_new' => _x('Einzelgesuch hinzufügen', 'Einzelgesuch'), 
        'add_new_item' => _('Neues Einzelgesuch hinzufügen'), 
        'edit_item' => _('Einzelgesuch bearbeiten'), 
        'new_item' => _('Neues Einzelgesuch'), 
        'view_item' => _('Einzelgesuch ansehen'),
        'search_items' => _('Einzelgesuch suchen'),
        'not_found' => _('Kein Einzelgesuch gefunden'),
        'not_found_in_trash' => _('Kein Einzelgesuch im Papierkorb'),
        'parent_item_colon' => ''
    );

    $supports = array('title', 
                      'editor', 
                      'thumbnail', 
                      'author'
                     ); 

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        '_builtin' => false,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array("slug" => "lze_s_wanted"), 
        'capability_type' => 'post',
        'hierarchical' => false, 
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'supports' => $supports
    ); 

    register_post_type('lze_s_wanted', $args);
}

//Customfields Single Wanted
//not needed in frontend
if (is_admin()) {

    add_action('admin_init', 'singlewanted_boxes');
    add_action('save_post', 'save_singlewanted');

    /* -------------------hier endet die Initialisierung des Custom-Post-Types 'Steckbrief-Felder' -------------------------------*/

    function singlewanted_boxes() {
        add_meta_box("singlewanted_seeker", "Wer sucht?", "singlewanted_seeker", "lze_s_wanted", "normal", "high");
        add_meta_box("singlewanted_wanted", "Beschreibung des gesuchten Charakters", "singlewanted_wanted", "lze_s_wanted", "normal", "high");
        add_meta_box("singlewanted_reason", "Warum wird der Charakter gesucht?", "singlewanted_reason", "lze_s_wanted", "normal", "high");
		add_meta_box("singlewanted_relatedto", "Gehört der Charakter zu einer Gruppe?", "singlewanted_relatedto", "lze_s_wanted", "normal", "high");
        add_meta_box("singlewanted_avatar", "Vorschlag Avatarperson", "singlewanted_avatar", "lze_s_wanted", "normal", "high");
        add_meta_box("singlewanted_pic", "Bild", "singlewanted_pic", "lze_s_wanted", "normal", "high");
    }

    function singlewanted_seeker() {
        global $post;
        $seekers = array();
        if(get_post_meta( $post->ID, 'singlewanted_seeker', true)) {
            $seekers = get_post_meta($post->ID, 'singlewanted_seeker', true);
        }
        $list   = lze_get_characters();
        usort($list, 'comparebyname');
        $htmlList = '<option value="empty">Bitte wählen</option>';
        foreach($list as $chara) {
            if (lze_character_active($chara['id'])) {
                $htmlList .= '<option value="' . $chara['id'] . '">' . $chara['name'] . '</option>';
            }
        }

        $basicList = $htmlList;

        ?>
        <script type="text/javascript">
            jQuery(document).ready(function( $ ){
                $( '#add-row' ).on('click', function() {
                    var row = $( '.rpg_empty_row' ).clone();
                    row.removeClass( 'rpg_empty_row hidden' );
                    row.insertBefore( '.rpg_inputrow:last' );
                    return false;
                });

                $( '.remove-row' ).on('click', function() {
                    $(this).parents('.rpg_inputrow').remove();
                    return false;
                });

                $('#rpg_members').sortable();

            });

        </script>

        <div id="rpg_seekers">
            <?php

            if ($seekers) {
                $htmlList = '<option value="empty">Bitte wählen</option>';
                foreach ($seekers as $seeker) {
                    foreach($list as $chara) {
                        if (lze_character_active($chara['id'])) {
                            if ($chara['id'] === $seeker['sise-name']) {
                                $htmlList .= '<option selected="selected" value="' . $chara['id'] . '">' . $chara['name'] . '</option>';
                            } else {
                                $htmlList .= '<option value="' . $chara['id'] . '">' . $chara['name'] . '</option>';
                            }
                        }
                    }
                    echo '<div class="rpg_inputrow">';
                    echo '<label for="seeker_name">Charaktername</label><br>';
                    echo '<select name="sise-name[]">' . $htmlList . '</select><br><br>';
                    echo '<label for="seeker_desc">Beschreibung</label><br>';
                    echo '<textarea class="seeker_desc" name="sise-desc[]">'.$seeker['sise-desc'].'</textarea><br><br>';
                    echo '<br><br><a class="button remove-row" href="#">Suchender entfernen</a>';
                    echo '</div>';

                }
            } else {
                echo '<div class="rpg_inputrow">';
                echo '<label for="seeker_name">Charaktername</label><br>';
                echo '<select name="sise-name[]">' . $htmlList . '</select><br><br>';
                echo '<label for="seeker_desc">Beschreibung</label><br>';
                echo '<textarea class="seeker_desc" name="sise-desc[]">Beschreibe die Charakterbeziehung. </textarea><br><br>';
                echo '<br><br><a class="button remove-row" href="#">Suchender entfernen</a>';
                echo '</div>';
            }


            echo '<div class="rpg_empty_row rpg_inputrow hidden">'.
                '<label for="seeker_name">Charaktername</label><br>'.
                '<select name="sise-name[]">' . $basicList . '</select><br><br>'.
                '<label for="seeker_desc">Beschreibung</label><br>'.
                '<textarea class="seeker_desc" name="sise-desc[]">Beschreibe die Charakterbeziehung. </textarea><br><br>'.
                '<br><br><a class="button remove-row" href="#">Suchender entfernen</a>'.
            '</div>'.

        '</div>'.

        '<p><a id="add-row" class="button" href="#">Suchender hinzufügen</a></p>';


    }

    function singlewanted_wanted() {
        global $post;
        $value = "";
        if ( get_post_meta( $post->ID, 'singlewanted_wanted', true) ) {
            $value = get_post_meta( $post->ID, 'singlewanted_wanted', true);
        }
        echo '<textarea class="singlewanted_wanted" name="singlewanted_wanted"/>'.$value.'</textarea>';
    }

    function singlewanted_reason() {
        global $post;
        $value = "";
        if (get_post_meta( $post->ID, 'singlewanted_reason', true)) {
            $value = get_post_meta( $post->ID, 'singlewanted_reason', true);
        }
        echo '<textarea class="singlewanted_reason" name="singlewanted_reason"/>'.$value.'</textarea>';
    }

	function singlewanted_relatedto() {
        global $post;
        $value = "";
        if (get_post_meta( $post->ID, 'singlewanted_relatedto', true)) {
            $value = get_post_meta( $post->ID, 'singlewanted_relatedto', true);
        }
		$options = '';
		$allGroups = lze_get_groups();
		foreach ($allGroups as $single_data) {
			if ($value == $single_data->slug) {
				$options .= '<option selected="selected" value="'.$single_data->slug.'">'.$single_data->name.'</option>';
			} else {
				$options .= '<option value="'.$single_data->slug.'">'.$single_data->name.'</option>';
			}
		}

		echo '<select name="singlewanted_relatedto"><option value="nogroup">Nein</option>'. $options .'</select><br><br>';
		
    }


    function singlewanted_avatar() {
        global $post;
        $value = "";
        if (get_post_meta( $post->ID, 'singlewanted_avatar', true)){
            $value = get_post_meta( $post->ID, 'singlewanted_avatar', true);
        }
        echo '<input class="singlewanted_avatar" name="singlewanted_avatar" value="'.$value.'"/>';
    }

    function xxxx_add_edit_form_multipart_encoding() {

        echo ' enctype="multipart/form-data"';

    }
    add_action('post_edit_form_tag', 'xxxx_add_edit_form_multipart_encoding');
    function singlewanted_pic() {
        global $post;
        $value = "";
        if (get_post_meta( $post->ID, 'singlewanted_pic', true)){
            $value = get_post_meta( $post->ID, 'singlewanted_pic', true);
        }
        echo '<input class="singlewanted_pic" name="singlewanted_pic" value="'.$value.'"/>';
    }

    function save_singlewanted($topic_id=0) {
        if (isset($_POST) && isset($_POST['singlewanted_reason'])) {
            update_post_meta( $topic_id, 'singlewanted_reason', $_POST['singlewanted_reason'] );
        }

        if (isset($_POST) && isset($_POST['singlewanted_wanted'])) {
            update_post_meta( $topic_id, 'singlewanted_wanted', $_POST['singlewanted_wanted'] );
        }

        if (isset($_POST) && isset($_POST['singlewanted_avatar'])){
            update_post_meta( $topic_id, 'singlewanted_avatar', $_POST['singlewanted_avatar'] );
        }

        if (isset($_POST) && isset($_POST['singlewanted_pic'])) {
            update_post_meta( $topic_id, 'singlewanted_pic', $_POST['singlewanted_pic'] );
        }

		if (isset($_POST)) {
            update_post_meta( $topic_id, 'singlewanted_relatedto', $_POST['singlewanted_relatedto'] );
        }


        if (isset($_POST)) {
            $newSise = array();
            $siseNames = array();
            $siseDescs = array();
            if (isset($_POST['sise-name'])) {
                $siseNames = $_POST['sise-name'];
            }
            if (isset($_POST['sise-desc'])) {
                $siseDescs = $_POST['sise-desc'];
            }

            $count = count($siseNames);

            for ( $i = 0; $i < $count; $i++ ) {
                if ($siseNames[$i] && $siseNames[$i] !== 'empty') {
                    $newSise[$i] = array();
                    $newSise[$i]['sise-name'] = $siseNames[$i];
                    $newSise[$i]['sise-desc'] = $siseDescs[$i];
                }
            }

            if (isset($_POST['sise-name'])) {
                update_post_meta( $topic_id, 'singlewanted_seeker', $newSise );
            }
        }
    }
}

//Register Group Wanted
//needed in frontend
add_action( 'init', 'lze_add_group_wanted' );
function lze_add_group_wanted() {
    $labels = array(
        'name' => _x('Gruppengesuch', 'post type general name'),
        'singular_name' => _x('Gruppengesuch', 'post type singular name'),
        'add_new' => _x('Gruppengesuch hinzufügen', 'Gruppengesuch'),
        'add_new_item' => _('Neues Gruppengesuch hinzufügen'),
        'edit_item' => _('Gruppengesuch bearbeiten'),
        'new_item' => _('Neues Gruppengesuch'),
        'view_item' => _('Gruppengesuch ansehen'),
        'search_items' => _('Gruppengesuch suchen'),
        'not_found' => _('Kein Gruppengesuch gefunden'),
        'not_found_in_trash' => _('Kein Gruppengesuch im Papierkorb'),
        'parent_item_colon' => ''
    );

    $supports = array('title',
                      'editor',
                      'thumbnail'
                     );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        '_builtin' => false,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array("slug" => "lze_group_wanted"),
        'capability_type' => 'post',
        'hierarchical' => false,
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'supports' => $supports
    );

    register_post_type('lze_group_wanted', $args);
}

if (is_admin()) {
    add_action('admin_init', 'groupwanted_boxes');
    add_action('save_post', 'save_groupwanted');

    function groupwanted_boxes() {
        add_meta_box("groupwanted_seeker", "Wer sucht?", "groupwanted_seeker", "lze_group_wanted", "normal", "high");
        add_meta_box("groupwanted_wanted", "Gruppenbeschreibung", "groupwanted_wanted", "lze_group_wanted", "normal", "high");
        add_meta_box("groupwanted_reason", "Warum wird gesucht?", "groupwanted_reason", "lze_group_wanted", "normal", "high");
        add_meta_box("groupwanted_members", "Gesuchte Gruppenmitglieder", "groupwanted_members", "lze_group_wanted", "normal", "high");
		add_meta_box("groupwanted_relatedto", "Ist das eine offizielle Foren-(Unter-)Gruppe?", "groupwanted_relatedto", "lze_group_wanted", "normal", "high");
        add_meta_box("groupwanted_pic", "Symbolbild der Gruppe", "groupwanted_pic", "lze_group_wanted", "normal", "high");
    }

    function groupwanted_seeker() {
        global $post;
        $seekers = array();
        if(get_post_meta( $post->ID, 'groupwanted_seeker', true)) {
            $seekers = get_post_meta($post->ID, 'groupwanted_seeker', true);
        }
        $list   = lze_get_characters();
        usort($list, 'comparebyname');
        $htmlList = '<option value="empty">Bitte wählen</option>';
        foreach($list as $chara) {
            if (lze_character_active($chara['id'])) {
                $htmlList .= '<option value="' . $chara['id'] . '">' . $chara['name'] . '</option>';
            }
        }
        $basicList = $htmlList;

        ?>
        <script type="text/javascript">
            jQuery(document).ready(function( $ ){
                $( '#add-seekerrow' ).on('click', function() {
                    var row = $( '.rpg_empty_row_s' ).clone();
                    row.removeClass( 'rpg_empty_row_s hidden_s' );
                    row.insertBefore( '.rpg_inputrow_s:last' );
                    return false;
                });

                $( '.remove-row' ).on('click', function() {
                    $(this).parents('.rpg_inputrow_s').remove();
                    return false;
                });

                $('#rpg_seekers').sortable();

            });

        </script>





        <div id="rpg_seekers">
            <?php

            if ($seekers) {
                $htmlList = '<option value="empty">Bitte wählen</option>';
                foreach ($seekers as $seeker) {
                    foreach($list as $chara) {
                        if (lze_character_active($chara['id'])) {
                            if ($chara['id'] === $seeker['grse-name']) {
                                $htmlList .= '<option selected="selected" value="' . $chara['id'] . '">' . $chara['name'] . '</option>';
                            } else {
                                $htmlList .= '<option value="' . $chara['id'] . '">' . $chara['name'] . '</option>';
                            }
                        }
                    }
                    echo '<div class="rpg_inputrow_s">';
                    echo '<label for="seeker_name">Charaktername</label><br>';
                    echo '<select name="grse-name[]">' . $htmlList . '</select><br><br>';
                    echo '<label for="seeker_desc">Beschreibung</label><br>';
                    echo '<textarea class="seeker_desc" name="grse-desc[]">'.$seeker['grse-desc'].'</textarea><br><br>';
                    echo '<br><br><a class="button remove-row" href="#">Suchender entfernen</a>';
                    echo '</div>';

                }
            } else {
                echo '<div class="rpg_inputrow_s">';
                echo '<label for="seeker_name">Charaktername</label><br>';
                echo '<select name="grse-name[]">' . $htmlList . '</select><br><br>';
                echo '<label for="seeker_desc">Beschreibung</label><br>';
                echo '<textarea class="seeker_desc" name="grse-desc[]">Beschreibe die Charakterbeziehung. </textarea><br><br>';
                echo '<br><br><a class="button remove-row" href="#">Suchender entfernen</a>';
                echo '</div>';
            }


            echo '<div class="rpg_empty_row_s rpg_inputrow_s hidden_s">'.
                '<label for="seeker_name">Charaktername</label><br>'.
                '<select name="grse-name[]">' . $basicList . '</select><br><br>'.
                '<label for="seeker_desc">Beschreibung</label><br>'.
                '<textarea class="seeker_desc" name="grse-desc[]">Beschreibe die Charakterbeziehung.</textarea><br><br>'.
                '<br><br><a class="button remove-row" href="#">Suchender entfernen</a>'.
                '</div>'.

                '</div>'.

                '<p><a id="add-seekerrow" class="button" href="#">Suchender hinzufügen</a></p>';

    }

    function groupwanted_wanted() {
        global $post;
        $value = get_post_meta( $post->ID, 'groupwanted_wanted', true);
        echo '<textarea class="groupwanted_wanted" name="groupwanted_wanted"/>'.$value.'</textarea>';
    }

    function groupwanted_reason() {
        global $post;
        $value = get_post_meta( $post->ID, 'groupwanted_reason', true);
        echo '<textarea class="groupwanted_reason" name="groupwanted_reason">'.$value.'</textarea>';
    }

	function groupwanted_relatedto() {
        global $post;
        $value = "";
        if (get_post_meta( $post->ID, 'groupwanted_relatedto', true)) {
            $value = get_post_meta( $post->ID, 'groupwanted_relatedto', true);
        }
		$options = '';
		$allGroups = lze_get_groups();
		foreach ($allGroups as $single_data) {
			if ($value == $single_data->slug) {
				$options .= '<option selected="selected" value="'.$single_data->slug.'">'.$single_data->name.'</option>';
			} else {
				$options .= '<option value="'.$single_data->slug.'">'.$single_data->name.'</option>';
			}
		}

		echo '<select name="groupwanted_relatedto"><option value="nogroup">Nein</option>'. $options .'</select><br><br>';
		
    }

    function groupwanted_pic() {
        global $post;
        $value = get_post_meta( $post->ID, 'groupwanted_pic', true);
        echo '<input class="groupwanted_pic" name="groupwanted_pic" value="'.$value.'"/>';
        echo '<p>180x250px</p>';
    }

    function groupwanted_members() {
        global $post;
        $members = get_post_meta($post->ID, 'groupwanted_members', true);
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function( $ ){
            $( '#add-row' ).on('click', function() {
                var row = $( '.rpg_empty_row' ).clone();
                row.removeClass( 'rpg_empty_row hidden' );
                row.insertBefore( '.rpg_inputrow:last' );
                // tinymce.remove();
                // tinymce.init({selector:'textarea'});
                return false;
            });

            $( '.remove-row' ).on('click', function() {
                $(this).parents('.rpg_inputrow').remove();
                return false;
            });

            $('#rpg_members').sortable();

        });

        </script>

        <div id="rpg_members">
        <?php

        if ($members) {
            foreach ($members as $member) {
                echo '<div class="rpg_inputrow">';
                echo '<label for="member_name">Charaktername</label><br>';
                echo '<input type="text" class="member_name" name="name[]" value="'.$member['name'].'"/><br><br>';
                echo '<label for="member_desc">Beschreibung</label><br>';
                echo '<textarea class="member_desc" name="desc[]">'.$member['desc'].'</textarea><br><br>';
                echo '<label for="member_avatar">Avatar (URL, 180x250px)</label><br>';
                echo '<input type="text" class="member_avatar" name="ava[]" value="'.$member['ava'].'"/>';
                echo '<br><br><a class="button remove-row" href="#">Gruppenmitglied entfernen</a>';
                echo '</div>';
            }
        }
            else {
                echo '<div class="rpg_inputrow">';
                echo '<label for="member_name">Charaktername</label><br>';
                echo '<input type="text" class="member_name" name="name[]"/><br><br>';
                echo '<label for="member_desc">Beschreibung</label><br>';
                echo '<textarea class="member_desc" name="desc[]">Beschreibe kurz den Charakter</textarea><br><br>';
                echo '<label for="member_avatar">Avatar (URL, 180x250px)</label><br>';
                echo '<input type="text" class="member_avatar" name="ava[]"/>';
                echo '<br><br><a class="button remove-row" href="#">Gruppenmitglied entfernen</a>';
                echo '</div>';
            }


            echo    '<div class="rpg_empty_row rpg_inputrow hidden">'.
                    '<label for="member_name">Charaktername</label><br>'.
                    '<input type="text" class="member_name" name="name[]"/><br><br>'.
                    '<label for="member_desc">Beschreibung</label><br>'.
                    '<textarea class="member_desc" name="desc[]">Beschreibe kurz den Charakter</textarea><br><br>'.
                    '<label for="member_avatar">Avatar (URL, 180x250px)</label><br>'.
                    '<input type="text" class="member_avatar" name="ava[]"/>'.
                    '<br><br><a class="button remove-row" href="#">Gruppenmitglied entfernen</a>'.
                    '</div>'.

                    '</div>'.

                    '<p><a id="add-row" class="button" href="#">Gruppenmitglied hinzufügen</a></p>';
    }

    function save_groupwanted() {
        global $post;
        if (isset($_POST) && isset($_POST['groupwanted_wanted'])) {
            update_post_meta( $post->ID, 'groupwanted_wanted', $_POST['groupwanted_wanted'] );
        }
        if (isset($_POST) && isset($_POST['groupwanted_reason'])) {
            update_post_meta( $post->ID, 'groupwanted_reason', $_POST['groupwanted_reason'] );
        }
        if (isset($_POST) && isset($_POST['groupwanted_pic'])) {
            update_post_meta( $post->ID, 'groupwanted_pic', $_POST['groupwanted_pic'] );
        }

		if (isset($_POST)) {
            update_post_meta( $post->ID, 'groupwanted_relatedto', $_POST['groupwanted_relatedto'] );
        }

        if (isset($_POST) && isset($_POST['groupwanted_wanted'])) {
            $newGrse = array();
            $grseNames = array();
            $grseDescs = array();
            if (isset($_POST['grse-name'])) {
                $grseNames = $_POST['grse-name'];
            }
            if (isset($_POST['grse-desc'])) {
                $grseDescs = $_POST['grse-desc'];
            }

            $grsecount = count($grseNames);

            for ($i = 0; $i < $grsecount; $i++) {
                if ($grseNames[$i] && $grseNames[$i] !== 'empty') {
                    $newGrse[$i] = array();
                    $newGrse[$i]['grse-name'] = $grseNames[$i];
                    $newGrse[$i]['grse-desc'] = $grseDescs[$i];
                }
            }
            if (isset($_POST['grse-name'])) {
                update_post_meta($post->ID, 'groupwanted_seeker', $newGrse);
            }



            $new = array();
            $names = array();
            $descs = array();
            $avas = array();
            if (isset($_POST['name'])) {
                $names = $_POST['name'];
            }
            if (isset($_POST['desc'])) {
                $descs = $_POST['desc'];
            }
            if (isset($_POST['ava'])) {
                $avas = $_POST['ava'];
            }

            $count = count($names);

            for ($i = 0; $i < $count; $i++) {
                if ($names[$i]) {
                    $new[$i] = array();
                    $new[$i]['name'] = $names[$i];
                    $new[$i]['desc'] = $descs[$i];
                    $new[$i]['ava'] = $avas[$i];
                }
            }
            if (isset($_POST['name'])) {
                update_post_meta($post->ID, 'groupwanted_members', $new);
            }
        }
    }
}

?>
