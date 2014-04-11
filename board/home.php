<?php

if(isset($_SESSION['user'])) { print '<title>' . $_SESSION['user']['uid'] . '</title>'; } else { print '<title>Network</title>'; }

include('nav.php');

?>
<link rel="stylesheet" href="bone.css" />
<script>

function quot(qid) {

document.getElementById('shitter').value += '@' + qid + ' ';
//document.getElementById('maek').style.left = '90px';
return;

}

</script>

<script>
function count(str) {

	var count = 140;
	var text = str;
	var maek = document.getElementById('maek');
	var lim = text.length;

	if(lim > count){

		text = text.substring(0, count);
		maek.value = text;
		return false;

	}

	document.getElementById('cnt').innerHTML = lim;
	return;

}
</script>

<?php

function last3($id) {

	global $con;

	$last3 = mysqli_query($con, "SELECT * FROM replies WHERE reply_rel = '$id' ORDER BY reply_id desc LIMIT 0, 3");
	$count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(reply_id) FROM replies WHERE reply_rel = '$id'"));
	
	if($count['COUNT(reply_id)'] != 0) {

print '<table class="replies" style="margin-left: 40px; font-size: .9em;">
';

	while($pl3 = mysqli_fetch_assoc($last3)) {

		print '<tr>';

		print '<td><span class="pid">' . $pl3['reply_id'] . '</span><img src="src/reply/' . $pl3['reply_pic'] . $pl3['reply_pic_ext'] . '" width="32" /> </td>';

		print '<td><a class="replyprev" href="?post=' . $pl3['reply_rel'] . '#' . $pl3['reply_id'] . '">' . $pl3['reply_content'] . '<br /><br />
		<small class="meta c2">by <b>' . $pl3['reply_sig'] . '</b> on ' . $pl3['reply_date'] . '</small></a></td></tr>'; 

	}

print '</table>'; }

}

function profile($id) {

	global $con;
	$profile = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE uid = '$id'"));
	$interests = (explode(', ', $profile['interests']));
	$posts = mysqli_query($con, "SELECT * FROM posts WHERE post_sig = '$id'");
	$replies = mysqli_query($con, "SELECT * FROM replies WHERE reply_sig = '$id'");
	print '<pre id="container" class="post">';

		print 'User by ID: ' . $id . '<br />';


if($id == $_SESSION['user']['uid']) {	

		if(isset($_POST['interests'])) {

			$post_interests = $_POST['interests'];
			mysqli_query($con, "UPDATE users SET interests = '$post_interests' WHERE uid = '$id'") or die(mysqli_error($con));
			header('location: ?' . $_SERVER['QUERY_STRING']);

		}

?>

<script>
function editable() {

	document.getElementById('interests').innerHTML = 

	<?php 

	print '\'<form method="post"><textarea name="interests" rows=5 cols=40>';

		
			print $profile['interests'];


	print '</textarea><br /><input type="submit" /> <a href="">Cancel</a></form>\'';

	?>;

	return;

}
</script>

<?php

	}

if($id == $_SESSION['user']['uid']) { print '<button class="submit" style="padding: 0; border: 0; background: none; float: right;" onclick="editable();">Edit</button>'; }
		print 'Interests:<div style="border-top: 1px solid #f55; padding: 10px;" id="interests">';
		print '<br />';
		foreach($interests as $interest) {
			print '<br /><a class="hash" href="?search=' . $interest . '">#' . $interest . '</a>';
		}
		print '</div>';

	print '</pre>';

}

function filter($content) {

$find = array(
	'/(^|\s)#(\w+)/',
	'/(^|\s)@(\w+)/'
	);

if(isset($_GET['p'])) {

$replace = array(
	'<a class="hash" href="?search=\2">#\2</a>',
	'<a class="quot" href="#\2">@\2</a>'
	);

} else {

$replace = array(
'<a class="hash" href="?search=\2">#\2</a>',
'<a class="quot" href="#\2">@\2</a>'
	);

}

print '<pre class="prewrap">';

print preg_replace($find, $replace, $content);

print '</pre>';


}

