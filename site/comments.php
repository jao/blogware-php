<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.5 $

include_once '../inc/config.php';
include_once '../inc/functions.php';
include_once '../inc/'.$langFile;

$secao = 'comments';
$secaoTitle = 'comments';

$id = $_REQUEST['id'];
?>
<?='<?xml version="1.0" encoding="utf-8"?>'?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>blogware - action file</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<script type="text/javascript" src="../js/functions.js"></script>
<script>
var F = '';
var S = '';

F += '<form name="sendform<?= $id ?>" action="post_comment.php" method="get" onSubmit="addComments(this);return false;">';
F += '<input type="hidden" name="id" value="<?= $id ?>"/>';
<? if($userId != 7){ ?>
F += '<input type="hidden" name="userid" value="<?= $userInfo['ID'] ?>"/>';
F += '<?= '<b>'.returnHtml($userInfo['NAME']).'</b> ('.$userInfo['URL'].')' ?><br/>';
F += '<br/>';
<? }else{ ?>
F += '<input type="hidden" name="userid" value="7"/>';
F += '<b><?= returnHtml($userInfo['NAME']) ?></b><br/>';
F += '<input type="text" name="label" class="w-300" size="25" maxlength="25"><br/>';
<? } ?>
F += '<b>mensagem:</b><br/>';
F += '<textarea name="message" rows="10" class="w-400" onkeypress="countChar(this)"></textarea><br/>';
F += '<?= ubbCodeButtons('document.sendform'.$id.'.message') ?>';
F += '<input type="submit" value="comentar" onfocus="blur()"/></form>';
<?
$sql = mysql_query("select * from {$dbPrefix}COMMENTS where POSTID='$id' and PUBLISH='1' order by ID") or $error['mysql'] .= '1.'.mysql_error().'\n]';
$n = 0;

while($rs = mysql_fetch_array($sql)){
	$userid = $rs['USERID'];
	$userSql = mysql_query("select * from {$dbPrefix}USERS where ID='$userid'") or $error['mysql'] .= '2.'.mysql_error().'\n';
	$user = mysql_fetch_array($userSql);

	$alias = ($userid != 7) ? $user['NAME'] : $rs['LABEL'];
	$message = returnHtml($rs['MESSAGE']);
?>
S +='<?= $message ?>';
S +='<div align="right">';
S +='<b><?= stripslashes(utf8_encode($alias)) ?></b><? if($userid == 7){ echo ' / an&ocirc;nimo';} else {?> <? if($user['URL']){ echo' <a href="'.stripslashes($user['URL']).'" target="_blank">w</a>'; }} ?><br/>';
S +='<?= date("d/m/Y H:i",$rs['ID']) ?><br/>';
<? if($adminMode) echo "S +='".$rs['USERIP']." <a href=\"../admin?type=ban&formaction=add&ipaddress=".$rs['USERIP']."\" target=\"_blank\" style=\"color: #FF3300\"><b>ban</b></a> | <a href=\"../admin?type=comments&formaction=view&id=".$rs['ID']."\" target=\"_blank\" style=\"color: #FF3300\"><b>edit</b></a><br/>';\n" ?>
S +='</div>';
S +='<br/>';
<? $n++; } ?>
S += F;

function init(){
	writeComments(S,'<?= $id ?>',<?= $n ?>);
}
onload=init;

function sendInfo(){
	document.writecomments.submit();
}
</script>
</head>
<body>
comments.php -> form writecomments()<br/>
<form name="writecomments" action="post_comment.php" method="post" target="info">
<input type="hidden" name="id" value=""/>
</form>
</body>
</html>
<? if($error['mysql']) echo '<script>alert("'.addslashes($error['mysql']).'")</script>'."\n" ?>
