<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.3 $

include_once '../inc/config.php';
include_once '../inc/functions.php';
include_once '../inc/'.$langFile;

$secao = 'postcomment';
$secaoTitle = 'postcomment';
?>
<?='<?xml version="1.0" encoding="utf-8"?>'?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>blogware :: <?= $siteTitle ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<script type="text/javascript" src="../js/functions.js"></script>
<?
if(@$_REQUEST['id'] != ''){

	# get values

	$id = $_REQUEST['id'];
	$userid = $_REQUEST['userid'];
	$label = addslashes(strip_tags(utf8_decode($_REQUEST['label']),''));

	$message = utf8_decode($_REQUEST['message']);
	# $message = strip_tags($message,'');
	$message = str_replace("<",'&lt;',$message);
	$message = str_replace(">",'&gt;',$message);
	$message = str_replace('"','&quot;',$message);
	$message = str_replace("'",'&#039;',$message);

	$message = addslashes($message);

	if($id && $label && $message){
		$sql_count = mysql_query("select count(*) from {$dbPrefix}COMMENTS where POSTID = '$id' and PUBLISH='1'") or $error['mysql'] .= '1.'.mysql_error().'\n';
		$n = mysql_result($sql_count,0,0);
		$n++;

		mysql_query("update {$dbPrefix}POSTS set COMMENTS='$n', `UPDATED`='$time' where ID='$id'") or $error['mysql'] .= '2.'.mysql_error().'\n';
		mysql_query("insert into {$dbPrefix}COMMENTS (ID,POSTID,USERID,LABEL,MESSAGE,USERIP) values ('$time','$id','$userid','$label','$message','$userIP')") or $error['mysql'] .= '3.'.mysql_error().'\n';

		sendMail($label,$message);
	}
?>
<script type="text/javascript">
function init(){readComments('<?= $id ?>',true);}
function sendInfo(){document.addcomment.submit();}
onload=init
</script>
</head>
<body>
post_comment.php -> read Comments()<br/>
<? }else{ ?>
<script type="text/javascript">
function sendInfo(){document.addcomment.submit();}
</script>
</head>
<body>
post_comment.php -> form AddComments()<br/>
<? } ?>
<a href="index.php">voltar para a página principal</a><br/>
<form name="addcomment" action="" method="post" target="info">
<input type="hidden" name="id" value=""/>
<input type="hidden" name="userid" value=""/>
<input type="hidden" name="label" value=""/>
<input type="hidden" name="message" value=""/>
</form>
</body>
</html>
