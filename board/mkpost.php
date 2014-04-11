<?php 

if(!isset($_SESSION['user']['uid'])) { print '<!--'; } 

####
function postshit($handle) {

	global $uid;
	global $shit;
	global $date;
	global $rel;

		global $con;

		if(isset($_FILES)) {

			#print '<pre>';
			#print_r($_FILES['postpic']);
			#print '</pre>';

			if($handle == 'post') {

				$getlast = mysqli_fetch_assoc(mysqli_query($con, "SELECT MAX(post_pic) AS postpic FROM posts"));
				$src = 'src/op/';

			} elseif ($handle == 'reply') {

				$getlast = mysqli_fetch_assoc(mysqli_query($con, "SELECT MAX(reply_pic) AS postpic FROM replies"));
				$src = 'src/reply/';

			}

			$picname = $getlast['postpic'] + '1';

				if($_FILES['postpic']['type'] == 'image/jpeg') {
					$ext = '.jpg';
				}
				elseif($_FILES['postpic']['type'] == 'image/gif') {
					$ext = '.gif';
				}
				elseif($_FILES['postpic']['type'] == 'image/png') {
					$ext = '.png';
				}

			move_uploaded_file($_FILES['postpic']['tmp_name'], $src . $picname . $ext) or die('ne');

print $uid . '<br />' . $shit . $date;

##
		if(isset($_GET['post'])) {
			$rel = $_GET['post'];
		
			mysqli_query($con, "INSERT INTO replies(reply_pic, reply_pic_ext, reply_sig, reply_content, reply_date, reply_rel) VALUES ('$picname', '$ext', '$uid', '$shit', '$date', $rel)") or die(mysqli_error($con));
			mysqli_query($con, "UPDATE posts SET last_post = '$date' WHERE post_id = '$rel'");
				header('location:?' . $_SERVER['QUERY_STRING']);

		} else {

			mysqli_query($con, "INSERT INTO posts(post_pic, post_pic_ext, post_content, post_date, post_sig, last_post) VALUES ('$picname', '$ext', '$shit', '$date', '$uid', '$date')") or die(mysqli_error($con));
					header('location:?' . $_SERVER['QUERY_STRING']);

		}
##

		} else {

			print 'no pic';

		}

	}
####

?>



<div id="maek">

<div style="width: 100%; height: 5px; cursor: move;"></div>

<script>
function hidemk() {

	document.getElementById('maek').style.left = '-350px';
	return;

}
</script>

<form method="post" enctype="multipart/form-data">

	<textarea class="stripe" style="font-size: 1.1em;" name="shit" onkeyup="count(this.value);" id="shitter" maxlength="2000" rows=5 cols=40></textarea><br />
	<?php //<input class="stripe" type="text" name="pic" onfocus="this.placeholder = 'Pic URL (optional)';" autocomplete="off" /> <br /> ?>
	<input type="file" name="postpic" /><br />
	<input class="submit stripe" type="submit" />

	<div id="cnt"></div>

</form>

</div>
<?php

if(!isset($_SESSION['user']['uid'])) { print '-->'; }

if(!empty($_POST['shit'])) {

	$txtarea = mysqli_real_escape_string($con, $_POST['shit']);
	$shit = substr($txtarea, 0, 2000);

			$date = date('d.m.y. - h:i:s');
		$uid = $_SESSION['user']['uid'];

		if(isset($_GET['post'])) {
			$rel = $_GET['post'];

			postshit('reply');

		} else {

			postshit('post');

		}

}

?>