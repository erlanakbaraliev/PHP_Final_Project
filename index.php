<?php
require_once 'Storage.php';

$storage = new Storage('data/books.json');
$books = $storage->getAllBooks();

session_start();
$loggedIn = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IK-Library | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <h1><a href="index.php">IK-Library</a></h1>
        <?php if($loggedIn): ?>
            <a class="btn btn-primary font-bold ml-10 mt-1" style='background-color:white' href="./login.php?type=logout">Logout</a>
            <a class="btn btn-primary font-bold ml-10 mt-1" style='background-color:white' href="addbook.php">Add a book</a>
        <?php else: ?>
            <a class="btn btn-primary font-bold ml-10 mt-1" style='background-color:white' href="login.php">Login</a>
        <?php endif; ?>
    </header>
    <div id="content">
        <div id="card-list">
            <?php foreach ($books as $book_id => $book): ?>
                <?php
                    $reviews = $storage->getReviews($book_id);
                    $totalRating = 0;
                    foreach ($reviews as $review) {
                        $totalRating += $review['rating'];
                    }
                    $averageRating = count($reviews) > 0 ? $totalRating / count($reviews) : 'No ratings yet';
                ?>
                <div class="book-card">
                    <div class="image">
                        <img src="assets/<?php echo $book['image']; ?>" alt="">
                    </div>
                    <div class="details">
                        <h2><a href="details.php?id=<?php echo $book_id; ?>"><?php echo $book['title']; ?></a></h2>
                        <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                        <p><strong>Year:</strong> <?php echo $book['year']; ?></p>
                        <p><strong>Planet:</strong> <?php echo $book['planet']; ?></p>
                        <p><strong>Average Rating:</strong> <?php echo is_numeric($averageRating) ? number_format($averageRating, 2) : $averageRating; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>

</html>
