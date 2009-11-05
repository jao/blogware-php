					<div class="menutit" onclick="ShowHide('commentsTop')"><img id="imgcommentsTop" src="../img/<?= $plusImg ?>close.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>posts mais comentados</div>
					<div class="menu" id="commentsTop" style="display: none">
<?
$sql = mysql_query("select * from `{$dbPrefix}POSTS` where PUBLISH = '1' and TYPE='post' and LEVEL<='$userInfo[LEVEL]' and ID>'1045797201' order by COMMENTS desc, ID desc limit $howmanyitenstolist");
$count = @mysql_num_rows($sql);

if($count > 0){
	while($rs = mysql_fetch_array($sql)) {
		echo '<a href="../index.php?pid='.$rs['ID'].'" title="'.date("d/m/Y H:i",$rs['ID']).'">'.returnHtml($rs['TITLE']).' ['.$rs['COMMENTS'].']</a><br/>';
	}
} else {
	echo "- ".$txt['noregisters']."<br/>";
}
@mysql_free_result($sql);
?>
					</div>
