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

$table = $_GET['table'];
$types = ['TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT', 'FLOAT', 'DOUBLE', 'TIMESTAMP', 'VARCHAR'];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET)) {

    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $field = $_GET['field'];
        $sql = "ALTER TABLE `$table` DROP COLUMN `$field`;";
        $statement = $pdo->prepare($sql);
        $statement->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

    if (isset($_POST['field']) && isset($_POST['var']) && !empty($_POST['var'])) {
        $old = $_GET['field'];
        $new = $_POST['var'];
        $type = $_GET['type'];
        $sql = "ALTER TABLE `$table` CHANGE `$old` `$new` $type";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        header("Location: table.php?table=$table");
        exit;
    }

    if (isset($_POST['type']) && isset($_POST['types']) && !empty($_POST['types'])) {
        $array = explode('_', $_POST['types']);
        $field = $array[1];
        $type = $array[0];
        $sql = "ALTER TABLE `$table` CHANGE `$field` `$field` $type";
        $statement = $pdo->prepare($sql);
        $statement->execute();
    }

}

$sql = "DESCRIBE `$table`";
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
    <style>
td {padding: 5px 20px 5px 20px;border: 1px solid black;}
        form.select {margin: 0;}
        thead td {text-align: center;background-color: #dbdbdb;font-weight: 700;}
        table {border-collapse: collapse;border-spacing: 0;}
        .done {margin-right: 20px;}
        .field {<?php if (!isset($_GET['action']) || $_GET['action'] <> 'edit') : ?> display: none; <?php endif;?>}
    </style>
</head>
<body>

<div>
    <form method='POST'>
        <input type="text" name="var" placeholder='Изменяемое значение' value="<?=(isset($_GET['action']) && $_GET['action'] == 'edit') ? $_GET['field'] : ''?>">
        <input type='submit' value='Изменить' name="field" class="field">
    </form>
</div>

<table>
    <thead>
    <tr>
        <td>Название поля</td>
        <td>Тип поля</td>
        <td></td>
        <td>Изменить тип поля</td>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($results)) { foreach ($results as $value) :?>
        <tr>
            <td><?=$value['Field']?></td>
            <td><?=$value['Type']?></td>
            <td><a class="done" href="<?="?table=$table&field=".$value['Field']."&type=".$value['Type']."&action=edit"?>">Изменить</a><a class="done" href="<?="?table=$table&field=".$value['Field']."&action=delete"?>">Удалить</a></td>
            <td>
                <form method="POST" class="select">
                    <select name='types'>
                        <?php if (!empty($types)) { foreach ($types as $type ) :?>
                            <option value="<?=$type . '_' . $value['Field']?>"><?=$type?></option>
                        <?php endforeach; }?>
                    </select>
                    <input type='submit' name='type' value='Изменить'>
                </form>
            </td>
        </tr>
    <?php endforeach; } ?>
    </tbody>
</table>