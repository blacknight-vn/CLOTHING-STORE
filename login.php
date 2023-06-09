<?php 

include('connect.php');

checkTrue($_SESSION['log']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $data_person = pdo($pdo, 'SELECT email, password, id FROM PERSON')->fetchAll();

    foreach($data_person as $value) {
        if (($value['email'] == $email) && ($value['password'] == $password)) {
            $_SESSION['log'] = true;

            $_SESSION['id'] = $value['id'];
            $id = $_SESSION['id'];
            header("Location: home.php?id=$id");
            exit;
        }
    }

    echo '<h1>failed</h1>';
}

?>

<form method = 'POST' action = 'login.php'>
    EMAIl:<input type='text' name = 'email'>
    <br>
    <br>
    PASSWORD:<input type = 'password' name = 'password'>
    <br>
    <br>
    <input type='submit' value='SUBMIT'> 
</form>