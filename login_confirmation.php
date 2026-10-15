<?php
require_once('database.php');

if (
    !isset($_SESSION['isLoggedIn']) ||
    $_SESSION['isLoggedIn'] !== true
) {
    header('Location: login_form.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Successful</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <h2>Login Successful</h2>

    <p>
        Welcome,
        <?php echo htmlspecialchars($_SESSION['userName']); ?>!
    </p>

    <p>
        <a href="index.php">Go to Movie Manager</a>
    </p>

    <?php include('footer.php'); ?>
</main>
</body>
</html>