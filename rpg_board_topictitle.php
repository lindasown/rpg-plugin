<?php
add_action ( 'bbp_theme_after_topic_title', 'rpg_scene_information');

//information in subtitles depending of forum type
function rpg_scene_information() {
    $post_id        = get_the_ID();
    $rpg_parent_id  = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_type       = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_partner    = get_post_meta($rpg_parent_id, 'rpg_partner_meta', true);
    if ($rpg_type == 'ingame') {
        $location       = get_post_meta($post_id, 'rpg_bbp_location', true);
        $date           = get_post_meta($post_id, 'rpg_bbp_date', true);
        $time           = get_post_meta($post_id, 'rpg_bbp_time', true);
        $characters     = get_post_meta($post_id, 'rpg_bbp_characters', true);
        $owncharacter   = get_post_meta($post_id, 'rpg_bbp_own_character', true);

        $date           = date("d.m.Y", strtotime($date));    
        $stuff          = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ', ');
        $characters     = str_replace($stuff, '', $characters);
        $owncharacter   = str_replace($stuff, '', $owncharacter);

        if ($location) {
            echo '<div class="rpg_scene_information">'.$date.', '.$time.' | '.$location.' | '.$owncharacter .'; '. $characters.'</div>';
        } else {
            echo '<div class="rpg_scene_information">'.$date.' | '.$owncharacter .'; '. $characters.'</div>';
        }
    } else if ($rpg_partner == 'partner') {
        $genre          = get_post_meta($post_id, 'rpg_bbp_partner_genre', true);
        $age            = get_post_meta($post_id, 'rpg_bbp_partner_age', true);
        $freetext       = get_post_meta($post_id, 'rpg_bbp_partner_freetext', true);
        if ($genre) {
            echo '<div class="rpg_scene_information">'.$genre.' | '.$age.' | '.$freetext.'</div>';
        }
    } else {
        $subtitle       = get_post_meta($post_id, 'rpg_bbp_offplay', true);
        if ($subtitle) {
            echo '<div class="rpg_scene_information">'.$subtitle.'</div>';
        }
    }
}

add_action( 'bbp_theme_before_forum_freshness_link', 'rpg_topic_title' ); 
add_action( 'bbp_theme_before_topic_freshness_link', 'rpg_scene_title_topic' ); 

