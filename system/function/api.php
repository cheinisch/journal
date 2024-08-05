<?php
require 'functions.php';

// Parameter aus der URL
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$userId = 1; // Beispiel-Benutzer-ID

// Blogpost-Termine abrufen
$highlightDates = getBlogPostDatesByUser($userId);

// Kalender-Widget erstellen
$calendarHtml = createCalendarWidget($highlightDates, $year, $month);

// JSON-Ausgabe
header('Content-Type: application/json');
echo json_encode(['html' => $calendarHtml]);

?>