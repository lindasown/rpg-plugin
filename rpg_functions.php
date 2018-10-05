<?php
//get post-id by meta-information.
function lze_get_post_id($value) {
    global $wpdb;
    //$value = implode($value);
    $lze_data   = $wpdb->get_results("SELECT post_id FROM wp_postmeta WHERE meta_value = '".$value."'");
    $lze_array  = array();
    foreach ($lze_data as $single_data) {
        array_push($lze_array, $single_data->post_id);
    }	
    return $lze_array;
}

//get a list of all available characters.
function lze_get_characters() {
    global $wpdb;
    $lze_data   = $wpdb->get_results("SELECT post_date, post_title, post_name, ID FROM wp_posts WHERE post_type = 'lze_character'");
    $lze_array  = array();
    $counter    = 0;
    foreach ($lze_data as $single_data) {
        if ($single_data->post_title != 'Automatisch gespeicherter Entwurf'){
            $lze_array[$counter] = array();
            $lze_array[$counter]['id'] = $single_data->ID;
            $lze_array[$counter]['name'] = $single_data->post_title;  
            $lze_array[$counter]['date'] = $single_data->post_date; 
            $lze_array[$counter]['slug'] = $single_data->post_name;
            $counter++;
        }
    }	
    return $lze_array;
}

//get a list of all characters of selected user
function lze_get_characters_by_user_id($userid) {
    global $wpdb;
    $lze_data   = $wpdb->get_results("SELECT post_title, post_name, ID FROM wp_posts WHERE post_type = 'lze_character' AND post_author='".$userid."'");
    $lze_array  = array();
    $counter = 0;
    foreach ($lze_data as $single_data) {
        if ($single_data->post_title != 'Automatisch gespeicherter Entwurf'){
            $lze_array[$counter] = array();
            $lze_array[$counter]['title'] = $single_data->post_title;
            $lze_array[$counter]['id'] = $single_data->ID;
            $lze_array[$counter]['slug'] = $single_data->post_name;
            $counter++;
        }
    }	
    return $lze_array;
}

//get character by ID
function lze_get_character_by_id($id) {
    global $wpdb;
    $lze_data   = $wpdb->get_results("SELECT post_date, post_title, ID FROM wp_posts WHERE post_type = 'lze_character' AND ID = '".$id."'");
    $lze_array  = array();
    $counter    = 0;
    foreach ($lze_data as $single_data) {
        if ($single_data->post_title != 'Automatisch gespeicherter Entwurf'){
            $lze_array[$counter] = array();
            $lze_array[$counter]['id'] = $single_data->ID;
            $lze_array[$counter]['name'] = $single_data->post_title;  
            $lze_array[$counter]['date'] = $single_data->post_date;  
            $counter++;
        }
    }
    return $lze_array;
}

//is character active?
function lze_character_active($id) {
    $result             = false;
    if (has_term('Charakter angenommen', 'Status', $id)) {
        $result = true;
    } 
    return $result;
}

//gets information of wanted seeker --> important cause of the id
function seeker_information($val, $id) {
    global $wpdb;
    $lze_data   = $wpdb->get_results("SELECT meta_value FROM wp_postmeta WHERE post_id = '".$id."' AND meta_key = '".$val."'");
    $text = "";
    foreach($lze_data as $single_data) {
        $val    = explode(';', $single_data->meta_value);
        $id     = $val[1];
        $beschreibung = $wpdb->get_results("SELECT meta_value FROM wp_postmeta WHERE post_id = '".$id."' AND meta_key = 'Kurzbeschreibung'");
            foreach ($beschreibung as $data) {
                $desc = $data->meta_value;
            }
        $text = '<p>'.$val[0].'</p><p>'.$desc.'</p>';
    }
    return $text;
}

//removes ID-Numbers
function rpg_cut_id($string) {
    $stuff      = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ', ');
    $return     = str_replace($stuff, '', $string);
    return $return;
}

//get information about 'steckbrieffelder'
function rpg_get_fieldlist() {
    global $wpdb;
    $lze_data   = $wpdb->get_results("SELECT meta_value, post_id FROM wp_postmeta WHERE meta_value = 'steckbrieffeld'");
    $sorted_data    = array();
    $counter        = 0;
    foreach ($lze_data as $single_data) {
        $lze_posts  = $wpdb->get_results("SELECT ID, post_date, post_title, post_content FROM wp_posts WHERE ID = '".$single_data->post_id."'");
        foreach($lze_posts as $lze_post) {
            $sorted_data[$counter] = array();
            $sorted_data[$counter]['title'] = $lze_post->post_title;
            $sorted_data[$counter]['date'] = $lze_post->post_date;
            $sorted_data[$counter]['content'] = $lze_post->post_content;
            $sorted_data[$counter]['id'] = $lze_post->ID;
            $counter++;
        }
    }
    usort($sorted_data, "cmp");
    //var_dump($sorted_data);
    return $sorted_data;
}

//gets active scenes of specific character
function rpg_get_character_scenes($charid) {
        $characters         = lze_get_character_by_id($charid);
        $char_array         = array();
        foreach ($characters as $chara) {
            $data = $chara['name'].', '.$chara['id'];
            array_push($char_array, $data);
        }
        get_scenes($char_array);
}

