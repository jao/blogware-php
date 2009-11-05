/*
$Author: blogware $
$Date: 2003/07/04 20:56:00 $
$Revision: 1.3 $
*/

d = document;
w=window;

function onlyNumbers(e){
	if(window.event)key=window.event.keyCode;
	else if(e)key=e.which;
	else return true;
	return (key==null||key==0||key==8||key==9||key==13||key==27||String.fromCharCode(key).match(/\d/));
}

function checkEmail(email){
  return(email.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]{2,64}(\.[a-z0-9-]{2,64})*\.[a-z]{2,4}$/));
}

function checkBrowser(){
	T=this;
	b=navigator.appName;
	v=navigator.appVersion;
	u=navigator.userAgent;
	if(b=='Netscape')T.b='ns';
	else if(b=='Microsoft Internet Explorer')T.b='ie';
	else T.b=b;
	T.v=parseInt(v);
	T.ns=(T.b=='ns'&&T.v>=4);
	T.ns4=(T.b=='ns'&&T.v==4);
	T.ns5=(T.b=='ns'&&T.v==5);
	T.ns6=(T.b=='ns'&&T.v==5);
	T.ie=(T.b=='ie'&&T.v>=4);
	T.ie4=(u.indexOf('MSIE 4')>0);
	T.ie5=(u.indexOf('MSIE 5.0')>0);
	T.ie55=(u.indexOf('MSIE 5.5')>0);
	T.ie6=(u.indexOf('MSIE 6.0')>0);
	if(T.ie5)T.v=5;
	if(T.ie55)T.v=5.5;
	if(T.ie6)T.v=6;
	T.min=(T.ns||T.ie);
	T.dom=(T.v>=5);
	T.win=(u.indexOf('Win')>0);
	T.mac=(u.indexOf('Mac')>0);
}
is=new checkBrowser();

function getElm(id){return (is.ie4)?d.all[id]:d.getElementById(id)}
function countChar(o){o.form.counter.value = o.value.length;}
function maxLength(o,n,e){
	if(is.ns){if(e.which==0||e.which==8)return true};
	if(o.value.length>=n) return false;
}

function sO(f){
	if (!is.ns4){
		for(var i=0;i<f.length;i++){
			if(f[i].type=='submit'||f[i].type=='reset')f[i].disabled=true;
		}
	}
}

// Ubb Code
function addCode(c,f,from){
	c = c.value;
	strSelection = d.selection.createRange().text;
	if(c=='url')	strHref = '='+prompt("URL","http://");
	else if(c=='link') strHref = '='+prompt("Id","");
	else strHref = '';

	if (strSelection == ""){
		f.focus();
		if(from == 2) f.select();
		strSelection = d.selection.createRange().text;
		d.selection.createRange().text = strSelection + "["+c+strHref+"][/"+c+"]";
	}
	else d.selection.createRange().text = "["+c+strHref+"]" + strSelection + "[/"+c+"]";
	return;
}

function viewComments(id){
	var obj = getElm('cm'+id);
	if(obj.style.display == 'none'){
		obj.innerHTML = StatWaitDone;
		obj.style.display = '';
		readComments(id,false);
	}else{
		obj.style.display = 'none';
	}
}

function addComments(f){
	var hf = parent.info.document.addcomment;

	if(f.label.value == ''){
		alert('Por favor escreva seu nome.');
		f.label.focus();
		return;
	}else if(f.message.value == ""){
		alert('Por favor escreva uma mensagem.');
		f.message.focus();
		return;
	}else{
		sO(f)
		hf.action = "post_comment.php";
		hf.id.value = f.id.value;
		hf.userid.value = f.userid.value;
		hf.label.value = f.label.value;
		hf.message.value = f.message.value;
		parent.info.sendInfo();
	}
}

function readComments(id,b){
	var hfr = (b) ? document.addcomment : parent.info.document.addcomment;
	hfr.action = "comments.php";
	hfr.id.value = id;
	parent.info.sendInfo();
}

function writeComments(S,id,n){
	var comObj,numObj,numStr;
	comObj = parent.main.document.getElementById('cm'+id);
	comObj.innerHTML = S;
	numObj = parent.main.document.getElementById('num'+id);
	numStr = numObj.innerHTML.replace(/\d+/,n);
	numStr = numStr.replace(/s$/,'');
	numObj.innerHTML = numStr+((n!=1) ?'s':'');
	if(!comObj || !numObj) comObj = parent.main.StatWaitErro;
	sendInfo();
}

// admin

function setRange(x) {
	d.postform.init.value = x;
	d.postform.formaction.value = "list";
}

function wC(s,obj){
	var submitForm = (obj) ? confirm('Tem certeza que deseja '+obj.value+' o item?') : true;
	if(submitForm) document.postform.formaction.value = s;
}

function ShowHide(x){
	obj = document.getElementById(x);
	img = document.getElementById('img'+x);
	if(obj.style.display == 'none'){
		img.src=img.src.replace(/([^_]+).+/,'$1_open.gif');
		obj.style.display = '';

	}else{
		img.src=img.src.replace(/([^_]+).+/,'$1_close.gif');
		obj.style.display = 'none';
	}
}
