<?php 
//Setting Page for RPG-Plugin

//just needed in backend
if (is_admin()) { 
    function rpg_register_settings() {
        add_option( 'rpg_option_charprofiles');
        add_option( 'rpg_option_pictures');
        add_option( 'rpg_option_styles');
        add_option( 'rpg_options_dummypic');
        add_option( 'rpg_options_profiles');
        add_option( 'rpg_option_date');
        register_setting( 'rpg_options_group', 'rpg_option_charprofiles', 'rpg_callback' );
        register_setting( 'rpg_options_group', 'rpg_option_pictures', 'rpg_callback' );
        register_setting( 'rpg_options_group', 'rpg_option_styles', 'rpg_callback' );
        register_setting( 'rpg_options_group', 'rpg_options_dummypic', 'rpg_callback' );
        register_setting( 'rpg_options_group', 'rpg_option_profiles', 'rpg_callback' );
        register_setting( 'rpg_options_group', 'rpg_option_date', 'rpg_callback' );
    }
    add_action( 'admin_init', 'rpg_register_settings' );

    function rpg_register_options_page() {
      add_options_page('RPG Einstellungen', 'RPG Einstellungen', 'manage_options', 'rpg', 'rpg_options_page');
    }
    add_action('admin_menu', 'rpg_register_options_page');

    function rpg_option_page() {
      echo 'Das ist meine tolle Plugin-Options-Seite.';
    }

    function rpg_options_page()
    {
    ?>
    <div>
        <h2>Einstellungen RPG Plugin</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'rpg_options_group' ); ?>
            <p>Hier kannst du verschiedene Einstellungen machen, um das RPG-System zu personalisieren. </p>
            <fieldset class="rpg-options options-pictures">
                <h4>Bilder</h4>
                <p>Füge hier die URL für den Platzhalter-Avatar ein. (Verborgene Avatare, fehlende Avatare, fehlende Bilder in Gesuchen)</p>
                <label for="rpg_options_dummypic">URL</label>
                <input type="text" name="rpg_options_dummypic" id="rpg_options_dummypic" value="<?php echo get_option('rpg_options_dummypic'); ?>" />

                <br><br>
                <p>Willst du die Charakterbilder (Avatare, Gesuche, Charakterlisten) vor den Gästen verbergen?</p>
                <label for="pic-yes">Ja</label>
                <input type="radio" id="pic-yes" name="rpg_option_pictures" value="pic-yes" <?php if (get_option('rpg_option_pictures') == 'pic-yes') {echo 'checked';} ?>/>
                <label for="pic-no">Nein</label>
                <input type="radio" id="pics-no" name="rpg_option_pictures" value="pic-no" <?php if (get_option('rpg_option_pictures') == 'pic-no') {echo 'checked';} ?>/>
            </fieldset>
            <fieldset class="rpg-options options-profiles">
                <h4>Steckbriefe</h4>
                <p>Dürfen Gäste die Steckbriefe lesen?</p>
                <label for="profiles-yes">Ja</label>
                <input type="radio" id="profiles-yes" name="rpg_option_profiles" value="profiles-yes" <?php if (get_option('rpg_option_profiles') == 'profiles-yes') {echo 'checked';} ?>/>
                <label for="profiles-no">Nein</label>
                <input type="radio" id="profiles-no" name="rpg_option_profiles" value="profiles-no" <?php if (get_option('rpg_option_profiles') == 'profiles-no') {echo 'checked';} ?>/>
            </fieldset>
            <fieldset class="rpg-options options-date">
                <h4>Spielzeitraum</h4>
                <p>In welchem Monat befindet ihr euch? Wähle einfach den ersten Tag des entsprechenden Monates an.</p>
                <label for="rpg_option_date">Spielmonat (JJJJ/MM/DD, zB: 2012/05/16)</label>
                <input type="date" name="rpg_option_date" id="rpg_option_date" value="<?php echo get_option('rpg_option_date'); ?>" />
                <p>Der gewählte Monat erscheint als erster im Datumspicker. Der User kann nach Belieben im Kalender vor- und zurückblättern, ist also nicht auf diesen einzelnen Monat beschränkt.</p>
            </fieldset>
            <fieldset class="rpg-options options-styles">
                <h4>Styleauswahl</h4>
                <p>Gibt es verschiedene Styles, welche die User auswählen können? Leg für jeden gewünschen Style ein CSS an, speichere es im Theme-Ordner und gib ihm dann hier einen Namen. Du kannst beliebig viele Styles anlegen. Die User können die Styles dann in ihrem Profil definieren.</p>
                <div id="rpg_stylerows">
                </div>
                <div id="rpg_addStyleRow">+</div>
                <div id="rpg-addstyle">Styles speichern</div>
                <input type="text" name="rpg_option_styles" id="rpg_option_styles" value=""/>
                
            </fieldset>
            <?php  submit_button(); ?>
        </form>
        </div>
<script>
    jQuery(function() {
        var rowid = 0;
        jQuery('#rpg_addStyleRow').on('click', function() {
            var newRow = '<div class="row" id="' + rowid + '"><input type="text" class="rpg-newstyle-url" placeholder="https://deine-domain.ch/deincss.css"><input type="text" class="rpg-newstyle-name" placeholder="Stylename"><span>Standard-Design: </span><input class="default" type="radio" name="default" value="' + rowid + '"></div>';
            jQuery('#rpg_stylerows').append(newRow);
            rowid++;
        })

        jQuery('#rpg-addstyle').on('click', function() {
            // var inputtext = [];
            var inputtext = "";
            var counter = 0;
            var allrows = jQuery('#rpg_stylerows .row');
            jQuery.each(allrows, function() {
                // inputtext[counter] = [];
                // inputtext[counter]['name'] = jQuery(this).find('.rpg-newstyle-url').val();
                // inputtext[counter]['url'] = jQuery(this).find('.rpg-newstyle-name').val();
                // inputtext[counter]['id'] = jQuery(this).attr('id');
                inputtext = inputtext + "['" + counter + "'][['name']['" + jQuery(this).find('.rpg-newstyle-url').val() + "']]";
                counter++;
            });
            inputtext = '[' + inputtext + ']';
            jQuery('#rpg_option_styles').val(inputtext);
            console.log(inputtext);
        })

    })
    
</script>
    <?php
    } 
}

?>