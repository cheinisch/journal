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
        $settings = getSettings();
        /*
        * Load Language Files
        */
        $langArray = require 'system/locale/'.$settings['language'].'.php';
        echo '<div class="uk-alert-success" uk-alert><p>'.$langArray['success'].'</p></div>';
    } else {
        echo '<div class="uk-alert-danger" uk-alert><p>'.$langArray['error'].'</p></div>';
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
                <ul uk-tab>
                    <li><a href="#">Settings</a></li>
                    <li><a href="#">Users</a></li>
                </ul>

                <ul class="uk-switcher uk-margin">
                    <li>
                    <!-- Settings Tab -->
                        <form class="uk-form-stacked" method="post">
                            <div class="uk-margin">
                                <label class="uk-form-label" for="site_name"><?php echo $langArray['sitename']?></label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="site_name" name="site_name" type="text" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="language"><?php echo $langArray['language']?></label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" id="language" name="language" required>
                                        <option value="en-EN" <?php echo $settings['language'] === 'en-EN' ? 'selected' : ''; ?>>English</option>
                                        <option value="de-DE" <?php echo $settings['language'] === 'de-DE' ? 'selected' : ''; ?>>Deutsch</option>
                                        <!-- Weitere Sprachen hier hinzufügen -->
                                    </select>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="template"><?php echo $langArray['template']?></label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" id="template" name="template" required>
                                        <option value="default" <?php echo $settings['template'] === 'default' ? 'selected' : ''; ?>>Default</option>
                                        <option value="custom-template" <?php echo $settings['template'] === 'custom-template' ? 'selected' : ''; ?>>Custom Template</option>
                                        <!-- Weitere Templates hier hinzufügen -->
                                    </select>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <button class="uk-button uk-button-primary" type="submit"><?php echo $langArray['save']?></button>
                            </div>
                        </form>
                        <div class="uk-grid-small" uk-grid>
                            <div class="uk-width-1-3@s">
                                Version: <?php echo get_version(); ?><br />
                                Version from Git: <?php echo get_versionfromgit(); ?>
                            </div>
                            <div class="uk-width-1-2@s">
                                <?php 
                                    if(check_update()) { 
                                        echo "<button onclick=\"location.href='index.php?update'\" class=\"uk-button uk-button-primary\" type=\"button\">Update verfügbar</button>";
                                    }
                                ?>
                            </div>
                        </div>
                    </li>
                    <li>
                        <!-- Users Tab -->
                        <h2>Users</h2>
                        <table class="uk-table uk-table-divider">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>User Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $users = getAllUsers(); ?>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['userrole']) ?></td>
                                    <td>
                                        <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="edit_user_id" value="<?= $user['id'] ?>">
                                            <button class="uk-button uk-button" type="submit">Edit</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                            <button class="uk-button uk-button-danger" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <h3>Add New User</h3>
                        <form action="" method="post" class="uk-form-stacked">
                            <div class="uk-margin">
                                <label class="uk-form-label" for="username">Username</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="username" type="text" name="user[username]" required>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="email">Email</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="email" type="email" name="user[email]" required>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="password">Password</label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="password" type="password" name="user[password]" required>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="userrole">User Role</label>
                                <div class="uk-form-controls">
                                    <select class="uk-select" id="userrole" name="user[userrole]">
                                        <option value="admin">Admin</option>
                                        <option value="editor">Editor</option>
                                        <option value="viewer">Viewer</option>
                                        <!-- Weitere Rollen hier hinzufügen -->
                                    </select>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <button class="uk-button uk-button-primary" type="submit">Add User</button>
                            </div>
                        </form>
                    </li>
                </ul>
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