<?php
require_once('database.php');

if (
    isset($_SESSION['isLoggedIn']) &&
    $_SESSION['isLoggedIn'] === true
) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In - Movie Manager</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <h2>Log In</h2>

    <form action="login.php" method="post">
        <label for="userName">Username:</label>
        <input
            type="text"
            id="userName"
            name="userName"
            required
        >

        <label for="password">Password:</label>
        <input
            type="password"
            id="password"
            name="password"
            required
        >

        <button type="submit">Log In</button>
    </form>

    <p>
        Don’t have an account?
        <a href="register_user_form.php">Register</a>
    </p>

    <?php include('footer.php'); ?>
</main>
</body>
</html>