//displays scene information of specific character or list of characters. 
function get_scenes($char_array) {
    global $wpdb;
    $ingame_topics      = $wpdb->get_results("SELECT post_id FROM wp_postmeta WHERE meta_value = 'ingame'");
    $ingame_topics_ids  = '';
    $ingame_scenes      = array();
    $ingame_sort        = array();
    $counter            = 0;
    $player              = array();
    $user_id            = get_current_user_id();
    foreach($ingame_topics as $single_scene) {
        $archive = get_post_meta($single_scene->post_id, 'rpg_archive_meta', true);
        $datas   = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_type ='topic' AND post_parent = '".$single_scene->post_id."'");
        if ($archive != 'archive'){
            foreach($datas as $data) {
                $creator = get_post_meta($data->ID, 'rpg_bbp_own_character', true);
                $playerstring = get_post_meta($data->ID, 'rpg_bbp_characters', true);
                if (strpos($playerstring, ';')) {
                    $player = explode('; ', $playerstring);
                } else {
                    $player = array($playerstring);
                }
                $all_characters = array();
                array_push($all_characters, $creator);
                //list of all involved characters, not depending on if its creator or player
                $all_characters = array_merge($player, $all_characters);
                foreach($all_characters as $single_data) {
                    if (in_array($single_data, $char_array)) {
                        if(!in_array($data->ID, $ingame_scenes)) {
                            //puts ids in array for checking doublettes
                            array_push($ingame_scenes, $data->ID);
                            $ingame_sort[$counter]                  = array();
                            $last_author_id                         = bbp_get_forum_last_reply_author_id($data->ID); 
                            $player                                 = implode('; ', $player);
                            $ingame_sort[$counter]['id']            = $data->ID;
                            $ingame_sort[$counter]['title']         = get_the_title($data->ID);
                            $ingame_sort[$counter]['authorid']      = $last_author_id;
                            $ingame_sort[$counter]['authorname']    = get_the_author_meta( 'nickname', $last_author_id );
                            $ingame_sort[$counter]['url']           = bbp_get_topic_last_reply_url( $data->ID );
                            $ingame_sort[$counter]['count']         = bbp_get_topic_post_count( $data->ID );
                            $reply_id                               = bbp_get_topic_last_reply_id( $data->ID );
                            $location                               = get_post_meta($data->ID, 'rpg_bbp_location', true);
                            if ($location) {
                                $ingame_sort[$counter]['location']      = get_post_meta($data->ID, 'rpg_bbp_location', true);
                                $ingame_sort[$counter]['time']          = get_post_meta($data->ID, 'rpg_bbp_time', true);
                            }
                            $ingame_sort[$counter]['date']          = date('d.m.Y \u\m G:i', strtotime(get_post_field( 'post_date', $reply_id ) )); 
                            
                            if ( get_post_meta($reply_id, 'rpg_bbp_own_character_p', true) ) { 
                                $ingame_sort[$counter]['lp_character']  = rpg_cut_id(get_post_meta($reply_id, 'rpg_bbp_own_character_p', true));
                                $ingame_sort[$counter]['sce_date']      = date('d.m.Y', strtotime(get_post_meta($data->ID, 'rpg_bbp_date', true))); 

                            } else { 
                                $ingame_sort[$counter]['lp_character']  = rpg_cut_id(get_post_meta($data->ID, 'rpg_bbp_own_character', true));
                                $ingame_sort[$counter]['sce_date']      = date('d.m.Y', strtotime(get_post_meta($data->ID, 'rpg_bbp_date', true))); 
                                
                            } 
                            $ingame_sort[$counter]['creator']       = rpg_cut_id($creator);
                            $ingame_sort[$counter]['player']        = rpg_cut_id($player);
                            $counter++;
                        }
                    }
                }        
            }
        }
    }
    usort ($ingame_sort, 'cmp');
    $ingame_sort = array_reverse($ingame_sort);
    foreach($ingame_sort as $sorted) {
        $title = $sorted['title'];
        if (!bbppu_user_has_read_topic( $sorted['id'] )) {
            echo '<li class="rpg_unread"><div class="rpg_titelspalte"><a href="';
        } else {
            echo '<li class="rpg_read"><div class="rpg_titelspalte"><a href="';
        }
        if (!$sorted['location']) {
            echo $sorted['url'].'">'.$sorted['title'].'</a><p>'.$sorted['sce_date'].' | '.$sorted['creator'].'; '.$sorted['player'].'</a></div>';
        } else {
            echo $sorted['url'].'">'.$sorted['title'].'</a><p>'.$sorted['sce_date'].', '.$sorted['time'].', '.$sorted['location'].' | '.$sorted['creator'].'; '.$sorted['player'].'</a></div>';
        }
        if(strlen($sorted['title']) > 30) {
            $title = wordwrap($title, 30);
            $title = substr($title, 0, strpos($title,"\n")).'...';
        } 
        echo '<a href="'.$sorted['url'].'">';
        echo '<div class = "rpg_countspalte">'.$sorted['count'].'</div>';
        echo '<div class= "rpg_freshnessspalte">'.$title.'<br>'.$sorted['lp_character'].'<br>';
        echo $sorted['date'];
        echo '</div></a></li>';
    }
    if (!$ingame_sort) {echo '<li>Du hast leider keine aktiven Szenen.</li>';}
    echo '</ul>';
}

