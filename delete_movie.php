<?php
require_once('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieID = filter_input(INPUT_POST, 'movieID', FILTER_VALIDATE_INT);

    if ($movieID) {
        $query = 'DELETE FROM movies WHERE movieID = :movieID';

        $statement = $db->prepare($query);
        $statement->bindValue(':movieID', $movieID, PDO::PARAM_INT);
        $statement->execute();
        $statement->closeCursor();
    }
}

header('Location: index.php');
exit();
?>