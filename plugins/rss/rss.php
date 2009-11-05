<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.6 $

include_once '../../inc/config.php';
include_once '../../inc/functions.php';

header("Content-type: text/xml");
echo "<".chr(077)."xml version='1.0' encoding='UTF-8'".chr(077).">\n";

$sql = mysql_query("select * from `{$dbPrefix}POSTS` where PUBLISH='1' and TYPE='post' and LEVEL <= '10' order by ID desc limit $howmanyRSSposts");
?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns="http://purl.org/rss/1.0/">
<channel rdf:about="<? echo $siteUrl ?>">
	<title><? echo $site ?></title>
	<link><? echo $siteUrl ?></link>
	<description><? echo $siteTitle ?></description>
	<dc:language>en-us</dc:language>
	<dc:creator><? echo $adminLogin ?></dc:creator>
	<dc:rights>Copyright 2003 <? echo $adminLogin ?></dc:rights>
	<admin:generatorAgent rdf:resource="http://blogware.sourceforge.net" />
	<admin:errorReportsTo rdf:resource="mailto:<? echo $adminEmail ?>"/>
	<sy:updatePeriod>weekly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>
</channel>
<?
$sHtml = '';
while($rs = mysql_fetch_array($sql)){
	$sHtml .= '
	<item>
		<title>'.returnHtml($rs['TITLE']).'</title>
		<description><![CDATA['.returnHtml($rs['MESSAGE']).']]></description>
		<link>\''.$siteUrl.'/index.php?pid='.$rs['ID'].'\'</link>
		<dc:creator>'.$adminLogin.'</dc:creator>
		<dc:date>'.date("Y-m-d\TH:m:i",$rs['ID']).'+00:00</dc:date>
	</item>
';
}
echo $sHtml;
?>
</rdf:RDF>
<?
@mysql_free_result($sql);
@mysql_close($sqlCon);
?>