//user register date

function get_user_register_date( $user_id ) {
    $user = get_userdata( $user_id );
    $time = $user->user_registered;
    $content = date('d.m.Y \u\m G:i', strtotime($time));
    return $content;
}

//displays character-history. All scenes are ordered by inplay-date.
function rpg_get_character_history($charid) {
    global $wpdb;
    $counter = 0;
    $characters         = lze_get_character_by_id($charid);
    $char_array         = array();
    foreach ($characters as $chara) {
        $data = $chara['name'].', '.$chara['id'];
        array_push($char_array, $data);
    }
    $ingame_topics      = $wpdb->get_results("SELECT post_id FROM wp_postmeta WHERE meta_value = 'ingame'");
    $ingame_topics_ids  = '';
    $ingame_scenes      = array();
    $date_comp_1        = 0;
    $date_comp_2        = 0;
    $year               = "";
    foreach($ingame_topics as $single_scene) {
        $datas   = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_type ='topic' AND post_parent = '".$single_scene->post_id."'");
        foreach($datas as $data) {
            $creator = get_post_meta($data->ID, 'rpg_bbp_own_character', true);
            $player = get_post_meta($data->ID, 'rpg_bbp_characters', true);
            $date = get_post_meta($data->ID, 'rpg_bbp_date', true);
            $player = explode('; ', $player);
            $all_characters = array();
            array_push($all_characters, $creator);
            //Liste aller beteiligter Charaktere, ob Ersteller oder nur Mitspieler ist irrelevant.
            $all_characters = array_merge($player, $all_characters);
            foreach($all_characters as $single_data) {
                if (in_array($single_data, $char_array)) {
                    if(!in_array($data->ID, $ingame_scenes)) {
                        //Um Verdopplungen zu vermeiden, werden die IDs in ein Array gepackt. 
                        $creator    = rpg_cut_id($creator);
                        //Für die Ausgabe in der Liste wieder zusammengeführt.
                        $player     = implode('; ', $player);
                        $player     = rpg_cut_id($player);
                        $regdate        = '/\d?\d.\d?\d.\d\d/';
                        $match          = array();
                        preg_match($regdate, $date, $match);
                                           
                        $datechecker = explode('/', $date);
                        $datecounter = 0;
                        $datearray = array();
                        foreach($datechecker as $single_date) {
                            if ($datecounter == 0 | $datecounter == 1) {
                                array_push($datearray, $single_date);
                            }
                            $datecounter++;
                        }
                        $datecheck = implode('/', $datearray);
                        
                        $ingame_scenes[$counter]                    = array();
                        $ingame_scenes[$counter]['id']              = $data->ID;
                        $ingame_scenes[$counter]['date']            = strtotime($date);
                        $ingame_scenes[$counter]['datecheck']       = $datecheck;
                        $ingame_scenes[$counter]['creator']         = $creator;
                        $ingame_scenes[$counter]['player']          = $player;
                        $ingame_scenes[$counter]['title']           = get_the_title($data->ID);
                        $ingame_scenes[$counter]['lastauthorid']    = bbp_get_forum_last_reply_author_id($data->ID);
                        $ingame_scenes[$counter]['lastauthorname']  = get_the_author_meta( 'nickname', $ingame_scenes[$counter]['lastauthorid'] );
                        $counter++;
                        }        
                    }
                }
            }        
        }
    //sorts scenes by inplay date
    usort ($ingame_scenes, 'cmp');
    $ingame_scenes = array_reverse($ingame_scenes);
    echo '<ul>';
    $counter = 0;
    foreach ($ingame_scenes as $scene) {
        $date_comp_1 = $ingame_scenes[$counter]['datecheck'];
        if ($date_comp_1 != $date_comp_2) {
            //var_dump($date_comp_1);
            if (strpos($date_comp_1, '/')) {
                $timedata = explode('/', $date_comp_1);
                $year = $timedata[0];
            } else {
                $timedata = explode('.', $date_comp_1);
                $year = $timedata[2];
            }
            $month_title = "";
            $timedata[1] = strval($timedata[1]);
            switch($timedata[1]) {
                case '01':
                case '1':
                    $month_title = 'Januar';
                    break;
                case '02':
                case '2':
                    $month_title = 'Februar';
                    break;
                case '03':
                case '3':
                    $month_title = 'März';
                    break;
                case '04':
                case '4':
                    $month_title = 'April';
                    break;
                case '05':
                case '5':
                    $month_title = 'Mai';
                    break;
                case '06': 
                case '6':
                    $month_title = 'Juni';
                    break;
                case '07':
                case '7':
                    $month_title = 'Juli';
                    break;
                case '08':
                case '8':
                    $month_title = 'August';
                    break;
                case '09':
                case '9':
                    $month_title = 'September';
                    break;
                case '10':
                    $month_title = 'Oktober';
                    break;
                case '11':
                    $month_title = 'November';
                    break;
                case '12':
                    $month_title = 'Dezember';
                    break;
            }
            echo '<h3>'.$month_title.' '.$year.'</h3>';  
        }
        $date_comp_2 = $date_comp_1;
        $date = date("j. n. Y", $ingame_scenes[$counter]['date']);
        echo '<li><a href="';
        bbp_topic_last_reply_url($ingame_scenes[$counter]['id']);
        echo '">'.$ingame_scenes[$counter]['title'].'</a><br>Charaktere: '.$ingame_scenes[$counter]['creator'].'; '.$ingame_scenes[$counter]['player'].'<br>Inplay-Datum: '.$date.'</li><br>';
        $counter++;
    }
    echo '</ul>'; 
}

