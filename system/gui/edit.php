<?php 

    include('template/head.php');

?>

<?php

if(isset($_GET['new-entry'])) {

    ?>

    <!-- Zweispaltiges Layout -->
    <div class="uk-container uk-margin-top">
        <div uk-grid>
            <!-- Blogposts -->
            <div class="uk-width-2-3@s uk-width-1-1">
            <form class="uk-grid-small" uk-grid action="system/function/save.php?new" method="post" >

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

<?php

    }else if(isset($_GET['edit']))
    {

        $postID = $_GET['edit'];

        $post = readBlogPost($postID);

        $date = date("Y-m-d", (strtotime($post['date'])));
        $time = date("H:i", (strtotime($post['date'])));
        
        ?>
        <div class="uk-container uk-margin-top">
        <div uk-grid>
            <!-- Blogposts -->
            <div class="uk-width-2-3@s uk-width-1-1">
            <form class="uk-grid-small" uk-grid action="system/function/save.php?update=<?php echo $postID; ?>" method="post" >

                    <legend class="uk-legend">Update vorhanden Eintrag</legend>

                    <div class="uk-width-1-1">
                        <input id="headline" name="headline" class="uk-input" type="text" placeholder="Headline" aria-label="Input" value="<?php echo $post['title']?>">
                    </div>

                    <div class="uk-width-1-2@s">
                        <label for="date" class="uk-form-label">Datum auswählen:</label>
                        <div class="uk-form-controls">
                            <input id="date" name="date" class="uk-input" type="date" value="<?php echo $date;?>">
                        </div>
                    </div>
                    <div class="uk-width-1-4@s">
                        <label for="time" class="uk-form-label">Uhrzeit auswählen:</label>
                        <div class="uk-form-controls">
                            <input id="time" name="time" class="uk-input" type="time" value="<?php echo $time;?>">
                        </div>
                    </div>


                    <div class="uk-width-1-1">
                        <textarea id="text" name="text" class="uk-textarea" rows="5" placeholder="Textarea" aria-label="Textarea"><?php echo $post['content']?></textarea>
                    </div>

                    <div class="uk-width-1-6@s">
                    <button class="uk-button uk-button-primary" type="submit" value="submit">Update</button>
                    </div>
                    <div class="uk-width-1-6@s">
                    <button onclick="location.href='index.php?post=<?php echo $postID; ?>'" class="uk-button uk-button-primary" type="button">Cancel</button>
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

<?php

    }

    ?>