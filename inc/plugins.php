<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.5 $

# Search for plugins in the specified directory
# and include the files in the current file
if ($handle = opendir('../plugins')) {
	$array = array();
	while (false !== ($file = readdir($handle))) {
		if(preg_match('/^[^.]{1,2}/',$file)) $array[count($array)] = $file;
	}
	closedir($handle);
	sort($array);

	foreach ($array as $file) {
    if(substr($file,-4) != '.txt'){
			if ($file != "CVS"){
				if (filetype ("../plugins/$file") == "dir")
					include_once "../plugins/".$file."/plugin.php";
			}
		}
	}
}
?>