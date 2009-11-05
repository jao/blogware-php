<?
# $Author: blogware $
# $Date: 2003/07/29 17:51:46 $
# $Revision: 1.20 $

if ($mysqlCon && $mysqlServer && $mysqlUser && $mysqlPasswd && $mysqlDb) {
	if (!$mysqlCon = @mysql_connect($mysqlServer,$mysqlUser,$mysqlPasswd)) echo 'could not connect to MySQL server at '.$mysqlServer.'<br/>';
	if (!$con = @mysql_select_db($mysqlDb,$mysqlCon)) echo 'could not connect to specified database ('.$mysqlDb.')<br/>';
}

if(@$_COOKIE['login'] == $adminLogin && @$_COOKIE['password'] == $adminPassword){
	$adminMode = true;
} elseif(@$_REQUEST['login'] == $adminLogin && @$_REQUEST['password'] == $adminPassword){
	setcookie ("password", $adminPassword,time()+3153600,"/","blogware.sourceforge.net", 0);
	setcookie ("login", $adminLogin,time()+3153600,"/","blogware.sourceforge.net", 0);
	$adminMode = true;
}elseif(@$_REQUEST['login'] && @$_REQUEST['password']){
	$error['login'] = true;
}

# user_whois
$userId = (@$_COOKIE['userId']) ? $_COOKIE['userId'] : 7;
$userInfo = getUsrInfo($userId);

function getUsrInfo($id){
	global $dbPrefix;
	$sql = mysql_query("select * from `{$dbPrefix}USERS` where ID='$id'");
	$rs = @mysql_fetch_array($sql);
	@mysql_free_result($sql);
	return $rs;
}

function getPostInfo($id){
	global $dbPrefix;
	$sql = mysql_query("select * from {$dbPrefix}POSTS where ID='$id'");
	$rs = mysql_fetch_array($sql);
	@mysql_free_result($sql);
	return $rs;
}

# security
function banning(){
	global $userIP,$dbPrefix;
	$ip = explode('.',$userIP);
	$sql = mysql_query("select USERIP from {$dbPrefix}BANNED where (USERIP='$userIP' or USERIP='$ip[0].$ip[1].$ip[2].*' or USERIP='$ip[0].$ip[1].*.*')");
	if (@mysql_num_rows($sql) != 0) {
		@mysql_free_result($sql);
		Header('Location: ../site/notallowed.php');
		return;
	}
}
banning();

# get a day value, at 0h0m0s
function getDay($unixtimestamp){
	return mktime(0,0,0,date('m',$unixtimestamp),date('d',$unixtimestamp),date('Y',$unixtimestamp));
}

# return microtime value
function getMs(){
	$mtime = explode(" ",microtime());
	return $mtime[1] + $mtime[0];
}
$tempom = getMs();