//FORUM-freshness-information depending of forum type
function rpg_scene_title() {
    $post_id                = 0;
    if (lze_get_last_post()) {
        $post_id            = lze_get_last_post();
    }
    if ($post_id) {
        $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
        $rpg_type           = get_post_meta(get_the_ID(), 'rpg_ingame_meta', true);
        $rpg_charastuff     = '';
        $rpg_charastuff     = get_post_meta(get_the_ID(), 'rpg_charastuff_meta', true);
        $funnytitle         = get_post($post_id)->post_title;
        $title              = "";
        $is_topic           = 0;
        if (get_post($post_id)->post_type == 'topic') {
            $is_topic = 1;
        }
        if (isset($funnytitle) && !$is_topic) {
            $pid    = wp_get_post_parent_id( $post_id );
            $title = get_post($pid)->post_title;
        } else if ($is_topic) {
            //if topic has answer
            $title = str_replace("Antwort zu: ", '', $funnytitle);
        }
            if(strlen($title) > 30) {
                $title = wordwrap($title, 30);
                $title = substr($title, 0, strpos($title,"\n")).'...';
            } 
        //if inplay or charastuff, information about the character is displayed.
        if ($rpg_type == 'ingame' | $rpg_charastuff == 'charastuff') {
            $character      = get_post_meta($post_id, 'rpg_bbp_own_character_p', true);
            if (!$character) {
                $character = get_post_meta($post_id, 'rpg_bbp_own_character', true);
                $character = get_post_meta($post_id, 'rpg_bbp_own_character', true);
                if (!$character) {
                    global $wpdb;
                    $active = 0;
                    $names = $wpdb->get_results("SELECT starter, username FROM wp_posts WHERE ID = '".$post_id."'");
                    foreach($names as $name) {
                        if ($name->starter) {
                            $character = $name->starter;
                        } else {
                            $character = $name->username;
                        }
                    }
                }
            }
            $stuff          = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ', ');
            $character      = str_replace($stuff, '', $character);
            echo '<div class="rpg_ingame_topic_information">';
            if (!lze_user_can_play() | (!is_user_logged_in()) ) {
                echo '<span class="rpg_versteckt">unbekannt</span><br>';
                echo 'von '.$character.'<br>';
                //echo bbp_get_forum_last_active_time();
                lze_post_date($post_id);
                echo '</div>';
            } else {
                echo '<a class="rpg_forum_title" href="';
                echo bbp_get_reply_url($post_id);
                echo '">';
                echo $title.'</a><br>';
                echo 'von '.$character.'<br>';
                lze_post_date($post_id);
                //echo bbp_get_forum_last_active_time();
                //lze_get_last_post();
                echo '</div>';
            }
            //if outplay, information about the user is displayed.
        } else {
            $authorid = get_post($post_id)->post_author; 
            $userinformation = get_userdata($authorid);
            echo '<div class="rpg_ingame_topic_information">';
            echo '<a class="rpg_forum_title" href="';
            echo bbp_get_reply_url($post_id);
            echo '">';
            echo $title.'</a><br>';
            if ($authorid) {
                echo 'von '.get_the_author_meta( 'nickname', $authorid ).'<br>';
            } else {
                echo 'von einem Gast<br>';
            }
            lze_post_date($post_id);

            //echo bbp_get_forum_last_active_time();
            echo '</div>';

        }
    } else {
        echo '<span class="rpg_keine_themen">Keine Themen</span>';
    }
}

function rpg_topic_title() {
    $post_id                = 0;
    if (lze_get_last_post()) {
        $post_id            = lze_get_last_post();
    }
    if ($post_id) {
        $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
        $funnytitle         = get_post($post_id)->post_title;
        $title              = "";
        $is_topic           = 0;
        if (get_post($post_id)->post_type == 'topic') {
            $is_topic = 1;
        }
        if (isset($funnytitle) && !$is_topic) {
            $pid    = wp_get_post_parent_id( $post_id );
            $title = get_post($pid)->post_title;
        } else if ($is_topic) {
            //if topic has answer
            $title = str_replace("Antwort zu: ", '', $funnytitle);
        }
		if(strlen($title) > 30) {
			$title = wordwrap($title, 30);
			$title = substr($title, 0, strpos($title,"\n")).'...';
		} 
        //if inplay or charastuff, information about the character is displayed.

		if (lze_board_is_inplay(bbp_get_forum_id())) {

			$character      = get_post_meta($post_id, 'rpg_bbp_own_character_p', true);
            if (!$character) {
                $character = get_post_meta($post_id, 'rpg_bbp_own_character', true);
                $character = get_post_meta($post_id, 'rpg_bbp_own_character', true);
                if (!$character) {
                    global $wpdb;
                    $active = 0;
                    $names = $wpdb->get_results("SELECT starter, username FROM wp_posts WHERE ID = '".$post_id."'");
                    foreach($names as $name) {
                        if ($name->starter) {
                            $character = $name->starter;
                        } else {
                            $character = $name->username;
                        }
                    }
                }
            }
            $stuff          = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ', ');
            $character      = str_replace($stuff, '', $character);
            echo '<div class="rpg_ingame_topic_information">';

			if (lze_board_check_access(bbp_get_forum_id())) {
				echo '<a class="rpg_forum_title" href="';
                echo bbp_get_reply_url($post_id);
                echo '">';
                echo $title.'</a><br>';
                echo 'von '.$character.'<br>';
                lze_post_date($post_id);
                echo '</div>';
				
			} else {
				echo '<span class="rpg_versteckt">unbekannt</span><br>';
                echo 'von '.$character.'<br>';
                lze_post_date($post_id);
                echo '</div>';

			}

		} else {
			$authorid = get_post($post_id)->post_author; 
            $userinformation = get_userdata($authorid);
            echo '<div class="rpg_ingame_topic_information">';

			if (lze_board_check_access(bbp_get_forum_id())) {
				echo '<a class="rpg_forum_title" href="';
				echo bbp_get_reply_url($post_id);
				echo '">';
				echo $title.'</a><br>';
				

			} else {
				echo '<span class="rpg_versteckt">unbekannt</span><br>';

			}

			if ($authorid) {
                echo 'von '.get_the_author_meta( 'nickname', $authorid ).'<br>';
            } else {
                echo 'von einem Gast<br>';
            }
            lze_post_date($post_id);

            echo '</div>';

		}
    } else {
        echo '<span class="rpg_keine_themen">Keine Themen</span>';
    }
}



