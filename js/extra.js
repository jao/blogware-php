/*
$Author: blogware $
$Date: 2003/07/04 20:56:00 $
$Revision: 1.2 $
*/

function addLink() {
	strSelection = d.selection.createRange().text
	if (strSelection == "") d.postform.message.focus()
	strHref = prompt("Enter the URL you want to link to:","http://")
	if (strHref == null) return
	d.selection.createRange().text = '<a href="' + strHref + '" target=_blank>' + strSelection + "</a>"
	return
}

function addImg() {
	strSelection = d.selection.createRange().text
	if (strSelection == "") d.postform.message.focus()
	strImg = prompt("Enter the URL of the image","http://keks.bitterfairy.com/")
	altText = prompt("Enter text","")
	
	if (strImg == null) return
	d.selection.createRange().text = '<img src="' + strImg +'" border=0 alt="'+altText+'">' + strSelection
	return
}

function addBold(from) {
	strSelection = d.selection.createRange().text
	if (strSelection == "") {
		d.postform.message.focus()
		if (from == 2) d.postform.message.select()
		strSelection = d.selection.createRange().text
		d.selection.createRange().text = strSelection + "<b></b>"
	}
	else d.selection.createRange().text = "<b>" + strSelection + "</b>"
	return;
}
function addItalic(from) {
	strSelection = d.selection.createRange().text
	if (strSelection == "") {
		d.postform.message.focus()
		if (from == 2) d.postform.message.select()
		strSelection = d.selection.createRange().text
		d.selection.createRange().text = strSelection + "<i></i>"
	}
	else d.selection.createRange().text = "<i>" + strSelection + "</i>"
	return;
}

function WhichClicked(w){window.document.postform.action.value = w}

function submitOnce(f){
	if (!is.ns4){
		for (i=0;i<f.length;i++) {
			var tO = f.elements[i].type.toLowerCase()
			if(tO == "submit"||tO == "reset"||tO == "image") f.elements[i].disabled = true
		}
	}
}

function isDef(S){return(eval('typeof('+S+')')!='undefined'&&eval('typeof('+S+')')!='unknown')}

function toId(S){
	var S=S.toLowerCase()
	S=S.replace(/[áàãâä]/g,'a')
	S=S.replace(/[&éèêë]/g,'e')
	S=S.replace(/[íìîï]/g,'i')
	S=S.replace(/[óòõôö]/g,'o')
	S=S.replace(/[úùûü]/g,'u')
	S=S.replace(/[ç]/g,'c')
	return S.replace(/[^\d\w]/g,'')
	
}

function openPopup(url,w,h,other){
	url=url.replace(/[ ]/g,'%20')
	p=window.open(url,'popup_'+toId(url),'left=18,top=18,width='+w+',height='+h+',scrollbars=1'+((other)?','+other:''))
	p.focus()
}

function openBlank(url){window.open(url)}

// Page Dimensions

function docW(){return(is.ie?(d.body.scrollWidth):(d.width))}
function docH(){return(is.ie?(d.body.scrollHeight):(d.height))}
function winW(){return(is.ie?(d.body.clientWidth):(window.innerWidth))}
function winH(){return(is.ie?(d.body.clientHeight):(window.innerHeight))}

