<?php

try {
    $database = 'global';
    $user = 'estukalov';
    $password = "neto1205";
    $pdo = new PDO("mysql:host=localhost;dbname=$database;charset=utf8", $user, $password);
}
catch (PDOException $e) {
//    die('Подключение не удалось: ' . $e->getMessage());
    die('Подключение не удалось: ');
}

$sql = "CREATE TABLE IF NOT EXISTS `my table` (
        `id` int not null auto_increment,
        `field1` float null,
        `field2` varchar(20),
        primary key (`id`)
        ) engine=InnoDB default charset=utf8";
$statement = $pdo->prepare($sql);
$statement->execute();

$sql = "SHOW TABLES FROM $database";

$statement = $pdo->prepare($sql);
$statement->execute();

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $results[] = $row;
}
echo '';
?>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <title>Управление таблицами и базами данных</title>
</head>
<body>
    <ou>
        <?php if (!empty($results)) { foreach ($results as $table) :?>
        <li><a href="table.php?table=<?=array_values($table)[0]?>"><?=array_values($table)[0]?></a></li>
        <?php endforeach; }?>
    </ou>
</body>
</html>