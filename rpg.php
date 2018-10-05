<?php
/**
 * Plugin Name: RPG
 * Author: Linda Zeller
 * Author URI: mein-apfelbaum.ch
 * Description: Dieses Plugin generiert RPG-Funktionalitäten. Zur korrekten Darstellung von Charakteren und Wanted nutze das Theme RPG. Ausserdem benötigst du folgende Plugins: bbPress, BuddyPress & bbPress Pencil Unread
 **/
include 'rpg_functions.php';
include 'rpg_shortcodes.php';
include 'rpg_choose_fields.php';
include 'rpg_taxonomies.php';
include 'rpg_board_extrafields.php';
include 'rpg_board_character.php';
include 'rpg_board_topictitle.php';
include 'rpg_create_ingame.php';
include 'rpg_wanted.php';
include 'rpg_user_profile.php';
include 'rpg_options.php';
include 'rpg_designoptions.php';
include 'rpg_user_roles.php';
include 'rpg_widgets.php';
include 'rpg_stuff.php';
include 'rpg_partner.php';
include 'rpg_dashboard_widgets.php';
include 'rpg_stylefunctions.php';

//just needed for import. 
//YOU HAVE TO KNOW WHAT YOU ARE DOING!
//include 'rpg_restructuring_db.php';


/*Dateien mit Import-Anpassungen
rpg_board_character, Zeile 27

DELETE:
rpg_stuff

*/


 ?>