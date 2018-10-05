<?php
//adds different forum-type-boxes
//forum can have multiple types

//information is not needed in frontend
if (is_admin()) {
    add_action("admin_init", "rpg_ingame");
    add_action('save_post', 'save_rpg_ingame');

    function rpg_ingame () {
        add_meta_box("rpg_ingame_meta", "Ingame?", "rpg_ingame_meta", "forum", "side", "high");
    }

    function rpg_ingame_meta() {
        global $post;
        $data = "";
        $custom = get_post_custom($post->ID);
        if(isset($custom["rpg_ingame_meta"])){
            $data   = $custom["rpg_ingame_meta"];
        }
        echo 'Ingame: ';
        if ($data) {
            $data   = implode($data);
            if ($data == 'ingame') {
                echo '<input type="checkbox" name="rpg_ingame_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_ingame_meta"/>';
            }
        } else {
                echo '<input type="checkbox" name="rpg_ingame_meta"/>';
            }
        echo '<br><br>Wähle diese Option, wenn das Forum ein Ingame-Forum oder ein Ingame-Archiv ist.';
    }

    function save_rpg_ingame() {
        global $post;
        if (isset($_POST["rpg_ingame_meta"])) {
            update_post_meta($post->ID, "rpg_ingame_meta", 'ingame');
        }else {
            delete_post_meta($post->ID, "rpg_ingame_meta");
        }
    }

    add_action("admin_init", "rpg_archive");
    add_action('save_post', 'save_rpg_archive');

    function rpg_archive() {
        add_meta_box("rpg_archive_meta", "Archiv?", "rpg_archive_meta", "forum", "side", "high");
    }

    function rpg_archive_meta() {
        global $post;
        $data = "";
        $custom = get_post_custom($post->ID);
        if(isset($custom["rpg_archive_meta"])){
            $data   = $custom["rpg_archive_meta"];
        }
        echo 'Archiv: ';
        if ($data) {
            $data   = implode($data);
            if ($data == 'archive') {
                echo '<input type="checkbox" name="rpg_archive_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_archive_meta"/>';
            }
        } else {
                echo '<input type="checkbox" name="rpg_archive_meta"/>';
            }
        echo '<br><br>Wähle diese Option, wenn das Forum ein Archiv ist.';
    }

    function save_rpg_archive() {
        global $post;
        if (isset($_POST["rpg_archive_meta"])) {
            update_post_meta($post->ID, "rpg_archive_meta", 'archive');
        } else {
            delete_post_meta($post->ID, "rpg_archive_meta");
        }
    }

    add_action("admin_init", "rpg_pastplay");
    add_action('save_post', 'save_rpg_pastplay');

    function rpg_pastplay() {
        add_meta_box("rpg_pastplay_meta", "Nebenplay?", "rpg_pastplay_meta", "forum", "side", "high");
    }

    function rpg_pastplay_meta() {
        global $post;
        $data = "";
        $custom = get_post_custom($post->ID);
        if(isset($custom["rpg_pastplay_meta"])){
            $data   = $custom["rpg_pastplay_meta"];
        }
        echo 'Nebenplay: ';
        if ($data) {
            $data   = implode($data);
            if ($data == 'pastplay') {
                echo '<input type="checkbox" name="rpg_pastplay_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_pastplay_meta"/>';
            }
        } else {
            echo '<input type="checkbox" name="rpg_pastplay_meta"/>';
        }
        echo '<br><br>Wähle diese Option, wenn das Forum ein Nebenplay ist.';
    }

    function save_rpg_pastplay() {
        global $post;
        if (isset($_POST["rpg_pastplay_meta"])) {
            update_post_meta($post->ID, "rpg_pastplay_meta", 'pastplay');
        } else {
            delete_post_meta($post->ID, "rpg_pastplay_meta");
        }
    }

    add_action("admin_init", "rpg_charastuff");
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
    }

    add_action("admin_init", "rpg_noaccess");
    add_action('save_post', 'save_rpg_noaccess');

    function rpg_noaccess() {
        add_meta_box("rpg_noaccess_meta", "Kein Zugriff für Newbies", "rpg_noaccess_meta", "forum", "side", "high");
    }

    function rpg_noaccess_meta() {
        global $post;
        $custom = get_post_custom($post->ID);
        $data = "";
        if (isset($custom["rpg_noaccess_meta"])) {
            $data   = $custom["rpg_noaccess_meta"];
        }
        echo 'Zugriff nur für Aktive: ';
        if ($data) {
            $data   = implode($data);
            if ($data == 'restricted') {
                echo '<input type="checkbox" name="rpg_noaccess_meta" checked/>';
            } else {
                echo '<input type="checkbox" name="rpg_noaccess_meta"/>';
            }
        } else {
                echo '<input type="checkbox" name="rpg_noaccess_meta"/>';
            }
        echo '<br><br>Wähle diese Option, wenn nur User mit aktiven Charakteren hier Zugriff haben sollen. Ist es eine Inplay-Area, musst du diese Funktion nicht zusätzlich auswählen.';
    }

    function save_rpg_noaccess() {
        global $post;
        if (isset($_POST["rpg_noaccess_meta"])) {
            update_post_meta($post->ID, "rpg_noaccess_meta", 'restricted');
        } else {
             delete_post_meta($post->ID, "rpg_noaccess_meta");
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
        echo '<br><br>Wähle diese Option, wenn in diesem Forum Partnerboards ihre Anfragen stellen / abgelegt werden.';
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