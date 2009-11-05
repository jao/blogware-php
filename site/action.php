<?
# $Author: blogware $
# $Date: 2003/07/04 20:56:00 $
# $Revision: 1.2 $

# get values
$type = @$_POST['type'];
$formaction = @$_POST['formaction'];

if($type == 'comments'){
	
	if ($formaction == 'add'){

	} elseif ($formaction == 'view'){

	}
}elseif($type == 'user'){

	
	if ($formaction == 'cadastro'){

	}elseif($formaction == 'login'){

	}
}else{
	$type = 'comments';
}
?>
<?='<?xml version="1.0" encoding="utf-8"?>'?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>blogware :: <?= $siteTitle ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<script type="text/javascript" src="../js/functions.js"></script>

</head>
<body>
<form name="transfer" action="" method="post">
<? # Geral ?>
<input type="hidden" name="type" value=""/>
<input type="hidden" name="formaction" value=""/>

<? # Comments ?>
<input type="hidden" name="postid" value=""/>
<? # Comments :: add ?>
<input type="hidden" name="userid" value=""/>
<input type="hidden" name="label" value=""/>
<input type="hidden" name="message" value=""/>

<? # User ?>
<input type="hidden" name="userid" value=""/>
<? # User :: Login ?>
<input type="hidden" name="login" value=""/>
<input type="hidden" name="passwd" value=""/>
<? # User :: add ?>
<input type="hidden" name="username" value=""/>
<input type="hidden" name="useremail" value=""/>
<input type="hidden" name="userurl" value=""/>
<input type="hidden" name="userurltitle" value=""/>
<input type="hidden" name="language" value=""/>
<input type="hidden" name="dateformat" value=""/>
<input type="hidden" name="icq" value=""/>
<input type="hidden" name="yahoo" value=""/>
<input type="hidden" name="msn" value=""/>
<input type="hidden" name="aim" value=""/>
</form>
</body>
</html>
