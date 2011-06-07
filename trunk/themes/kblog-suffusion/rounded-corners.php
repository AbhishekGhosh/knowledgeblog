<?php
$files = array('rounded-corners.css');
$comp = $_GET['comp'];

ob_start ("ob_gzhandler");
header("Content-type: text/css; charset=UTF-8");
header("Cache-Control: must-revalidate");
$offset = 1209600 ;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);

foreach ($files as $file) {
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

	// print the contents on your page
	echo $contents;
}

ob_end_flush();
?>