//displays a list of active character scenes
function rpg_get_actual_scenes($charid) {
    global $wpdb;
    $counter = 0;
    $characters         = lze_get_character_by_id($charid);
    //besteht aus den Informationen aller beteiligter Charaktere
    $char_array         = array();
    foreach ($characters as $chara) {
        $data = $chara['name'].', '.$chara['id'];
        array_push($char_array, $data);
    }
    //all ingame-forums
    $ingame_topics      = $wpdb->get_results("SELECT post_id FROM wp_postmeta WHERE meta_value = 'ingame'");
    $ingame_topics_ids  = '';
    $ingame_scenes      = array();
    $date_comp_1        = 0;
    $date_comp_2        = 0;

    foreach($ingame_topics as $single_scene) {
            //all topics sorted in a ingame-forum
            $datas   = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_type ='topic' AND post_parent = '".$single_scene->post_id."'");
            foreach($datas as $data) {
                $creator = get_post_meta($data->ID, 'rpg_bbp_own_character', true);
                $player = get_post_meta($data->ID, 'rpg_bbp_characters', true);
                $date = get_post_meta($data->ID, 'rpg_bbp_date', true);
                $player = explode('; ', $player);
                $all_characters = array();
                array_push($all_characters, $creator);
                //list of all involved characters, not depending on if its creator or player
                $all_characters = array_merge($player, $all_characters);
                foreach($all_characters as $single_data) {
                    if (in_array($single_data, $char_array)) {
                        if(!in_array($data->ID, $ingame_scenes)) {
                            $rpg_parent_id      = wp_get_post_parent_id( $data->ID );
                            $rpg_type           = get_post_meta($data->ID, 'rpg_archive_meta', true);
                            $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_archive_meta', true);
                            if (!$rpg_parent_type == 'archive') {
                                //put ids in array for avoiding doublette
                                $creator    = rpg_cut_id($creator);
                                //implode for displaying in list
                                $player     = implode('; ', $player);
                                $player     = rpg_cut_id($player);
                                //get dateinformation for automatic titles
                                $regdate        = '/\d?\d.\d?\d.\d\d/';
                                $match          = array();
                                preg_match($regdate, $date, $match);
                                $datechecker = explode('/', $date);
                                $datecounter = 0;
                                $datearray = array();
                                foreach($datechecker as $single_date) {
                                    if ($datecounter == 0 | $datecounter == 1) {
                                        array_push($datearray, $single_date);
                                    }
                                    $datecounter++;
                                }
                                $datecheck = implode('/', $datearray);

                                $ingame_scenes[$counter]                    = array();
                                $ingame_scenes[$counter]['id']              = $data->ID;
                                $ingame_scenes[$counter]['date']            = strtotime($date);
                                $ingame_scenes[$counter]['datecheck']       = $datecheck;
                                $ingame_scenes[$counter]['creator']         = $creator;
                                $ingame_scenes[$counter]['player']          = $player;
                                $ingame_scenes[$counter]['title']           = get_the_title($data->ID);
                                $ingame_scenes[$counter]['lastauthorid']    = bbp_get_forum_last_reply_author_id($data->ID);
                                $ingame_scenes[$counter]['lastauthorname']  = get_the_author_meta( 'nickname', $ingame_scenes[$counter]['lastauthorid'] );
                                $counter++;
                            }   
                        }
                    }
                    
                }
            }        
        }
    //sorts scenes by inplay date
    usort ($ingame_scenes, 'cmp');
    $ingame_scenes = array_reverse($ingame_scenes);
    echo '<ul>';
    $counter = 0;
    foreach ($ingame_scenes as $scene) {
        $date_comp_1 = $ingame_scenes[$counter]['datecheck'];
        if ($date_comp_1 != $date_comp_2) {
            if (strpos($date_comp_1, '/')) {
                $timedata = explode('/', $date_comp_1);
                $year = $timedata[0];
            } else {
                $timedata = explode('.', $date_comp_1);
                $year = $timedata[2];
            }
            $month_title = "";
            $timedata[1] = strval($timedata[1]);
            switch($timedata[1]) {
                case '01':
                case '1':
                    $month_title = 'Januar';
                    break;
                case '02':
                case '2':
                    $month_title = 'Februar';
                    break;
                case '03':
                case '3':
                    $month_title = 'März';
                    break;
                case '04':
                case '4':
                    $month_title = 'April';
                    break;
                case '05':
                case '5':
                    $month_title = 'Mai';
                    break;
                case '06': 
                case '6':
                    $month_title = 'Juni';
                    break;
                case '07':
                case '7':
                    $month_title = 'Juli';
                    break;
                case '08':
                case '8':
                    $month_title = 'August';
                    break;
                case '09':
                case '9':
                    $month_title = 'September';
                    break;
                case '10':
                    $month_title = 'Oktober';
                    break;
                case '11':
                    $month_title = 'November';
                    break;
                case '12':
                    $month_title = 'Dezember';
                    break;
            }
            echo '<h3>'.$month_title.' '.$year.'</h3>';              
        }
        $date_comp_2 = $date_comp_1;
        $date = date("j. n. Y", $ingame_scenes[$counter]['date']);
        echo '<li><a href="';
        bbp_topic_last_reply_url($ingame_scenes[$counter]['id']);
        echo '">'.$ingame_scenes[$counter]['title'].'</a><br>Charaktere: '.$ingame_scenes[$counter]['creator'].'; '.$ingame_scenes[$counter]['player'].'<br>Inplay-Datum: '.$date.'</li><br>';
        $counter++;
    }
    echo '</ul>'; 
};

