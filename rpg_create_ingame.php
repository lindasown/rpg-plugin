<?php
//adds different forum-type-boxes
//forum can have multiple types

//information is not needed in frontend
if (is_admin()) {
    add_action("admin_init", "rpg_ingame");
    add_action('save_post', 'save_rpg_ingame');

    function rpg_ingame () {
        add_meta_box("rpg_ingame_meta", "Ingame", "rpg_ingame_meta", "forum", "side", "high");
    }

    function rpg_ingame_meta() {
        global $post;
        $data = "";
        $custom = get_post_custom($post->ID);
        if(isset($custom["rpg_ingame_meta"])){
            $ingame   = $custom["rpg_ingame_meta"];
        }
		echo '<div>';
        echo 'Ingame: ';
        if ($ingame) {
            $ingame   = implode($ingame);
			
            if ($ingame == 'ingame') {
                echo '<input type="checkbox" name="rpg_ingame_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_ingame_meta"/>';
            }
        } else {
                echo '<input type="checkbox" name="rpg_ingame_meta"/>';
        }
		echo '</div>';


		$archive = "";
        if(isset($custom["rpg_archive_meta"])){
            $archive   = $custom["rpg_archive_meta"];
        }
		echo '<div>';
        echo 'Archiv: ';
        if ($archive) {
            $archive   = implode($archive);
            if ($archive == 'archive') {
                echo '<input type="checkbox" name="rpg_archive_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_archive_meta"/>';
            }
        } else {
                echo '<input type="checkbox" name="rpg_archive_meta"/>';
        }
		echo '</div>';


		$past = "";
        if(isset($custom["rpg_pastplay_meta"])){
            $past   = $custom["rpg_pastplay_meta"];
        }
		echo '<div>';
        echo 'Nebenplay: ';
        if ($past) {
            $past   = implode($past);
            if ($past == 'pastplay') {
                echo '<input type="checkbox" name="rpg_pastplay_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_pastplay_meta"/>';
            }
        } else {
            echo '<input type="checkbox" name="rpg_pastplay_meta"/>';
        }
		echo '</div>';



    }

    function save_rpg_ingame() {
        global $post;
        if (isset($_POST["rpg_ingame_meta"])) {
            update_post_meta($post->ID, "rpg_ingame_meta", 'ingame');
        }else {
            delete_post_meta($post->ID, "rpg_ingame_meta");
        }

		if (isset($_POST["rpg_archive_meta"])) {
            update_post_meta($post->ID, "rpg_archive_meta", 'archive');
        } else {
            delete_post_meta($post->ID, "rpg_archive_meta");
        }

		if (isset($_POST["rpg_pastplay_meta"])) {
            update_post_meta($post->ID, "rpg_pastplay_meta", 'pastplay');
        } else {
            delete_post_meta($post->ID, "rpg_pastplay_meta");
        }


    }

    add_action("admin_init", "rpg_archive");
    add_action('save_post', 'save_rpg_archive');


    /*add_action("admin_init", "rpg_charastuff");
    add_action('save_post', 'save_rpg_charastuff');

    function rpg_charastuff() {
        add_meta_box("rpg_charastuff_meta", "Charakterpostings?", "rpg_charastuff_meta", "forum", "side", "high");
    }

    function rpg_charastuff_meta() {
        global $post;
        $custom = get_post_custom($post->ID);
        $data = "";
        if (isset($custom["rpg_charastuff_meta"])) {
            $data   = $custom["rpg_charastuff_meta"];
        }
        echo 'Charakterpostings: ';
        if ($data) {
            $data   = implode($data);
            if ($data == 'charastuff') {
                echo '<input type="checkbox" name="rpg_charastuff_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_charastuff_meta"/>';
            }
        } else {
                echo '<input type="checkbox" name="rpg_charastuff_meta"/>';
            }
        echo '<br><br>Wähle diese Option, wenn in der Area mit Charakter gepostet werden können soll - zum Beispiel im Charakterspam.';
    }

    function save_rpg_charastuff() {
        global $post;
        if (isset($_POST["rpg_charastuff_meta"])) {
            update_post_meta($post->ID, "rpg_charastuff_meta", 'charastuff');
        } else {
            delete_post_meta($post->ID, "rpg_charastuff_meta");
        }
    }*/

    add_action("admin_init", "rpg_noaccess");
    add_action('save_post', 'save_rpg_noaccess');

    function rpg_noaccess() {
        add_meta_box("rpg_noaccess_meta", "Zugriffsrechte Outgame", "rpg_noaccess_meta", "forum", "side", "high");
    }

    function rpg_noaccess_meta() {
        global $post;
        $custom = get_post_custom($post->ID);
        

		$forguests = "";
        if (isset($custom["rpg_forguests_meta"])) {
            $forguests   = $custom["rpg_forguests_meta"];
        }
		echo '<div>';
        echo 'Gäste: ';
        if ($forguests) {
            $forguests   = implode($forguests);
            if ($forguests == 'guests-allowed') {
                echo '<input type="checkbox" name="rpg_forguests_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_forguests_meta"/>';
            }
        } else {
            echo '<input type="checkbox" name="rpg_forguests_meta"/>';
        }
		echo '</div>';

		$useronly = "";
        if (isset($custom["rpg_useronly_meta"])) {
            $useronly   = $custom["rpg_useronly_meta"];
        }
		echo '<div>';
        echo 'User: ';
        if ($useronly) {
            $useronly   = implode($useronly);
            if ($useronly == 'user-allowed') {
                echo '<input type="checkbox" name="rpg_useronly_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_useronly_meta"/>';
            }
        } else {
            echo '<input type="checkbox" name="rpg_useronly_meta"/>';
        }
		echo '</div>';

		

		$playeronly = "";
        if (isset($custom["rpg_noaccess_meta"])) {
            $playeronly   = $custom["rpg_noaccess_meta"];
        }
		echo '<div>';
        echo 'Spieler: ';
        if ($playeronly) {
            $playeronly   = implode($playeronly);
            if ($playeronly == 'restricted') {
                echo '<input type="checkbox" name="rpg_noaccess_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_noaccess_meta"/>';
            }
        } else {
            echo '<input type="checkbox" name="rpg_noaccess_meta"/>';
        }
		echo '</div>';

		$foradmins = "";
        if (isset($custom["rpg_foradmins_meta"])) {
            $foradmins   = $custom["rpg_foradmins_meta"];
        }

		echo '<div>';
        echo 'Team: ';
        if ($foradmins) {
            $foradmins   = implode($foradmins);
            if ($foradmins == 'admins-allowed') {
                echo '<input type="checkbox" name="rpg_foradmins_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_foradmins_meta"/>';
            }
        } else {
            echo '<input type="checkbox" name="rpg_foradmins_meta"/>';
        }
		echo '</div>';

        echo '</br><b>Legende</b></br>Gäste: Nicht registriert</br>User: registriert, ohne Charakter</br>Spieler: registriert, mit Charakter</br>Team: Administratoren';
    }

    function save_rpg_noaccess() {
        global $post;
        if (isset($_POST["rpg_noaccess_meta"])) {
            update_post_meta($post->ID, "rpg_noaccess_meta", 'restricted');
        } else {
             delete_post_meta($post->ID, "rpg_noaccess_meta");
        }

		if (isset($_POST["rpg_useronly_meta"])) {
            update_post_meta($post->ID, "rpg_useronly_meta", 'user-allowed');
        } else {
             delete_post_meta($post->ID, "rpg_useronly_meta");
        }

		if (isset($_POST["rpg_forguests_meta"])) {
            update_post_meta($post->ID, "rpg_forguests_meta", 'guests-allowed');
        } else {
             delete_post_meta($post->ID, "rpg_forguests_meta");
        }

		if (isset($_POST["rpg_foradmins_meta"])) {
            update_post_meta($post->ID, "rpg_foradmins_meta", 'admins-allowed');
        } else {
             delete_post_meta($post->ID, "rpg_foradmins_meta");
        }

    }
    
    add_action("admin_init", "rpg_partner");
    add_action('save_post', 'save_rpg_partner');

    function rpg_partner() {
        add_meta_box("rpg_partner_meta", "Partnerarea", "rpg_partner_meta", "forum", "side", "high");
    }

    function rpg_partner_meta() {
        global $post;
        $custom = get_post_custom($post->ID);
        $data = "";
        if (isset($custom["rpg_partner_meta"])) {
            $data   = $custom["rpg_partner_meta"];
        }
		echo '<div>';
        echo 'Partnerarea: ';
        if ($data) {
            $data   = implode($data);
            if ($data == 'partner') {
                echo '<input type="checkbox" name="rpg_partner_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_partner_meta"/>';
            }
        } else {
                echo '<input type="checkbox" name="rpg_partner_meta"/>';
            }
		echo '</div>';
        echo '<br>Wähle diese Option, wenn in diesem Forum Partnerboards ihre Anfragen stellen / abgelegt werden.';
    }

    function save_rpg_partner() {
        global $post;
        if (isset($_POST["rpg_partner_meta"])) {
            update_post_meta($post->ID, "rpg_partner_meta", 'partner');
        } else {
             delete_post_meta($post->ID, "rpg_partner_meta");
        }
    }
}
?>
