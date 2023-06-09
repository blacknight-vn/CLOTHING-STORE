<link rel="stylesheet" href="home_payment.css">

<?php 

include('connect.php');

checkFalse($_SESSION['log']);

if ($_GET['user_id'] == null) {
    $id = $_SESSION['id'];
    header("Location: home.php?id = $id");
    exit;
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $payment_id = pdo($pdo, "SELECT MAX(id) AS id FROM PAYMENT")->fetch();
    if ($payment_id['id'] == null) {
        $id_payment = '1';
    } else{
        $id_payment = strval((int)$payment_id['id'] + 1);
}

pdo($pdo, "INSERT INTO PAYMENT (id, created) VALUES (:id, :date)", ['id' => $id_payment, 'date' => date("Y-m-d h:i:s A")]);

$person_payment_id = pdo($pdo, "SELECT MAX(id) AS id FROM PERSON_PAYMENT")->fetch();

if ($person_payment_id['id'] == null) {
    $id_person_payment = '1';
} else {
    $id_person_payment = strval((int)$person_payment_id['id'] + 1);
}

pdo($pdo, "INSERT INTO PERSON_PAYMENT (id, person_id, payment_id) VALUES(:id, :person_id, :payment_id)", ['id' => $id_person_payment, 'person_id' => $_GET['user_id'], 'payment_id' => $id_payment]);

   $pp_id = pdo($pdo, "SELECT MAX(id) AS id FROM PAYMENT_PRODUCT")->fetch();
    if ($pp_id['id'] == null) {
        $id_pp = '1';
    } else{
        $id_pp = strval((int)$pp_id['id'] + 1);
}

    pdo($pdo, 'INSERT INTO PAYMENT_PRODUCT (id, payment_id, product_id, amount) VALUES (:id, :payment_id, :product_id, :amount)', ['id' => $id_pp, 'payment_id' => $id_payment, 'product_id' => $_POST['product_id'], 'amount' => $_POST['amount']]);


    pdo($pdo, "DELETE FROM CART_PRODUCT WHERE cart_id = :cart_id AND product_id = :product_id", ['cart_id' => $_GET['user_id'], 'product_id' => $_POST['product_id']]);

    $data = pdo($pdo, "SELECT pp.amount AS amount, p.price AS price FROM PAYMENT_PRODUCT AS pp JOIN PRODUCT AS p ON pp.product_id = p.id WHERE pp.id = (SELECT MAX(id) FROM PAYMENT_PRODUCT)")->fetch();

    $payment_total = $data['amount'] * $data['price'];

    pdo($pdo, "UPDATE PAYMENT SET payment = :payment_total WHERE id = (SELECT MAX(id) FROM PAYMENT_PRODUCT)", ['payment_total' => $payment_total]);

};

$data2 = pdo($pdo, "SELECT pm.created AS created, pm.payment AS payment, pd.name AS name, pd.image AS image, pd.price AS price, pp.amount AS amount FROM PAYMENT_PRODUCT AS pp JOIN PRODUCT AS pd ON pp.product_id = pd.id JOIN PAYMENT AS pm ON pp.payment_id = pm.id JOIN PERSON_PAYMENT AS prpp ON prpp.payment_id = pp.id WHERE prpp.person_id = :person_id ORDER BY pm.created DESC", ['person_id' => $_GET['user_id']])->fetchAll();

?>

<div class="container">
    <a href = 'home.php?id=<?= $_GET['user_id'] ?>'>HOME</a>
    <a href = 'cart.php?id=<?= $_GET['user_id'] ?>'>CART</a>
    <a href = 'logout.php'>LOGOUT</a>
</div>

<h1>PAYMENT</h1>

<?php 

foreach($data2 as $value) {
    ?>
        <div style = 'background: red; margin-bottom: 15px; padding: 20px 0 20px 20px;'>
            <div style = 'background: grey; width: 300px; overflow: scroll'>
                <h1>PAYMENT</h1>
                <h2>NAME: <?= $value['name'] ?></h2>
                <img src ='<?= $value['image'] ?>'>
                <h2>AMOUNT: <?= $value['amount'] ?></h2>
                <h2>PRICE: <?= $value['price'] ?>$</h2>
                <h2>PAYMENT: <?= $value['payment'] ?>$</h2>
                <h2>CREATED: <?= $value['created'] ?></h2>
            </div>
        </div>
    <?php
}

?>