//gets a list of all wanted single characters
function lze_get_s_wanted() {
    global $wpdb;
    $lze_data   = $wpdb->get_results("SELECT post_title, post_name, ID FROM wp_posts WHERE post_type = 'lze_s_wanted'");
    $lze_array  = array();
    $counter    = 0;
    foreach ($lze_data as $single_data) {
        if ($single_data->post_title != 'Automatisch gespeicherter Entwurf'){
            $lze_array[$counter] = array();
            $lze_array[$counter]['id'] = $single_data->ID;
            $lze_array[$counter]['name'] = $single_data->post_title;  
            $lze_array[$counter]['slug'] = $single_data->post_name;
            $counter++;
        }
    }	
    return $lze_array;
}

//gets information about seeking character (wanted)
//if $html = 0 text is for wanted-copycode
function lze_get_seeker($val, $html=1, $prefix, $type) {
    global $wpdb;
    $members = get_post_meta($val, $type, true);
    $counter = 0;
    $characterdata = array();
    $picsallowed = 0;
    $class = '"rpg_wanted_members"';
    if ($prefix === 'grse' OR $prefix === 'sise') {
        $class = '"rpg_wanted_seeker"';
    }
    if (get_option('rpg_option_pictures') == 'pic-no' OR (is_user_logged_in())) {
        $picsallowed = 1;
    }
    if ($members) {
        if ($html == 1) {
            foreach ($members as $data) {
                $characterdata = lze_get_character_by_id($data[$prefix . '-name']);
                if ($data[$prefix . '-name']){
                    echo '<div class=' . $class . '>';
                    echo '<h3>'.$characterdata[0]['name'].'</h3>';
                    if ($picsallowed) {
                        echo '<img src="'. lze_get_pic($characterdata[0]['id'], 'lze_character_ava') . '">';
                    }
                    else {
                        echo '<img src="'.get_option('rpg_options_dummypic').'">';
                    }
                    echo ''.$data[$prefix . '-desc'].'';
                    echo '</div>';
                }
                $counter++;
            }
        } else {
            foreach ($members as $data) {
                $characterdata = lze_get_character_by_id($data[$prefix . '-name']);
                if ($data[$prefix . '-name']){
                    echo '&lt;div class='. $class .'&gt;';
                    echo '&lt;h3&gt;'.$characterdata[0]['name'].'&lt;/h3&gt;';
                    echo '&lt;img src="'. lze_get_pic($characterdata[0]['id'], 'lze_character_ava') .'"&gt;';
                    echo ''.$data[$prefix . '-desc'].'';
                    echo '&lt;/div&gt;';
                }
                $counter++;
            }
        }
    }


}

//gets members of a group-wanted 
function rpg_wanted_members($val, $html = 1) {
    global $wpdb;
    $members = get_post_meta($val, 'groupwanted_members', true);
    $counter = 0;
    $picsallowed = 0;
    if (get_option('rpg_option_pictures') == 'pic-no' OR (is_user_logged_in())) {
        $picsallowed = 1;
    }
    if ($members) {
        if ($html == 1) {
            echo '<h2>Mitglieder</h2>';
            foreach ($members as $data) {
                if ($data['name']){
                    echo '<div class="rpg_wanted_members">';
                    echo '<h3>'.$data['name'].'</h3>';
                    if ($picsallowed) {
                        echo '<img src="'.$data['ava'].'">';
                    } 
                    else {
                        echo '<img src="'.get_option('rpg_options_dummypic').'">';
                    }
                    echo ''.$data['desc'].'';
                    echo '</div>';
                }
                $counter++;
            }
        } else {
            echo '&lt;h2&gt;Mitglieder&lt;/h2&gt;';
            foreach ($members as $data) {
                if ($data['name']){
                    echo '&lt;div class="rpg_wanted_members"&gt;';
                    echo '&lt;h3&gt;'.$data['name'].'&lt;/h3&gt;';
                    echo '&lt;img src="'.$data['ava'].'"&gt;';
                    echo ''.$data['desc'].'';
                    echo '&lt;/div&gt;';
                }
                $counter++;
            }
        }
    }  
}

//gets data for charactersheet
function charactersheet_information($val, $id) {
    global $wpdb;
    $lze_data   = $wpdb->get_results("SELECT meta_value FROM wp_postmeta WHERE post_id = '".$id."' AND meta_key = '".$val."'");
    $text = "";
    foreach($lze_data as $single_data) {
        $text = $single_data->meta_value;
    }
    return $text;
}

