<?php
require_once('database.php');

$movieID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$movieID) {
    header('Location: index.php');
    exit();
}

$query = 'SELECT movies.title,
                 movies.director,
                 genres.genreName,
                 movies.releaseYear,
                 movies.duration,
                 movies.imageName
          FROM movies
          INNER JOIN genres
              ON movies.genreID = genres.genreID
          WHERE movies.movieID = :movieID';

$statement = $db->prepare($query);
$statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);
$statement->execute();
$movie = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

if (!$movie) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Details</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <section class="movie-details">
        <img
            src="images/<?php echo htmlspecialchars(
                $movie['imageName'] ?: 'placeholder_100.jpg'
            ); ?>"
            alt="<?php echo htmlspecialchars($movie['title']); ?> poster"
            width="250"
        >

        <div>
            <h2><?php echo htmlspecialchars($movie['title']); ?></h2>

            <p>
                <strong>Director:</strong>
                <?php echo htmlspecialchars($movie['director']); ?>
            </p>

            <p>
                <strong>Genre:</strong>
                <?php echo htmlspecialchars($movie['genreName']); ?>
            </p>

            <p>
                <strong>Release Year:</strong>
                <?php echo htmlspecialchars($movie['releaseYear']); ?>
            </p>

            <p>
                <strong>Duration:</strong>
                <?php echo htmlspecialchars($movie['duration']); ?> minutes
            </p>

            <a href="index.php">Back to Movie List</a>
        </div>
    </section>

    <?php include('footer.php'); ?>
</main>
</body>
</html>