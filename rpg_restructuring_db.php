<?php

//functions are not included until needed.
//just use if you know what you are doing.
//functions are written for import wbb lite.

//collects and updates information for topics
function rpg_topic() {
    global $wpdb;
    $lze_data = $wpdb->get_results("SELECT * FROM wp_posts WHERE import = 'thread'");
    foreach($lze_data as $single_data) {
        if($single_data->threadid) {
            //needed variables
            $where              = array('ID' => $single_data->ID);
            $time               = date('Y-m-d G:i:s', $single_data->starttime);
            $slug               = $single_data->ID;
            $parentid           = "";
            
            switch ($single_data->boardid) {
                //teamarea
                case 2:
                    $parentid = 586;
                    break;
                    
                //headquarter
                case 4: 
                    $parentid = 588;
                    break;
                //shield protocoll
                case 5:
                    $parentid = 590;
                    break;
                //support
                case 6:
                    $parentid = 592;
                    break;
                    
                //new york
                case 12:
                    $parentdid = 596;
                    break;
                    
                //schawarma
                case 17:
                    $parentid = 604;
                    break;
                //russian roulette
                case 18:
                    $parentid = 606;
                    break;
                //guggenheim museum
                case 19:
                    $parentid = 608;
                    break;
                    
                //archiv
                case 21:
                    $parentid = 610;
                    break;
                    
                //other places: asgard    
                case 23:
                    $parentid = 650;
                    break;
                
                //sethilfe    
                case 25:
                    $parentid = 565;
                    break;
                    
                //away
                case 35:
                    $parentid = 634;
                    break;
                    
                //postpartnersuche
                case 37:
                    $parentid = 632;
                    break;
                //rules
                case 38:
                    $parentid = 625;
                    break;
                
                //team alte steckbriefe
                case 62:
                    $parentid = 621;
                    break;
                    
                //öffentliche Bereiche
                case 64:
                    $parentid = 638;
                    break;    
                //wohnungen
                case 65:
                    $parentid = 646;
                    break;
                //stark tower
                case 66:
                    $parentid = 644;
                    break;
                
                //x-institut
                case 72:
                    $parentid = 648;
                    break;
                    
                //nebenplay
                case 81:
                    $parentid = 600;
                    break;
                //helicarrier
                case 82:
                    $parentid = 652;
                    break;
                    
                //shield hq
                case 88:
                    $parentid = 654;
                    break;   
                
                //finished plots
                case 93:
                    $parentid = 700;
                    break;
                //archiv september 2013
                case 94:
                    $parentid = 660;
                    break;
                //archiv oktober 2013
                case 95:
                    $parentid = 662;
                    break;
                //nebenplay archiv
                case 96:
                    $parentid = 681;
                    break;    
                    
                //waiting room
                case 106:
                    $parentid = 614;
                    break;   
                    
                //partner schwestern
                case 108:
                    $parentid = 683;
                    break;
                //partner real life
                case 109:
                    $parentid = 685;
                    break;
                //partner bücher 
                case 110:
                    $parentid = 687;
                    break;
                    //partner fantasy
                case 111:
                    $parentid = 689;
                    break;
                //partner crossover
                case 112:
                    $parentid = 691;
                    break;
                //shield ny department
                case 113:
                    $parentid = 642;
                    break;
                
                //team alter kram
                case 131:
                    $parentid = 623;
                    break;
                
                //archiv november 2013
                case 137:
                    $parentid = 664;
                    break;
                
                //archiv april 2014
                case 225:
                    $parentid = 670;
                    break;    
                
                //communications
                case 155:
                    $parentid = 602;
                    break;
                
                //hydra quartier ny
                case 157:
                    $parentid = 636;
                    break;
                
                //pym technologies ny
                case 161:
                    $parentid = 640;
                    break;
                  
                //finished plots oktober 13
                case 172:
                    $parentid = 714;
                    break;
                //finished plots november 13
                case 173:
                    $parentid = 712;
                    break;
                //finished plots dezember 13
                case 174:
                    $parentid = 710;
                    break;
                //finished plots mai 14
                case 175:
                    $parentid = 702;
                    break;
                
                //archiv dezember 13
                case 178:
                    $parentid = 666;
                    break;
                
                //archiv januar 14
                case 207:
                    $parentid = 668;
                    break;
                
                //finished plots juni 14
                case 221:
                    $parentid = 706;
                    break;
                //finished plots januar 14
                case 222:
                    $parentid = 708;
                    break;
                
                //archiv april 2014
                case 225:
                    $parentid = 673;
                    break;
                
                //finished plots april 14
                case 243:
                    $parentid = 704;
                    break;
                
                //archiv mai 14
                case 245:
                    $parentid = 672;
                    break;
                
                //archiv juni 14
                case 260:
                    $parentid = 675;
                    break;
                 
                //spielerplots
                case 262:
                    $parentid = 698;
                    break;    
                   
                //plots juli 14
                case 275:
                    $parentid = 696;
                    break;
                
                //archiv juli 2014
                case 277:
                    $parentid = 677;
                    break;
                
                //plots august 14
                case 282:
                    $parentid = 694;
                    break;
                    
                //archiv august 2014
                case 292:
                    $parentid = 679;
                    break;   
                    
                //archiv gesuche
                case 61:
                    $parentid = 658;
                    break;
            }
                
            //update-arrays
            $update_posttype    = array( 'post_type' => 'topic' );
            $update_postslug    = array( 'post_name' => $slug );
            $update_date        = array( 'post_date' => $time );
            $update_date_gmt    = array( 'post_date_gmt' => $time );
            $update_post_m      = array( 'post_modified' => $time );
            $update_post_m_gmt  = array( 'post_modified_gmt' => $time );
            $update_postcontent = array( 'post_content' => 'Importiert aus altem Forum.' );
            $update_import      = array( 'import' => 'thread' );
            $update_parent      = array( 'post_parent' => $parentid );
            
            //editing database
            $wpdb->update( 'wp_posts', $update_posttype, $where );
            $wpdb->update( 'wp_posts', $update_postslug, $where );
            $wpdb->update( 'wp_posts', $update_date, $where );
            $wpdb->update( 'wp_posts', $update_date_gmt, $where );
            $wpdb->update( 'wp_posts', $update_post_m, $where );
            $wpdb->update( 'wp_posts', $update_post_m_gmt, $where );
            $wpdb->update( 'wp_posts', $update_postcontent, $where );
            $wpdb->update( 'wp_posts', $update_import, $where );
            $wpdb->update( 'wp_posts', $update_parent, $where );
            
        }
    }
}
//rpg_topic();

