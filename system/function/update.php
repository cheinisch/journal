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
    // 1. Neueste Release-URL von GitHub abrufen
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
    $version = $data['tag_name'];

    // 2. ZIP-Datei herunterladen
    $zipFile = sys_get_temp_dir() . "/{$version}.zip";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $zipUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $fp = fopen($zipFile, 'w+');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_exec($ch);
    fclose($fp);
    curl_close($ch);

    if (!file_exists($zipFile) || filesize($zipFile) === 0) {
        throw new Exception("Fehler beim Herunterladen der ZIP-Datei.");
    }

    // 3. ZIP-Datei entpacken
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === true) {
        $rootPath = realpath(__DIR__);

        // Dateien im Root-Verzeichnis löschen
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileInfo) {
            $todo = ($fileInfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileInfo->getRealPath());
        }

        // ZIP-Datei entpacken
        $zip->extractTo($rootPath);
        $zip->close();
    } else {
        throw new Exception("Fehler beim Entpacken der ZIP-Datei.");
    }

    // 4. Temporäre ZIP-Datei löschen
    unlink($zipFile);

    echo "Update erfolgreich!";
}


?>