<?php

# Update Datei für Journal CMS
# Datei wird geladen und entpackt

load_update();


# Hier kommen die Funktionen


function load_update()
{
    try {
        $repoOwner = 'cheinisch'; // Ersetze 'owner' durch den Besitzer des Repositories
        $repoName = 'journal'; // Ersetze 'repository' durch den Namen des Repositories
        updateFromGitHub($repoOwner, $repoName);
    } catch (Exception $e) {
        echo "Fehler: " . htmlspecialchars($e->getMessage());
    }
}

/**
 * Lädt die neueste Release-ZIP-Datei von einem GitHub-Repository herunter und entpackt sie ins Root-Verzeichnis.
 *
 * @param string $repoOwner Der Besitzer des GitHub-Repositories.
 * @param string $repoName Der Name des GitHub-Repositories.
 * @throws Exception Wenn ein Fehler auftritt.
 */
function updateFromGitHub($repoOwner, $repoName) {

    // 1. Konfig auslesen

    $config_old = require('storage/config.php');
    $dbHost = $config_old['db']['host'];
    $dbName = $config_old['db']['name'];
    $dbUser = $config_old['db']['user'];
    $dbPass = $config_old['db']['pass'];


    // 2. Neueste Release-URL von GitHub abrufen
    $url = "https://api.github.com/repos/$repoOwner/$repoName/releases/latest";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // GitHub API erfordert einen User-Agent Header
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        throw new Exception("Fehler beim Abrufen der GitHub-Version.");
    }

    $data = json_decode($response, true);
    if (!isset($data['zipball_url'])) {
        throw new Exception("Die ZIP-URL konnte nicht abgerufen werden.");
    }

    $zipUrl = $data['zipball_url'];
    echo $zipUrl;
    $version = $data['tag_name'];

   

    // 3. ZIP-Datei herunterladen
    $zipFile = sys_get_temp_dir() . "/{$version}.zip";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $zipUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Weiterleitungen folgen
    $fp = fopen($zipFile, 'w+');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_exec($ch);
    fclose($fp);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!file_exists($zipFile) || filesize($zipFile) === 0) {
        throw new Exception("Fehler beim Herunterladen der ZIP-Datei.". $status);
    }

    // 4. ZIP-Datei entpacken
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === true) {
        $rootPath = realpath(__DIR__);

        // Dateien im Root-Verzeichnis löschen, außer "storage" und "config.php" im "storage"-Verzeichnis
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileInfo) {
            $filePath = $fileInfo->getRealPath();
            // Skip "storage" directory and "config.php" inside "storage"
            if ($fileInfo->getFilename() === 'storage' || strpos($filePath, 'storage/config.php') !== false) {
                continue;
            }

            $todo = ($fileInfo->isDir() ? 'rmdir' : 'unlink');
            $todo($filePath);
        }

        // ZIP-Datei entpacken, obersten Ordner ignorieren
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);

            // Prüfen, ob der Eintrag den obersten Ordner enthält und diesen entfernen
            $entryParts = explode('/', $entry);
            array_shift($entryParts); // Entferne den obersten Ordner
            $cleanedEntry = implode('/', $entryParts);

            if (empty($cleanedEntry)) {
                continue;
            }

            $entryPath = $rootPath . DIRECTORY_SEPARATOR . $cleanedEntry;

            // Prüfen, ob es sich um ein Verzeichnis handelt
            if (substr($entry, -1) == '/') {
                @mkdir($entryPath, 0755, true);
            } else {
                $dir = dirname($entryPath);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                if (!copy("zip://{$zipFile}#{$entry}", $entryPath)) {
                    throw new Exception("Fehler beim Kopieren von {$entry} nach {$entryPath}");
                }
            }
        }

        $zip->close();
    } else {
        throw new Exception("Fehler beim Entpacken der ZIP-Datei.");
    }

    // 5. Config neu schreiben

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
    file_put_contents('storage/config.php', $configContent);

    // 6. Temporäre ZIP-Datei löschen
    unlink($zipFile);

    include("system/function/db_updater.php");

    #header("Location: /system/function/db_updater.php");
}

?>