//collects and updates information for posts
function rpg_posts() {
    global $wpdb;
        $lze_data = $wpdb->get_results("SELECT * FROM wp_posts WHERE ID > '36191'");
        //hier kein variablendump, doofie...
        foreach($lze_data as $single_data) {
            if($single_data->postid) {
    
                            
                $where              = array('ID' => $single_data->ID);
                
                //if no new data no change
                $userid             = $single_data->post_author;
                
                //collect correct user ids
                switch($single_data->post_author) {
                        //linda
                    case 292:
                    case 427:
                    case 564:
                    case 379:
                    case 396:
                    case 580:
                    case 604:
                    case 455:
                    case 555:
                    case 472:
                    case 487:
                        $userid = 1;
                        break;

                        //thor
                    case 567:
                        $userid = 26;
                        break;

                        //leon
                    case 605:
                        $userid = 24;
                        break;

                        //jake
                    case 584:
                        $userid = 9;
                        break;

                        //isi
                    case 393:
                    case 424:
                        $userid = 13;
                        break;

                        //jamie white
                    case 497:
                        $userid = 14;
                        break;

                        //greebo
                    case 341:
                    case 335:
                    case 397:
                    case 433:
                        $userid = 10;
                        break;

                        //reader
                    //case 331:
                        //$userid = ;
                        //break;

                        //isa
                    case 488:
                    case 440:
                    case 422:
                    case 458:
                        $userid = 12;
                        break;

                        //garry / jessica
                    case 608:
                    case 597:
                        $userid = 18;
                        break;

                        //Melanie
                    case 609:
                    case 556:
                    case 566:
                        $userid = 4;
                        break;

                        //torsten
                    case 401:
                        $userid = 23;
                        break;

                        //maggie
                    case 388:
                    case 330:
                        $userid = 15;
                        break;

                        //mira
                    case 541:
                    case 581:
                        $userid = 16;
                        break;

                        //nica
                    case 591:
                        $userid = 17;
                        break;

                        //doris
                    case 431:
                        $userid = 11;
                        break;

                        //ela
                    case 596:
                    case 607:
                    case 594:
                        $userid = 22;
                        break;

                        //rhea
                    case 395:
                    case 402:
                    case 425:
                    case 408:
                    case 490:
                    case 506:
                        $userid = 19;
                        break;

                        //kat
                    case 577:
                        $userid = 25;
                        break;

                        //rica
                    case 484:
                    case 298:
                    case 563:
                    case 451:
                    case 524:
                        $userid = 6;
                        break;

                        //wsc
                    case 294:
                        $userid = 5;
                        break;

                        //kilgrave
                    case 558:
                        $userid = 8;
                        break;

                        //annette
                    case 426:
                    case 399:
                    case 611:
                    case 545:
                    case 587:
                        $userid = 21;
                        break;

                        //kitty
                    case 610:
                        $userid = 20;
                        break;

                }
                $parentobj          = $wpdb->get_results("SELECT ID FROM wp_posts WHERE threadid = '".$single_data->threadid."' AND import = 'thread'");
                $parentid           = "";
                foreach ($parentobj as $parent) {
                    $parentid = $parent->ID;
                }
                
                $postcontent        = $single_data->message;
                $slug               = $single_data->ID;
                $time               = date('Y-m-d G:i:s', $single_data->posttime);
                
                $update_postparent  = array( 'post_parent' => $parentid );
                $update_posttype    = array( 'post_type' => 'reply' );
                $update_postslug    = array( 'post_name' => $slug );
                $update_date        = array( 'post_date' => $time );
                $update_date_gmt    = array( 'post_date_gmt' => $time );
                $update_post_m      = array( 'post_modified' => $time );
                $update_post_m_gmt  = array( 'post_modified_gmt' => $time );
                $update_postcontent = array( 'post_content' => $postcontent );
                $update_import      = array( 'import' => 'post' );
                $update_author      = array( 'post_author' => $userid );
                
                //editing database
                $wpdb->update( 'wp_posts', $update_posttype, $where );
                $wpdb->update( 'wp_posts', $update_postslug, $where );
                $wpdb->update( 'wp_posts', $update_date, $where );
                $wpdb->update( 'wp_posts', $update_date_gmt, $where );
                $wpdb->update( 'wp_posts', $update_post_m, $where );
                $wpdb->update( 'wp_posts', $update_post_m_gmt, $where );
                $wpdb->update( 'wp_posts', $update_postcontent, $where );
                $wpdb->update( 'wp_posts', $update_import, $where );
                $wpdb->update( 'wp_posts', $update_author, $where );
                $wpdb->update( 'wp_posts', $update_postparent, $where );

            }
        }
}
//rpg_posts();

