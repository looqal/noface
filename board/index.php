<?php

session_start();
$con = mysqli_connect('host', 'user', 'pass', 'dbname');

include('home.php');
exit();

?>

<!DOCTYPE html>
<html>

	<head>

		<script src="//code.jquery.com/jquery-1.9.1.js"></script>
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

		<script>
$(function() {
 $( "#maek" ).draggable();
  });
  		</script>

<?php

$browsers = array('::-webkit-input-placeholder', ':-moz-placeholder', '::-moz-placeholder', ':-ms-input-placeholder');

print '<style>';

	foreach($browsers as $placeholder) {

	print $placeholder . ' { color: #AAA; }';

	}

print '
.stripe {
	/* background: url(\'bg.png\'); */
	padding: 5px;
	/* border: 0; */ outline: none;
}
</style>
<link rel="stylesheet" href="bone.css" />';

?>

	</head>
	<body>

<?php

session_start();

if(isset($_SESSION['user'])) {

	print '<title>' . $_SESSION['user']['uid'] . '</title>';

	include('home.php');
	#print '<iframe width="100%" height="100%" style="margin: 0; border: 0;" src="http://yuchan.org/yu"></iframe>';

} else { 

?>

<div id="wrap">

	<div id="reg">

<a style="float: right;" href="#">X</a>

<form method="post" class="fields">
	<input class="stripe" autocomplete="off" type="password" name="key" placeholder="Key" /><br />
	<input class="stripe" autocomplete="off" type="password" name="hash" placeholder="Hash" /><br />
	<input class="stripe" type="submit" value="Register" />
</form>

<?php

if(isset($_POST['key']) && isset($_POST['hash'])) {

	$key = $_POST['key'];
	$hash = md5($_POST['hash']);
	$shortened_hash = substr($hash, 0, 3);

		$compare = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM invites WHERE inviter = '$key'"));
		if($compare['inviter'] == $key && $compare['used'] == 0) {

			mysqli_query($con, "UPDATE invites SET used = 0 WHERE inviter = '$key'");
			mysqli_query($con, "INSERT INTO invites(inviter, used) VALUES ('$shortened_hash', 0)");
			mysqli_query($con, "INSERT INTO users(hash, inv, inviter, interests) VALUES ('$hash', 1, '$key', 'all')") or die(mysqli_error($con));
			print 'Registered';
			header('location: ?');

		} else {

			print '<br /> <center>Incorrect key</center>';

		}

}

?>

	</div>

	<div id="req">

<a href="#">X</a>
<form method="post" class="fields">
	<input class="stripe" autocomplete="off" type="text" name="contact" placeholder="Contact" /><br />
	<input class="stripe" type="submit" value="Request" />
</form>

<?php

if(!empty($_POST['contact'])) {

	$contact = $_POST['contact'];
	mysqli_query($con, "INSERT INTO requests(contact) VALUES ('$contact')") or die(mysqli_error($con));
	print '<br /> <center>Request received</center>';

}

?>

	</div>

<style>
body { margin: 0; color: #543AAA; overflow: hidden; }
.login { max-width: 400px; margin-top: 200px; border: 0; font-size: 4em; outline: none; color: #876DDD; font-weight: bold; }
a { font-weight: bold; color: #333; font-family: Tahoma; text-decoration: none; }
a:hover { text-decoration: underline; }

#reg, #req {
	background: rgba(255, 255, 255, .5);
	position: absolute; height: 100%;
/*	opacity: 0;
	transition: opacity .5s;
}
#reg:target, #req:target {
	opacity: 1;
	transition: opacity .5s;
} */

}

#reg {
	left: -300px;
	max-width: 300px;
	transition: left 1s;
}
#req {
	right: 0;
	width: 0;
	transition: width 1s;
}

#reg:target { left: 0; }
#req:target { width: 187px; }
.fields { margin-top: 200px; }
/* .fields input {
	background: #333; 
	border: 0; 
	outline: none; 
	padding: 5px; 
	color: #BCADFF;
	margin: 5px;
} */
</style>

<form class="log" method="post">
	<center>
		<input class="login stripe" autofocus type="password" name="pw" /><br /> <br />
		<a style="padding: 10px; border-radius: 100%;" class="stripe" title="Have a key?" href="#reg">&larr;</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="padding: 10px; border-radius: 100%;" title="Request a key" class="stripe" href="#req">&rarr;</a>
	</center>
</form>

</div> <!-- /wrap -->

<?php

	if(!empty($_POST['pw'])) {

	$pw = md5($_POST['pw']);
	$uid = mysqli_fetch_assoc(mysqli_query($con, "SELECT uid FROM users WHERE hash = '$pw'"));

	if(isset($uid)) {

		$_SESSION['user'] = $uid;

				print '

<center style="margin: 200px; font-size: 2em;">Logging in...</center>';

		header('Refresh: 1; URL=http://noface.cf/');
		exit();

	} else { $_SESSION['user'] == false; }

	} else { $_SESSION['user'] == false; }

}

?>