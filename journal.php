<?php

?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog mit Sidebar und Pagination</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.15.14/css/uikit.min.css" />

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
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Über uns</a></li>
                    <li><a href="#">Kontakt</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Zweispaltiges Layout -->
    <div class="uk-container uk-margin-top">
        <div uk-grid>
            <!-- Blogposts -->
            <div class="uk-width-2-3@s uk-width-1-1">
                <article class="uk-article">
                    <h1 class="uk-article-title">Erster Blogpost</h1>
                    <p class="uk-article-meta">Geschrieben am 21. Juni 2024 von Autor</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                </article>

                <hr class="uk-divider-icon">

                <article class="uk-article">
                    <h1 class="uk-article-title">Zweiter Blogpost</h1>
                    <p class="uk-article-meta">Geschrieben am 22. Juni 2024 von Autor</p>
                    <p>Curabitur blandit tempus porttitor. Maecenas faucibus mollis interdum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </article>

                <hr class="uk-divider-icon">

                <article class="uk-article">
                    <h1 class="uk-article-title">Dritter Blogpost</h1>
                    <p class="uk-article-meta">Geschrieben am 23. Juni 2024 von Autor</p>
                    <p>Aenean lacinia bibendum nulla sed consectetur. Cras mattis consectetur purus sit amet fermentum.</p>
                </article>

                <hr class="uk-divider-icon">

                <!-- Pagination -->
                <ul class="uk-pagination" uk-margin>
                    <li><a href="#"><span uk-pagination-previous></span></a></li>
                    <li><a href="#">1</a></li>
                    <li class="uk-active"><span>2</span></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#"><span uk-pagination-next></span></a></li>
                </ul>
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

</body>

</html>
