<?php
//adds character information in buddypress-profile
function rpg_user_profile_chara_tab() {
    global $bp;

    bp_core_new_nav_item( array( 
        'name' => 'Charaktere', 
        'slug' => 'chara_tab', 
        'screen_function' => 'chara_tab_screen', 
        'position' => 40,
        'parent_url'      => bp_loggedin_user_domain() . '/characters/',
        'parent_slug'     => $bp->profile->slug,
        'default_subnav_slug' => 'chara_tab'
    ));
}
add_action( 'bp_setup_nav', 'rpg_user_profile_chara_tab' );
 
 
function chara_tab_screen() {
    
    // Add title and content here - last is to call the members plugin.php template.
    add_action( 'bp_template_title', 'chara_tab_title' );
    add_action( 'bp_template_content', 'chara_tab_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function chara_tab_title() {
    echo '<h2>Charaktere</h2>';
}

function chara_tab_content() { 
    $userid = bp_displayed_user_id();
    $characters = lze_get_characters_by_user_id($userid);
    foreach ($characters as $single_data) {
        echo '<ul>';
        //echo '<img src="'. lze_get_pic($single_data['id'], 'lze_character_ava') . '"><br><p>'.$single_data['title'].'</p>';
        if (lze_user_can_play()) {
            echo '<li class="rpg_button"><a href="'.get_permalink($single_data['id']).'" target="_blank">'.$single_data['title'].'</a></li>';
        } else {
            echo $single_data['title'];
        }
        echo '</ul>';
    }
}

//adds character information in buddypress-profile
function rpg_user_profile_design_tab() {
    global $bp;

    bp_core_new_nav_item( array( 
        'name' => 'Design', 
        'slug' => 'design_tab_screen', 
        'screen_function' => 'design_tab_screen', 
        'position' => 40,
        'parent_url'      => bp_loggedin_user_domain(),
        'parent_slug'     => $bp->profile->slug,
        'default_subnav_slug' => 'design_tab'
    ));
}
add_action( 'bp_setup_nav', 'rpg_user_profile_design_tab' );
 
 
function design_tab_screen() {
    
    // Add title and content here - last is to call the members plugin.php template.
    add_action( 'bp_template_title', 'design_tab_title' );
    add_action( 'bp_template_content', 'design_tab_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}


function design_tab_title() {
    echo '<h2>Design</h2>';
}

function design_tab_content() { 
    $nickname = get_the_author_meta('login', get_current_user_id());
    echo "Gleich geht's weiter zum Design.";
    echo '<script>window.location.replace("'.get_site_url().'/members/'.$nickname.'/profile/edit/group/2/");</script>';
}


add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
function wpb_new_gravatar ($avatar_defaults) {
$myavatar = get_site_url().'/wp-content/uploads/2017/11/Marv14.png';
$avatar_defaults[$myavatar] = "Default Gravatar";
return $avatar_defaults;
}

?>
