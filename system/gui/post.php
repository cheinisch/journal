<?php
require_once("system/function/function.php");
// check active Session
if(!check_session())
{
    header('location: index.php');
}

$postID = $_GET['post'];
$post = readBlogPost($postID);

?>

<?php 

    include('template/head.php');

?>

    <!-- Zweispaltiges Layout -->
    <div class="uk-container uk-margin-top">
        <div uk-grid>
            <!-- Blogposts -->
            <div class="uk-width-2-3@s uk-width-1-1">

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
                        <?php echo $post['content']; ?>
                    </div>
                    <a href="index.php?edit=<?php echo $post['id']; ?>" class="uk-button uk-button-text">Bearbeiten</a>
                </article>
                <hr class="uk-divider-icon">

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