<?php
/* initialise and build taxonomies for characters*/
//needed in frontend
add_action('init', 'lze_fertig_tax');

function lze_fertig_tax() {
    register_taxonomy( "Fertig",
        array( "lze_character" ),
        array( 
            "hierarchical"      => true,
            "label"             => "Fertig?",
            "singular_label"    => "Fertig!",
            "rewrite"           => true
             )
    );  
    wp_insert_term('Fertig!', 'Fertig');
}

add_action('init', 'lze_character_tax');
function lze_character_tax() {
        register_taxonomy( "Status",
            array( "lze_character" ),
            array( 
                "hierarchical"      => true,
                "label"             => "Status",
                "singular_label"    => "WoB",
                "rewrite"           => true
                 )
        );
        wp_insert_term('Charakter angenommen', 'Status');
        
        register_taxonomy( "Gruppe",
            array( "lze_character" ),
            array( 
                "hierarchical"      => true,
                "label"             => "Gruppe",
                "singular_label"    => "Gruppe",
                "rewrite"           => true
                 )
        );   
}

add_action('init', 'lze_mult_profiles');
function lze_mult_profiles() {
    if (get_option('rpg_option_charprofiles') == 'mp-yes'){
        register_taxonomy( "Steckbriefarten",
            array( "lze_felder" ),
            array( 
                "hierarchical"      => true,
                "label"             => "Steckbriefart",
                "singular_label"    => "Art",
                "rewrite"           => true
                 )
        );  
    }
}

add_action('init', 'lze_felder_tax');
function lze_felder_tax() {
    register_taxonomy( "Typ",
        array( "lze_felder" ),
        array( 
            "hierarchical"      => true,
            "label"             => "Typ",
            "singular_label"    => "Typ",
            "rewrite"           => true
             )
    );  
    wp_insert_term('Kurzbeschreibung', 'Typ');
}

//Taxonomies for Wanted
add_action('init', 'lze_s_wanted_mind');
function lze_s_wanted_mind() {
    register_taxonomy( "Gesinnung",
        array( "lze_s_wanted" ),
        array( 
            "hierarchical"      => true,
            "label"             => "Gesinnung",
            "singular_label"    => "Gesinnung",
            "rewrite"           => true
             )
    );  
    wp_insert_term('Gut', 'Gesinnung');
    wp_insert_term('Neutral', 'Gesinnung');
    wp_insert_term('Böse', 'Gesinnung');
}

add_action('init', 'lze_s_wanted_sex');
function lze_s_wanted_sex() {
    register_taxonomy( "Geschlecht",
        array( "lze_s_wanted" ),
        array( 
            "hierarchical"      => true,
            "label"             => "Geschlecht",
            "singular_label"    => "Geschlecht",
            "rewrite"           => true
             )
    );  
    wp_insert_term('weiblich', 'Geschlecht');
    wp_insert_term('männlich', 'Geschlecht');
}

add_action('init', 'lze_g_wanted_team');
function lze_g_wanted_team() {
    register_taxonomy( "Boardgesuch",
        array( "lze_group_wanted" ),
        array( 
            "hierarchical"      => true,
            "label"             => "Boardgesuch",
            "singular_label"    => "Boardgesuch",
            "rewrite"           => true
             )
    );  
    wp_insert_term('Boardgesuch', 'Boardgesuch');
}

add_action('init', 'lze_s_wanted_team');
function lze_s_wanted_team() {
    register_taxonomy( "Boardgesuch",
        array( "lze_s_wanted" ),
        array( 
            "hierarchical"      => true,
            "label"             => "Boardgesuch",
            "singular_label"    => "Boardgesuch",
            "rewrite"           => true
             )
    );  
    wp_insert_term('Boardgesuch', 'Boardgesuch');
}

add_action('init', 'lze_s_wanted_stat');
function lze_s_wanted_stat() {
    register_taxonomy( "Frei",
        array( "lze_s_wanted" ),
        array( 
            "hierarchical"      => true,
            "label"             => "Frei",
            "singular_label"    => "Frei",
            "rewrite"           => true
             )
    );  
    wp_insert_term('Reserviert', 'Frei');
}


?>