//puts meta information per post in wp_postmeta
function character_per_post() {
    global $wpdb;
        $lze_data = $wpdb->get_results("SELECT * FROM wp_posts WHERE import = 'post' AND starter = 'Mishka Wolkow'");
        $characters = lze_get_characters();
        foreach($lze_data as $single_data) {
            $post_id = $single_data->ID;
            $character = "";
            foreach ($characters as $chara) {
                if($single_data->username == $chara['name']) {
                    $character = $chara['name'].', '.$chara['id'];
                    update_post_meta( $post_id, 'rpg_bbp_own_character_p', $character );
                }
            }
        }
}
character_per_post();

//updates user per topic
function rpg_topic_user() {
    global $wpdb;
        $lze_data = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_type = 'topic' AND import = 'thread'");
        //hier kein variablendump, doofie...
        foreach($lze_data as $single_data) {
            //var_dump($single_data->post_author);
                        
                $where              = array('ID' => $single_data->ID);
                
                //if no new data no change
                $userid             = $single_data->post_author;
                
                //collect correct user ids
                switch(intval($single_data->post_author)) {
                        //linda
                    case 292:
                    case 427:
                    case 564:
                    case 379:
                    case 396:
                    case 580:
                    case 604:
                    case 455:
                    case 555:
                    case 472:
                    case 487:
                        $userid = 1;
                        break;

                        //thor
                    case 567:
                        $userid = 26;
                        break;

                        //leon
                    case 605:
                        $userid = 24;
                        break;

                        //jake
                    case 584:
                        $userid = 9;
                        break;

                        //isi
                    case 393:
                    case 424:
                        $userid = 13;
                        break;

                        //jamie white
                    case 497:
                        $userid = 14;
                        break;

                        //greebo
                    case 341:
                    case 335:
                    case 397:
                    case 433:
                        $userid = 10;
                        break;

                        //isa
                    case 488:
                    case 440:
                    case 422:
                    case 458:
                        $userid = 12;
                        break;

                        //garry / jessica
                    case 608:
                    case 597:
                        $userid = 18;
                        break;

                        //Melanie
                    case 609:
                    case 556:
                    case 566:
                        $userid = 4;
                        break;

                        //torsten
                    case 401:
                        $userid = 23;
                        break;

                        //maggie
                    case 388:
                    case 330:
                        $userid = 15;
                        break;

                        //mira
                    case 541:
                    case 581:
                        $userid = 16;
                        break;

                        //nica
                    case 591:
                        $userid = 17;
                        break;

                        //doris
                    case 431:
                        $userid = 11;
                        break;

                        //ela
                    case 596:
                    case 607:
                    case 594:
                        $userid = 22;
                        break;

                        //rhea
                    case 395:
                    case 402:
                    case 425:
                    case 408:
                    case 490:
                    case 506:
                        $userid = 19;
                        break;

                        //kat
                    case 577:
                        $userid = 25;
                        break;

                        //rica
                    case 484:
                    case 298:
                    case 563:
                    case 451:
                    case 524:
                        $userid = 6;
                        break;

                        //wsc
                    case 294:
                        $userid = 5;
                        break;

                        //kilgrave
                    case 558:
                        $userid = 8;
                        break;

                        //annette
                    case 426:
                    case 399:
                    case 611:
                    case 545:
                    case 587:
                        $userid = 21;
                        break;

                        //kitty
                    case 610:
                        $userid = 20;
                        break;

                }

                $update_author      = array( 'post_author' => $userid );
                
                //editing database
                $wpdb->update( 'wp_posts', $update_author, $where );
            }
        
    var_dump($userid);
}
//rpg_topic_user();

