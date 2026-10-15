<header>
    <h1>Movie Manager</h1>

    <nav class="account-nav">
        <?php
        if (
            isset($_SESSION['isLoggedIn']) &&
            $_SESSION['isLoggedIn'] === true
        ):
        ?>
            <span>
                Logged in as
                <strong>
                    <?php
                    echo htmlspecialchars($_SESSION['userName']);
                    ?>
                </strong>
            </span>

            <a href="index.php">Movies</a>
            <a href="logout.php">Log Out</a>
        <?php else: ?>
            <a href="login_form.php">Log In</a>
            <a href="register_user_form.php">Register</a>
        <?php endif; ?>
    </nav>
</header>