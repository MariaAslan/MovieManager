<?php
require_once('database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_form.php');
    exit();
}

$userName = trim($_POST['userName'] ?? '');
$password = $_POST['password'] ?? '';

$error = '';

if ($userName === '' || $password === '') {
    $error = 'Please enter your username and password.';
} else {
    $query = 'SELECT userID,
                     userName,
                     password,
                     failedAttempts,
                     lastFailedLogin
              FROM registrations
              WHERE userName = :userName';

    $statement = $db->prepare($query);
    $statement->bindValue(':userName', $userName);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    if (!$user) {
        $error = 'Incorrect username or password.';
    } else {
        // Lock the account for 5 minutes after 3 failed attempts
        if (
            $user['failedAttempts'] >= 3 &&
            !empty($user['lastFailedLogin'])
        ) {
            $lastFailedTime = strtotime($user['lastFailedLogin']);
            $secondsPassed = time() - $lastFailedTime;

            if ($secondsPassed < 300) {
                $minutesLeft = ceil((300 - $secondsPassed) / 60);

                $error = 'Too many failed attempts. Try again in ' .
                         $minutesLeft . ' minute(s).';
            }
        }

        if (
            $error === '' &&
            password_verify($password, $user['password'])
        ) {
            // Reset failed attempts after successful login
            $query = 'UPDATE registrations
                      SET failedAttempts = 0,
                          lastFailedLogin = NULL
                      WHERE userID = :userID';

            $statement = $db->prepare($query);
            $statement->bindValue(
                ':userID',
                $user['userID'],
                PDO::PARAM_INT
            );
            $statement->execute();
            $statement->closeCursor();

            session_regenerate_id(true);

            $_SESSION['isLoggedIn'] = true;
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['userName'] = $user['userName'];

            header('Location: login_confirmation.php');
            exit();
        } elseif ($error === '') {
            // Record the failed login attempt
            $query = 'UPDATE registrations
                      SET failedAttempts = failedAttempts + 1,
                          lastFailedLogin = NOW()
                      WHERE userID = :userID';

            $statement = $db->prepare($query);
            $statement->bindValue(
                ':userID',
                $user['userID'],
                PDO::PARAM_INT
            );
            $statement->execute();
            $statement->closeCursor();

            $error = 'Incorrect username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Error</title>
    <link rel="stylesheet" href="CSS/movie.css">
</head>

<body>
<main>
    <?php include('header.php'); ?>

    <h2>Login Error</h2>

    <p class="error">
        <?php echo htmlspecialchars($error); ?>
    </p>

    <p>
        <a href="login_form.php">Return to Log In</a>
    </p>

    <?php include('footer.php'); ?>
</main>
</body>
</html>