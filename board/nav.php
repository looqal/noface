<div id="nav" style="display: table;">

	<script>
	function display(id) {

		document.getElementById(id).style.display = 'table';
		return;

	}
	function hide(id) {

		document.getElementById(id).style.display = 'none';
		return;

	}
	</script>

<?php 

	//$reqc = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) FROM requests"));
	$noreq = $reqc['COUNT(*)'];

?>

<table>

	<tr>

		<td><form style="display: inline;" method="get"><input autocomplete="off" name="search" placeholder="Search" type="search" /></form></td>

		<td><a href="?">home</a></td>
<?php if(!isset($_SESSION['user']['uid'])) { print '
<td> <a onclick="display(\'login\');">login</a> </td><td> <a onclick="display(\'register\');">register</a> </td>
<!--'; } ?>
		<td><a href="?id">profile</a></td>

		<?php // print '<td><a style="font-weight: bold;" href="?invite"><span class="pid" style="float: left; color: #F55; margin-top: -10px;">' . $noreq . '</span>invite</a></td>' ?>
<?php if(!isset($_SESSION['user']['uid'])) { print '-->'; } ?>
		<td><a href="?rules">rules</a></td>
<?php if(!isset($_SESSION['user']['uid'])) { print '<!--'; } ?>
		<td><a href="logout.php">logout</a></td>
<?php if(!isset($_SESSION['user']['uid'])) { print '-->'; } ?>
		<td><a href="http://port.noface.cf">dataport</a></td>
		<td><a class="snl" target="_blank" href="http://twitter.com/nofacecf"><img src="files/tw.png" /></a></td>

		<td style="position: fixed; right: 10; bottom: 10px;"><?php if(isset($nav)) { print $nav; } else {} ?></td>

	</tr>

</table>

</div>

<?php /*	<form method="post" id="register">
		<span style="display: block; width: 500px; margin: 50px auto;">Registering is as simple as this. Use a desired password, and an ID will be assigned to it.
		Later on, you can login and post with the same password and ID.</span>
		<input type="password" name="hash" placeholder="hash" />
		<center onclick="hide('register');" style="margin: 50px;">Close</center>
	</form>
*/ ?>

	<form method="post" id="login">
		<center style="margin: 50px;">Use the password you registered with to login.</center>
		<input type="password" name="pw" placeholder="hash" />
		<center onclick="hide('login');" style="margin: 50px;">Close</center>
	</form>

<?php

if(isset($_POST['hash'])) {

	$hash = md5($_POST['hash']);
	$shortened_hash = substr($hash, 0, 3);

			mysqli_query($con, "INSERT INTO users(hash, inv, inviter, interests) VALUES ('$hash', 1, '$key', 'all')") or die(mysqli_error($con));
			print 'Registered';
			header('location: ?');

}

	if(!empty($_POST['pw'])) {

	$pw = md5($_POST['pw']);
	$uid = mysqli_fetch_assoc(mysqli_query($con, "SELECT uid FROM users WHERE hash = '$pw'"));

	if(isset($uid)) {

		$_SESSION['user'] = $uid;

				print '

<center style="margin: 200px; font-size: 2em;">Logging in...</center>';

		header('location: ?');
		exit();

	} else { $_SESSION['user'] == false; }

	} else { $_SESSION['user'] == false; }

?>