<a href="?getDirectories">View Directories</a> | <a href="?getFiles">View Files</a><br /><br />

<?php
// based on index05.php


//print just file names exclude directory names
	if (isset($_GET['getDirectories'])) getDirectories();
	if (isset($_GET['getFiles'])) getFiles();


	//list directories
	function getDirectories(){
		echo "Available Directories:<br />";

		foreach (glob("*") as $dirname ) {
		if( is_dir( $dirname ) )
		echo "<a target=\"_blank\" href=\"$dirname \">$dirname</a><br />";}
		}

	//list files
	function getFiles(){
		echo "Available Files:<br />";

		foreach (glob("*.php") as $filename) {
		//prints file name & file size!
		//echo "$filename size " . filesize($filename) . "\n";

		echo "<a target=\"_blank\" href=\"$filename\">$filename</a><br />";}
		}

?>