//get a list of all steckbrief fields
function lze_field_list() {
    global $wpdb;
    $lze_data   = $wpdb->get_results("SELECT post_id, meta_value FROM wp_postmeta WHERE meta_value = 'steckbrieffeld'");
    $sorted_data    = array();
    $counter        = 0;
    foreach ($lze_data as $single_data) {
        $lze_posts  = $wpdb->get_results("SELECT ID, post_date, post_title, post_name, post_content FROM wp_posts WHERE ID = '".$single_data->post_id."'");
        foreach($lze_posts as $lze_post) {
            $sorted_data[$counter] = array();
            $sorted_data[$counter]['title'] = $lze_post->post_title;
            $sorted_data[$counter]['date'] = $lze_post->post_date;
            $sorted_data[$counter]['content'] = $lze_post->post_content;
            $sorted_data[$counter]['id'] = $lze_post->ID;
            $sorted_data[$counter]['fieldname'] = 'lze_'.str_replace("-", "", $lze_post->post_name);
            $counter++;
        }
    }
    return $sorted_data;
}

//get a character sheet for template
function lze_get_chara_sheet() {
    $list = lze_field_list();
    $id = get_the_ID();
    foreach($list as $single_data) {
        $title = $single_data['title'];
        $fieldname = $single_data['fieldname'];
        echo '<h2>'.$title.'</h2>';
        echo '<p>'.charactersheet_information($fieldname, $id).'</p>';
    }
}

//get picture, depending on options and imagetype
function lze_get_pic($id, $key, $ava = 1) {
    $image = 0;
    if (get_option('rpg_option_pictures') == 'pic-no' OR (is_user_logged_in())) {
            global $wpdb;
            $lze_data2      = $wpdb->get_results("SELECT meta_value FROM wp_postmeta WHERE post_id = '".$id."' AND meta_key='".$key."'");
            $ava_ids        = array();
            foreach ($lze_data2 as $single_data) {
                array_push($ava_ids, $single_data->meta_value);
            }
            $image          = implode($ava_ids);
        }
    if (!$image) {
        if ($ava == 1){
            $image = get_option('rpg_options_dummypic');
        } else if ($ava == 0) {
            $image = "";
        }
    } 
    return $image;
}

//checks if user has an active character
function lze_user_can_play() {
    $userid = get_current_user_id();
    $handler = 0;
    if (is_user_logged_in()) {
        $charalist = lze_get_characters_by_user_id($userid);
        foreach($charalist as $single_data) {
            if (lze_character_active($single_data['id'])) {
                $handler = 1;
            }
        }
    }
    return $handler;
}

//makes nice post date 
function lze_post_date($reply_id) {
    //$reply_id = bbp_get_forum_last_reply_id();
    $last_active = get_post_field( 'post_date', $reply_id );
    $datum = date('d.m.Y \u\m G:i', strtotime($last_active));
    echo $datum;
}

//gets last date of topic in nice form
function lze_post_date_topic($reply_id=0) {
    if (!$reply_id) {
        $reply_id = bbp_get_topic_last_reply_id();
    }
    $last_active = get_post_field( 'post_date', $reply_id );
    $datum = date('d.m.Y \u\m G:i', strtotime($last_active));
    echo $datum;
}

//checks if forum is inplay
function lze_board_is_inplay($id) {
    $return = 0;
    $rpg_parent_id      = wp_get_post_parent_id( $id );
    $rpg_type           = get_post_meta($id, 'rpg_ingame_meta', true);
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    
    if ($rpg_type == 'ingame' | $rpg_parent_type == 'ingame') {
        $return = 1;
    }
    return $return;
}

//checks if forum is charastuff
function lze_board_is_charastuff($id) {
    $return = 0;
    $rpg_parent_id      = wp_get_post_parent_id( $id );
    $rpg_type           = get_post_meta($id, 'rpg_charastuff_meta', true);
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_charastuff_meta', true);
    
    if ($rpg_type == 'charastuff' | $rpg_parent_type == 'charastuff') {
        $return = 1;
    }
    return $return;
}

//checks if forum is restricted
function lze_board_is_restricted($id) {
    $return = 0;
    $rpg_parent_id      = wp_get_post_parent_id( $id );
    $rpg_type           = get_post_meta($id, 'rpg_noaccess_meta', true);
    $rpg_parent_type    = get_post_meta($rpg_parent_id, 'rpg_noaccess_meta', true);
    
    if ($rpg_type == 'restricted' | $rpg_parent_type == 'restricted') {
        $return = 1;
    }
    return $return;
}

//gets last-post-date of encapsulated boards
function lze_get_last_post() {
    $actualid       = get_the_ID();
    $encaps         = array($actualid);
    $lastposts      = array();
    $lastposts_data = array();
    $counter        = 0;
    //sammelt IDs der Child-Boards
    if(bbp_forum_get_subforums($actualid)) {
        $children = bbp_forum_get_subforums($actualid);
        foreach ($children as $child) {
            array_push($encaps, $child->ID);
        }     
    }
    //sammelt IDs der lastposts
    foreach($encaps as $caps) {
        $lastcap = bbp_get_forum_last_reply_id($caps);
        array_push($lastposts, $lastcap);
    }
    
    //sammelt Postdata der Lastposts (Zwischenschritt zur besseren Übersicht)
    foreach($lastposts as $post) {
        $data = get_post($post);
        //var_dump($data);
        if($post) {
            $lastposts_data[$counter] = array();
            $lastposts_data[$counter]['id'] = $post;
            $lastposts_data[$counter]['date'] = strtotime($data->post_date);
            $counter++;
        }
    }
    usort($lastposts_data, 'cmp');
    $sorted_posts = array_reverse($lastposts_data);
    //var_dump($sorted_posts);
    if (!empty($sorted_posts[0]['id'])) {
        return $sorted_posts[0]['id'];  
    }
}

