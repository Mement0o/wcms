<?php

class Controllerfont extends Controller
{

    protected $fontmanager;
    
    public function __construct($router)
    {
        parent::__construct($router);
        $this->fontmanager = new Modelfont();

    }

    public function desktop()
    {
        if($this->user->isadmin()) {

            $fontlist = $this->fontmanager->getfontlist();
            
            $this->showtemplate('font', ['fontlist' => $fontlist, 'fonttypes' => $this->fontmanager->getfonttypes(), 'fontfile' => Model::globalpath().'fonts.css']);
        } else {
            $this->routedirect('home');
        }
    }
    
    public function render()
    {
        $this->fontmanager->renderfontface();
        $this->routedirect('font');
    }

    public function add()
    {
        if(isset($_POST['fontname'])) {
            $fontname = $_POST['fontname'];
        } else {
            $fontname = '';
        }
        $message = $this->fontmanager->upload($_FILES, 2 ** 16, $fontname);
        if($message !== true) {
            echo $message;
        } else {
            $this->render();
        }
    }
}


?>