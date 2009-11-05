<?
# $Author: blogware $
# $Date: 2003/07/04 20:50:38 $
# $Revision: 1.4 $

include_once 'inc/config.php';
include_once 'inc/functions.php';

$secao = 'home';
$secaoTitle = 'Home';

$postid = @$_REQUEST['pid'];
$arq = @$_REQUEST['a'];
?>
<?='<?xml version="1.0" encoding="utf-8"?>'?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"><head><title>blogware :: <?= $siteTitle ?></title><meta http-equiv="Content-Type" content="text/html;charset=utf-8"/><meta name="Description" content="Blog do j&atilde;o, ex - passandomal.com"/><meta name="keywords" content="blog, jao, passandomal, blogware, none.com.br"/><link rel="shortcut icon" href="./favicon.ico"/></head>
<frameset rows="100%,*" style="border:0px; margin:0px">
	<frame src="site/index.php?<? if($arq) echo 'a='.$arq.'&amp;' ?><? if($postid) echo 'pid='.$postid.'#id'.$postid ?>" name="main" noresize="noresize" id="main"/>
	<frame src="site/post_comment.php" name="info" scrolling="no" id="info"/>
</frameset>
</html>
