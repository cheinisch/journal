<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_name']); ?></title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.15.14/css/uikit.min.css" />
    <link rel="stylesheet" href="systm/gui/template/style.css" />

    <!-- UIkit JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.15.14/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.15.14/js/uikit-icons.min.js"></script>
</head>

<body>

    <!-- Menüleiste -->
    <div class="uk-container uk-margin-top">
        <nav class="uk-navbar-container" uk-navbar>
            <div class="uk-navbar-left">
                <a class="uk-navbar-item uk-logo" href="index.php"><?php echo htmlspecialchars($settings['site_name']); ?></a>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li><a>Hello <?php echo get_currentusername(); ?></a></li>
                    
                    <li><a href="index.php?new-entry"><?php echo $langArray['write']?></a></li>
                    
                    <li><a href="index.php?user" class="uk-icon-link" uk-icon="user"></a></li>
                    <?php if(enable_admin()) { echo '<li><a href="index.php?settings" class="uk-icon-link" uk-icon="settings"></a></li>'; } ?>
                    <li><a href="index.php?login=logout" class="uk-icon-link" uk-icon="sign-out"></a></li>
                </ul>
            </div>
        </nav>
    </div>