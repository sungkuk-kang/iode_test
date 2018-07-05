<?php
session_start();
if(isset($_SESSION['key'], $_COOKIE['key']) && $_SESSION['key'] == $_COOKIE['key'])
{
    setcookie('key', null, -1);
    exit('<meta charset="utf-8" /><script>alert("Logout success."); location.href="index.php";</script>');
}
?><meta charset="utf-8" /><script>alert("You are already logged out."); location.href="index.php";</script>