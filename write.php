<?php
if(!isset($_POST['scientificname'], $_POST['longitude'], $_POST['latitude'])) exit('<meta charset="utf-8" /><script>alert("Access denied."); location.href="index.php";</script>');

$db = 'project';
include '.htinclude.php';

if(!$login)
{
    mysqli_close($db);
    exit('<meta charset="utf-8" /><script>alert("Please login."); location.href="index.php";</script>');
}

mysqli_query($db, 'SET time_zone = "+9:00"');

$query = 'INSERT INTO `observations` (scientificname, longitude, latitude, `date`, `user_id`) VALUES ("' . addslashes(urldecode($_POST['scientificname'])) . '", "' . addslashes(urldecode($_POST['longitude'])) . '", "' . addslashes(urldecode($_POST['latitude'])) . '", NOW(), "' . $_SESSION['id'] . '")';

if(!mysqli_query($db, $query))
{
    mysqli_close($db);
    exit('<meta charset="utf-8" /><script>alert("Fail to register."); location.href="index.php";</script>');
}


mysqli_close($db);
?><meta charset="utf-8" /><script>alert("Successfully registed."); location.href="index.php";</script>