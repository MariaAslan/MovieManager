<?php
require_once('database.php');

$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    /* Add genre */
    if ($action === 'add') {
      $genreName = ucwords(strtolower(trim($_POST['genreName'] ?? '')));

        if ($genreName === '') {
            $error = 'Please enter a genre name.';
        } else {
            $query = 'SELECT COUNT(*) FROM genres WHERE genreName = :genreName';
            $statement = $db->prepare($query);
            $statement->bindValue(':genreName', $genreName);
            $statement->execute();
            $genreExists = $statement->fetchColumn();
            $statement->closeCursor();

            if ($genreExists) {
                $error = 'This genre already exists.';
            } else {
                $query = 'INSERT INTO genres (genreName)
                          VALUES (:genreName)';

                $statement = $db->prepare($query);
                $statement->bindValue(':genreName', $genreName);
                $statement->execute();
                $statement->closeCursor();

                $message = 'Genre added successfully.';
            }
        }
    }

    /* Delete genre */
    if ($action === 'delete') {
        $genreID = filter_input(
            INPUT_POST,
            'genreID',
            FILTER_VALIDATE_INT
        );

        if ($genreID) {
            $query = 'SELECT COUNT(*)
                      FROM movies
                      WHERE genreID = :genreID';

            $statement = $db->prepare($query);
            $statement->bindValue(
                ':genreID',
                $genreID,
                PDO::PARAM_INT
            );
            $statement->execute();
            $movieCount = $statement->fetchColumn();
            $statement->closeCursor();

            if ($movieCount > 0) {
                $error = 'This genre cannot be deleted because it is being used by a movie.';
            } else {
                $query = 'DELETE FROM genres
                          WHERE genreID = :genreID';

                $statement = $db->prepare($query);
                $statement->bindValue(
                    ':genreID',
                    $genreID,
                    PDO::PARAM_INT
                );
                $statement->execute();
                $statement->closeCursor();

                $message = 'Genre deleted successfully.';
            }
        }
    }
}

/* Get all genres */
$query = 'SELECT genreID, genreName
          FROM genres
          ORDER BY genreName';

$statement = $db->prepare($query);
$statement->execute();
$genres = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Genres</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <h2>Manage Genres</h2>

    <?php if ($error !== ''): ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <?php if ($message !== ''): ?>
        <p class="success">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form action="genres.php" method="post">
        <input type="hidden" name="action" value="add">

        <label for="genreName">New Genre:</label>
        <input
            type="text"
            id="genreName"
            name="genreName"
            required
        >

        <button type="submit">Add Genre</button>
    </form>

    <h3>Genre List</h3>

    <table>
        <tr>
            <th>Genre Name</th>
            <th>Action</th>
        </tr>

        <?php foreach ($genres as $genre): ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($genre['genreName']); ?>
                </td>

                <td>
                    <form action="genres.php" method="post">
                        <input
                            type="hidden"
                            name="action"
                            value="delete"
                        >

                        <input
                            type="hidden"
                            name="genreID"
                            value="<?php echo $genre['genreID']; ?>"
                        >

                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p>
        <a href="index.php">Back to Movie List</a>
    </p>

    <?php include('footer.php'); ?>
</main>
</body>
</html>