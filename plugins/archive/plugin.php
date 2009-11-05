<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.3 $
?>
			<div class="menutit" onclick="ShowHide('archive')"><img id="imgarchive" src="../img/<?= $plusImg ?><?= ((!$arq)?'close':'open') ?>.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>arquivos</div>
			<div class="menu" id="archive"<?if(!$arq) echo' style="display: none"' ?>>
				<? if($postid) echo'<a href="../" target="_top"><b>voltar para o blog</b></a><br/><br/>'; ?>
				<?= $htmArquivo ?>
			</div>