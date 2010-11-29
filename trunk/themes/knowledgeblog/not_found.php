<?php 
if(!isset($_REQUEST['error'])){
	$STATUS_CODE = '404';
}
else {
	$STATUS_CODE = $_REQUEST['error'];
}
if(isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS']!='200'){
	$STATUS_CODE = $_SERVER['REDIRECT_STATUS'];
}

$error = array(
	'400'=>array('400 Bad Request','400 Bad Request', ''),
	'401'=>array('401 Authorization Required', '401 Authorization Required', ''),
	'403'=>array('403 Forbidden', '403 Forbidden', 'You are not authorized to access this resource.'),
	'404'=>array('404 Not Found', '404 Not Found', 'You are lost! Did you take flight Oceanic 815? NO? Then you myst have followed the dirk side, men! Anyway, go to the <a href="'.get_option('home').'">home page</a> and start looking for what you came for.'),
	'405'=>array('405 Method Not Allowed', '405 Method Not Allowed', 'The requested method is not allowed for this URL.'),
	'500'=>array('500 Internal Server Error', 'BAAAAAAANNNNNGGGGG!!!!', 'The code has explodaded and memory leacked on the room floor! Don\'t do this again, it hurts!'),
	'503'=>array('503 Service Temporarily Unavailable', '503 Service Temporarily Unavailable', 'There are some construction works going on, please come back later!')
);


//header("HTTP/1.1 ".$error[$STATUS_CODE][0]);

?>
	<div id="content">
		<div class="post" id="p404">
			<h2><?php echo $error[$STATUS_CODE][1] ?></h2>
			<p>&nbsp;</p>
			<p><?php echo $error[$STATUS_CODE][2] ?></p>
		</div>
	</div>