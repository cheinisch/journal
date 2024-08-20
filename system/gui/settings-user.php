<?php
require_once("system/function/function.php");
// check active Session
if(!check_session())
{
    header('location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = get_userfromhash(); // Angenommen, die Benutzer-ID wird in der Session gespeichert
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $oldPassword = $_POST['old_password'];
    
    $message = updateUserSettings($userId['id'], $name, $email, $password, $oldPassword);
} elseif (isset($_GET['export']) && $_GET['export'] == 'xml') {
    exportPostsAsXML($userId['id']);
}

// Benutzerinformationen laden
$userIdDB = get_userfromhash();
$userId = $userIdDB['id'];
$db = getDatabaseConnection();
$stmt = $db->prepare("SELECT name, email FROM users WHERE id = :userId");
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2>Benutzereinstellungen</h2>

<?php if (isset($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST" action="index.php?user">
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
    </div>
    <div>
        <label for="email">E-Mail:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    <div>
        <label for="old_password">Altes Passwort:</label>
        <input type="password" name="old_password" id="old_password" required>
    </div>
    <div>
        <label for="password">Neues Passwort (optional):</label>
        <input type="password" name="password" id="password">
    </div>
    <div>
        <label for="confirm_password">Neues Passwort bestätigen:</label>
        <input type="password" name="confirm_password" id="confirm_password">
    </div>
    <div>
        <button type="submit">Einstellungen speichern</button>
    </div>
</form>

<h2>Beiträge exportieren</h2>
<a href="index.php?user&export=xml">Beiträge als XML exportieren</a>

</body>
</html>