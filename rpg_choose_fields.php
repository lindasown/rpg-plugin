<?php
//not needed in frontend
if (is_admin()) {
    add_action( 'init', 'lze_add_new_field' );
    function lze_add_new_field() {  
        $labels = array(
            'name' => _x('Steckbrief-Felder', 'post type general name'), 
            'singular_name' => _x('Steckbrief-Feld', 'post type singular name'), 
            'add_new' => _x('Feld hinzufügen', 'Steckbrief-Feld'), 
            'add_new_item' => _('Neues Feld hinzufügen'), 
            'edit_item' => _('Feld bearbeiten'), 
            'new_item' => _('Neues Feld'), 
            'view_item' => _('Feld ansehen'),
            'search_items' => _('Feld suchen'),
            'not_found' => _('Kein Feld gefunden'),
            'not_found_in_trash' => _('Kein Feld im Papierkorb'),
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
            'rewrite' => array("slug" => "lze_felder"), 
            'capability_type' => 'post',
            'hierarchical' => false, 
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'supports' => $supports
        ); 

        register_post_type('lze_felder', $args);
    }

    add_action("admin_init", "steckbrief_meta_boxes");
    add_action('save_post', 'save_character_profile_field');

    /* -------------------hier endet die Initialisierung des Custom-Post-Types 'Steckbrief-Felder' -------------------------------*/

    function steckbrief_meta_boxes () {
        add_meta_box("character_profile_meta", "Steckbrieffeld", "character_profile_field", "lze_felder", "normal", "high");
        add_meta_box("character_profile_type_meta", "Datentyp", "character_profile_type_meta", "lze_felder", "normal", "high");
    }

    function character_profile_field() {
        global $post;
        $custom = get_post_custom($post->ID);
        echo '<input type="hidden" name="character_profile_field" value="steckbrieffeld"/>';
    }

    function character_profile_type_meta() {
        global $post;
        $custom     = get_post_custom($post->ID);
        $lze_radio  = "";
        $lze_lt     = "";
        $lze_kt     = "";
        $character_profile_type_meta = $custom["character_profile_type_meta"][0];
        if(isset($character_profile_type_meta)) {
            if ($character_profile_type_meta == "Radio") {
                $lze_radio = "checked";
            } else if ($character_profile_type_meta == "LangerText") {
                $lze_lt = "checked";
            } else {
                $lze_kt = "checked";
            }
            echo    '<input type="radio" name="character_profile_type_meta" value="KurzerText" '. $lze_kt . '> Einzeiliger Text<br>
                    <input type="radio" name="character_profile_type_meta" value="LangerText" '. $lze_lt . '> Mehrzeiliger Text<br>';
        } else {
            echo    '<input type="radio" name="character_profile_type_meta" value="KurzerText"> Einzeiliger Text<br>
                    <input type="radio" name="character_profile_type_meta" value="LangerText" checked> Mehrzeiliger Text<br>';
        }
    }

    function save_character_profile_field() {
        global $post;
        if (isset($_POST["character_profile_field"])){
            update_post_meta($post->ID, "character_profile_field", $_POST["character_profile_field"]);
        }
        if (isset($_POST["character_profile_type_meta"])){
            update_post_meta($post->ID, "character_profile_type_meta", $_POST["character_profile_type_meta"]);
        }
    }


/* -------------------- hier beginnt der Text für den individuellen Charaktersteckbrief ------------------------*/
}
//needed in frontend

/* Initialise Character-Posttype. */
add_action( 'init', 'lze_add_character' );

function lze_add_character() {  
    $labels = array(
        'name' => _x('Charaktere', 'post type general name'), 
        'singular_name' => _x('Charakter', 'post type singular name'), 
        'add_new' => _x('Charakter erstellen', 'Character'), 
        'add_new_item' => _('Neuer Charakter hinzufügen'), 
        'edit_item' => _('Charakter bearbeiten'), 
        'new_item' => _('Neuer Charakter'), 
        'view_item' => _('Charakter ansehen'),
        'search_items' => _('Charakter suchen'),
        'not_found' => _('Kein Charakter gefunden'),
        'not_found_in_trash' => _('Kein Charakter im Papierkorb'),
        'parent_item_colon' => ''
    );

    $supports = array('title', 
                      'editor', 
                      'author',
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
        'rewrite' => array("slug" => "lze_character"), 
        'capability_type' => 'post',
        'hierarchical' => false, 
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'supports' => $supports
    ); 

    register_post_type('lze_character', $args);
}

