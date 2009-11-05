<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.20 $

# Site info
$site = 'blogware';
$siteTitle = 'blogware';
$siteUrl = '..';

# Admin/Login info
$adminLogin = 'blogwareuser';
$adminPassword = '123';
$adminEmail = 'blogware@mandic.com.br';

$cssFile = 'original.css';
$langFile = 'lang.pt.php';
$plusImg = 'seta_';

$layoutwidth = 400;
$howmanyitens = 5;
$howmanyitenstolist = 15;
$howmanyposts = 3;
$howmanyRSSposts = 30;

$use['header'] = true;
$use['footer'] = true;
$use['centerLayout'] = true;
$use['avatar'] = true;
$use['comments'] = true;
$use['commentsByEmail'] = true;

# MySQL info
$mysqlCon = true;
$mysqlServer = "localhost";
$mysqlUser = "";
$mysqlPasswd = "";
$mysqlDb = "";

$dbPrefix = 'BW_';

# general info
$adminMode = false;
$userInfo = false;
$error['mysql'] = null;
$error['login'] = null;

$userIP = $_SERVER['REMOTE_ADDR'];
$userAgent = $_ENV['HTTP_USER_AGENT'];

$time = time();

$aboutMe = '';
?>
