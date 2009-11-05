<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.3 $
?>
			<div class="menutit" onclick="ShowHide('article')"><img id="imgarticle" src="../img/<?= $plusImg ?><?= ((!$postid && !$article)?'close':'open') ?>.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>artigos</div>
			<div class="menu" id="article"<?if(!$postid && !$article) echo' style="display: none"' ?>>
<?
if($postid && $article) echo'<a href="../" target="_top"><b>voltar para o blog</b></a><br/><br/>';
$sql = mysql_query("select * from {$dbPrefix}POSTS where TYPE='article' and PUBLISH='1' order by ID desc");
$count = mysql_num_rows($sql);
if($count > 0){
	while($post = mysql_fetch_array($sql)){
		echo '			- <a href="../?pid='.$post['ID'].'" target="_top">'.returnHtml($post['TITLE']).' ['.$post['COMMENTS'].']</a><br/>'."\n";
	}
}else{
	echo "			- n&atilde;o h&aacute; registros.<br/>\n";
}
?>
			</div>