<?php
require_once('database.php');

$movieID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$movieID) {
    header('Location: index.php');
    exit();
}

/* Get genres */
$query = 'SELECT genreID, genreName FROM genres ORDER BY genreName';
$statement = $db->prepare($query);
$statement->execute();
$genres = $statement->fetchAll(PDO::FETCH_ASSOC);
$statement->closeCursor();

/* Get selected movie */
$query = 'SELECT movieID, title, director, genreID, releaseYear, duration,imageName
          FROM movies
          WHERE movieID = :movieID';

$statement = $db->prepare($query);
$statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);
$statement->execute();
$movie = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

if (!$movie) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $director = trim($_POST['director'] ?? '');
    $genreID = filter_input(INPUT_POST, 'genreID', FILTER_VALIDATE_INT);
    $releaseYear = filter_input(INPUT_POST, 'releaseYear', FILTER_VALIDATE_INT);
    $duration = filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);
    $imageName = $movie['imageName'] ?: 'placeholder_100.jpg';

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === UPLOAD_ERR_OK
) {
    $extension = strtolower(
        pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)
    );

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($extension, $allowedExtensions, true)) {
        $imageName = uniqid('movie_', true) . '.' . $extension;

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            __DIR__ . '/images/' . $imageName
        );
    }
}

    if (
        $title === '' ||
        $director === '' ||
        !$genreID ||
        !$releaseYear ||
        !$duration
    ) {
        $error = 'Please complete all fields correctly.';

        $movie['title'] = $title;
        $movie['director'] = $director;
        $movie['genreID'] = $genreID;
        $movie['releaseYear'] = $_POST['releaseYear'] ?? '';
        $movie['duration'] = $_POST['duration'] ?? '';
    } else {
        $query = 'UPDATE movies
                  SET title = :title,
                      director = :director,
                      genreID = :genreID,
                      releaseYear = :releaseYear,
                      duration = :duration,
imageName = :imageName
                  WHERE movieID = :movieID';

        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':director', $director);
        $statement->bindValue(':genreID', $genreID, PDO::PARAM_INT);
        $statement->bindValue(':releaseYear', $releaseYear, PDO::PARAM_INT);
        $statement->bindValue(':duration', $duration, PDO::PARAM_INT);
        $statement->bindValue(':imageName', $imageName);
        $statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);
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
    <title>Edit Movie</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <h2>Edit Movie</h2>

    <?php if ($error !== ''): ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form action="edit_movie.php?id=<?php echo $movieID; ?>"
      method="post"
      enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input
            type="text"
            id="title"
            name="title"
            value="<?php echo htmlspecialchars($movie['title']); ?>"
            required
        >

        <label for="director">Director:</label>
        <input
            type="text"
            id="director"
            name="director"
            value="<?php echo htmlspecialchars($movie['director']); ?>"
            required
        >

        <label for="genreID">Genre:</label>
        <select id="genreID" name="genreID" required>
            <option value="">Select a genre</option>

            <?php foreach ($genres as $genre): ?>
                <option
                    value="<?php echo $genre['genreID']; ?>"
                    <?php
                    if ($movie['genreID'] == $genre['genreID']) {
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
            value="<?php echo htmlspecialchars($movie['releaseYear']); ?>"
            required
        >

        <label for="duration">Duration (minutes):</label>
        <input
            type="number"
            id="duration"
            name="duration"
            min="1"
            value="<?php echo htmlspecialchars($movie['duration']); ?>"
            required
        >
<label for="image">Movie Poster:</label>

<img
    id="imagePreview"
    src="images/<?php echo htmlspecialchars(
        $movie['imageName'] ?: 'placeholder_100.jpg'
    ); ?>"
    alt="Movie poster preview"
    width="120"
>

<input
    type="file"
    id="image"
    name="image"
    accept="image/*"
>
        <button type="submit">Update Movie</button>
        <a href="index.php">Cancel</a>
    </form>

    <?php include('footer.php'); ?>
</main>
<script>
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function () {
        const selectedFile = this.files[0];

        if (selectedFile) {
            imagePreview.src = URL.createObjectURL(selectedFile);
        }
    });
</script>
</body>
</html>