//count all topics and postings 
function lze_count_postings() {
    global $wpdb;
    $counter    = 0;
    $add        = 0;
    $lze_data   = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_type = 'forum'");
    foreach($lze_data as $single_data) {
        $add = intval(bbp_get_forum_post_count($single_data->ID));
        $counter = $counter + $add;
    }
    return $counter;
}

//get user visited in last x hours
function lze_user_visited($hours = 24) {
    $diff           = $hours * 60 * 60;
    $stop           = time() - $diff;
    $users          = get_users();
    $count          = count($users);
    $counter        = 0;
    $nickname       = "";
    $corr           = 3600;
    if (date('I')) {
        $corr = 7200;
    }
    echo '<div class="rpg_last_activity"><p>Aktiv in den letzten '.$hours.' Stunden:</p><p>';
    foreach($users as $user) {
        $lasttime = bp_get_user_last_activity($user->ID);
        if (strtotime($lasttime) > $stop) {
            $displayname       = get_the_author_meta( 'nickname', $user->ID );
            $displaytime = date('d.m.Y \u\m G:i', strtotime($lasttime) + $corr);
            $nickname = get_the_author_meta('login', $user->ID);
            echo '<strong><a href="'.get_home_url().'/members/'.$nickname.'">'.$displayname.'</a></strong> ['.$displaytime.']';
            if ($counter <= $count) {
                echo ' | ';
            }
            $counter++;
        } 
    }
    echo '</p></div>';
    
}

//functions  just needed in backend
if (is_admin()) {
    

    //gets scenes where is no new postings for x days. Default 14.
    function lze_blacklist_charas($days = 14) {
        global $wpdb;
        $diff           = 14 * 24 * 60 * 60;
        $timestamp      = time() - $diff;
        $activeboards   = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_type = 'forum'"); 
        $boardlist      = array();
        $scenelist      = array();
        $counter        = 0;
        $active_charas = array();
        foreach($activeboards as $board) {
            $ingame     = get_post_meta($board->ID, 'rpg_ingame_meta', true);
            $archive    = get_post_meta($board->ID, 'rpg_archive_meta', true);
            $pastplay   = get_post_meta($board->ID, 'rpg_pastplay_meta', true);
            if ($ingame == 'ingame' && $archive != 'archive' && $pastplay != 'pastplay') {
                array_push($boardlist, $board->ID);
            }
        }
        
        foreach($boardlist as $listing) {
            $childlist  = $wpdb->get_results("SELECT ID, post_title FROM wp_posts WHERE post_type = 'topic'  AND post_status = 'publish' AND post_parent = '".$listing."'"); 
            foreach($childlist as $child) {
                $lastid = bbp_get_topic_last_reply_id($child->ID);
                $postdata = get_post($lastid);
                $lasttime = $postdata->post_date;
                $lastsec = strtotime($lasttime);
                $players = rpg_cut_id(get_post_meta($child->ID, 'rpg_bbp_characters', true));
                $playarray = array();
                $starter = rpg_cut_id(get_post_meta($child->ID, 'rpg_bbp_own_character', true));
                if (strpos($players, ';')) {
                    $playarray = explode('; ', $players);
                    foreach($playarray as $pa) {
                        if (!in_array($pa, $active_charas)) {
                            array_push($active_charas, $pa);
                        }
                    }
                } else {
                    array_push($active_charas, $players);
                }
                if (!in_array($starter, $active_charas)) {
                    array_push($active_charas, $starter);
                }
                if ($lastsec < $timestamp && $listing != '600') {
                    echo '<a href="'. bbp_get_topic_last_reply_url($child->ID).'" target="_blank">';
                    echo get_post($child->ID)->post_title;
                    echo '</a><br>Letzter Post: ';
                    echo date('d.m.Y \u\m G:i', $lastsec);
                    echo '<br>';
                    echo $starter;
                    echo '; ';
                    echo $players;
                    echo '<br><br>';
                } 
                
            }
        }
        $inactive = array();
        $allcharas = lze_get_characters(); 
        foreach ($allcharas as $char) {
            if (lze_character_active($char['id'])) {
                if (!in_array($char['name'], $active_charas)) {
                    //$singlechar = $char['name'].', '.$name['id'];
                    if (!has_term('nebencharacter', 'Gruppe', $char['id'])) {
                        array_push($inactive, $char['name']);
                    }

                }
            }
        }
        sort($inactive);
        echo '<br><b>Inaktive Charaktere ohne NCs</b><br><br>';
        foreach($inactive as $inac) {
            echo $inac.'<br>';
        }
    }
}

