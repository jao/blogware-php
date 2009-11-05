<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.10 $
?>
<? $tempon = getMs() ?>
<? if ($use['footer']){ ?>
<div class="footer">
	<a href="http://sourceforge.net"><img src="http://sourceforge.net/sflogo.php?group_id=84262&amp;type=1" width="88" height="31" border="0" alt="SourceForge.net Logo" style="float: right"/></a>
	powered by <b><a href="<?= $siteUrl ?>">blogware</a></b> 0.5 unstable<br/>
	<span class="fn-0">script executado em <?= round($tempon - $tempom,3); ?> segundos</span><br/>
</div>
<? } ?>
</div>