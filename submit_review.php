<?php
require_once 'Storage.php';

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$book_id = $_POST['book_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment = $_POST['comment'] ?? null;

if ($book_id && $rating && $comment) {
    $storage = new Storage('data/books.json');
    $review = [
        'user' => $_SESSION['email'],
        'rating' => (int)$rating,
        'comment' => $comment,
    ];
    $storage->saveReview($book_id, $review);
}

header("Location: details.php?id=" . $book_id);
exit;
?>
