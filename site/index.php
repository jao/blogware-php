<?
# $Author: blogware $
# $Date: 2003/07/07 22:04:43 $
# $Revision: 1.13 $

include_once '../inc/config.php';
include_once '../inc/functions.php';
include_once '../inc/'.$langFile;

$secao = 'home';
$secaoTitle = 'Home';

$postid = @$_REQUEST['pid'];
$arq = ($postid) ? mktime(0,0,0,date("n",$postid),1,date("Y",$postid)) : @$_GET['a'];

# Arquivo
$foo = mysql_fetch_array(mysql_query("select ID from `{$dbPrefix}POSTS` where PUBLISH = '1' and TYPE='post' order by ID asc limit 1"));

$htmArquivo = '';
$m = date("n",$foo['ID']);
$y = date("Y",$foo['ID']);

$utVar = mktime(0,0,0,$m,1,$y);

$utNow = mktime(0,0,0,date("n",$time),1,date("Y",$time));

while ($utNow >= $utVar){
	$htmArquivo .= '- '.(($arq==$utVar)?'<b>':'').'<a href="../index.php?a='.$utVar.'">'.utf8_encode($month[$m-1])."/".date("Y",$utVar).'</a>'.(($arq==$utVar)?'</b>':'').'<br>';

	$m++;
	if($m == 13){
		$m=1;
		$y++;
	}
	$utVar = mktime(0,0,0,$m,1,$y);
}

include_once '../inc/openHtml.php';
?>
<script type="text/javascript">
function init(){
<? if($postid) echo '	viewComments('.$postid.')'."\n" ?>
}
onload=init

// Transferencia entre frames
StatWaitDone = '<img src="<?= $siteUrl ?>/img/status_wait.gif" width="16" height="16" alt="" align="absmiddle"/> <?= $txt['commentLoading'] ?>';
StatWaitErro = '<img src="<?= $siteUrl ?>/img/status_error.gif" width="16" height="16" alt="" align="absmiddle"/> <?= $txt['commentError'] ?>';
</script>
</head>
<body <? if($use['centerLayout']) echo 'style="text-align: center"' ?>>
<? include_once '../inc/header.php' ?>
<div style="float: right; text-align: left">
<? include_once '../inc/plugins.php' ?>
</div>
<div style="text-align: left">
<?
# artigos
$article = mysql_query("select * from `{$dbPrefix}POSTS` where LEVEL<='$userInfo[LEVEL]' and TYPE='article' and ID='$postid' limit 1");
if($postid && $rs = mysql_fetch_array($article)){
?>
	<div class="contenttit">Artigos</div>
	<div class="content"><?= utf8_encode('Esse é um artigo postado no dia <b>'.date("d",$postid).' de '.$month[date("n",$postid)-1].' de '.date("Y",$postid).'</b>!') ?></div>
	<div class="date" onclick="ShowHide('<?= $rs['ID'] ?>')"><img id="img<?= $rs['ID'] ?>" src="../img/<?= $plusImg ?>open.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/><?= returnHtml($rs['TITLE']) ?></div>
	<div id="<?= $rs['ID'] ?>">
	<div class="content">
<?
	echo returnHtml($rs['MESSAGE']).'<br>'."\n";
	echo drawSignature($rs)."\n";
	echo drawDivViewComment($rs)."\n";
?>
	</div>
<?
}else{
	if($arq){
		$end = mktime(0,0,0,date("n",$arq)+1,1,date("Y",$arq));
		$query = "select * from `{$dbPrefix}POSTS` where PUBLISH = '1' and TYPE='post' and LEVEL<='$userInfo[LEVEL]' and ID between '$arq' and '$end' order by ID desc";
	}else{
		$query = "select * from `{$dbPrefix}POSTS` where PUBLISH = '1' and TYPE='post' and LEVEL<='$userInfo[LEVEL]' order by ID desc limit $howmanyposts";
	}

	$sql = mysql_query($query);

	if($arq){echo '<div class="contenttit">Arquivo</div><div class="content">'.utf8_encode('Esse é o arquivo do mês de <b>'.$month[date("n",$arq)-1].'</b>, aqui você encontrará os posts feitos nesse período!').'</div>';}

	$lastDay = '';
	while($rs = mysql_fetch_array($sql)){
		$thisday = getDay($rs['ID']);

		if($thisday != $lastDay){
			if($lastDay != '') echo '</div>';
?>
	<div class="date" onclick="ShowHide('<?= $rs['ID'] ?>')"><img id="img<?= $rs['ID'] ?>" src="../img/<?= $plusImg ?><?= (($postid && $postid != $rs['ID'])?'close':'open')?>.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/><?= date("d.m.Y",$rs['ID']) ?></div>
	<div id="<?= $rs['ID'] ?>"<? if($postid && getDay($postid) != $thisday) echo' style="display: none"' ?>>
<?
	}
?>
	<div class="content">
		<div class="title"><? if($postid) echo'<a name="id'.$rs['ID'].'">' ?><b><?= returnHtml(($rs['TITLE'])) ?></b></div>
<?
	echo returnHtml($rs['MESSAGE']).'<br>';
	echo drawSignature($rs);
	echo drawDivViewComment($rs);
?>
	</div>
<?
		$lastDay = getDay($rs['ID']);
	}
}
?>
</div>
<?
include_once '../inc/footer.php';
include_once '../inc/closeHtml.php';
?>
