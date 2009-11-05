<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.7 $
?>
			<script type="text/javascript">function chWebCam(x,id){d.images[id].src=x;}</script>
			<div class="menutit" onclick="ShowHide('webcam')"><img id="imgwebcam" src="../img/<?= $plusImg ?>open.gif" style="width: 12px; height: 12px" align="absmiddle" alt=""/>webcam</div>
			<div class="menu" id="webcam">
<?
if ($handle = opendir('../plugins/webcam')) {
	$aryImg = array();
	while (false !== ($file = readdir($handle))) {
		if(preg_match('/(jpg|gif|png)$/',$file)) $aryImg[count($aryImg)] = $file;
	}
	closedir($handle);
	sort($array);
}
$htmCam = '';
for($i=0;$i<count($aryImg);$i++){
	$htmCam .= '<a href="javascript:chWebCam(\'../plugins/webcam/'.$aryImg[$i].'\',\'webcamImg\')" onfocus="blur()">x</a> ';
}
echo '<div align="center"><img src="../plugins/webcam/'.$aryImg[0].'" name="webcamImg" style="width:280px;height:210px;border:1px solid black"/></div>
'.$htmCam;
?>
			</div>
