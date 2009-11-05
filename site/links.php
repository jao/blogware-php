<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.3 $

include_once '../inc/config.php';
include_once '../inc/functions.php';
include_once '../inc/'.$langFile;

$secao = 'links';
$secaoTitle = 'links';

$urlid = $_REQUEST['id'];

$sql = mysql_query("select * from {$dbPrefix}LINKS where ID='$urlid'") or die("URL not found.");
$rs = mysql_fetch_array($sql);
$n = $rs['VISITS']+1;
mysql_query("update {$dbPrefix}LINKS set VISITS='$n' where ID='$urlid'");

@Header("Location: ".$rs['URL']);
?>