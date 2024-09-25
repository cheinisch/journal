<?php
require_once("system/function/function.php");
// check active Session
if(!check_session())
{
    header('location: index.php');
}

$settings = getSettings();
$langArray = require 'system/locale/'.$settings['language'].'.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = get_userfromhash(); // Angenommen, die Benutzer-ID wird in der Session gespeichert
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $oldPassword = $_POST['old_password'];
    
    $message = updateUserSettings($userId['id'], $name, $email, $password, $oldPassword);
    destroy_session();
    create_session($name);
} elseif (isset($_GET['export']) && $_GET['export'] == 'xml') {
    $userId = get_userfromhash(); // Angenommen, die Benutzer-ID wird in der Session gespeichert
    exportPostsAsXML($userId['id']);
}

// Benutzerinformationen laden
$userIdDB = get_userfromhash();
$user = getUserData($userIdDB['id']);
?>


<?php 

    include('template/head.php');

    $users = getAllUsers();

?>

    <!-- Zweispaltiges Layout -->
    <div class="uk-container uk-margin-top">
        <div uk-grid>
            <!-- Blogposts -->
            <div class="uk-width-2-3@s uk-width-1-1">
            <h2><?php echo $langArray['user_settings']; ?></h2>

<?php if (isset($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<form class="uk-form-stacked" method="POST" action="index.php?user">
    <div class="uk-margin">
        <label for="name"><?php echo $langArray['user_settings_name']; ?>:</label>
        <input class="uk-input" type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    <div class="uk-margin">
        <label for="email"><?php echo $langArray['user_settings_mail']; ?>:</label>
        <input class="uk-input" type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>

    <h3><?php echo $langArray['user_settings_pwchange']; ?></h3>
    <hr>

    <div class="uk-margin">
        <label for="old_password"><?php echo $langArray['user_settings_oldpw']; ?>:</label>
        <input class="uk-input" type="password" name="old_password" id="old_password" required>
    </div>
    <div class="uk-margin">
        <label for="password"><?php echo $langArray['user_settings_newpw']; ?>:</label>
        <input class="uk-input" type="password" name="password" id="password">
    </div>
    <div class="uk-margin">
        <label for="confirm_password"><?php echo $langArray['user_settings_newpw2']; ?>:</label>
        <input class="uk-input" type="password" name="confirm_password" id="confirm_password">
    </div>
    <div class="uk-margin">
        <button class="uk-button uk-button-primary" type="submit"><?php echo $langArray['user_settings_save']; ?></button>
    </div>
</form>

<h2><?php echo $langArray['export_posts']; ?></h2>
<a href="index.php?user&export=xml"><?php echo $langArray['export_file']; ?></a>
<h2><?php echo $langArray['import_posts']; ?></h2>
<form action="api/import.php" method="post" enctype="multipart/form-data">
        <div class="uk-margin" uk-margin>
            <div uk-form-custom="target: true">
                <input type="file" name="xmlFile" accept=".xml" aria-label="Custom controls">
                <input class="uk-input uk-form-width-medium" type="text" placeholder="Datei auswählen" aria-label="Custom controls" disabled>
            </div>
            <button class="uk-button uk-button-primary" type="submit"><?php echo $langArray['import_file']; ?></button>
        </div>
    </form>
</div>
</div>
</div>
</body>
</html>
