<?php 
//Setting Page for RPG-Plugin

//just needed in backend
if (is_admin()) { 
    function rpg_register_designsettings() {
        add_option( 'rpg_designoptions_group');

        register_setting( 'rpg_designoptions_group', 'rpg_designoptions_group', 'rpg_callback' );

    }
    add_action( 'admin_init', 'rpg_register_designsettings' );

    function rpg_register_designoptions_page() {
      add_options_page('RPG Design-Einstellungen', 'RPG Design-Einstellungen', 'manage_options', 'rpg', 'rpg_designoptions_page');
    }
    add_action('admin_menu', 'rpg_register_designoptions_page');

    function rpg_designoption_page() {
      echo 'Das ist meine tolle Plugin-Options-Seite.';
    }

    function rpg_designoptions_page()
    {
    ?>
    <div>
        <h2>Design-Einstellungen</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'rpg_designoptions_group' ); ?>
            <p>Registriere hier ein neues Design. Pro Design kannst du Farben, Bilder und Schriftarten definieren und ausserdem ein individuelles CSS hinterlegen.</p>
            <fieldset class="rpg-options designoptions-register">
                <h4>Design registrieren</h4>
                <p>Alle Designs basieren auf dem Marvelous-Theme. Wenn du dieses Theme nicht installiert hast, bekommst du bestenfalls ein Durcheinander, wenn du hier Designs registrierst.</p>
                <label for="rpg_designoptions_name">Name</label>
                <input type="text" name="rpg_designoptions_name" id="rpg_designoptions_name" value="<?php echo get_option('rpg_designoptions_name'); ?>" />
            </fieldset>






            <?php  submit_button(); ?>
        </form>
        </div>

    <?php
    }
}

?>