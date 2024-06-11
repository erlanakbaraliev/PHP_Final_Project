<?php
    require_once 'Storage.php';

    $storage = new Storage('data/books.json');
    $books = $storage->getAllBooks();
    
    $input = $_GET;
    $errors = [];
    $data = [];

    function validate($input, &$errors, &$data) {
        if(!isset($input['title']) || trim($input['title']) === "") {
            $errors[] = "Enter a title!";
        }
        else {
            $data['title'] = $input['title'];
        }

        if(!isset($input['author']) || trim($input['author']) === "") {
            $errors[] = "Enter the author!";
        }
        else {
            $data['author'] = $input['author'];
        }

        if(!isset($input['description']) || trim($input['description']) === "") {
            $errors[] = "Enter a description!";
        }
        else {
            $data['description'] = $input['description'];
        }

        if(!isset($input['year']) || trim($input['year']) === "") {
            $errors[] = "Enter the year!";
        } else if(!filter_var($input['year'], FILTER_VALIDATE_INT)) {
            $errors[] = "The year must be an integer!";
        } else {
            $data['year'] = $input['year'];
        }
        
        if(!isset($input['image'])) {
            $errors[] = "Choose an image";
        }
        else {
            $data['image'] = $input['image'];
        }

        if(!isset($input['planet']) || trim($input['planet']) === "") {
            $errors[] = "Enter a planet!";
        }
        else {
            $data['planet'] = $input['planet'];
        }
        
        return count($errors);
    }

    validate($input, $errors, $data);

    if(count($errors) === 0) {
        $old_data = json_decode(file_get_contents('data/books.json'), true);

        $new_book_id = 'book' . count($old_data);

        $old_data[$new_book_id] = $data;

        file_put_contents('data/books.json', json_encode($old_data, JSON_PRETTY_PRINT));
    }
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
        <h1><a href="index.php">IK-Library</a> > Add a Book</h1>
    </header>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>

    <div class="">
        <form action="addbook.php" method="get" class="">
            <h1 class="text-3xl p-5 font-bold" style="color: black">Add a new book</h1>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Title</span>
                </div>
                <input type="text" name="title" placeholder="Type here" class="input input-bordered w-full max-w-xs" value=<?= $data['title'] ?? '' ?>>
            </label>

            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Author</span>
                </div>
                <input type="text" name="author" placeholder="Type here" class="input input-bordered w-full max-w-xs" value=<?= $data['author'] ?? '' ?>>
            </label>

            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Description</span>
                </div>
                <input type="text" name="description" placeholder="Type here" class="input input-bordered w-full max-w-xs" value=<?= $data['description'] ?? '' ?>>
            </label>

            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Year</span>
                </div>
                <input type="number" name="year" placeholder="Type here" class="input input-bordered w-full max-w-xs" value=<?= $data['year'] ?? '' ?>>
            </label>

            <select class="select w-full max-w-xs mb-3 select-bordered" name="image">
                <option disabled selected>Select the picture</option>
                <option value="book_cover_1.png" <?= isset($input['image']) && $input['image'] === 'book_cover_1.png'? 'selected' : '' ?>>book_cover_1.png</option>
                <option value="book_cover_2.png" <?= isset($input['image']) && $input['image'] === 'book_cover_2.png'? 'selected' : '' ?>>book_cover_2.png</option>
                <option value="book_cover_3.png" <?= isset($input['image']) && $input['image'] === 'book_cover_3.png'? 'selected' : '' ?>>book_cover_3.png</option>
                <option value="book_cover_4.png" <?= isset($input['image']) && $input['image'] === 'book_cover_4.png'? 'selected' : '' ?>>book_cover_4.png</option>
                <option value="book_cover_5.png" <?= isset($input['image']) && $input['image'] === 'book_cover_5.png'? 'selected' : '' ?>>book_cover_5.png</option>
                <option value="book_cover_6.png" <?= isset($input['image']) && $input['image'] === 'book_cover_6.png'? 'selected' : '' ?>>book_cover_6.png</option>
            </select>
            
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Planet</span>
                </div>
                <input type="text" name="planet" placeholder="Type here" class="input input-bordered w-full max-w-xs" value=<?= $data['planet'] ?? '' ?>>
            </label>

            <input type="submit" value="Add a new book" class="btn btn-primary font-bold mt-5">
        </form>

        <div class="results w-6/12  m-auto p-10">
            <div class="errors">
            <?php if(count($_GET) !== 0 && count($errors) > 0): ?>
                <h2 class="text-3xl mb-5 font-bold">Failed addition</h2>
            
                <?php foreach($errors as $error): ?>
                    <div role="alert" class="alert alert-error mb-2">
                        <span><?php echo $error?></span>
                    </div>
                <?php endforeach;?>
            <?php endif?>
            </div>

            <?php if(count($errors) === 0): ?>
                <div class="success">
                    <h2 class="text-3xl mb-2 font-bold">Successful addition</h2>
                </div>
            <?php endif?>
        </div>
    </div>
</body>

</html>
