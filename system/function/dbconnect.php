<?php

// Funktionen zum Arbeiten mit MySQL

/**
 * Stellt eine Verbindung zur MySQL-Datenbank her.
 *
 * @return PDO Die PDO-Instanz der Datenbankverbindung.
 */
function getDatabaseConnection() {
    $host = 'localhost'; // Hostname oder IP-Adresse
    $dbname = 'journal';    // Datenbankname
    $username = 'root';  // Benutzername
    $password = '';      // Passwort

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
    }
}
?>