#date('d.m.y. - h:i:s');

	if(isset($_GET['post'])) {

	include('mkpost.php');
	
		$post = $_GET['post'];
		$rez = mysqli_query($con, "SELECT * FROM posts WHERE post_id = $post");

print '<div id="container">';

			while($posts = mysqli_fetch_assoc($rez)) {

				print '
<table id="OP"><tr>
';
				if(!empty($posts['post_pic'])) { print '<td><span class="pid">' . $posts['post_id'] . '</span><img onclick="pic(this);" src="src/op/' . $posts['post_pic'] . $posts['post_pic_ext'] . '" class="_pic" /></td>'; }
				print '<td class="post reply">';
				filter($posts['post_content']);
				print '<br /><small onclick="quot(\'OP\');" class="meta c2"> quote:<b>' . $posts['post_sig'] . '</b></small><small class="date">' . $posts['post_date'] . '</small>';
				print '</span></span></td>';

print '</tr></table>';

			}

			$getrep = mysqli_query($con, "SELECT * FROM replies WHERE reply_rel = $post");

				print '<div id="container" style="top: 100px; margin-left: 100px; border-left: 2px solid #444;">';
					while($rep = mysqli_fetch_assoc($getrep)) {

						print '<table>';
						print '<td style="vertical-align: top;"> <span class="pid">' . $rep['reply_id'] . '</span>';

						print '<img onclick="pic(this);" src="src/reply/' . $rep['reply_pic'] . $rep['reply_pic_ext'] . '" class="_pic" /></td>';
						print '<td class="post reply" id="' . $rep['reply_id'] . '">';
						filter($rep['reply_content']);
						print '<br /><small onclick="quot(' . $rep['reply_id'] . ')" class="meta c2"> quote:<b>' . $rep['reply_sig'] . '</b></small><small class="date">' . $rep['reply_date'] . '</small></span></td>
						</tr></table>';

					}
				print '</table></div>';

print '</div>';

} elseif(isset($_GET['id'])) {

	if(!empty($_GET['id'])) {

		profile($_GET['id']);

	} else {

	profile($_SESSION['user']['uid']);

	}

} elseif(isset($_GET['search'])) {

	$search = $_GET['search'];
	$searchr = mysqli_query($con, "SELECT * FROM replies WHERE reply_content LIKE '%$search%'") or die(mysqli_error($con));
	$searchp = mysqli_query($con, "SELECT * FROM posts WHERE post_content LIKE '%$search%'") or die(mysqli_error($con));

print '<div id="container"><span class="post" id="srez"><span style="color: #AAA;">Search results: </span><br /><br />';

		while($srezp = mysqli_fetch_assoc($searchp)) {

			print '<a class="srezp" href="?post=' . $srezp['post_id'] . '">' . $srezp['post_content'] . '</a><br />';

		}

		while($srez = mysqli_fetch_assoc($searchr)) {

			print '<a class="srez" href="?post=' . $srez['reply_rel'] . '#' . $srez['reply_id'] . '">' . $srez['reply_content'] . '</a> <br />';

		}

print '</span></div>';

} elseif(isset($_GET['rules'])) {

	print '<div id="container" class="post">

	<b><h3>RULEZ R AZ FOLLOWZ:</h3></b>

	<ul style="margin: 0 auto; display: block; width: 200px;">
	<li>NO SPAM</li>
	<li>NO ILLEGAL CONTENT</li>
	</ul>

	</div>';

} elseif(isset($_GET['invite'])) {

	$requests = mysqli_query($con, "SELECT * FROM requests");
	
	print '<ul class="reqs">Key requests: <br /><br />';

		while($request = mysqli_fetch_assoc($requests)) {

			print '<li><small>' . $request['contact'] . '</small></li>';

		}

	print '</ul>';

	print '<div id="container" style="margin-left: 20px; margin-top: 20px;"><span class="post">';
	include('invite.php');
	print '</span></div>';

} else {

	include('mkpost.php');

	if(isset($_GET['p'])) {

		$blim = $_GET['p'] * 10 -10;
		$tlim = $blim + 10;
	
	} else { 

		$blim = 0; $tlim = 10;

		}
			$count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(post_id) FROM posts"));
			$cnt = $count['COUNT(post_id)'];

			$linkovi = array();
			$pgmax = ceil($cnt/10);

				for($link = 1; $link <= $pgmax; $link++) {
					$nav = '<a style="z-index: 3;" href="?p=' . $link . '">' . $link . '</a>';
				}


			$rez = mysqli_query($con, "SELECT * FROM posts ORDER BY last_post DESC LIMIT $blim, $tlim");
print '<div id="container">'; # TEMP
	while($posts = mysqli_fetch_assoc($rez)) {

		print '
<hr />
<table style="display: inline-block; vertical-align: top;"><tr>
';
		if(!empty($posts['post_pic'])) { print '<td> <span class="pid">' . $posts['post_id'] . '</span> <a><img onclick="pic(this);" src="src/op/' . $posts['post_pic'] . $posts['post_pic_ext'] . '" class="_pic" id="resizable" /></a></td>'; }
		print '<td><span class="post">';
		filter($posts['post_content']);
		print '
<br />
<a href="?post=' . $posts['post_id'] . '"><small class="meta c2"> read: <b>' . $posts['post_sig'] . '</b></small></a><small class="date">' . $posts['post_date'] . '</small>';
		print '
	</span><br /></td>
';
print '</tr></table>';

	last3($posts['post_id']);

	}

print '</div>';
print '</div>';

}

if(isset($nav)) { 

	print '<div style="position: fixed; bottom: 20px; right: 15px; z-index: 2;">';
	print $nav; 
	print '</div>';

}

?>

<script>

i=1;
function pic(item) {
    item.click=++i;
    item.style.width = ((item.click)%2 == 0) ? "auto" : "100px";
}
</script>