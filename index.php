<?php
require_once('database.php');

$query = 'SELECT movies.movieID,
                 movies.title,
                 movies.director,
                 genres.genreName,
                 movies.releaseYear,
                 movies.duration
          FROM movies
          INNER JOIN genres
              ON movies.genreID = genres.genreID
          ORDER BY movies.title';

$statement = $db->prepare($query);
$statement->execute();
$movies = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Manager</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <h2>Movie List</h2>

    <p>
        <a href="add_movie.php">Add New Movie</a> |
        <a href="genres.php">Manage Genres</a>
    </p>

    <table>
        <tr>
            <th>Title</th>
            <th>Director</th>
            <th>Genre</th>
            <th>Release Year</th>
            <th>Duration (minutes)</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($movies as $movie): ?>
            <tr>
                <td><?php echo htmlspecialchars($movie['title']); ?></td>

                <td>
                    <?php echo htmlspecialchars($movie['director']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($movie['genreName']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($movie['releaseYear']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($movie['duration']); ?>
                </td>

                <td>
                    <a href="edit_movie.php?id=<?php
                        echo $movie['movieID'];
                    ?>">Edit</a>

                    <form
                        action="delete_movie.php"
                        method="post"
                        style="display:inline;"
                        onsubmit="return confirm('Delete this movie?');"
                    >
                        <input
                            type="hidden"
                            name="movieID"
                            value="<?php echo $movie['movieID']; ?>"
                        >
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include('footer.php'); ?>
</main>
</body>
</html>