//TOPIC-freshness-information depending of forum type
function rpg_scene_title_topic() {
    $rpg_parent_id      = wp_get_post_parent_id( bbp_get_topic_id() );
    $rpg_type           = '';
    $rpg_type           = get_post_meta($rpg_parent_id, 'rpg_ingame_meta', true);
    $rpg_charastuff     = '';
    $rpg_charastuff     = get_post_meta($rpg_parent_id, 'rpg_charastuff_meta', true);
    $funnytitle         = bbp_get_topic_last_reply_title();
    $title              = "";
    if (isset($funnytitle)) {
        //if topic has answer
        $title = str_replace("Antwort zu: ", '', $funnytitle);
    } else {
        //if topic has no answer
        $topicid = bbp_get_forum_last_topic_id;
        $funnytitle = get_the_title($topicid);
    }
        if(strlen($title) > 30) {
            $title = wordwrap($title, 30);
            $title = substr($title, 0, strpos($title,"\n")).'...';
        } 
    //if inplay or charastuff, information about the character is displayed.
    if ($rpg_type == 'ingame' | $rpg_charastuff == 'charastuff') {
        $post_id        = bbp_get_topic_last_reply_id();
        $character      = get_post_meta($post_id, 'rpg_bbp_own_character_p', true);
        if (!$character) {
            $character = get_post_meta($post_id, 'rpg_bbp_own_character', true);
            if (!$character) {
                global $wpdb;
                $active = 0;
                $names = $wpdb->get_results("SELECT starter, username FROM wp_posts WHERE ID = '".$post_id."'");
                foreach($names as $name) {
                    if ($name->starter) {
                        $character = $name->starter;
                    } else {
                        $character = $name->username;
                    }
                }
            }
        }
        $stuff          = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ', ');
        $character      = str_replace($stuff, '', $character);
        echo '<div class="rpg_ingame_topic_information">';
        if (!lze_user_can_play() | (!is_user_logged_in()) ) {
            echo 'von '.$character.'<br>';
            lze_post_date_topic();
            echo '</div>';
        } else {
            echo '<a href="'.bbp_get_topic_last_reply_url().'">';
            echo $title;
            echo '<br>von '.$character.'<br>';
            lze_post_date_topic();
            echo '</a>';
            echo '</div>';
        }
        //if outplay, information about the user is displayed.
    } else {
        $postid = bbp_get_topic_last_reply_id();
        $authorid = get_post($postid)->post_author; 
        if (!$authorid) {
            $authorid = bbp_get_topic_author_id();
        }
        $userinformation = get_userdata($authorid);
        echo '<div class="rpg_ingame_topic_information">';
        echo '<a href="';
        
        bbp_topic_last_reply_url();
        
        echo '">';
        echo 'von '.get_the_author_meta( 'nickname', $authorid ).'<br>';
        lze_post_date_topic();
        echo '</a></div>';
        
    }
    
}



add_filter( 'bbp_get_forum_freshness_link', 'change_fressness_text', 10, 2 );

function change_fressness_text( $anchor, $forum_id )
    {
        return str_replace( "Keine Themen", "", $anchor );
    }
?>
