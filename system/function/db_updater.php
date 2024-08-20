<?php

# Updatefile for the database

db_update();


function db_update()
{

    $config = require('storage/config.php');

    update_db_v1($config);
    ensureUserTableIsUpToDate($config);
    echo "Update erfolgreich!";
    header("Location: index.php?settings");
}

function check_current_db_version()
{
    return 0;
}


function update_db_v1($config)
{

    

    $dbHost = $config['db']['host'];
    $dbName = $config['db']['name'];
    $dbUser = $config['db']['user'];
    $dbPass = $config['db']['pass'];

    $host = $dbHost; // Hostname oder IP-Adresse
    $dbname = $dbName;    // Datenbankname
    $username = $dbUser;  // Benutzername
    $password = $dbPass;      // Passwort
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
                ALTER TABLE `settings`
                ADD COLUMN `dbversion` INT DEFAULT 1,
                ADD COLUMN `release` ENUM('stable', 'dev') DEFAULT 'stable';

            ");
        }catch (PDOException $e){
            
        }

}

// Aufruf der Funktion zur Überprüfung und Anpassung der Tabelle
function ensureUserTableIsUpToDate($config)
{

    $dbHost = $config['db']['host'];
    $dbName = $config['db']['name'];
    $dbUser = $config['db']['user'];
    $dbPass = $config['db']['pass'];

    try {
        // Datenbankverbindung herstellen
        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
        $db = new PDO($dsn, $dbUser, $dbPass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prüfe, ob die Spalte `reset_token` in der Tabelle `users` existiert
        $stmt = $db->prepare("SHOW COLUMNS FROM users LIKE 'reset_token'");
        $stmt->execute();
        $columnExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$columnExists) {
            // Spalte `reset_token` existiert nicht, also wird die Tabelle aktualisiert
            $sql = "ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL";
            $db->exec($sql);
            echo "Spalte `reset_token` hinzugefügt.\n";
        }

        // Prüfe, ob die Spalte `reset_token_expiry` in der Tabelle `users` existiert
        $stmt = $db->prepare("SHOW COLUMNS FROM users LIKE 'reset_token_expiry'");
        $stmt->execute();
        $columnExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$columnExists) {
            // Spalte `reset_token_expiry` existiert nicht, also wird die Tabelle aktualisiert
            $sql = "ALTER TABLE users ADD COLUMN reset_token_expiry DATETIME NULL";
            $db->exec($sql);
            echo "Spalte `reset_token_expiry` hinzugefügt.\n";
        }

        // Optional: Eine Meldung ausgeben, wenn die Tabelle bereits aktuell ist
        if ($columnExists) {
            echo "Die Tabelle `users` ist bereits aktuell.\n";
        }
    } catch (PDOException $e) {
        echo "Datenbankfehler: " . $e->getMessage();
    }
}


?>