//puts mega information per topic in wp_postmeta
function character_per_topic() {
    global $wpdb;
    $lze_data = $wpdb->get_results("SELECT * FROM wp_posts WHERE import = 'thread'");
    $characters = lze_get_characters();
    foreach($lze_data as $single_data) {
        $post_id = $single_data->ID;
        $character = "";
        foreach ($characters as $chara) {
            if($single_data->starter == $chara['name']) {
                $character = $chara['name'].', '.$chara['id'];
                update_post_meta( $post_id, 'rpg_bbp_own_character', $character );
            }
        }
    }
}
//character_per_topic();

//collects date as numbers
function date_collector($string) {
    //gesuchtes Endformat: 2014/08/15
    $testdata_1     = $string;
    $regdate        = '/\d?\d..?\d?\d/';
    $match          = array();
    preg_match($regdate, $testdata_1, $match);
    $matchstring = implode($match);
    if ($matchstring) {
        $splitted = explode('.', $matchstring);
        $counter = 0;
        $day = 0;
        $month = 0;
        $year = 0;
        foreach($splitted as $split) {
            if ($counter == 0) {
                if (strlen($split) == 1) {
                    $day = '0'.$split;
                } else {
                    $day = $split;
                }
            } else if ($counter == 1){
                if (strlen($split) == 1) {
                    $month = '0'.$split;
                } else {
                $month = $split;
                }
            }
            $counter++;
        }
        if ($month <= 8) {
            $year = 2014;
        } else {
            $year = 2013;
        }
        
        if ($year && $month && $day) {
            $fulldate = $year.'/'.$month.'/'.$day;
        } else {
            $fulldate = "";
        }
        return $fulldate;
    }
}