//chooses the right style for user
function lze_choose_style() {
    //Sunset
    //Freedom
    $lze_standart   = get_site_url()."/wp-content/themes/marvelous/style.css";
    $lze_style_1    = get_site_url()."/wp-content/themes/ultron/style.css";;
    $lze_style_2    = "";
    global $wpdb;
    $styleid       = $wpdb->get_results("SELECT value FROM wp_bp_xprofile_data WHERE user_id = '" . get_current_user_id() . "' AND field_id = '5'"); 
    $stylevalue = "";
    foreach($styleid as $single_data) {
        $stylevalue = $single_data->value;
    }
    if (is_user_logged_in()) {
        if ($stylevalue == 'Ultron') {
            echo '<link rel="stylesheet" href="'.$lze_style_1.'">';  
        } else {
            echo '<link rel="stylesheet" href="'.$lze_standart.'">';
        }
    } else {
        echo '<link rel="stylesheet" href="'.$lze_standart.'">';
    }
}

function lze_display_all_usernames() {
    $userlist =  get_users();
    $displaylist = [];
    foreach($userlist as $user) {
        $displayname = get_the_author_meta( 'nickname', $user->ID );
        echo $displayname . ', ';
    }
}



//LITTLE HELPERS
//Snippets

//sort, widget from github
function cmp($a, $b) {
    return strcmp($a["date"], $b["date"]);
}

//sorts arrays by title
function compareByTitle($a, $b) {
  return strcmp($a["title"], $b["title"]);
}

//sorts arrays by name (doubles title, need a update in next version)
function compareByName($a, $b) {
  return strcmp($a["name"], $b["name"]);
}

//allow html tags in bbpress posts
function ja_filter_bbpress_allowed_tags() {
	return array(
		// Links
		'a' => array(
			'href'     => array(),
			'title'    => array(),
			'rel'      => array(),
            'target'   => array(),
		),     
        'b'            => array(),
        'i'            => array(),
        'br'           => array(),
		// Quotes
		'blockquote'   => array(
			'cite'     => array()
		),
		// Code
		'code'         => array(),
		'pre'          => array(),
		// Formatting
		'em'           => array(),
		'strong'       => array(),
		'del'          => array(
			'datetime' => true,
		),
		// Lists
		'ul'           => array(),
		'ol'           => array(
			'start'    => true,
		),
		'li'           => array(),
		// Images
		'img'          => array(
			'src'      => true,
			'border'   => true,
			'alt'      => true,
			'height'   => true,
			'width'    => true,
		),        
        'div'          => array(
			'class'    => true,
			'id'       => true,
		),        
        /*'style'        => array(
            'text-transform' => true,
        ),*/
        'center'       => array(), 
        'table'        => array(
            'cellpadding' => true,
            'cellspacing' => true,
        ),
        'tr'           => array(),
        'td'           => array(
            'width'    => true,
            'style'    => true,
        )
	);
}
add_filter( 'bbp_kses_allowed_tags', 'ja_filter_bbpress_allowed_tags' );



//add tinymce editor to custom fields, widget from github
function admin_add_wysiwyg_custom_field_textarea() { ?>
<script type="text/javascript">/* <![CDATA[ */
    jQuery(function($){
        tinymce.init({selector:'textarea'});
        // var i=1;
        // $('textarea').each(function(e)
        // {
        //   var id = $(this).attr('id');
        //   if (!id)
        //   {
        //    id = 'customEditor-' + i++;
        //    $(this).attr('id',id);
        //   }
        //   tinyMCE.execCommand("mceAddEditor", false, id);
        //   tinyMCE.execCommand('mceAddControl', false, id);
        // });
    });
/* ]]> */</script>
<?php }
add_action( 'admin_print_footer_scripts', 'admin_add_wysiwyg_custom_field_textarea', 99 );

//STRUCTURAL AND COSMETIC FUNCTIONS
//puts Stuff in Header
// add the action 
function rpg_headinformation() { 
    //bindet das js ein
    echo '<script src="'.get_site_url().'/wp-content/plugins/rpg/jquery/jquery-ui.js"></script>';
    echo '<link rel="stylesheet" href="'.get_site_url().'/wp-content/plugins/rpg/css/rpg_styles.css">';
}
add_action( 'wp_head', 'rpg_headinformation', 10, 0 ); 

//puts Stuff in Footer
function rpg_footerinformation() { 
    //gets the js
    echo '<script src="'.get_site_url().'/wp-content/plugins/rpg/js/footerfunctions.js"></script>';
    $id = get_the_ID();
    
    if (lze_board_is_inplay($id)) {
        echo '<script>jQuery("body").addClass("ingame");</script>';
    }
    
    if (lze_board_is_charastuff($id)) {
        echo '<script>jQuery("body").addClass("charastuff");</script>';
    }
    
    if (lze_board_is_restricted($id)) {
        echo '<script>jQuery("body").addClass("no-newbies");</script>';
    }
}
add_action( 'wp_footer', 'rpg_footerinformation', 10, 0 ); 

function rpg_headinformation_be() { 
    //gets the plugin-css.
    echo '<link rel="stylesheet" href="'.get_site_url().'/wp-content/plugins/rpg/css/rpg_styles_be.css">';
    if (! current_user_can('administrator') ){
        echo "<script>";
        echo "jQuery('#Statusdiv').remove();";
        echo "jQuery('#Gruppediv').remove();";
        echo "</script>";
    }
}
add_action( 'admin_footer', 'rpg_headinformation_be', 10, 0 ); 

?>