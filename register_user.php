<?php
require_once('database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register_user_form.php');
    exit();
}

$userName = trim($_POST['userName'] ?? '');
$emailAddress = filter_input(
    INPUT_POST,
    'emailAddress',
    FILTER_VALIDATE_EMAIL
);
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

$error = '';

if (
    $userName === '' ||
    !$emailAddress ||
    $password === '' ||
    $confirmPassword === ''
) {
    $error = 'Please complete all fields correctly.';
} elseif (strlen($password) < 6) {
    $error = 'Password must be at least 6 characters.';
} elseif ($password !== $confirmPassword) {
    $error = 'Passwords do not match.';
} else {
    // Check whether the username already exists
    $query = 'SELECT userID
              FROM registrations
              WHERE userName = :userName';

    $statement = $db->prepare($query);
    $statement->bindValue(':userName', $userName);
    $statement->execute();
    $existingUser = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    if ($existingUser) {
        $error = 'This username is already registered.';
    }
}

if ($error !== '') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Registration Error</title>
        <link rel="stylesheet" href="CSS/movie.css">
    </head>
    <body>
    <main>
        <?php include('header.php'); ?>

        <h2>Registration Error</h2>

        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>

        <p>
            <a href="register_user_form.php">
                Return to Registration
            </a>
        </p>

        <?php include('footer.php'); ?>
    </main>
    </body>
    </html>
    <?php
    exit();
}

// Encrypt the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Add the new user
$query = 'INSERT INTO registrations
            (userName, password, emailAddress)
          VALUES
            (:userName, :password, :emailAddress)';

$statement = $db->prepare($query);
$statement->bindValue(':userName', $userName);
$statement->bindValue(':password', $hashedPassword);
$statement->bindValue(':emailAddress', $emailAddress);
$statement->execute();
$statement->closeCursor();

header('Location: register_user_confirmation.php');
exit();
?>