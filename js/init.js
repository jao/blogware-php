/*
$Author: blogware $
$Date: 2003/07/04 20:56:00 $
$Revision: 1.2 $
*/

function initDefault(){
	if(isDef('init'))init()
	if(is.ns4)BUG_ns4_reloadOnResize()
}
onload=initDefault

