<?php
// {SKL_FILE_DISCLOSURE_IS_AWESOME}

function getAuthorized(){
	return json_decode(file_get_contents(INC.'authd'), true);
}

function getTimeOffset(){
	$current_time = time();
	$start_time = strtotime(date('m/d/Y', $current_time));
	$offset = round(($current_time - $start_time) / 60);
	$offset = str_pad($offset, 4, '0', STR_PAD_LEFT);
	return $offset;
}

function generateSession($userid, $javaScript='N', $cookieEnabled='N'){
	$javaScript = strtoupper($javaScript);
	$cookieEnabled = strtoupper($cookieEnabled);
	if($javaScript != 'N' && $javaScript != 'J') $javaScript = 'N';
	if($cookieEnabled != 'N' && $cookieEnabled != 'C')	$cookieEnabled = 'N';	
	$iSession =
	$protectionBlock =
	$sessionBlock =
	$userBlock =
	$timeBlock = '';
	$sessionBlock = strtoupper(uniqid('p', true));
	$sessionBlock = str_replace(array('-','@'), rand(0,9), $sessionBlock);
	$userBlock = str_pad($userid, 8, '0', STR_PAD_LEFT);
	$protectionBlock = getHash($userBlock.$sessionBlock.getSalt());
	$timeBlock = getTimeOffset();
	$iSession = $javaScript.$cookieEnabled.$sessionBlock.$userBlock.$timeBlock;
	$cut1 = substr($iSession,0,4);
	$cut2 = substr($iSession,4,8);
	$cut3 = substr($iSession,12,2);
	$cut4 = substr($iSession,14,6);
	$cut5 = substr($iSession,20,5);
	$cut6 = substr($iSession,25,5);
	$cut7 = substr($iSession,30,5);
	$cut8 = substr($iSession,35,3);
	$iSession =  $cut2.$protectionBlock{12}.$cut4.$protectionBlock{2}.$cut3.$protectionBlock{30}.$cut8.$protectionBlock{25}.
				  $cut7.$protectionBlock{20}.$cut6.$protectionBlock{17}.$cut5.$protectionBlock{8}.$cut1;
	return strtoupper($iSession);
}

function e($str){
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function maybeCSSRequest(){
	if(!isset($_GET['css'])) return;
	$css = '';
	$files = array_map('trim', explode(',', $_GET['css']));
	foreach($files as $file)
		$css .= (strpos($file, '.css') !== false ? file_get_contents(basename($file)) : '');
	header('Content-type: text/css');
	exit($css ? $css : 'Hacking attempt!');
}

function getHash($str){
	return hash('SHA512', $str);
}

function userLogin($username, $password, $users){
	if(!is_string($username)) return 0;
	$username = preg_replace('/[^a-zA-Z0-9]+/', '', $username);
	if(isset($users[$username]) && $users[$username]['password'] === getHash($password)){
		$userid = $users[$username]['userid'];
		setcookie('rememberSession', generateSession($userid), 0, '/', '', false, true);
		return array('userid' => $userid);
	}
	return false;
}

function getPrefix(){
	return trim(file_get_contents(INC.'prefix'));
}

function getSessionArray($iSession){
	$expire_time = 5;
	$session_array = array();	
	$timeCheck = getTimeOffset();
	$cut2 = substr($iSession,0,8);
	$cut4 = substr($iSession,9,6);
	$cut3 = substr($iSession,16,2);
	$cut8 = substr($iSession,19,3);
	$cut7 = substr($iSession,23,5);
	$cut6 = substr($iSession,29,5);
	$cut5 = substr($iSession,35,5);
	$cut1 = substr($iSession,41,4);
	$pcut1 = $iSession{8};
	$pcut2 = $iSession{15};
	$pcut3 = $iSession{18};
	$pcut4 = $iSession{22};
	$pcut5 = $iSession{28};
	$pcut6 = $iSession{34};
	$pcut7 = $iSession{40};
	$protectionBlock = $pcut1.$pcut2.$pcut3.$pcut4.$pcut5.$pcut6.$pcut7;
	$iSession = $cut1.$cut2.$cut3.$cut4.$cut5.$cut6.$cut7.$cut8;
	$iSession = strtoupper($iSession);
	$sessionBlock = substr($iSession,2,24);
	$userid = substr($iSession,26,8);
	$timeBlock = substr($iSession,34,4);
	$javaScript = $iSession{0};
	$cookie_enabled = $iSession{1};
	$test_protection_hash = strtoupper(getHash($userid.$sessionBlock.getSalt()));
	$test_protection = $test_protection_hash{12}.$test_protection_hash{2}.$test_protection_hash{30}.$test_protection_hash{25}.$test_protection_hash{20}.$test_protection_hash{17}.$test_protection_hash{8};
	if ($test_protection != $protectionBlock || !is_numeric($timeBlock)) return false;
	$time_dif = $timeCheck - $timeBlock;
	$session_expired = false;
	if ($time_dif >= $expire_time || $time_dif < 0) $session_expired = true;
	$session_array['protection'] = $protectionBlock;
	$session_array['javascript'] = $javaScript;
	$session_array['session'] = $sessionBlock;
	$session_array['userid'] = intval($userid);
	$session_array['expired'] = $session_expired;
	$session_array['minutes_remaining'] = ($expire_time - $time_dif < 0 || $expire_time - $time_dif > $expire_time) ? 0 : $expire_time - $time_dif;
	$session_array['base_minutes'] = $timeBlock;
	$session_array['cookie'] = $cookie_enabled;
	return $session_array;
}

function getSalt(){
	return file_get_contents(INC.'salt');
}

function checkRememberSession(){
	$checkedSession = sessionBlocksCheck($_COOKIE['rememberSession']);
	if(!$checkedSession || !$user = getSessionArray($checkedSession)) return false;
	return $user;
}

function getUsers(){
	return json_decode(file_get_contents(INC.'users'), true);
}
?>
