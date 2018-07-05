<?php
$db = 'project';
include '.htinclude.php';

if(($id = intval($_GET['id'])) != 0 && $login)
{
    $result = mysqli_query($db, 'DELETE FROM observations WHERE id = "' . $id . '"');
    echo '<meta charset="utf-8" /><script>alert("Delete ' . ($result ? 'completed' : 'failed') . '."); location.href="'
    . ($result ? 'index.php' : ('view.php?id=' . $id)) . '";</script>';

    mysqli_close($db);
    exit();
}

mysqli_close($db);

?><meta charset="utf-8" /><script>alert("Permission denied."); location.href="index.php";</script>