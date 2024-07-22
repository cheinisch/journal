<?php
require_once("system/function/function.php");
// check active Session
if(!check_session())
{
    header('location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteName = $_POST['site_name'];
    $language = $_POST['language'];
    $template = $_POST['template'];

    if (updateSettings($siteName, $language, $template)) {
        echo '<div class="uk-alert-success" uk-alert><p>Einstellungen erfolgreich aktualisiert.</p></div>';
    } else {
        echo '<div class="uk-alert-danger" uk-alert><p>Fehler beim Aktualisieren der Einstellungen.</p></div>';
    }
}

?>

<?php 

    include('template/head.php');

?>

    <!-- Zweispaltiges Layout -->
    <div class="uk-container uk-margin-top">
        <div uk-grid>
            <!-- Blogposts -->
            <div class="uk-width-2-3@s uk-width-1-1">

            <form class="uk-form-stacked" method="post">
            <div class="uk-margin">
                <label class="uk-form-label" for="site_name">Seitenname</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="site_name" name="site_name" type="text" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="language">Sprache</label>
                <div class="uk-form-controls">
                    <select class="uk-select" id="language" name="language" required>
                        <option value="en-EN" <?php echo $settings['language'] === 'en-EN' ? 'selected' : ''; ?>>Englisch</option>
                        <option value="de-DE" <?php echo $settings['language'] === 'de-DE' ? 'selected' : ''; ?>>Deutsch</option>
                        <!-- Weitere Sprachen hier hinzufügen -->
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="template">Template</label>
                <div class="uk-form-controls">
                    <select class="uk-select" id="template" name="template" required>
                        <option value="default" <?php echo $settings['template'] === 'default' ? 'selected' : ''; ?>>Default</option>
                        <option value="custom-template" <?php echo $settings['template'] === 'custom-template' ? 'selected' : ''; ?>>Custom Template</option>
                        <!-- Weitere Templates hier hinzufügen -->
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <button class="uk-button uk-button-primary" type="submit">Einstellungen speichern</button>
            </div>
        </form>
        Version: <?php echo get_version(); ?>, <?php get_versionfromgit(); ?>

            </div>

            <!-- Sidebar mit Kalender -->
            <div class="uk-width-1-3@s uk-width-1-1">
                <div class="uk-card uk-card-default uk-card-body">
                    <h3 class="uk-card-title">Kalender</h3>
                    <div uk-calendar></div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>