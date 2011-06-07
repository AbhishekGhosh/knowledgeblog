<?php
$files = array();
$skin = "";
if (isset($_GET['skin'])) {
	$skin = $_GET['skin'];
}
$comp = "";
if (isset($_GET['comp'])) {
	$comp = $_GET['comp'];
}
$child = "";
if (isset($_GET['sdir'])) {
	$child = $_GET['sdir'];
}
if ($skin != "") {
	$files = explode(',',$skin);
}
if ($child != "") {
	$files[] = $child."/style.css";
}

ob_start ("ob_gzhandler");
header("Content-type: text/css; charset=UTF-8");
header("Cache-Control: must-revalidate");
$offset = 1209600 ;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);

foreach ($files as $file) {
	// security check
	$ext = substr($file, -3);
	if ($ext != 'css') {
		continue;
	}
	
	// connect to handle a file
	$file_handler = fopen($file, "r");

	// read the contents
	$contents = fread($file_handler, filesize($file));

	// close the file
	fclose($file_handler);

	if ($comp == 'gzip-minify') {
		$contents = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $contents);
		/* remove tabs, spaces, newlines, etc. */
		$contents = str_replace(array("\r\n", "\r", "\n", "\t"), '', $contents);
		$contents = str_replace(array('  ', '   ', '    ', '     '), ' ', $contents);
		$contents = str_replace(array(": ", " :"), ':', $contents);
		$contents = str_replace(array(" {", "{ "), '{', $contents);
		$contents = str_replace(';}','}', $contents);
		$contents = str_replace(', ', ',', $contents);
		$contents = str_replace('; ', ';', $contents);
	}
	$contents = str_replace('url(../../images/', 'url(images/', $contents);

	echo "/* Style sheet $file */\n";
	echo $contents;
}

ob_end_flush();
?>