function ubbCode($s){
	global $layoutwidth,$siteUrl;
	$s = str_replace ("<",'&lt;', $s);
	$s = str_replace (">",'&gt;', $s);
	$s = str_replace ('"','&quot;', $s);
	$s = str_replace ("'",'&#039;', $s);
	$s = str_replace ("[[","&#091;",$s);
	$s = str_replace ("]]","&#093;",$s);
	$s = str_replace ("|","&#124;",$s);

	$s = str_replace ("[b]", "<b>", $s);
	$s = str_replace ("[i]", "<i>", $s);
	$s = str_replace ("[u]", "<u>", $s);
	$s = str_replace ("[s]", "<s>", $s);
	$s = str_replace ("[/b]", "</b>", $s);
	$s = str_replace ("[/i]", "</i>", $s);
	$s = str_replace ("[/u]", "</u>", $s);
	$s = str_replace ("[/s]", "</s>", $s);
	$s = str_replace ("[sup]", "<sup>", $s);
	$s = str_replace ("[/sup]", "</sup>", $s);
	$s = str_replace ("[sub]", "<sub>", $s);
	$s = str_replace ("[/sub]", "</sub>", $s);

	preg_match_all("/\[img\](http(s)?:\/\/[^\[]+)\[\/img\]/",$s,$matches,PREG_SET_ORDER);
	for($i=0; $i<count($matches); $i++){
		# $imgSize = @getimagesize($matches[$i][1]);
		# $imgHtml = '<img src="'.$matches[$i][1].'" style="border: 1px solid black"'.(($imgSize[0] > $layoutwidth)?' width='.$layoutwidth:'').'>';
		$imgHtml = '<img src="'.$matches[$i][1].'" style="border: 1px solid black">';
		$s = str_replace($matches[$i][0],$imgHtml,$s);
	}

	preg_match_all("/\[img\]([^\[:]+)\[\/img\]/", $s, $matches,PREG_SET_ORDER);
	for($i=0; $i<count($matches); $i++){
		$imgSize = getimagesize('../img/upload/'.$matches[$i][1]);
		$imgHtml = '<img src="'.$siteUrl.'/img/upload/'.$matches[$i][1].'" style="border: 1px solid black"'.(($imgSize[0] > $layoutwidth)?' width='.$layoutwidth:'').'>';
		$s = str_replace($matches[$i][0],$imgHtml,$s);
	}

	$s = str_replace ("[tt]", "<tt>", $s);
	$s = str_replace ("[/tt]", "</tt>", $s);
	$s = str_replace ("[center]", "<div align=\"center\">", $s);
	$s = str_replace ("[/center]", "</div>", $s);
	$s = str_replace ("[left]", "<div align=\"left\">", $s);
	$s = str_replace ("[/left]", "</div>", $s);
	$s = str_replace ("[right]", "<div align=\"right\">", $s);
	$s = str_replace ("[/right]", "</div>", $s);
	$s = str_replace ("[align=(\w+)]", '<div align="$1">', $s);
	$s = str_replace ("[/align]", "</div>", $s);
	$s = str_replace ("[monospace]", "<span style=\"font-family: monospace,serif\">", $s);
	$s = str_replace ("[/monospace]", "</span>", $s);
	$s = str_replace ("[hr]","<hr width=\"100%\" size=\"1\" />",$s);
	$s = preg_replace("/\[script=([^\]]+)\]([^\[]+)\[\/script\]/","<a href=\"#\" onclick=\"$1\">$2</a>",$s);
	$s = preg_replace("/\[url=([^\]]+)\]([^\[]+)\[\/url\]/","<a href=\"$1\" target=\"_blank\">$2</a>",$s);
	$s = preg_replace("/\[url\]([^\[]+)\[\/url\]/","<a href=\"$1\" target=\"_blank\">$1</a>",$s);

	preg_match_all("/\[link=(\d+)\]([^\[]+)?\[\/link\]/", $s, $matches,PREG_SET_ORDER);
	for($i=0; $i<count($matches); $i++){
		$linkHtml = makeLink($matches[$i][1],$matches[$i][2]);
		$s = str_replace($matches[$i][0],$linkHtml,$s);
	}

	$s = preg_replace("/\[email\]([^\[]+)\[\/email\]/","<a href=\"mailto:$1\">$1</a>",$s);
	$s = preg_replace("/\[email=([^\]]+)\]([^\]]+)\[\/email\]/","<a href=\"mailto:$1\">$2</a>",$s);
	$s = str_replace("[pre]","<pre>",$s);
	$s = str_replace("[/pre]","</pre>",$s);
	$s = str_replace("\n","<br/>",$s);
	$s = str_replace("\r","",$s);
	return $s;
}

function returnHtml($s){
	$s = strip_tags($s,"");
	$s = stripslashes($s);
	$s = ubbCode($s);
	return utf8_encode($s);
}

function returnText($s){
	$s = stripslashes($s);
	$s = str_replace ("<",'&lt;', $s);
	$s = str_replace (">",'&gt;', $s);
	$s = str_replace ('"','&quot;', $s);
	$s = str_replace ("'",'&#039;', $s);
	return utf8_encode($s);
}

