<?php

require_once '_connec.php';
$pdo = new \PDO(DSN, USER, PASS);
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$contact = array_map('trim', $_POST);

if (empty($contact['firstname'])) {
    $errors[] = 'Le prénom est obligatoire';
}

$firstnameMaxLength = 45;
if (strlen($contact['firstname']) > $firstnameMaxLength) {
    $errors[] = 'Le prénom doit faire moins de ' . $firstnameMaxLength . ' caractères';
}

if (empty($contact['lastname'])) {
    $errors[] = 'Le nom est obligatoire';
}

if (empty($errors)) {
        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $contact['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $contact['lastname'], \PDO::PARAM_STR);
        $statement->execute();
        $friends = $statement->fetchAll();
        header('Location: /index.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <ul>
        <?php foreach ($friends as $friend) : ?>
    <li><?= htmlentities($friend['firstname']) .' ' . htmlentities($friend['lastname']) ?></li>
    <?php endforeach; ?>
    </ul>
    <form action="" method="POST">
    <label for="firstname">First Name</label>
    <input type="text" id="firstname" name="firstname">
    <label for="lastname">Last Name</label>
    <input type="text" id="lastname" name="lastname">
    <button>Send</button>
    </form>
</body>
</html>
