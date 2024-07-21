<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog mit Sidebar und Pagination</title>
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
                <a class="uk-navbar-item uk-logo" href="#">Meine Seite</a>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li><a>Hello <?php echo get_currentusername(); ?></a></li>
                    
                    <li><a href="index.php?new-entry">Write new one</a></li>
                    
                    <li><a href="index.php?user" class="uk-icon-link" uk-icon="user"></a></li>
                    <?php if(enable_admin()) { echo '<li><a href="index.php?settings" class="uk-icon-link" uk-icon="settings"></a></li>'; } ?>
                    <li><a href="index.php?login=logout" class="uk-icon-link" uk-icon="sign-out"></a></li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Zweispaltiges Layout -->
    <div class="uk-container uk-margin-top">
        <div uk-grid>
            <!-- Blogposts -->
            <div class="uk-width-2-3@s uk-width-1-1">
            <form class="uk-grid-small" uk-grid action="system/function/save.php" method="get" >

                    <legend class="uk-legend">Neuer Eintrag</legend>

                    <div class="uk-width-1-1">
                        <input id="headline" name="headline" class="uk-input" type="text" placeholder="Headline" aria-label="Input">
                    </div>

                    <div class="uk-width-1-2@s">
                        <label for="date" class="uk-form-label">Datum auswählen:</label>
                        <div class="uk-form-controls">
                            <input id="date" name="date" class="uk-input" type="date" value="<?php echo date("Y-m-d");?>">
                        </div>
                    </div>
                    <div class="uk-width-1-4@s">
                        <label for="time" class="uk-form-label">Uhrzeit auswählen:</label>
                        <div class="uk-form-controls">
                            <input id="time" name="time" class="uk-input" type="time" value="<?php echo date("H:i");?>">
                        </div>
                    </div>


                    <div class="uk-width-1-1">
                        <textarea id="text" name="text" class="uk-textarea" rows="5" placeholder="Textarea" aria-label="Textarea"></textarea>
                    </div>

                    <div class="uk-width-1-1">
                    <button class="uk-button uk-button-primary" type="submit" value="submit">Save</button>
                    </div>

            </form>              
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

    <script>
        UIkit.util.ready(function () {
            UIkit.datepicker('#date', {
                format: 'YYYY-MM-DD',
                i18n: {
                    months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                    weekdays: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
                    weekdaysShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa']
                }
            });
        });
    </script>

</body>

</html>
