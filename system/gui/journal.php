<?php
require_once("system/function/function.php");
// check active Session
if(!check_session())
{
    header('location: index.php');
}

    // Parameter
    $postsPerPage = 10;
    $userId = get_userfromhash();
    $currentPage = isset($_GET['journal']) ? (int)$_GET['journal'] : 1;

    if($currentPage == 0)
    {
        $currentPage = 1;
    }

    // Gesamtanzahl der Posts
    $totalPosts = getTotalBlogPostsByUser($userId['id']);

    // Blogposts für die aktuelle Seite
    $posts = getBlogPostsByPage($currentPage, $postsPerPage, $userId);

    // Paginierung
    $paginationHtml = getPagination($totalPosts, $currentPage, $postsPerPage);

    // Jahr und Monat
    $year = date('Y');
    $month = date('m');

    // Blogpost-Termine abrufen
    $highlightDates = getBlogPostDatesByUser($userId['id']);

    // Kalender-Widget erstellen
    $calendarHtml = createCalendarWidget($highlightDates, $year, $month);
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
                            <?php echo $Parsedown->text(nl2br(htmlspecialchars(substr($post['content'], 0, 200)))); ?>...
                        </div>
                        <a href="index.php?post=<?php echo $post['id']; ?>" class="uk-button uk-button-text">Weiterlesen</a>
                    </article>
                    <hr class="uk-divider-icon">
                <?php endforeach; ?>
            <?php endif; ?>
                <!-- Pagination -->
                <!--<ul class="uk-pagination" uk-margin>
                    <li><a href="?page=<?php echo max(1, $currentPage - 1); ?>"><span uk-pagination-previous></span></a></li>
                    <li><a href="?page=<?php echo $currentPage + 1; ?>"><span uk-pagination-next></span></a></li>
                </ul>-->

                <?php echo $paginationHtml; ?>
            </div>

            <!-- Sidebar mit Kalender -->
            <?php 

            include('template/sidebar.php');

            ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendar = document.getElementById('calendar');
            var currentMonthElement = document.getElementById('currentMonth');
            var currentMonth = new Date(<?= $year ?>, <?= $month ?> - 1);

            document.getElementById('prevMonth').addEventListener('click', function() {
                currentMonth.setMonth(currentMonth.getMonth() - 1);
                updateCalendar();
            });

            document.getElementById('nextMonth').addEventListener('click', function() {
                currentMonth.setMonth(currentMonth.getMonth() + 1);
                updateCalendar();
            });

            function updateCalendar() {
                var year = currentMonth.getFullYear();
                var month = currentMonth.getMonth() + 1; // Monate sind 0-basiert
                fetch('system/fuction/api.php?year=' + year + '&month=' + month)
                    .then(response => response.json())
                    .then(data => {
                        calendar.innerHTML = data.html;
                        currentMonthElement.textContent = new Date(year, month - 1).toLocaleString('default', { month: 'long', year: 'numeric' });
                        addEventListeners(); // Erneut Event-Listener hinzufügen
                    });
            }

            function addEventListeners() {
                document.getElementById('prevMonth').addEventListener('click', function() {
                    currentMonth.setMonth(currentMonth.getMonth() - 1);
                    updateCalendar();
                });

                document.getElementById('nextMonth').addEventListener('click', function() {
                    currentMonth.setMonth(currentMonth.getMonth() + 1);
                    updateCalendar();
                });
            }
        });
    </script>

</body>

</html>
