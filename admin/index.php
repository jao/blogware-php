<?
# $Author: blogware $
# $Date: 2003/07/29 17:58:27 $
# $Revision: 1.8 $

include_once '../inc/config.php';
include_once '../inc/functions.php';
include_once '../inc/'.$langFile;

$secao = 'admin';
$secaoTitle = 'Admin';

include_once '../inc/openHtml.php';
?>
<body>
<?
include_once '../inc/header.php';

# get values
$formaction = @$_REQUEST['formaction'];
$type = @$_REQUEST['type'];
$id = @$_REQUEST['id'];
$init = (@$_POST['init']) ? $_POST['init'] : 0;

$previewHtml = null;
$listHtml = null;
?>
<form name="postform" method="post" action="index.php" onsubmit="sO(this)"<?if($type == 'files') echo ' enctype="multipart/form-data"'?>>
<?
if($adminMode){
	if($type == 'posts'){
		$message = addslashes(utf8_decode(@$_POST['message']));
		$message = strip_tags($message,"");

		$title = addslashes(utf8_decode(@$_POST['title']));
		$publish = @$_POST['publish'];
		$posttype = @$_POST['posttype'];

		$level = @$_POST['level'];

		if ($formaction == 'add' && $title && $message) {
			mysql_query("insert into {$dbPrefix}POSTS (ID,TITLE,MESSAGE,LEVEL,TYPE,UID,PUBLISH) values ('$time','$title','$message','$level','$posttype','1337','$publish')") or $error['mysql'] .= mysql_error()."\n";
			$id = $time;
			$post['MESSAGE'] = $message;
			$formaction = 'view';
		} elseif ($formaction == 'delete' && isset($id)) {
			mysql_query("delete from {$dbPrefix}POSTS where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			mysql_query("delete from {$dbPrefix}COMMENTS where POSTID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$formaction = 'list';
		} elseif ($formaction == 'edit' && isset($id)) {
			mysql_query("update {$dbPrefix}POSTS set MESSAGE='$message', TITLE='$title', PUBLISH='$publish', LEVEL='$level', TYPE='$posttype' where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$message = stripslashes($message);
			$formaction = 'view';
		}
		if ($formaction == 'list') {
			$sql = mysql_query("select * from {$dbPrefix}POSTS") or $error['mysql'] .= mysql_error()."\n";
			$count = mysql_num_rows($sql);

			$sql = mysql_query("select * from {$dbPrefix}POSTS order by ID desc limit $init,$howmanyitens") or $error['mysql'] .= mysql_error()."\n";

			if($count > 0){
				$listHtml = "<b>Itens ".($init+1)." ~ ".(($count < $init+$howmanyitens) ? $count : $init+$howmanyitens)." / $count</b> (mostrando ".$howmanyitens." por p&aacute;gina)<br/><br/>";

				while($post = mysql_fetch_array($sql)){
					$listHtml .= '<a href="index.php?type=posts&amp;formaction=view&amp;postid='.$post['ID'].'">'.returnHtml($post['TITLE']).(($post['PUBLISH']==0)?' <i style="color: #FF3300">hidden</i>':'').'</a><br/>'."\n";
				}
				$listHtml .= printList();
			} else {
				$listHtml = "n&atilde;o h&aacute; registros."."<br/>\n";
			}

			$formaction = 'add';
		} elseif ($formaction == 'view' && $id) {
			$sql = mysql_query("select * from {$dbPrefix}POSTS where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$post = mysql_fetch_array($sql);
			$post['MESSAGE'] = stripslashes($post['MESSAGE']);
			$authorInfo = getUsrInfo($post['UID']);
			$previewHtml = '<b>'.returnHtml($post['TITLE']).'</b><br/><hr style="height: 1px"/>'.returnHtml($post['MESSAGE']).'<br/><br/>- <i style="color: #FF3300">'.$post['TYPE'].'</i> escrito por <b>'.$authorInfo['NAME'].'</b><br/>';
			$formaction = 'edit';
		} else {
			$formaction = 'post';
		}
	} elseif ($type == 'comments'){
		$message = addslashes(strip_tags(utf8_decode($_POST['message']),''));
		$publish = @$_POST['publish'];

		if ($formaction == 'delete' && isset($id)) {
			$sql = mysql_query("select * from {$dbPrefix}COMMENTS where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$com = mysql_fetch_array($sql);
			$postid = $com['POSTID'];
			mysql_query("delete from {$dbPrefix}COMMENTS where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			if($com['PUBLISH'] == 1){
				$sql_count = mysql_query("select * from {$dbPrefix}COMMENTS where POSTID='$postid' and PUBLISH='1'") or $error['mysql'] .= mysql_error()."\n";
				$n = mysql_num_rows($sql_count);
				mysql_query("update {$dbPrefix}POSTS set COMMENTS='$n' where ID='$postid'") or $error['mysql'] .= mysql_error()."\n";
			}
			$formaction = 'list';

		} elseif ($formaction == 'edit' && isset($id)) {
			$sql = mysql_query("select * from {$dbPrefix}COMMENTS where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$com = mysql_fetch_array($sql);
			$postid = $com['POSTID'];
			mysql_query("update {$dbPrefix}COMMENTS set MESSAGE='$message', PUBLISH='$publish' where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$sql_count = mysql_query("select * from {$dbPrefix}COMMENTS where POSTID='$postid' and PUBLISH='1'") or $error['mysql'] .= mysql_error()."\n";
			$n = mysql_num_rows($sql_count);
			mysql_query("update {$dbPrefix}POSTS set COMMENTS='$n' where ID='$postid'") or $error['mysql'] .= mysql_error()."\n";
			$message = stripslashes($message);
			$formaction = 'view';
		}

		if ($formaction == 'list') {
			$sql = mysql_query("select * from {$dbPrefix}COMMENTS") or $error['mysql'] .= mysql_error()."\n";
			$count = mysql_num_rows($sql);

			$sql = mysql_query("select * from {$dbPrefix}COMMENTS order by POSTID desc, ID desc limit $init,$howmanyitens") or $error['mysql'] .= mysql_error()."\n";

			if($count > 0){
				$listHtml = "<b>Itens ".($init+1)." ~ ".(($count < $init+$howmanyitens) ? $count : $init+$howmanyitens)." / $count</b> (mostrando ".$howmanyitens." por p&aacute;gina)<br/><br/>";

				$lastPOSTID = 0;
				$i=0;
				while($com = mysql_fetch_array($sql)) {
					$userTmp = getUsrInfo($com['USERID']);
					$postTmp = getPostInfo($com['POSTID']);

					if($com['POSTID'] != $lastPOSTID && $i!=0) $listHtml .= '</div>';
					if($com['POSTID'] != $lastPOSTID) $listHtml .= '<b><i>'.returnHtml($postTmp['TITLE']).'</i> ('.date('d/m/Y H:i',$com['POSTID']).')</b><div style="margin: 5px">';

					$username = ($userTmp['ID'] != 7) ? $userTmp['NAME'] : '<span style="color: #FF6600">'.returnHtml($com['LABEL']).'*</span>';
					$listHtml .= '<a href="index.php?type=comments&amp;formaction=view&amp;id='.$com['ID'].'">'.$username.' - '.date("d/m/Y H:i",$com['ID']).(($com['PUBLISH']==0)?' <i style="color: #FF3300">hidden</i>':'').'</a><br/>';

					$lastPOSTID = $com['POSTID'];
					$i++;
					if($i==$count) $listHtml .= '</div>';
				}
				$listHtml .= printList();
			} else {
				$listHtml = "n&atilde;o h&aacute; registros.<br/>";
			}

		}elseif($formaction == 'view' && $id){
			$sql = mysql_query("select * from {$dbPrefix}COMMENTS where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$com = mysql_fetch_array($sql);

			$userTmp = getUsrInfo($com['USERID']);
			$postTmp = getPostInfo($com['POSTID']);

			$com['MESSAGE'] = stripslashes($com['MESSAGE']);
			$username = ($userTmp['ID'] != 7) ? $userTmp['NAME'].'<br/>'.$userTmp['EMAIL'].' / '.$userTmp['URL'].'<br/>' : '<span style="color: #FF6600">'.returnHtml($com['LABEL']).'*</span>';
			$previewHtml ='<b>'.$username.'</b><br/>('.$com['USERIP'].')<br/>'.returnHtml($com['MESSAGE']).'<br/>';
			$formaction = 'edit';
		} else {
			$formaction = 'post';
		}
	} elseif ($type == 'links'){
		$urltitle = addslashes(utf8_decode(@$_POST['urltitle']));
		$url = addslashes(@$_POST['url']);
		$description = addslashes(utf8_decode(@$_POST['description']));
		$linktype = addslashes(utf8_decode(@$_POST['linktype']));
		$publish = @$_POST['publish'];
		$publishhome = @$_POST['publishhome'];

		if($formaction == 'add' && $urltitle && $url && $linktype){
			mysql_query("insert into {$dbPrefix}LINKS (ID,URLTITLE,URL,DESCRIPTION,TYPE,PUBLISH,PUBLISH_HOME) values ('$time','$urltitle','$url','$description','$linktype','$publish','$publishhome')") or $error['mysql'] .= mysql_error()."\n";
			$id = $time;
			$formaction = 'view';
		}elseif ($formaction == 'delete' && isset($id)){
			mysql_query("delete from {$dbPrefix}LINKS where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$formaction = 'list';
		}elseif ($formaction == 'edit' && isset($id)){
			mysql_query("update {$dbPrefix}LINKS set URLTITLE='$urltitle', URL='$url', DESCRIPTION='$description', TYPE='$linktype', PUBLISH='$publish', PUBLISH_HOME='$publishhome' where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$description = stripslashes($description);
			$formaction = 'view';
		}

		if ($formaction == 'list') {
			$sql = mysql_query("select * from {$dbPrefix}LINKS") or $error['mysql'] .= mysql_error()."\n";
			$count = mysql_num_rows($sql);

			$sql = mysql_query("select * from {$dbPrefix}LINKS order by TYPE,URLTITLE,ID limit $init,$howmanyitens") or $error['mysql'] .= mysql_error()."\n";

			if($count > 0){
				$listHtml = "<b>Itens ".($init+1)." ~ ".(($count < $init+$howmanyitens) ? $count : $init+$howmanyitens)." / $count</b> (mostrando ".$howmanyitens." por p&aacute;gina)<br/><br/>";

				$lastCat = '';

				while($link = mysql_fetch_array($sql)){
					$thisCat = $link['TYPE'];
					if($thisCat != $lastCat) $listHtml .= '<b>'.returnHtml($link['TYPE']).'</b><br/>';
					$listHtml .= '<a href="index.php?type=links&amp;formaction=view&amp;id='.$link['ID'].'">'.returnHtml($link['URLTITLE']).' ['.$link['VISITS'].']'.(($link['PUBLISH_HOME']==0)?(($link['PUBLISH']==0)?' <i style="color: #FF3300">hidden</i>':' <i style="color: #FF6600">listed</i>'):'').'</a><br/>';
					$lastCat = $link['TYPE'];
				}
				$listHtml .= printList();
			} else {
				$listHtml = "n&atilde;o h&aacute; registros.<br/>";
			}

			$formaction = 'add';
		} elseif ($formaction == 'view' && $id) {
			$sql = mysql_query("select * from {$dbPrefix}LINKS where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$link = mysql_fetch_array($sql);
			$previewHtml = '<b>'.returnHtml($link['URLTITLE']).'</b> <a href="../site/links.php?id='.$link['ID'].'" target="_blank">['.$link['VISITS'].']</a><br/><a href="'.$link['URL'].'" target="_blank">'.$link['URL'].'</a><br/>'.returnHtml($link['DESCRIPTION']).'<br/>tipo: <i>'.returnHtml($link['TYPE']).'</i><br/>'.(($link['PUBLISH_HOME']==0)?(($link['PUBLISH']==0)?' <i style="color: #FF3300">hidden</i>':' <i style="color: #FF6600">listed</i>'):'').'<br/>';
			$formaction = 'edit';
		} else {
			$formaction = 'post';
		}
	} elseif ($type == 'files'){
		$filetype = @$_REQUEST['filetype'];

		$description = addslashes(utf8_decode(@$_POST['description']));
		$description = strip_tags($description,"");

		$title = $_FILES['title']['name'];
		$size = $_FILES['title']['size'];
		$mimetype = $_FILES['title']['type'];

		if ($formaction == 'add' && $title) {

			if($filetype == 'img') $url = '../img/upload/';
			elseif($filetype == 'cam') $url = '../img/cam/';
			elseif($filetype == 'areka') $url = '../img/areka/';
			elseif($filetype == 'etc') $url = '../upload/';
			else die('please choose the location to save your file.');

			if(!file_exists($url.$title) && move_uploaded_file($_FILES['title']['tmp_name'],$url.$title)) chmod($url.$title,0666);
			else die('Não é permitido substituir arquivos.');

			mysql_query("insert into {$dbPrefix}FILES (ID,TITLE,URL,DESCRIPTION,TYPE,SIZE,MIMETYPE) values ('$time','$title','$url','$description','$filetype','$size','$mimetype')") or $error['mysql'] .= mysql_error()."\n";
			$id = $time;
			$file['DESCRIPTION'] = $description;
			$formaction = 'view';
		} elseif ($formaction == 'delete' && isset($id)) {

			$rs = mysql_fetch_array(mysql_query("select * from {$dbPrefix}FILES where ID='$id'"));

			if(unlink($rs['URL'].$rs['TITLE'])) mysql_query("delete from {$dbPrefix}FILES where ID='$id'") or $error['mysql'] .= mysql_error()."\n";

			$formaction = 'list';
		} elseif ($formaction == 'edit' && isset($id)) {
			mysql_query("update {$dbPrefix}FILES set DESCRIPTION='$description' where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$message = stripslashes($message);
			$formaction = 'view';
		}

		if ($formaction == 'list') {
			$sql = mysql_query("select * from {$dbPrefix}FILES") or $error['mysql'] .= mysql_error()."\n";
			$count = mysql_num_rows($sql);

			$sql = mysql_query("select * from {$dbPrefix}FILES order by ID desc limit $init,$howmanyitens") or $error['mysql'] .= mysql_error()."\n";

			if($count > 0){
				$listHtml = "<b>Itens ".($init+1)." ~ ".(($count < $init+$howmanyitens) ? $count : $init+$howmanyitens)." / $count</b> (mostrando ".$howmanyitens." por p&aacute;gina)<br/><br/>";

				while($file = mysql_fetch_array($sql)) {
					$listHtml .= '<a href="index.php?type=files&amp;formaction=view&amp;id='.$file['ID'].'">'.returnHtml($file['TITLE']).'</a><br/>'."\n";
				}
				$listHtml .= printList();
			} else {
				$listHtml = "n&atilde;o h&aacute; registros.<br/>\n";
			}

			$formaction = 'add';
		} elseif ($formaction == 'view' && $id) {
			$sql = mysql_query("select * from {$dbPrefix}FILES where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$file = mysql_fetch_array($sql);
			$file['DESCRIPTION'] = stripslashes($file['DESCRIPTION']);

			if(ereg('image',$file['MIMETYPE'])){
				$imgSize = getimagesize($file['URL'].$file['TITLE']);
				$preview = '<div align="center"><img src="'.$file['URL'].$file['TITLE'].'" style="width: '.$imgSize[0].'px; height: '.$imgSize[1].'px" style="border: 1px solid black"/></div><br/>';
			} else {
				$preview = '';
			}

			$previewHtml = '<b>'.returnHtml($file['TITLE']).'</b><br/><hr style="height: 1px"/>'.$preview.'<b>tipo:</b> '.$file['MIMETYPE'].'<br/><b>Tamanho:</b> '.($file['SIZE']/1024).'Kb<br/>'.returnHtml($file['DESCRIPTION']).'<br/>';
			$formaction = 'edit';
		} else {
			$formaction = 'post';
		}
	} elseif ($type == 'ban'){
		$ipAddress = @$_REQUEST['ipaddress'];

		if ($formaction == 'add' && $ipAddress) {
			mysql_query("insert into {$dbPrefix}BANNED(ID,USERIP) values ('$time','$ipAddress')") or $error['mysql'] .= mysql_error()."\n";
			$id = $time;
			$ban['USERIP'] = $ipAddress;
			$formaction = 'view';
		} elseif ($formaction == 'delete' && isset($id)) {
			mysql_query("delete from {$dbPrefix}BANNED where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$formaction = 'list';
		} elseif ($formaction == 'edit' && isset($id)) {
			mysql_query("update {$dbPrefix}BANNED set USERIP='$ipAdress' where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$ipAddress = stripslashes($ipAddress);
			$formaction = 'view';
		}

		if ($formaction == 'list') {
			$sql = mysql_query("select * from {$dbPrefix}BANNED") or $error['mysql'] .= mysql_error()."\n";
			$count = mysql_num_rows($sql);

			$sql = mysql_query("select * from {$dbPrefix}BANNED order by ID desc limit $init,$howmanyitens") or $error['mysql'] .= mysql_error()."\n";

			if($count > 0){
				$listHtml = "<b>Itens ".($init+1)." ~ ".(($count < $init+$howmanyitens) ? $count : $init+$howmanyitens)." / $count</b> (mostrando ".$howmanyitens." por p&aacute;gina)<br/><br/>";

				while($ban = mysql_fetch_array($sql)) {
					$listHtml .= "<a href=".$_SERVER['PHP_SELF']."?type=ban&amp;formaction=view&amp;id=".$ban['ID'].">".$ban['USERIP']." - banido em ".date("d/m/Y H:i",$ban['ID'])."</a><br/>\n";
				}
				$listHtml .= printList();
			}else{
				$listHtml = "n&atilde;o h&aacute; registros.<br/>";
			}

			$formaction = 'add';
		} elseif ($formaction == 'view' && $id) {
			$sql = mysql_query("select * from {$dbPrefix}BANNED where ID='$id'") or $error['mysql'] .= mysql_error()."\n";
			$ban = mysql_fetch_array($sql);

			$ban['USERIP'] = stripslashes($ban['USERIP']);
			$previewHtml = '<b>'.$ban['USERIP'].'</b> banido em '.date('d/m/Y H:i',$ban['ID']).'<br/>';
			$formaction = 'edit';
		} else {
			$formaction = 'post';
		}
	} else {
		$type = 'posts'	;
	}
?>
	<table cellpadding="0" cellspacing="0" border="0" width="760">
		<tr>
			<td valign="top">
					<div class="contenttit">SISTEMA DE ADMINISTRA&Ccedil;&Atilde;O :: <?= strtoupper($type) ?></div>
						<input type="hidden" name="formaction" value="<?= $formaction ?>"/>
						<input type="hidden" name="type" value="<?= $type ?>"/>
					<div class="contenttit" align="center">
						<input type="submit" value="adicionar" class="w-100" onclick="wC('add')"<? if($type == 'comments') echo " disabled" ?> style="font-weight:bold"/>
						<input type="submit" value="editar" class="w-100" onclick="wC('edit')"<? if($formaction == 'add') echo " disabled" ?>/>
						<input type="submit" value="listar" class="w-100" onclick="wC('list')"/>
						<input type="submit" value="apagar" class="w-100" onclick="wC('delete',this)"<? if($formaction == 'add') echo " disabled" ?>/>
					</div>
					<div class="content">
<? if($type == 'posts'){ ?>
						<b>T&iacute;tulo:</b><br/>
						<input type="text" size="25" name="title" style="width: 430px" value="<?=returnText(@$post['TITLE'])?>"/><br/>
						<br/>
						<b>Mensagem:</b><br/>
						<textarea name="message" rows="20" style="width:430px" onkeypress="countChar(this)"><?=returnText(@$post['MESSAGE'])?></textarea><br/>
						<?= ubbCodeButtons('d.postform.message') ?>
						<br/>
						<b>N&iacute;vel de seguran&ccedil;a:</b><br/>
						<select name=level style="width: 150px">
							<option value=10<? if(@$post['LEVEL']==10) echo' selected="selected"' ?>>Normal</option>
							<option value=20<? if(@$post['LEVEL']==20) echo' selected="selected"' ?>>Importante</option>
							<option value=30<? if(@$post['LEVEL']==30) echo' selected="selected"' ?>>Segredo</option>
						</select><br/>
						<br/>
						<b>Tipo de post:</b><br/>
						<select name=posttype style="width: 150px">
							<option value="post"<? if(@$post['TYPE']=='post') echo' selected="selected"' ?>>Normal</option>
							<option value="article"<? if(@$post['TYPE']=='article') echo' selected="selected"' ?>>Artigo</option>
						</select><br/>
						<br/>
						<b>publicar?</b> <input type="checkbox" name="publish" value="1"<? if( (isset($post['PUBLISH']) && $post['PUBLISH'] == 1) || !isset($post['PUBLISH']) ) echo' checked="checked"' ?> class="no-css"/><br/>
<? } elseif ($type == 'comments'){ ?>
						<b>Coment&aacute;rio:</b><br/>
						<textarea name="message" rows="10" style="width:430px" onkeypress="countChar(this)"><?=returnText(@$com['MESSAGE'])?></textarea><br/>
						<?= ubbCodeButtons('d.postform.message') ?>
						<br/>
						<b>publicar?</b> <input type="checkbox" name="publish" value="1"<? if( (isset($com['PUBLISH']) && $com['PUBLISH'] == 1) || !isset($com['PUBLISH']) ) echo' checked="checked"' ?> class="no-css"/><br/>
<? } elseif ($type == 'links'){ ?>
						<b>T&iacute;tulo:</b><br/>
						<input type="text" size="25" name="urltitle" style="width:430px" value="<?=returnText(@$link['URLTITLE'])?>"/><br/>
						<br/>
						<b>Endere&ccedil;o:</b><br/>
						<input type="text" size="25" name="url" style="width:430px" value="<?=@$link['URL']?>"/><br/>
						<br/>
						<b>Descri&ccedil;&atilde;o:</b><br/>
						<textarea name="description" rows="5" style="width:430px" onkeypress="countChar(this)"><?=returnText(@$link['DESCRIPTION'])?></textarea><br/>
						<br/>
						<b>Tipo:</b><br/>
						<select name="linktype" style="width:200px">
							<option value="blogs"<? if(@$link['TYPE']=='blogs') echo' selected="selected"' ?>>blogs</option>
							<option value="coding"<? if(@$link['TYPE']=='coding') echo' selected="selected"' ?>>coding</option>
							<option value="design"<? if(@$link['TYPE']=='design') echo' selected="selected"' ?>>design</option>
							<option value="geek"<? if(@$link['TYPE']=='geek') echo' selected="selected"' ?>>geek</option>
							<option value="forum"<? if(@$link['TYPE']=='forum') echo' selected="selected"' ?>>forum</option>
							<option value="funstuff"<? if(@$link['TYPE']=='funstuff') echo' selected="selected"' ?>>funstuff</option>
							<option value="news"<? if(@$link['TYPE']=='news') echo' selected="selected"' ?>>news</option>
							<option value="search engine"<? if(@$link['TYPE']=='search engine') echo' selected="selected"' ?>>search engine</option>
							<option value="webfolio"<? if(@$link['TYPE']=='webfolio') echo' selected="selected"' ?>>webfolio</option>
							<option value="wishlist"<? if(@$link['TYPE']=='wishlist') echo' selected="selected"' ?>>wishlist</option>
							<option value="etc"<? if(@$link['TYPE']=='etc') echo' selected="selected"' ?>>etc</option>
						</select>
						<br/>
						<b>publicar?</b> <input type="checkbox" name="publish" value="1"<? if( (isset($link['PUBLISH']) && $link['PUBLISH'] == 1) || !isset($link['PUBLISH']) ) echo' checked="checked"' ?> class="no-css"/><br/>
						<b>publicar na home?</b> <input type="checkbox" name="publishhome" value="1"<? if(@$link['PUBLISH_HOME'] == 1) echo' checked="checked"' ?> class="no-css"/><br/>
						<br/>
<? } elseif ($type == 'files') {?>
						<b>Arquivo:</b><br/>
						<input type="file" size="25" name="title" style="width:430px"/><br/>
						<br/>
						<b>Descri&ccedil;&atilde;o:</b><br/>
						<textarea name="description" rows="5" style="width:430px" onkeypress="countChar(this)"><?=returnText(@$file['DESCRIPTION'])?></textarea><br/>
						<br/>
						<select name="filetype" style="width:200px">
							<option>Selecione</option>
							<option value="img"<? if(@$file['TYPE']=='img') echo' selected="selected"' ?>>/img/upload</option>
							<option value="cam"<? if(@$file['TYPE']=='cam') echo' selected="selected"' ?>>/img/cam</option>
							<option value="areka"<? if(@$file['TYPE']=='areka') echo' selected="selected"' ?>>/img/areka</option>
							<option value="etc"<? if(@$file['TYPE']=='etc') echo' selected="selected"' ?>>/upload</option>
						</select>
<? } elseif ($type == 'ban') { ?>
						<b>Endere&ccedil;o IP:</b><br/>
						<input type="text" size="25" name="ipaddress" style="width:430px" value="<?=@$ban['USERIP']?>"/><br/>
						<br/>
<? } ?>
						<? if(@$id) echo'<input name="id" type="hidden" value="'.$id.'"/>' ?>

					</div>
					<div class="contenttit" align="center">
						<input type="submit" value="adicionar" class="w-100" onclick="wC('add')"<? if($type == 'comments') echo " disabled" ?> style="font-weight:bold"/>
						<input type="submit" value="editar" class="w-100" onclick="wC('edit')"<? if($formaction == 'add') echo " disabled" ?>/>
						<input type="submit" value="listar" class="w-100" onclick="wC('list')"/>
						<input type="submit" value="apagar" class="w-100" onclick="wC('delete',this)"<? if($formaction == 'add') echo " disabled" ?>/>
					</div>
					<div class="content">
<?
if($previewHtml && !$error['mysql']){
	echo $previewHtml.'<hr style="height: 1px"/>"'.$formaction.' '.$type.'" executado com sucesso.';
}elseif($listHtml && !$error['mysql']){
	echo $listHtml;
}elseif($error['mysql']){
	echo "<b>Erro</b><br/>".$error['mysql'];
}
?>
					</div>
				</td>
				<td valign="top">
					<div class="menutit" onclick="ShowHide('menu')"><img id="imgmenu" src="../img/<?= $plusImg ?>open.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>MENU</div>
					<div class="menu" id="menu">
						Em breve instru&ccedil;&otilde;es aqui.<br/>
						<br/>
						<a href="../index.php?" target="_blank"><b>ABRIR O BLOG</b></a><br/>
					</div>
					<div class="menutit" onclick="ShowHide('posts')"><img id="imgposts" src="../img/<?= $plusImg ?><?= ($type != 'posts')?'close':'open'?>.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>POSTS</div>
					<div class="menu" id="posts"<?if($type != 'posts') echo ' style="display: none"'?>>
						- <a href="?type=posts&amp;formaction=list"><b>listar/adicionar posts</b></a><br/>
						<br/>
<?
$sql = mysql_query("select * from {$dbPrefix}POSTS order by ID desc limit 30");
$count = @mysql_num_rows($sql);
if($count > 0){
	while($post = mysql_fetch_array($sql)){
		echo '						- <a href="index.php?type=posts&amp;formaction=view&amp;id='.$post['ID'].'">'.returnHtml($post['TITLE']).' ['.$post['COMMENTS'].'] '.(($post['PUBLISH']==0)?' <i style="color: #FF3300">hidden</i>':'').'</a><br/>'."\n";
	}
}else{
	echo "					- n&atilde;o h&aacute; registros.<br/>";
}
?>
					</div>
					<div class="menutit" onclick="ShowHide('comments')"><img id="imgcomments" src="../img/<?= $plusImg ?><?= ($type != 'comments')?'close':'open'?>.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>COMMENTS</div>
					<div class="menu" id="comments"<?if($type != 'comments') echo ' style="display: none"'?>>
						- <a href="?type=comments&amp;formaction=list"><b>listar/adicionar coment&aacute;rios</b></a><br/>
						<br/>
<?
$sql = mysql_query("select * from {$dbPrefix}COMMENTS order by POSTID desc, ID desc limit 30");
$count = @mysql_num_rows($sql);



if($count > 0){

	$lastPOSTID = 0;
	$i=0;
	while($com = mysql_fetch_array($sql)) {
		$userTmp = getUsrInfo($com['USERID']);
		$postTmp = getPostInfo($com['POSTID']);

		if($com['POSTID'] != $lastPOSTID && $i!=0) echo '</div>';
		if($com['POSTID'] != $lastPOSTID) echo '<b><i>'.returnHtml($postTmp['TITLE']).'</i> ('.date('d/m/Y H:i',$com['POSTID']).')</b><div style="margin: 5px">';

		$username = ($userTmp['ID'] != 7) ? $userTmp['NAME'] : '<span style="color: #FF6600">'.returnHtml($com['LABEL']).'*</span>';
		echo'					 <a href="index.php?type=comments&amp;formaction=view&amp;id='.$com['ID'].'">'.$username.' - '.date("d/m/Y H:i",$com['ID']).(($com['PUBLISH']==0)?' <i style="color: #FF3300">hidden</i>':'').'</a><br/>';

		$lastPOSTID = $com['POSTID'];
		$i++;
		if($i==$count) echo '</div>';
	}
} else {
	echo "					- n&atilde;o h&aacute; registros.<br/>";
}
?>
					</div>
					<div class="menutit" onclick="ShowHide('files')"><img id="imgfiles" src="../img/<?= $plusImg ?><?= ($type != 'files')?'close':'open'?>.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>FILES</div>
					<div class="menu" id="files"<?if($type != 'files') echo ' style="display: none"'?>>
						- <a href="?type=files&amp;formaction=list"><b>listar/adicionar arquivos</b></a><br/>
						<br/>
<?
$sql = mysql_query("select * from {$dbPrefix}FILES order by ID desc limit 30");
$count = @mysql_num_rows($sql);
if($count > 0){
	while($file = mysql_fetch_array($sql)){
		echo '					- <a href="index.php?type=files&amp;formaction=view&amp;id='.$file['ID'].'" title="'.returnHtml($file['DESCRIPTION']).'">'.returnHtml($file['TITLE']).'</a><br/>';
	}
}else{
	echo "					- n&atilde;o h&aacute; registros.<br/>";
}
?>
					</div>
					<div class="menutit" onclick="ShowHide('links')"><img id="imglinks" src="../img/<?= $plusImg ?><?= ($type != 'links')?'close':'open'?>.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>LINKS</div>
					<div class="menu" id="links"<?if($type != 'links') echo ' style="display: none"'?>>
						- <a href="?type=links&amp;formaction=list"><b>listar/adicionar links</b></a><br/>
						<br/>
<?
$sql = mysql_query("select * from {$dbPrefix}LINKS order by VISITS desc,ID asc limit 30");
$count = @mysql_num_rows($sql);
if($count > 0){
	while($link = mysql_fetch_array($sql)){
		echo '					- <a href="index.php?type=links&amp;formaction=view&amp;id='.$link['ID'].'" title="'.returnHtml($link['DESCRIPTION']).'">'.returnHtml($link['URLTITLE']).' ['.$link['VISITS'].'] '.(($link['PUBLISH_HOME']==0)?(($link['PUBLISH']==0)?' <i style="color: #FF3300">hidden</i>':' <i style="color: #FF6600">listed</i>'):'').'</a><br/>';
	}
}else{
	echo "					- n&atilde;o h&aacute; registros.<br/>";
}
?>
					</div>
					<div class="menutit" onclick="ShowHide('banned')"><img id="imgbanned" src="../img/<?= $plusImg ?><?= ($type != 'ban')?'close':'open'?>.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>BANNED</div>
					<div class="menu" id="banned"<?if($type != 'ban') echo ' style="display: none"'?>>
						- <a href="?type=ban&amp;formaction=list"><b>listar/adicionar ips banidos</b></a><br/>
						<br/>
<?
$sql = mysql_query("select * from {$dbPrefix}BANNED order by ID desc limit 30");
$count = @mysql_num_rows($sql);
if($count > 0){
	while($ban = mysql_fetch_array($sql)) {
		echo '					- <a href="index.php?type=ban&amp;formaction=view&amp;id='.$ban['ID'].'>'.$ban['USERIP'].' - banido em '.date("d/m/Y H:i",$ban['ID']).'</a><br/>';
	}
}else{
	echo "					- n&atilde;o h&aacute; registros.<br/>";
}
?>
					</div>
					<div class="menutit" onclick="document.location='logoff.php'">LOG OFF</div>
				</td>
			</tr>
		</table>
<? } else { ?>
	<div class="footer" align="center">
		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td><b>username:</b><br/><input type="text" name="login" size="10" class="w-150"/></td>
				<td><b>password:</b><br/><input type="password" name="password" size="10" class="w-150"/></td>
				<td valign="bottom"><input type="submit" value="ok"/></td>
			</tr>
<? if($error['login']) echo'					<tr><td colspan="3">Dados incorretos.</td></tr>' ?>
		</table>
		<br/>
	</div>
<? } ?>
</form>
<? include_once '../inc/footer.php' ?>
<? include_once '../inc/closeHtml.php' ?>
<?
function printList(){
	global $init,$formaction,$count,$howmanyitens;
	$endpost = $init+$howmanyitens;
	return'
				<input type="hidden" name="init" value="'.$init.'"/><br/>
				<div align="center">
					<table cellpadding="0" cellspacing="0" border="0" width="400">
						<tr>
							<td><input type="submit" value="<" style="width:25px" onclick="setRange('.($init-$howmanyitens).')" name="anterior" '.(($init <= 0)?' disabled':'').'/></td>
							<td align="right"><input type="submit" value=">" style="width:25px" onclick="setRange('.($init+$howmanyitens).')" name="proxima" '.(($endpost >= $count) ? ' disabled':'').'/></td>
						</tr>
					</table>
				</div>
';
}
?>