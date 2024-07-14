<?php
require_once("system/function/function.php");
// check active Session
if(!check_session())
{
    header('location: index.php');
}

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
                    <li><a>Hello <?php echo get_currentusername(); ?></a></li>
                    <li><a href="" class="uk-icon-link" uk-icon="settings"></a></li>
                    <li><a href="">Write new one</a></li>
                    <li><a href="" class="uk-icon-link" uk-icon="user"></a></li>
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
                
            <?php if (empty($posts)): ?>
                <p>Keine Blogposts gefunden.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <article class="uk-article">
                        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                        <p class="uk-article-meta">
                            Geschrieben am <?php echo htmlspecialchars($post['date']); ?> von <?php echo htmlspecialchars($post['author']); ?>
                            <?php if (!empty($post['location'])): ?>
                                | Ort: <?php echo htmlspecialchars($post['location']); ?>
                            <?php endif; ?>
                            <?php if (!empty($post['tags'])): ?>
                                | Tags: <?php echo implode(', ', array_map('htmlspecialchars', $post['tags'])); ?>
                            <?php endif; ?>
                        </p>
                        <div>
                            <?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>...
                        </div>
                        <a href="post.php?id=<?php echo $post['id']; ?>" class="uk-button uk-button-text">Weiterlesen</a>
                    </article>
                    <hr class="uk-divider-icon">
                <?php endforeach; ?>
            <?php endif; ?>
                <!-- Pagination -->
                <ul class="uk-pagination" uk-margin>
                    <li><a href="?page=<?php echo max(1, $currentPage - 1); ?>"><span uk-pagination-previous></span></a></li>
                    <li><a href="?page=<?php echo $currentPage + 1; ?>"><span uk-pagination-next></span></a></li>
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
