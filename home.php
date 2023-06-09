<link rel="stylesheet" href="home_payment.css">


<?php 

include('connect.php');

checkFalse($_SESSION['log']);

if ($_GET['id'] == null) {
    header('Location: login_and_sign_up.php');
    exit;
}

$id = $_GET['id'];

?>

<div class = 'container'>

<a href = 'cart.php?id=<?= $id ?>'>CART</a>

<?php 

    if ($id == '1') {
        ?>
            <a href='admin.php'>ADMIN</a>
        <?php
    };

    $product = pdo($pdo, 'SELECT * FROM PRODUCT')->fetchAll();

    $name = pdo($pdo, 'SELECT username FROM PERSON WHERE id = :id', ['id' => $id])->fetch();
?>

<a href = 'payment.php?user_id=<?= $id ?>'>BILL</a>

<a href='logout.php'>LOGOUT</a>


<!------------------------------------------------------------------------------------------>
<!-------------------------------------- REQUEST_METHOD ------------------------------------>
<!------------------------------------------------------------------------------------------>

<?php 

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $cp_id = pdo($pdo, 'SELECT MAX(id) AS id FROM CART_PRODUCT')->fetch();
        if ($cp_id['id'] == null) {
            $id_cp = '1';
        } else {
            $id_cp = strval((int)$cp_id['id'] + 1);
        }

        pdo($pdo, 'INSERT INTO CART_PRODUCT (id, cart_id, product_id, amount) VALUES (:id, :cart_id, :product_id, :amount)',['id' => $id_cp, 'cart_id' => $_GET['id'], 'product_id' => $_POST['product_id'], 'amount' => $_POST['amount']]);
    }

?>

</div>

<h1>HELLO <?= $name['username'] ?></h1>

<h1>PRODUCT</h1>
<br>

<?php 

    foreach($product as $value) {
        ?>
        <div style = 'background: grey; width: 400px; height: 520px; overflow: scroll; margin-bottom: 30px; padding-bottom: 30px; display: inline-block; margin-right: 30px;'>
            <h2 style = 'padding-top: 10px'>NAME: <?= $value['name'] ?></h2>
            <h2>PRICE: <?= $value['price'] ?>$</h2>
            <img src = '<?= $value['image'] ?>'>
            <h2>DESCRIPTION: <?= $value['description'] ?></h2>
            <h2>CREATED: <?= $value['created'] ?></h2>
            <form method = 'POST' action = 'home.php?id=<?= $id ?>'>
                AMOUNT:<input type = 'number' name = 'amount' value = '0'>
                <br>
                <input type = 'hidden' name = 'product_id' value = '<?= $value['id'] ?>'> 
                <input type = 'submit' value = 'ADD'>
            </form> 
        </div>
        <?php
    }

?>

