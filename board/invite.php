<form method="post">
	<input type="submit" value="Give me my key" class="stripe" name="generator" />
</form><br />

<?php

	if(isset($_POST['generator'])) {

	$uid = $_SESSION['user']['uid'];
	
	$invkey = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE uid = $uid"));
	$key = substr($invkey['hash'], 0, 3);

	print 'Your invitation key: <u>' . $key . '</ul>';

}

?>