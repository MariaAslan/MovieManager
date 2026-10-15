<?php
require_once('database.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Movie Manager</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <h2>Create Account</h2>

    <form action="register_user.php" method="post">
        <label for="userName">Username:</label>
        <input
            type="text"
            id="userName"
            name="userName"
            required
        >

        <label for="emailAddress">Email Address:</label>
        <input
            type="email"
            id="emailAddress"
            name="emailAddress"
            required
        >

        <label for="password">Password:</label>
        <input
            type="password"
            id="password"
            name="password"
            minlength="6"
            required
        >

        <label for="confirmPassword">Confirm Password:</label>
        <input
            type="password"
            id="confirmPassword"
            name="confirmPassword"
            minlength="6"
            required
        >

        <button type="submit">Register</button>
    </form>

    <p>
        Already have an account?
        <a href="login_form.php">Log In</a>
    </p>

    <?php include('footer.php'); ?>
</main>
</body>
</html>