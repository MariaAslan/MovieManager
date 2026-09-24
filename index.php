<?php
require_once('database.php');

$query = 'SELECT title, director, genre, releaseYear, duration FROM movies';
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
        <table>
            <tr>
                <th>Title</th>
                <th>Director</th>
                <th>Genre</th>
                <th>Release Year</th>
                <th>Duration (minutes)</th>
            </tr>
            <?php foreach ($movies as $movie): ?>
                <tr>
                    <td><?php echo htmlspecialchars($movie['title']); ?></td>
                    <td><?php echo htmlspecialchars($movie['director']); ?></td>
                    <td><?php echo htmlspecialchars($movie['genre']); ?></td>
                    <td><?php echo htmlspecialchars($movie['releaseYear']); ?></td>
                    <td><?php echo htmlspecialchars($movie['duration']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php include('footer.php'); ?>
    </main>
</body>
</html>