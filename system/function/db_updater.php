<?php

# Updatefile for the database

db_update();


function db_update()
{
    update_db_v1();
    echo "Update erfolgreich!";
    header("Location: ./../../index.php?settings");
}

function check_current_db_version()
{
    return 0;
}


function update_db_v1()
{

    $config = require('./../../storage/config.php');

    $dbHost = $config['db']['host'];
    $dbName = $config['db']['name'];
    $dbUser = $config['db']['user'];
    $dbPass = $config['db']['pass'];

    $host = $dbHost; // Hostname oder IP-Adresse
    $dbname = $dbName;    // Datenbankname
    $username = $dbUser;  // Benutzername
    $password = $dbPass;      // Passwort

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
                ALTER TABLE `settings`
                ADD COLUMN `dbversion` INT DEFAULT 1,
                ADD COLUMN `update` ENUM('stable', 'dev') DEFAULT 'stable';

            ");
        }catch (PDOException $e){
            
        }
    }

}

?>