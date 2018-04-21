<?php

// _____________________________________________________ R E Q U I R E ________________________________________________________________


$config = require('../../config.php');



require('../../fn/fn.php');
require('../../class/class.art.php');
require('../../class/class.app.php');
require('../../class/class.aff.php');
session();
if (!isset($_SESSION['level'])) {
	$level = 0;
} else {
	$level = $_SESSION['level'];
}
$app = new App($config);
$aff = new Aff($level);



// ______________________________________________________ H E A D _____________________________________________________________
$titre = 'home';
$aff->head($titre);

// _____________________________________________________ A L E R T _______________________________________________________________ 

if (isset($_GET['message'])) {
	echo '<h4>' . $_GET['message'] . '</h4>';
}



// ____________________________________________________ A C T I O N _______________________________________________________________ 


if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'addmedia':
			$app->addmedia($_FILES, 2 ** 30, $_POST['id']);
			break;
	}
}

// ______________________________________________________ B O D Y _______________________________________________________________ 



echo '<body>';
$aff->nav($app);
$aff->addmedia();

echo '<h1>Media</h1>';

var_dump(2 ** 29);
var_dump(readablesize(2 ** 29));

echo '<section class="grid">';


$dir = "../media/";


if ($handle = opendir($dir)) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			$fileinfo = pathinfo($entry);

			$filepath = '../media/' . $fileinfo['filename'] . '.' . $fileinfo['extension'];

			list($width, $height, $type, $attr) = getimagesize($filepath);
			$filesize = filesize($filepath);

			echo '<span>';
			echo '<h2>' . $entry . '</h2>';

			echo 'width = ' . $width;
			echo '<br/>';
			echo 'height = ' . $height;
			echo '<br/>';
			echo 'filesize = ' . readablesize($filesize);
			echo '<br/>';


			echo '<img class="thumbnail" src="' . $filepath . '" title="' . $fileinfo['filename'] . '" alt="image">';

			echo '</span>';
		}
	}
	closedir($handle);
}

echo '</section>';

echo '</body>';


?>


