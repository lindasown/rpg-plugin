<?php
//admininformation for Dashboard

//information not needed in frontend
if (is_admin()) {

    function rpg_dash_wob() {
        wp_add_dashboard_widget(
            'rpg_dash_wob',         // Widget slug.
            'Charakter zur Korrektur',         // Title.
            'rpg_dash_wob_function' // Display function.
        );	
    }
    add_action( 'wp_dashboard_setup', 'rpg_dash_wob' );

    function rpg_dash_wob_function() {
        lze_notification_wob();
    }

    function rpg_dash_blacklist() {
        wp_add_dashboard_widget(
            'rpg_dash_blacklist',         // Widget slug.
            'Blacklist',         // Title.
            'rpg_dash_blacklist_function' // Display function.
        );	
    }
    add_action( 'wp_dashboard_setup', 'rpg_dash_blacklist' );

    function rpg_dash_blacklist_function() {
            lze_blacklist_charas();
    }
    
    function rpg_dash_pn() {
        wp_add_dashboard_widget(
            'rpg_dash_pn',         // Widget slug.
            'Liste der PN-Empfänger',         // Title.
            'rpg_dash_pn_function' // Display function.
        );	
    }
    add_action( 'wp_dashboard_setup', 'rpg_dash_pn' );

    function rpg_dash_pn_function() {
        lze_display_all_usernames();
    }
    
    //remove column with post counts and adds column with register date
    add_action('manage_users_columns','lze_modify_user_columns');
    function lze_modify_user_columns($column_headers) {
        unset($column_headers['posts']);
        $column_headers['lze_registered'] = 'Registriert';
        return $column_headers;
    }
    
    //gets the register date of each user
    add_action('manage_users_custom_column', 'lze_get_register_date_content', 10, 3);
    function lze_get_register_date_content($value, $column_name, $user_id) {
        $user = get_userdata( $user_id );
        if ( 'lze_registered' == $column_name ) {
            $time = $user->user_registered;
            $content = date('d.m.Y \u\m G:i', strtotime($time));
        }
        return $content;
    }
}


?>