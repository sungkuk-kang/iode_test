<?php
if(!isset($_POST['username'], $_POST['pw']))
{
    header('Location: login.html');
}

$db = 'project';
include '.htinclude.php';

if($login)
{
    mysqli_close($db);
    exit('<meta charset="utf-8" /><script>alert("Already logged on."); location.href="index.php";</script>');
}

if(!$result = mysqli_query($db, 'SELECT id, username, password FROM `users` WHERE username = "' . addslashes($_POST['username']) . '"'))
{
    mysqli_close($db);
    exit('database error.');
}
if(mysqli_num_rows($result) != 0)
{
    $row = mysqli_fetch_assoc($result);
    mysqli_close($db);
    $password = $_POST['pw'];
    $password_DB = $row['password'];

    if(password_verify($password, $password_DB))

    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 32; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        if(!setcookie('key', $randomString, time() + 86400)) exit('<meta charset="utf-8" /><script>alert("Login failed. Please contact the administrator."); location.href="index.php";</script>');
        $_SESSION['key'] = $randomString;
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['id'] = intval($row['id']);
        exit('<meta charset="utf-8" /><script>alert("Login success."); location.href = "index.php";</script>');
    }
}
else
{
    mysqli_close($db);
}
?><meta charset="utf-8" /><script>alert("Please check your ID or Password."); location.href = "login.html";</script>