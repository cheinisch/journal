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
            <?php 
            $userid = get_userfromhash();
            $posts = getAllBlogPosts($userid['id']);
            $currentDate = '';
            
            ?>
            <?php if (empty($posts)): ?>
                <p>Keine Blogposts gefunden.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                 <?php
                    $postDate = date('d.m.Y', strtotime($post['date']));
                    if ($postDate !== $currentDate) {
                        $currentDate = $postDate;
                        echo "<h3>" . htmlspecialchars($currentDate) . "</h3>";
                    }
                    
                ?>
                    <article class="uk-article">
                        <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                        <!--<p class="uk-article-meta">
                            Geschrieben am <?php echo htmlspecialchars($post['date']); ?> von <?php echo htmlspecialchars($post['author']); ?>
                            <?php if (!empty($post['location'])): ?>
                                | Ort: <?php echo htmlspecialchars($post['location']); ?>
                            <?php endif; ?>
                            <?php if (!empty($post['tags'])): ?>
                                | Tags: <?php echo implode(', ', array_map('htmlspecialchars', $post['tags'])); ?>
                            <?php endif; ?>
                        </p>-->
                        <div>
                            <?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>...
                        </div>
                        <a href="index.php?post=<?php echo $post['id']; ?>" class="uk-button uk-button-text">Weiterlesen</a>
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
