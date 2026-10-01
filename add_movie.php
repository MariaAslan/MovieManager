<?php
require_once('database.php');

$query = 'SELECT genreID, genreName FROM genres ORDER BY genreName';
$statement = $db->prepare($query);
$statement->execute();
$genres = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $director = trim($_POST['director'] ?? '');
    $genreID = filter_input(INPUT_POST, 'genreID', FILTER_VALIDATE_INT);
    $releaseYear = filter_input(INPUT_POST, 'releaseYear', FILTER_VALIDATE_INT);
    $duration = filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);

    if (
        $title === '' ||
        $director === '' ||
        !$genreID ||
        !$releaseYear ||
        !$duration
    ) {
        $error = 'Please complete all fields correctly.';
    } else {
        $query = 'INSERT INTO movies
                  (title, director, genreID, releaseYear, duration)
                  VALUES
                  (:title, :director, :genreID, :releaseYear, :duration)';

        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':director', $director);
        $statement->bindValue(':genreID', $genreID, PDO::PARAM_INT);
        $statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
        $statement->bindValue(':duration', $duration, PDO::PARAM_INT);
        $statement->execute();
        $statement->closeCursor();

        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Movie</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <h2>Add New Movie</h2>

    <?php if ($error !== ''): ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form action="add_movie.php" method="post">
        <label for="title">Title:</label>
        <input
            type="text"
            id="title"
            name="title"
            value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
            required
        >

        <label for="director">Director:</label>
        <input
            type="text"
            id="director"
            name="director"
            value="<?php echo htmlspecialchars($_POST['director'] ?? ''); ?>"
            required
        >

        <label for="genreID">Genre:</label>
        <select id="genreID" name="genreID" required>
            <option value="">Select a genre</option>

            <?php foreach ($genres as $genre): ?>
                <option
                    value="<?php echo $genre['genreID']; ?>"
                    <?php
                    if (
                        isset($_POST['genreID']) &&
                        $_POST['genreID'] == $genre['genreID']
                    ) {
                        echo 'selected';
                    }
                    ?>
                >
                    <?php echo htmlspecialchars($genre['genreName']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="releaseYear">Release Year:</label>
        <input
            type="number"
            id="releaseYear"
            name="releaseYear"
            min="1888"
            max="2100"
            value="<?php echo htmlspecialchars($_POST['releaseYear'] ?? ''); ?>"
            required
        >

        <label for="duration">Duration (minutes):</label>
        <input
            type="number"
            id="duration"
            name="duration"
            min="1"
            value="<?php echo htmlspecialchars($_POST['duration'] ?? ''); ?>"
            required
        >

        <button type="submit">Add Movie</button>
        <a href="index.php">Cancel</a>
    </form>

    <?php include('footer.php'); ?>
</main>
</body>
</html>