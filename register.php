<?php

if(!isset($_POST['username'], $_POST['pw'])) header('Location: register.html');

$db = 'project';
include '.htinclude.php';

$result = mysqli_query($db, 'INSERT INTO `users` (username, password) VALUES ("' . addslashes($_POST['username']) . '", "' . password_hash(($_POST['pw']), PASSWORD_DEFAULT) . '")');
if($result === true)
{
    echo '<meta charset="utf-8" /><script>alert("ID registration success."); location.href = "login.html";</script>';
}
else
{
    echo '<meta charset="utf-8" /><script>alert("ID registration failed."); history.back();</script>';
}

?>