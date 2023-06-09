<?php 

    include('connect.php');

    checkFalse($_SESSION['log']);

    if (!($_SESSION['id'] == '1')){
        $id = $_SESSION['id'];
        header("Location: home.php?id=$id");
        exit;
    }

    $path = 'IMAGE/';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $image_name = create_name($_FILES['image']['name'], $path);
        move_uploaded_file($_FILES['image']['tmp_name'], $path . $image_name);

        $new_image_name = 'new_' . $image_name;
        hanlde_image($path . $image_name, $path . $new_image_name, 260, 260);


        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $file = $path . $new_image_name;

        $id_product = pdo($pdo, "SELECT MAX(id) AS id FROM PRODUCT")->fetch();

        if ($id_product['id'] == null) {
            $id = '1';
        } else {
            $id = strval((int)$id_product['id'] + 1);
        }

        $product = pdo($pdo, 'SELECT name, description, price FROM PRODUCT')->fetchAll();

        $checked_var = true;

        foreach($product as $value){
            if (($value['name'] == $name) && ($value['description'] == $description) && ($value['price'] == $price)) {
                $checked_var = false;
            };
        };  

        if ($checked_var == 'true'){
            pdo($pdo, "INSERT INTO PRODUCT (id, image, name, description, price, created) VALUES (:id, :file, :name, :description, :price, :date)",['id' => $id, 'file' => $file, 'name' => $name, 'description' => $description, 'price' => $price, 'date' => date("Y-m-d h:i:s A")]);
        };
    }




?>

<h1>UPLOAD PRODUCT</h1>
<form method = 'POST' action = 'admin.php' enctype = 'multipart/form-data'>
    NAME: <input type='text' name='name'>
    <br>
    <br>

    DESCRIPTION: <textarea name='description'></textarea>
    <br>
    <br>

    PRICE: <input type='text' name='price'>
    <br>
    <br>

    IMAGE: <input type='file' name='image' accept='image/*'>
    <br>
    <br>
    <input type='submit' value='SUBMIT'>
    <br>
    <br>
</form>

<a href='logout.php'>LOGOUT</a>
<br>
<a href = 'home.php'>HOME</a>