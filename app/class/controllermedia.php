<?php

class Controllermedia extends Controller
{
    protected $medialist;
    protected $mediamanager;

    public function __construct($render) {
        parent::__construct($render);
        
        $this->mediamanager = new Modelmedia;

    }

    public function desktop()
    {
        
    }

    public function addmedia()
    {
        echo $this->templates->render('media', ['interface' => 'addmedia']);

        //$message = $this->mediamanager->addmedia($_FILES, 2 ** 24, $_POST['id']);

    }


}


?>