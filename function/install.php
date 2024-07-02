<?php
// Installationsskript für Blog

// Variablen initialisieren
$error = '';
$success = '';
$dbHost = '';
$dbName = '';
$dbUser = '';
$dbPass = '';
$username = '';
$email = '';
$password = '';

// Prüfen, ob das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['dbHost']);
    $dbName = trim($_POST['dbName']);
    $dbUser = trim($_POST['dbUser']);
    $dbPass = trim($_POST['dbPass']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if ($dbHost && $dbName && $dbUser && $username && $email && $password) {
        // Datenbankverbindung testen und Tabellen erstellen
        try {
            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

            // Tabellen erstellen
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) NOT NULL UNIQUE,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    userrole ENUM('admin', 'editor', 'user') NOT NULL
                );
                
                CREATE TABLE IF NOT EXISTS blog_posts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    content TEXT NOT NULL,
                    author_id INT NOT NULL,
                    date DATE NOT NULL,
                    location VARCHAR(255),
                    tags JSON,
                    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
                );
            ");

            // Admin-Benutzer erstellen
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, userrole) VALUES (:username, :email, :password, 'admin')");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword
            ]);

            // Konfigurationsdatei speichern
            $configContent = "<?php
            return [
                'db' => [
                    'host' => '$dbHost',
                    'name' => '$dbName',
                    'user' => '$dbUser',
                    'pass' => '$dbPass'
                ]
            ];
            ?>";
            file_put_contents('config.php', $configContent);

            $success = "Installation erfolgreich abgeschlossen!";
        } catch (PDOException $e) {
            $error = "Fehler bei der Installation: " . $e->getMessage();
        }
    } else {
        $error = 'Bitte füllen Sie alle Felder aus.';
    }
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.15.20/dist/css/uikit.min.css" />
</head>
<body>

<div class="uk-container uk-margin-large-top">
    <h1>Diary CMS Installation</h1>
    
    <?php if ($error): ?>
        <div class="uk-alert-danger" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php elseif ($success): ?>
        <div class="uk-alert-success" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>
    
    <form class="uk-form-stacked uk-column-1-2" method="post">
        <fieldset class="uk-fieldset">
            <legend class="uk-legend">Datenbankverbindung</legend>

            <div class="uk-margin">
                <label class="uk-form-label" for="dbHost">Datenbank-Host</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="dbHost" name="dbHost" type="text" value="<?php echo htmlspecialchars($dbHost); ?>" required>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="dbName">Datenbankname</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="dbName" name="dbName" type="text" value="<?php echo htmlspecialchars($dbName); ?>" required>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="dbUser">Datenbank-Benutzer</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="dbUser" name="dbUser" type="text" value="<?php echo htmlspecialchars($dbUser); ?>" required>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="dbPass">Datenbank-Passwort</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="dbPass" name="dbPass" type="password" value="<?php echo htmlspecialchars($dbPass); ?>">
                </div>
            </div>
        </fieldset>
        
        <fieldset class="uk-fieldset">
            <legend class="uk-legend">Admin-Benutzer</legend>

            <div class="uk-margin">
                <label class="uk-form-label" for="username">Benutzername</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="username" name="username" type="text" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="email">E-Mail</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="email" name="email" type="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="password">Passwort</label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="password" name="password" type="password" required>
                </div>
            </div>
        </fieldset>

        <button class="uk-button uk-button-primary" type="submit">Installieren</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/uikit@3.15.20/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.15.20/dist/js/uikit-icons.min.js"></script>
</body>
</html>