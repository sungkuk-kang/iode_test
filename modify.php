<?php

if(isset($_POST['id'], $_POST['scientificname'], $_POST['longitude'], $_POST['latitude']))
{
    $id = intval($_POST['id']);
    $db = 'project';
    include '.htinclude.php';
    if($login)
    {
        echo '<meta charset="utf-8" /><script>alert("Editing ' . (mysqli_query($db, '
        UPDATE observations SET scientificname = "' . addslashes($_POST['scientificname']) . '", longitude = "' . addslashes($_POST['longitude']) . '", latitude = "' . addslashes($_POST['latitude']) . '" WHERE id = "' . $id . '"
        ') ? 'success' : 'failed') . '."); location.href="view.php?id=' . $id . '";</script>';
    }
    else
    {
        echo '<meta charset="utf-8" /><script>alert("Permission denied."); location.href="view.php?id=' . $id . '";</script>';
    }

    mysqli_close($db);
    exit();
}

if(!isset($_GET['id']) || ($id = intval($_GET['id'])) == 0)
{
    header("Location: index.php");
    exit();
}

$db = 'project';
include '.htinclude.php';

$result = mysqli_query($db, 'SELECT scientificname, longitude, latitude, `user_id` FROM observations WHERE id = ' . $id);

if($result === false)
{
    mysqli_close($db);
    exit('<meta charset="utf-8" /><script>alert("Database error!"); location.href="index.php";</script>');
}

if(mysqli_num_rows($result) == 0)
{
    mysqli_close($db);
    exit('<meta charset="utf-8" /><script>alert("Cannot find the data."); location.href="index.php";</script>');
}

$row = mysqli_fetch_assoc($result);
echo '<meta charset="utf-8" />
<form action="modify.php" onsubmit="return validateForm()" method="POST">
    Scientificname : <input type="text" name="scientificname" value="' . addslashes($row['scientificname']) . '" pattern="[a-zA-Z0-9]+" required/> <br>
    Longitude : <input type="number" name="longitude" value="' . htmlspecialchars($row['longitude']) . '" step="any" required/> <br>
    Latitude : <input type="number" name="latitude" value="' . htmlspecialchars($row['latitude']) . '" step="any" required/> <br>
    <input type="hidden" name="id" value="' . $id . '">
    <input type="submit" value="modify" />
</form>';
?>