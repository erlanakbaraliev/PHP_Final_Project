<?php
require_once 'Storage.php';

$storage = new Storage('data/books.json');

$book_id = $_GET['id'] ?? null;

if ($book_id === null || ($book = $storage->getBook($book_id)) === null) {
    echo "Book not found.";
    exit;
}

$reviews = $storage->getReviews($book_id);
$totalRating = 0;
foreach ($reviews as $review) {
    $totalRating += $review['rating'];
}
$averageRating = count($reviews) > 0 ? $totalRating / count($reviews) : 'No ratings yet';

session_start();
$loggedIn = isset($_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $book['title']; ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IK-Library</a> > <?php echo $book['title']; ?></h1>
    </header>
    <div id="content">
        <div class="book-details">
            <div class="image">
                <img src="assets/<?php echo $book['image']; ?>" alt="">
            </div>
            <div class="details">
                <h2><?php echo $book['title']; ?></h2>
                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                <p><strong>Description:</strong> <?php echo $book['description']; ?></p>
                <p><strong>Year:</strong> <?php echo $book['year']; ?></p>
                <p><strong>Planet:</strong> <?php echo $book['planet']; ?></p>
                <p><strong>Average Rating:</strong> <?php echo is_numeric($averageRating) ? number_format($averageRating, 2) : $averageRating; ?></p>
            </div>
        </div>
        
        <div class="reviews">
            <h3>Reviews:</h3>
            <?php if (count($reviews) > 0): ?>
                <ul>
                    <?php foreach ($reviews as $review): ?>
                        <li><strong><?php echo $review['user']; ?>:</strong> <?php echo $review['rating']; ?>/5 - <?php echo $review['comment']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>

            <?php if ($loggedIn): ?>
                <form action="submit_review.php" method="post">
                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                    <label for="rating">Rating:</label>
                    <select name="rating" id="rating" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <label for="comment">Comment:</label>
                    <textarea name="comment" id="comment" required></textarea>
                    <input type="submit" value="Submit Review">
                </form>
            <?php else: ?>
                <p><a href="login.php">Log in</a> to write a review.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>

</html>