function makeLink($id,$text=null){
	global $dbPrefix;
	$sql = mysql_query("select * from `{$dbPrefix}LINKS` where ID = '$id'");
	$rs = mysql_fetch_array($sql);
	@mysql_free_result($sql);
	return '<a href="../site/links.php?id='.$rs['ID'].'" target="_blank" onmouseover="return window.status=\''.$rs['URL'].'\'" onmouseout="return window.status=\'\'">'.(($text)?$text:$rs['URLTITLE']).'</a>';
}

function ubbCodeButtons($f){
	return '<div style="margin-bottom: 5px"><input type="button" value="b" onclick="addCode(this,'.$f.')" style="width:20px; font-weight: bold"/> <input type="button" value="i" onclick="addCode(this,'.$f.')" style="width:20px; text-decoration: italic"/> <input type="button" value="u" onclick="addCode(this,'.$f.')" style="width:20px; text-decoration: underline"/> | <input type="button" value="img" onclick="addCode(this,'.$f.')" style="width:35px"/> <input type="button" value="url" onclick="addCode(this,'.$f.')" style="width:35px"/> <input type="button" value="link" onclick="addCode(this,'.$f.')" style="width:35px"/> | <input type="button" value="center" onclick="addCode(this,'.$f.')" style=""/> | <input type="text" name="counter" readonly="readonly" style="width: 40px;text-align: right" value="0"></div>';
}

function MyGlob($dir='.',$regEx='.+') {
	$array = array();
	$content = opendir($dir);
  while($file = readdir($content)){
    if(eregi($regEx,$file)) $array[count($array)] = $file;
  }
  closedir($content);
  return sort($array);
}

function sendMail($name,$msg){
	global $adminEmail,$time,$id,$userIP,$_ENV,$siteUrl;
	$queryString = '?pid='.$id;
	$message = '-----------------------------------------------------------
Blogware :: comment++ - '.date("d/m/Y H:i's\"",$time).'
-----------------------------------------------------------

'.stripslashes($name).' (ip: '.$userIP.')
escreveu em '.date("d/m/Y H:i's\"",$id).':

'.stripslashes($msg).'

-----------------------------------------------------------
'.$siteUrl.$queryString;

	# To send HTML mail, you can set the Content-type header.
	$header  = "From: Blogware Engine <blogware@none.com.br>
Return-Path: blogware@none.com.br
";
	# and now mail it
	return mail($adminEmail,'comments++', $message, $header);
}

function drawSignature($rs){
	global $txt,$use;
	$authorInfo = getUsrInfo($rs['UID']);
	$sHtml = '<div class="comment">'."\n";
	if ($use['avatar']){
		$sHtml .= '<div style="float:right; width:48px; height:48px; background-color:#FFFFFF; border: 1px solid black; text-align: center"><img src="'.$authorInfo['AVATAR'].'" alt="'.$authorInfo['NAME'].'"></div>'."\n<br/>\n<br/>\n";
	}
	$sHtml .= '<a href="'.$siteUrl.'/?pid='.$rs['ID'].'" target="_blank" onfocus="blur()"><b>'.date("H:i",$rs['ID']).'</b></a>';
	if($use['comments']){
		$sHtml .= '<br/>
		<a href="javascript:viewComments(\''.$rs['ID'].'\')" onfocus="blur()" id="num'.$rs['ID'].'">'.$rs['COMMENTS'].' '.$txt['comment'].(($rs['COMMENTS'] == 1)?'':'s').'</a>';
	}
	$sHtml .= '</div>';
	return $sHtml;
}

function drawDivViewComment($rs){
	return '<div id="cm'.$rs['ID'].'" class="viewcomments" style="display: none"></div>';
}

$blogwareLink = '<b><a href="http://blogware.sourceforge.net" target="_blank">blogware</a></b>';
?>