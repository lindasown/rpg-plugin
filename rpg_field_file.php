<?php function lze_new_character_meta_boxes() {add_meta_box("lze_sex_meta", "Geschlecht", "lze_character_sex", "lze_character", "normal", "high"); add_meta_box("lze_ava_meta", "Avatar", "lze_character_ava", "lze_character", "normal", "high");add_meta_box("AlterEgo_meta", "Alter Ego" , "testcharacter_AlterEgo" , "lze_character", "normal","high");add_meta_box("PersonnelFile_meta", "Personnel File" , "testcharacter_PersonnelFile" , "lze_character", "normal","high");add_meta_box("CastofMind_meta", "Cast of Mind" , "testcharacter_CastofMind" , "lze_character", "normal","high");add_meta_box("_meta", "" , "testcharacter_" , "lze_character", "normal","high");add_meta_box("NocheinTestfeld_meta", "Noch ein Testfeld" , "testcharacter_NocheinTestfeld" , "lze_character", "normal","high");}function lze_character_sex() {
    global $post;
    $custom         = get_post_custom($post->ID);
    $lze_male       = "";
    $lze_female     = "";
    $lze_character_sex  = $custom["lze_character_sex"][0];
    $lze_character_sex  = get_post_meta( $post->ID, 'lze_character_sex', true );
    if ($lze_character_sex == "Mann") {
        $lze_male = "checked";
    } else {
        $lze_female = "checked";
    }

    echo '<input type="radio" name="lze_character_sex" value="Mann" '. $lze_male . '> Männlich<br><input type="radio" name="lze_character_sex" value="Frau" '. $lze_female . '> Weiblich';};
                        
    function lze_character_ava() {
    global $post;
    $custom             = get_post_custom($post->ID);
    $lze_character_ava  = $custom["lze_character_ava"][0];
    $lze_character_ava  = get_post_meta( $post->ID, 'lze_character_ava', true );
    echo '<p>Bitte gib eine URL an.</p>';
    echo '<input class="lze_textfeld" name="lze_character_ava" value="'. $lze_character_ava .'"/>';}function testcharacter_PersonnelFile() {
                        $info = "oneline";
                        global $post;
                        $custom = get_post_custom($post->ID);
                        $PersonnelFile = $custom["PersonnelFile"][0];
                        if (isset($PersonnelFile)) { 
                            echo '<input class="lze_textfeld" name="PersonnelFile" value="' . $PersonnelFile . '"/><p class="description PersonnelFile">Schreibt einen kurzen Text (keine Stichpunkte) über die Daten eures Charakters - Name, Alter, Geburtstag, Herkunft, Familienstand, Beruf und was euch sonst noch wichtig erscheint.<p>';} else {
                            echo '<input class="lze_textfeld" name="PersonnelFile" value=""/><p class="description PersonnelFile">Schreibt einen kurzen Text (keine Stichpunkte) über die Daten eures Charakters - Name, Alter, Geburtstag, Herkunft, Familienstand, Beruf und was euch sonst noch wichtig erscheint.<p>';}
                        }function testcharacter_CastofMind() {
                        $info = "bigpart";
                        global $post;
                        $custom = get_post_custom($post->ID);
                        $wert = $custom["CastofMind"][0];
                        if (isset($wert)) {
                            echo '<textarea class="lze_textarea" name="CastofMind"/>'.$wert.'</textarea><p class="description CastofMind">Euer Charakter gehört einer Gruppe an? Oder ihr möchtet etwas über seine moralischen Grundlagen mitteilen? Und was hält er von den ganzen Dingen, die in der Welt passieren? <p>';} else {
                            echo '<textarea class="lze_textarea" name="CastofMind"/></textarea><p class="description CastofMind">Euer Charakter gehört einer Gruppe an? Oder ihr möchtet etwas über seine moralischen Grundlagen mitteilen? Und was hält er von den ganzen Dingen, die in der Welt passieren? <p>';} 
                        }function testcharacter_AlterEgo() {
                        $info = "bigpart";
                        global $post;
                        $custom = get_post_custom($post->ID);
                        $wert = $custom["AlterEgo"][0];
                        if (isset($wert)) {
                            echo '<textarea class="lze_textarea" name="AlterEgo"/>'.$wert.'</textarea><p class="description AlterEgo">Euer Charakter tritt in der Öffentlichkeit als jemand anderes auf? Wir bitten darum zu beachten, dass wir bei Mutationen als Team versuchen durch gewisse Reduzierung der Kräfte die Charaktere im Laufe der Korrekturrunden auf einem gemeinsamen, spielbaren Niveau zu halten. Wenn ihr euch Unsicher seid, könnt ihr gerne jederzeit vor dem Erstellen des Steckbriefs auf uns zukommen.

Für alle nicht-Helden: Auch Menschen, die nicht "super" sind, haben gewisse Talente oder Fähigkeiten, die sie ausmachen - und sei es nur, dass sie die weltbeste Sauce Bolognese kochen oder beim Dartspielen grundsätzlich den Barkeeper am anderen Ende des Raumes treffen können. Auch was eure Charaktere einschränkt oder was sie so gar nicht können, sollte hier Platz finden, deswegen ist dieser Punkt verpflichtend.<p>';} else {
                            echo '<textarea class="lze_textarea" name="AlterEgo"/></textarea><p class="description AlterEgo">Euer Charakter tritt in der Öffentlichkeit als jemand anderes auf? Wir bitten darum zu beachten, dass wir bei Mutationen als Team versuchen durch gewisse Reduzierung der Kräfte die Charaktere im Laufe der Korrekturrunden auf einem gemeinsamen, spielbaren Niveau zu halten. Wenn ihr euch Unsicher seid, könnt ihr gerne jederzeit vor dem Erstellen des Steckbriefs auf uns zukommen.

Für alle nicht-Helden: Auch Menschen, die nicht "super" sind, haben gewisse Talente oder Fähigkeiten, die sie ausmachen - und sei es nur, dass sie die weltbeste Sauce Bolognese kochen oder beim Dartspielen grundsätzlich den Barkeeper am anderen Ende des Raumes treffen können. Auch was eure Charaktere einschränkt oder was sie so gar nicht können, sollte hier Platz finden, deswegen ist dieser Punkt verpflichtend.<p>';} 
                        }function testcharacter_NocheinTestfeld() {
                        $info = "oneline";
                        global $post;
                        $custom = get_post_custom($post->ID);
                        $NocheinTestfeld = $custom["NocheinTestfeld"][0];
                        if (isset($NocheinTestfeld)) { 
                            echo '<input class="lze_textfeld" name="NocheinTestfeld" value="' . $NocheinTestfeld . '"/><p class="description NocheinTestfeld">Lorem Ipsum dolor sit amet.<p>';} else {
                            echo '<input class="lze_textfeld" name="NocheinTestfeld" value=""/><p class="description NocheinTestfeld">Lorem Ipsum dolor sit amet.<p>';}
                        }function lze_save_character() {global $post; if (isset($_POST["lze_character_sex"])) {update_post_meta($post->ID, "lze_character_sex", $_POST["lze_character_sex"]);} if (isset($_POST["lze_character_ava"])) {update_post_meta($post->ID, "lze_character_ava", $_POST["lze_character_ava"]);}if (isset($_POST["PersonnelFile"])) {update_post_meta($post->ID, "PersonnelFile", $_POST["PersonnelFile"]);}if (isset($_POST["CastofMind"])) {update_post_meta($post->ID, "CastofMind", $_POST["CastofMind"]);}if (isset($_POST["AlterEgo"])) {update_post_meta($post->ID, "AlterEgo", $_POST["AlterEgo"]);}if (isset($_POST[""])) {update_post_meta($post->ID, "", $_POST[""]);}if (isset($_POST["NocheinTestfeld"])) {update_post_meta($post->ID, "NocheinTestfeld", $_POST["NocheinTestfeld"]);}} ?>