<?
# $Author: blogware $
# $Date: 2003/07/04 20:55:59 $
# $Revision: 1.2 $

session_start();
$_SESSION['login'] = '';
$_SESSION['password'] = '';
session_write_close();
$adminMode = false;

header("Location: index.php");
?>