//collects date as textdate
function date_collector_textdate($string) {
    //gesuchtes Endformat: 2014/08/15
    $testdata_1     = $string;
    $regdate        = array('/\d?\d.?.?Januar/','/\d?\d.?.?April/','/\d?\d.?.?Mai/','/\d?\d.?.?Juni/','/\d?\d.?.?Juli/','/\d?\d.?.?August/','/\d?\d.?.?September/','/\d?\d.?.?Oktober/','/\d?\d.?.?November/','/\d?\d.?.?Dezember/');
    $match          = array();
    $ismatch        = array();
    foreach($regdate as $reg) {
        if (preg_match($reg, $testdata_1, $match)) {
            if (!empty($match)) {
                $ismatch = $match;
            }
        }
    }
    $ismatchstring = implode($ismatch);
    if ($ismatchstring) {
        //var_dump($ismatchstring);
        $splitted = explode('.', $ismatchstring);
        $counter = 0;
        $day = 0;
        $month = '';
        $year = '';
        foreach($splitted as $split) {
            $split = str_replace(' ', '', $split);
            if ($counter == 0) {
                if (strlen($split) == 1) {
                    $day = '0'.$split;
                } else {
                    $day = $split;
                }
            } else if ($counter == 1){
                switch ($split) {
                    case 'Januar':
                        $month = '01';
                        $year = '2014';
                        break;
                    case 'April':
                        $month = '04';
                        $year = '2014';
                        break;
                    case 'Mai':
                        $month = '05';
                        $year = '2014';
                        break;
                    case 'Juni':
                        $month = '06';
                        $year = '2014';
                        break;
                    case 'Juli':
                        $month = '07';
                        $year = '2014';
                        break;
                    case 'August':
                        $month = '08';
                        $year = '2014';
                        break;
                    case 'September':
                        $month = '09';
                        $year = '2013';
                        break;
                    case 'Oktober':
                        $month = '10';
                        $year = '2013';
                        break;
                    case 'November':
                        $month = '11';
                        $year = '2013';
                        break;
                    case 'Dezember':
                        $month = '12';
                        $year = '2013';
                        break;
                }
            }
            $counter++;
        }
        
        if ($day && $month && $year) {
            $fulldate = $year.'/'.$month.'/'.$day;
            echo 'Postdate';
        } else {
            $fulldate = "";
        }
        return $fulldate;
    }
}

//writes date if numberdate before
function date_per_topic() {
    global $wpdb;
    $lze_data = $wpdb->get_results("SELECT descr, ID FROM wp_posts WHERE import='thread'");
    foreach($lze_data as $single_data) {
        if($single_data->descr) {
            //needed variables
            $postdate = date_collector($single_data->descr);
            if ($postdate) {
                echo 'Postdate';
                $post_id = $single_data->ID;
                update_post_meta( $post_id, 'rpg_bbp_date', $postdate );
            }  
        }
    }  
}
//date_per_topic();

//writes date if textdate before
function date_per_topic_text() {
    global $wpdb;
    $lze_data = $wpdb->get_results("SELECT descr, ID FROM wp_posts WHERE import='thread'");
    $postdate = "";
    foreach($lze_data as $single_data) {
        if($single_data->descr) {
            //needed variables
            $postdate = date_collector_textdate($single_data->descr);
            if ($postdate) {
                $post_id = $single_data->ID;
                update_post_meta( $post_id, 'rpg_bbp_date', $postdate );
            }    
        }
    }  
}
//date_per_topic_text();

//was needed to re-implement per mistake deleted information. Should not be needed in normal import.
function split_sql_statement() {
    $string ="(414, 4, '', 'News', 0, 1396709212, 294, 'World Security Council', 1492524162, 294, 'World Security Council', 68, 1697, 0, 0, 0, 0, 0, 0, 1, ''),
(66, 61, '', 'Wade ''Deadpool'' Wilson', 0, 1374417281, 292, 'Steve Rogers', 1375457321, 0, 'Peter Parker', 4, 154, 0, 0, 0, 0, 0, 0, 1, 'RESERVIERT'),";
    $rowarray = explode('),', $string);
    $singlerows = array();
    $counter = 0;
    //var_dump($rowarray);
    foreach($rowarray as $row) {
        $stuff = array('(', '),', " '", "'");
        $data = str_replace($stuff, '', $row);
        $singlerows = explode(',', $data);
        $counter = 0;
        $threadid = 0;
        $desc = "";
        foreach($singlerows as $singlerow) {
            if ($counter == 0) {
                $threadid = intval($singlerow);
            } else if ($counter == 20) {
                $desc = $singlerow;
            }
            $counter ++;
        }
        if ($desc) {
            echo "UPDATE wp_posts SET descr = '".$desc."' WHERE threadid = '".$threadid."' AND import = 'thread';";
            echo '<br>';
        }
    } 
    $stuff = array('(', '),');
}

?>