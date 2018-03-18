<?php


// _____________________________________________________ R E Q U I R E ________________________________________________________________


$_SESSION['level'] = 0;

$config = require('../../config.php');
require('../../vendor/autoload.php');
use Michelf\Markdown;

require('../../fn/fn.php');
require('../../class/class.art.php');
require('../../class/class.app.php');
require('../../class/class.aff.php');
session();
$app = new App($config);
$aff = new Aff($_SESSION['level']);


// _____________________________________________________ A C T I O N __________________________________________________________________

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        
        case 'update':
        if ($app->exist($_GET['id'])) {
            $art = new Art($_POST);
            $app->update($art);
            header('Location: ?id=' . $art->id() . '&edit=1');
        }
        break;
        
        case 'login' :
        $app->login($_POST['pass']);
        header('Location: ?id=' . $_GET['id']);
        break;
        
        case 'logout' :
        $app->logout();
        header('Location: ?id=' . $_GET['id']);
        break;
    }
    
}




// _______________________________________________________ H E A D _____________________________________________________________
$titre = 'home';
if (isset($_GET['id'])) {
    $titre = $_GET['id'];
    if ($app->exist($_GET['id'])) {
        $art = $app->get($_GET['id']);
        $titre = $art->titre();
    }
}
$aff->head($titre);




// ______________________________________________________ B O D Y _______________________________________________________________ 


echo '<body>';
$aff->nav($app);

if (isset($_GET['id'])) {


    if ($app->exist($_GET['id'])) {

        $art = $app->get($_GET['id']);

        if (isset($_GET['edit']) and $_GET['edit'] == 1) {
            $aff->edit($art);
            $aff->aside($app->list());
        } else {
            $aff->lecture($art);

        }
    } else {
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'new') {
                $art = new Art($_GET);
                $art->default();
                var_dump($art);
                $app->add($art);
                header('Location: ?id=' . $_GET['id'] . '&edit=1');
            }
        } else {
            echo '<span class="alert"><h4>Cet article n\'existe pas encore</h4></span>';

            if ($_SESSION['level'] >= 2) {
                echo '<form action="?id=' . $_GET['id'] . '&edit=1" method="post"><input type="hidden" name="action" value="new"><input type="submit" value="créer"></form>';
            }

        }

    }
} else {
    echo "<h4>Bienvenue sur ce site.</h4>";
    $aff->home($app->list());
}
echo '</body>';



?>
