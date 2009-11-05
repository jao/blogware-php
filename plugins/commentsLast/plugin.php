			<div class="menutit" onclick="ShowHide('lastcommentlist')"><img id="imglastcommentlist" src="../img/<?= $plusImg ?>close.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>&uacute;ltimos coment&aacute;rios</div>
			<div class="menu" id="lastcommentlist" style="display: none">
<?
$sql = mysql_query("select * from {$dbPrefix}COMMENTS where PUBLISH='1' order by ID desc limit $howmanyitenstolist");
$count = mysql_num_rows($sql);
if($count > 0){
	while($rs = mysql_fetch_array($sql)){
		$userTmp = getUsrInfo($rs['USERID']);
		$postTmp = getPostInfo($rs['POSTID']);
		
		$username = ($userTmp['ID'] != 7) ? $userTmp['NAME'] : $rs['LABEL'];
		?><a href="../?pid=<?= $post['ID'] ?>" title="<?= date("d/m/Y H:i",$rs['ID']) ?>"><b><?= stripslashes(utf8_encode($username)) ?></b> @ <?= $postTmp['TITLE'] ?></a><?
		if($adminMode) { ?><?= $rs['USERIP'] ?><a href="../admin?type=ban&formaction=add&ipaddress=<?= $rs['USERIP'] ?>" target="_blank" style="color: #FF3300"><b>ban</b></a> | <a href="../admin?type=comments&formaction=view&id=<?= $rs['ID'] ?>" target="_blank" style="color: #FF3300"><b>edit</b></a><? } ?><br/><?
	}
}else{
	echo "- ".$txt['noregisters']."<br/>";
}
@mysql_free_result($sql);
?>
			</div>
