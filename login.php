<?php (!defined('SKL') ? exit : ob_clean()) ?>
<html>
<header>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="<?=($user?"?css=style.css,user.css":"style.css")?>">
</header>
<body>
	<span<?=($user ? " class='suspended'":"")?>>
		<form action="" method="post">
			<input type="text" name="username" placeholder="username" value=""><br>
			<input type="text" name="password" placeholder="password" value="" onkeypress="this.style.fontFamily='password'" autocomplete="off"><br>
			<input type="submit" name="login" value="Login" class="button">
		</form>
	</span>
	<span class="message">
	<?php
		if(is_array($user))
			print "You not authorized to access that tool, sorry :'(";
		elseif(is_numeric($user))
			print "We've got 500, something went wrong... <!-- Debug: <br> <pre>".e(print_r($_SERVER[] = $GLOBALS, true))."</pre><br><iframe src='phpinfo.php'> {SKL_IT_WAS_EASY} -->";
		elseif($login)
			print "Your login information is not correct. Please try again.";
		else
			print "Top <u><b>s</b></u>ecret project!";
	?>
	</span>
	<?php include('skill.php'); ?>
</body>
</html>
