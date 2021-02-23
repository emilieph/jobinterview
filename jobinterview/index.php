<?php

include_once __DIR__ . '/connec.php';

$pdo = new \PDO(DSN, USER, PASS);


$error = [];
$todo = "";

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && !empty($_POST)
    && isset($_POST["submit"])
) {
    $todo = isset($_POST["todo"]) ? trim($_POST["todo"]) : null;

    if (!$todo)
        $error["todo"] = "Required";

    if (empty($error)) {
        //insert in DB
        $query = "INSERT INTO todo (task) VALUES (:todo)";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':todo', $todo, \PDO::PARAM_STR);
        $statement->execute();

        header("Location: /index.php");
    }
}


$query = 'SELECT * FROM todo';

$statement = $pdo->query($query);

$todos = $statement->fetchAll(\PDO::FETCH_ASSOC);

foreach ($todos as $todo) {
    echo "<li>" . $todo["task"] . "</li>";
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkpoint</title>
</head>
<body>
    <form action="index.php" method="post">
        <div>
            <label  for="todo"> To do :</label>
            <input  type="text"  id="todo" name="todo">
            <?php
            if (isset ($error ["todo"]))
                echo ($error ["todo"]);
            ?>
        </div>
        <div class="button">
                <button  type="submit" name="submit">Send task</button>
        </div>
    </form>
</body>
</html>