//not needed in frontend
if (is_admin()) {    
    //initialise function which writes custom-field-file for character-post-type
    add_action('admin_init', 'lze_write_field_document');
    
    //opens, writes and closes file based on functions generating content
    function lze_write_field_document() {
        $lze_field_file = fopen("rpg_field_file.php", "w") or die('Konnte die Datei nicht öffnen.');
        fwrite($lze_field_file, '<?php ') or die ("konnte php-start nicht schreiben.");
        $txt            = lze_get_field_initial();   
        fwrite($lze_field_file, $txt) or die ("konnte initalisierung nicht schreiben.");
        $txt            = lze_get_field_information();
        fwrite($lze_field_file, $txt) or die ("konnte feldinformationen nicht schreiben.");
        $txt            = lze_get_field_saving();
        fwrite($lze_field_file, $txt) or die ("konnte saving nicht schreiben.");
        fwrite($lze_field_file, ' ?>') or die ("konnte php-ende nicht schreiben.");
        fclose($lze_field_file);  
    }

    //generates content for initialising custom fields
    function lze_get_field_initial() {
        global $wpdb;
        //Initialisation of sex, avatar and signature is default in file
        $lze_txt    = array('add_meta_box("lze_sex_meta", "Geschlecht", "lze_character_sex", "lze_character", "normal", "high"); add_meta_box("lze_ava_meta", "Avatar", "lze_character_ava", "lze_character", "side", "high"); add_meta_box("lze_sig_meta", "Signatur", "lze_character_sig", "lze_character", "side", "high");');

        $lze_data   = $wpdb->get_results("SELECT meta_value, post_id FROM wp_postmeta WHERE meta_value = 'steckbrieffeld'");
        $sorted_data    = array();
        $counter        = 0;
        $multprof       = 0;


        foreach ($lze_data as $single_data) {
            $lze_posts  = $wpdb->get_results("SELECT post_date, post_title, post_content, post_name FROM wp_posts WHERE ID = '".$single_data->post_id."'");
            foreach($lze_posts as $lze_post) {
                $sorted_data[$counter]              = array();
                $sorted_data[$counter]['title']     = $lze_post->post_title;
                $date                               = strtotime($lze_post->post_date);
                $sorted_data[$counter]['date']      = $date;
                $sorted_data[$counter]['content']   = $lze_post->post_content;
                $sorted_data[$counter]['slug']      = $lze_post->post_name;
                $counter++;
            }
        }
        usort($sorted_data, "cmp");
        $sorted_data = array_reverse($sorted_data);
        foreach ($sorted_data as $single_data) {
            $val_title = $single_data['title'];
            $val_slug = $single_data['slug'];
            //cuts '-' out of slugs
            $val_slug = str_replace("-", "", $val_slug);
            $newcontent = 'add_meta_box("lze_'.$val_slug.'", "'.$val_title.'" , "lze_'.$val_slug.'" , "lze_character", "normal","high");';
            array_push($lze_txt, $newcontent);                        
                      }
        //changes array to string for the file
        $lze_txt = implode($lze_txt);
        //add text and function header
        $lze_new_txt    = 'function lze_new_character_meta_boxes() {'. $lze_txt . '}';
        return $lze_new_txt;
    }

    //generates content for displaying custom fields
    function lze_get_field_information() {
        global $wpdb;
        //functions for sex, avatar and signature are default
        $lze_txt    = array('function lze_character_sex() {
        global $post;
        $custom         = get_post_custom($post->ID);
        $lze_male       = "";
        $lze_female     = "";
        if (get_post_meta( $post->ID, \'lze_character_sex\', true )) {
            $lze_character_sex  = get_post_meta( $post->ID, \'lze_character_sex\', true );
        }
        if ($lze_character_sex == "Mann") {
            $lze_male = "checked";
        } else {
            $lze_female = "checked";
        }

        echo \'<input type="radio" name="lze_character_sex" value="Mann" \'. $lze_male . \'> Männlich<br><input type="radio" name="lze_character_sex" value="Frau" \'. $lze_female . \'> Weiblich\';};

        function lze_character_ava() {
        global $post;
        $lze_character_ava      = "";
        $toggle = 0;
        if (get_post_meta( $post->ID, \'lze_character_ava\', true )) {
            $lze_character_ava  = get_post_meta( $post->ID, \'lze_character_ava\', true );
            $toggle = 1;
        }
        if ($toggle) {
            $display = $lze_character_ava;
        } else {
            $display = $image = get_option(\'rpg_options_dummypic\');
        }
        echo \'<img class="rpg_profile_img" src=\'.$display.\'>\';
        echo \'<p>Bitte gib eine URL an. <br>180px * 250px</p>\';
        echo \'<input class="lze_textfeld" name="lze_character_ava" value="\'. $lze_character_ava .\'"/>\';}

        function lze_character_sig() {
        global $post;
        $lze_character_sig = "";
        $toggle = 0;
        if (get_post_meta( $post->ID, \'lze_character_sig\', true )) {
            $lze_character_sig  = get_post_meta( $post->ID, \'lze_character_sig\', true );
            $toggle = 1;
        }
        if ($toggle) {echo \'<img class="rpg_profile_img" src=\'.$lze_character_sig.\'>\';}
        echo \'<p>Bitte gib eine URL an.<br>max. 400px * 200px</p>\';
        echo \'<input class="lze_textfeld" name="lze_character_sig" value="\'. $lze_character_sig .\'"/>\';}'

    );
        //gets all post-IDs with clue for 'textarea'
        $longtext   = $wpdb->get_results("SELECT post_id FROM wp_postmeta WHERE meta_value = 'LangerText'");
        $ltArray    = array();
        foreach ($longtext as $longie) {
            array_push($ltArray, $longie->post_id);        
        }
        //gets all post-IDs with clue for 'input'
        $shorttext  = $wpdb->get_results("SELECT post_id FROM wp_postmeta WHERE meta_value = 'KurzerText'");
        $stArray    = array();
        foreach ($shorttext as $shortie) {
            array_push($stArray, $shortie->post_id);        
        }
        //gets all post-IDs with value 'steckbrieffeld'
        $lze_data   = $wpdb->get_results("SELECT post_id, meta_value FROM wp_postmeta WHERE meta_value = 'steckbrieffeld'");
        $sorted_data    = array();
        $counter        = 0;
        foreach ($lze_data as $single_data) {
            $lze_posts  = $wpdb->get_results("SELECT ID, post_date, post_title, post_content, post_name FROM wp_posts WHERE ID = '".$single_data->post_id."'");
            foreach($lze_posts as $lze_post) {
                $sorted_data[$counter] = array();
                $sorted_data[$counter]['title'] = $lze_post->post_title;
                $sorted_data[$counter]['date'] = $lze_post->post_date;
                $sorted_data[$counter]['content'] = $lze_post->post_content;
                $sorted_data[$counter]['id'] = $lze_post->ID;
                $sorted_data[$counter]['slug'] = $lze_post->post_name;
                $counter++;
            }
        }

        foreach ($sorted_data as $single_data) {
            $id             = $single_data['id'];
            $value          = $single_data['title'];
            $text           = $single_data['content'];
            $slug           = $single_data['slug'];
            $slug           = str_replace("-", "", $slug);
            //checks, if field is textarea. Else: inputfield.
            if ($slug){
                if (in_array($id, $ltArray)) {
                    $new_content    = 
                        'function lze_'.$slug.'() {
                            $info = "bigpart";
                            global $post;
                            $wert = "";
                            if (get_post_meta( $post->ID, \'lze_'.$slug.'\', true )) {
                                $wert  = get_post_meta( $post->ID, \'lze_'.$slug.'\', true );
                            }
                            if (lze_character_active(get_the_ID()) && !current_user_can(\'administrator\')) {
                                if ($wert) {echo $wert;} else {echo "Warum nicht?";}
                            } else if (!lze_character_active(get_the_ID()) | current_user_can(\'administrator\')) {
                                if (isset($wert)) {
                                    echo \'<textarea class="lze_textarea" name="lze_'.$slug.'"/>\'.$wert.\'</textarea><p class="description '.$slug.'">'.$text.'<p>\';} else {
                                    echo \'<textarea class="lze_textarea" name="lze_'.$slug.'"/></textarea><p class="description '.$slug.'">'.$text.'<p>\';
                                    } 
                            }}';
                } else {
                    $new_content    = 
                        'function lze_'.$slug.'() {
                            $info = "oneline";
                            global $post;
                            $'.$slug.' = "";
                            if (get_post_meta( $post->ID, \'lze_'.$slug.'\', true )) {
                                $'.$slug.'  = get_post_meta( $post->ID, \'lze_'.$slug.'\', true );
                            }
                            if (lze_character_active(get_the_ID()) && !current_user_can(\'administrator\')) {
                                if ($'.$slug.') {echo $'.$slug.';} else {echo "Warum nicht?";}
                            } else if (!lze_character_active(get_the_ID()) | current_user_can(\'administrator\')) {
                                if (isset($'.$slug.')) { 
                                    echo \'<input class="lze_textfeld" name="lze_'.$slug.'" value="\' . $'.$slug .' . \'"/><p class="description '.$slug.'">'.$text.'<p>\';} else {
                                    echo \'<input class="lze_textfeld" name="lze_'.$slug.'" value=""/><p class="description '.$slug.'">'.$text.'<p>\';}
                                }}';
                }
            array_push($lze_txt, $new_content);
            }

        }

        //string for functionsdocument
        $lze_txt = implode($lze_txt);
        return $lze_txt;
    }

    //collects the saving-functions for the different custom fields.
    function lze_get_field_saving() {
        global $wpdb;
        //contents per default information for sex, avatar and signature
        $lze_txt = array('function lze_save_character() {global $post; if (isset($_POST["lze_character_sex"])) {update_post_meta($post->ID, "lze_character_sex", $_POST["lze_character_sex"]);} if (isset($_POST["lze_character_ava"])) {update_post_meta($post->ID, "lze_character_ava", $_POST["lze_character_ava"]);} if (isset($_POST["lze_character_sig"])) {update_post_meta($post->ID, "lze_character_sig", $_POST["lze_character_sig"]);}');
        $lze_data   = $wpdb->get_results("SELECT meta_value, post_id FROM wp_postmeta WHERE meta_value = 'steckbrieffeld'");
        foreach ($lze_data as $single_data) {
            $moredata = $wpdb->get_results("SELECT post_name FROM wp_posts WHERE ID = '".$single_data->post_id."'");
            $value = "";
            foreach($moredata as $onedata) {
                $value = $onedata->post_name;
            }   
            //$value = get_the_title($single_data->post_id);
            $value = str_replace("-", "", $value);
            $newcontent = 'if (isset($_POST["lze_'.$value.'"])) {update_post_meta($post->ID, "lze_'.$value.'", $_POST["lze_'.$value.'"]);}';
            array_push($lze_txt, $newcontent);
        } 
        //adds code ending to content
        $end = "}";
        array_push($lze_txt, $end);  
        $lze_txt = implode($lze_txt);
        return $lze_txt;
    }

    add_action("admin_init", "lze_new_character_meta_boxes");
    add_action('save_post', 'lze_save_character');
}


//including file with information for posttype-fields.
//file includes customfield-information for character, needed in frontend
function lze_load_field_file() {
    include ('rpg_field_file.php');        
}
add_action("init", "lze_load_field_file");
?>