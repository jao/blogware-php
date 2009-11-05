<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.2 $
?>
			<div class="menutit" onclick="ShowHide('link')"><img id="imglink" src="../img/<?= $plusImg ?>open.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>links (a-z)</div>
			<div class="menu" id="link">
<?
$lastCat = '';

$sql = mysql_query("select * from `{$dbPrefix}LINKS` where PUBLISH_HOME='1' order by TYPE asc,URLTITLE,ID");

$linksHtml = '';
$count = mysql_num_rows($sql);
if($count > 0){
	$lastCat = '';
	while($rs = mysql_fetch_array($sql)){
		$thisCat = $rs['TYPE'];
		if($thisCat != $lastCat) $linksHtml .= '<b>'.returnHtml($rs['TYPE']).'</b><br/>';
		if($rs['DESCRIPTION']) $desc = $rs['DESCRIPTION'].utf8_encode("\r\n").'since:'.date("d.m.Y H:i",$rs['ID']);
		else $desc = 'since:'.date("d.m.Y H:i",$rs['ID']);
		$linksHtml .= '<a href="../site/links.php?id='.$rs['ID'].'" target="_blank" onfocus="blur()" onmouseover="return window.status=\''.$rs['URL'].'\'" onmouseout="return window.status=\'\'"title="'.$desc.'">'.returnHtml($rs['URLTITLE']).' ['.$rs['VISITS'].']</a><br/>';
		$lastCat = $rs['TYPE'];
	}
}else{
	$listHtml = "n&atilde;o h&aacute; registros.<br/>";
}
echo $linksHtml;
?>
			</div>