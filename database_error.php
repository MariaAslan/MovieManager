<?php
session_start();
$error = $_SESSION['database_error'] ?? 'Database connection failed.';
unset($_SESSION['database_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Error - Movie Manager</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>
<body>
    <main>
        <?php include('header.php'); ?>

        <h2>Database Error</h2>
        <p>There was an error connecting to the database.</p>
        <p>Please make sure MySQL is running.</p>
        <p><?php echo htmlspecialchars($error); ?></p>
        <p><a href="index.php">View Movie List</a></p>

        <?php include('footer.php'); ?>
    </main>
</body>
</html>