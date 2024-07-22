<?php
require_once("system/function/function.php");
// check active Session
if(!check_session())
{
    header('location: index.php');
}

?>

<?php 

    include('template/head.php');

?>

    <!-- Zweispaltiges Layout -->
    <div class="uk-container uk-margin-top">
        <div uk-grid>
            <!-- Blogposts -->
            <div class="uk-width-2-3@s uk-width-1-1">
            <?php $posts = getAllBlogPosts(1); ?>
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
                            <?php echo $post['content']; ?>
                        </div>
                    </article>
                    <hr class="uk-divider-icon">
                <?php endforeach; ?>
            <?php endif; ?>
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