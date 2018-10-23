<?php (!defined('SKL') ? exit : ob_clean()) ?>
<html>
<header>
	<title>IMAP Inbox Checker v0.337 (Alpha)</title>
	<link rel="stylesheet" type="text/css" href="<?=($user?"?css=style.css,user.css":"style.css")?>">
</header>
<body>
	<form action="" method="post">
		<input type="text" name="iserver" placeholder="imap.host.com" value="<?=e($_POST['iserver'])?>"><br>
		<input type="text" name="ilogin" placeholder="username@host.com" value="<?=e($_POST['ilogin'])?>"><br>
		<input type="text" name="ipassword" placeholder="password" value="" onkeypress="this.style.fontFamily='password'" autocomplete="off"><br>
		<!-- <input type="checkbox" name="idebug" checked=""><br> {SKL_AUTH_BYPASSED_LIKE_A_PRO} -->
		<input type="submit" name="check" value="Get" class="button">
	</form>
	<span class="message">
		<?php
			if(isset($_POST['iserver'])){
	
				$EOL = '<br>';
				$EMD = '<hr>';
	
				//error reporting will be useful here, if any shit happens again
				if($_POST['idebug'] === 'on')
					error_reporting(E_ALL & ~E_NOTICE);
				
				//PHP's IMAP poorly documented, I had to look under the hood, debug(!) and find out what's what :( Now it works perfect!
				$imap = imap_open('{'.$_POST['iserver'].':993/imap/ssl}INBOX', $_POST['ilogin'], $_POST['ipassword']);
				
				if(is_resource($imap)){
					$mails = imap_search($imap, 'NEW');
		
					print 'You '.($mails ? '' : 'don\'t ').'have new mails.'.$EOL;
		
					if($mails){
						foreach($mails as $mail){
							$header = imap_header($imap, $mail);
							print $EMD;
							print 'From: '.e($header->sender[0]->mailbox).'@'.e($header->sender[0]->host).$EOL;
							print 'Subject: '.e($header->subject).$EOL;
							print 'Body: '.$EOL.$EOL.e(imap_fetchbody($imap, $mail, 1)).$EOL;
						}
					}
		
					imap_close($imap);
				}
				else
					print 'Error: '.e(imap_last_error());
			}
			else{ //{SKL_OH_NO_FPD_HAPPENED_RCE_IS_COMING}
		?>
		Welcome to early access!
		<div class="tip">
			<p>The Internet Message Access Protocol (IMAP) is an Internet standard protocol used by email clients to retrieve email messages from a mail server over a TCP/IP 	connection. IMAP is defined by RFC 3501.</p>
			<p>IMAP was designed with the goal of permitting complete management of an email box by multiple email clients, therefore clients generally leave messages on the server until the user explicitly deletes them. An IMAP server typically listens on port number 143. IMAP over SSL (IMAPS) is assigned the port number 993.</p>
			<p>Virtually all modern e-mail clients and servers support IMAP. IMAP and the earlier POP3 (Post Office Protocol) are the two most prevalent standard protocols for email retrieval, with many webmail service providers such as Gmail, Outlook.com, Yahoo! Mail and Yandex also providing support for either IMAP or POP3.</p>
		</div>
	</span>
	<?php } ?>
	<?php include('skill.php'); ?>
</body>
</html>
