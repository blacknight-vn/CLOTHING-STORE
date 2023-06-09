<?php 


include('connect.php');

checkTrue($_SESSION['log']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $data_email_person = pdo($pdo, 'SELECT email FROM PERSON')->fetchAll();
    $data_max_id_person = pdo($pdo, 'SELECT MAX(id) AS id FROM PERSON')->fetch();

    $checked_var = 'true';

    foreach ($data_email_person as $value) {
        if ($value['email'] === $email) {
            $checked_var = 'false';
            echo 'Lỗi';
            break;
        }
    }

    if ($checked_var === 'true') {

        if (!$data_max_id_person['id']) {
            $id = 1;
        } else {
            $id = $data_max_id_person['id'] + 1;
        };

        $_SESSION['log'] = true;
        $_SESSION['id'] = $id;
        
        pdo($pdo, "INSERT INTO PERSON (id, email, password, username, created) VALUES (:id, :email, :password,  :username, :date)", ['id' => $id, 'email' => $email, 'password' => $password, 'date' => date("Y-m-d h:i:s A"), 'username' => $username]);

        pdo($pdo, "INSERT INTO CART (id, person_id) VALUES (:id, :person_id)", ['id' => $id, 'person_id' => $id]);

        header("Location: home.php?id=$id");
        exit;

    }
}


?>

<form action='sign_up.php' method='POST'>
    USERNAME:<input name = 'username' type='text'>
    <br>
    <br>
    EMAIL:<input name = 'email' type='text'>
    <br>
    <br>
    PASSWORD:<input name = 'password' type='password'>
    <br>
    <br>
    <input type = 'submit' value = 'SUBMIT'>
</form>


