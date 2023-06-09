<link rel="stylesheet" href="cart.css">

<?php 

include('connect.php');

checkFalse($_SESSION['log']);

if ($_GET['id'] == null) {
    $id = $_SESSION['id'];
    header("Location: home.php?id = $id");
    exit;
}

$cp = pdo($pdo, "SELECT * FROM CART_PRODUCT WHERE cart_id = :id", ['id' => $_GET['id']])->fetchAll();

$l = [];

foreach($cp as $value) {
    $checked_var = 'true';
    for($i = 0; $i < count($l); $i++){
        if ($l[$i][0] === $value['product_id']) {
            $checked_var = 'false';
            $l[$i][1] += $value['amount'];
            break;
        }
    };

    if ($checked_var == 'true') {
        array_push($l, [$value['product_id'], $value['amount']]);
    }
};

$sum = 0;

foreach($l as $value) {
    $product = pdo($pdo, 'SELECT price FROM PRODUCT WHERE id = :id', ['id' => $value[0]])->fetch();
    $sum += $value[1] * $product['price'];
}

?>

    <div class = 'top__container'>
        <div style = 'float: left; margin-top: 0; margin-right: 7px;'><a href = 'home.php?id=<?= $_GET['id'] ?>'>HOME</a></div>
        <div style = 'float: left; margin-top: 0;'><a href = 'payment.php?user_id=<?= $_GET['id'] ?>'>BILL</a></div>
        <div style = 'float: right; margin-top: 0;'><a href = 'logout.php'>LOGOUT</a></div>
    </div>

    <div class = 'top__container'>
        <h1 style = 'float: left; margin-top: 10px;'>CART</h1>
        <h1 style = 'float: right; margin-top: 10px;'>TOTAL_AMOUNT: <?= $sum ?>$</h1>
    </div>    

<?php

foreach($l as $value) {
    $product = pdo($pdo, "SELECT id, image, name, price FROM PRODUCT WHERE id = :id",['id' => strval($value[0])])->fetch();

    $total = $value[1] * (float)$product['price'];

    ?>
        <div style = 'background: grey; color: white; padding: 10px 0 20px 20px; margin-bottom: 40px;'>
            <div style = 'background: black; width: 300px;'>
                <h2>NAME: <?= $product['name'] ?></h2>
                <img src = '<?= $product['image'] ?>'>
                <h2>PRICE: <?= $product['price'] ?> $ </h2>
                <h2>AMOUNT: <?= $value['1'] ?></h2>

                <div class = 'top__container'>
                    <h2 style = 'float: left; margin-top: 0;'>TOTAL: <?= $total ?> $ </h2>
                    <form method = 'POST' action = 'payment.php?user_id=<?= $_GET['id'] ?>'>
                        <input type = 'hidden' name = 'product_id' value = '<?= $product['id'] ?>'>
                        <input type = 'hidden' name = 'amount' value = '<?= $value[1] ?>'>
                        <input style = 'float: right;' type='submit' value = 'BUY'>
                    </form>
                </div>
            </div>
        </div>
    <?php
}

?>