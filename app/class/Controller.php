<?php

namespace Wcms;

use DateTimeImmutable;
use League\Plates\Engine;

class Controller
{
    /** @var User */
    protected $user;

    /** @var \AltoRouter */
    protected $router;

    /** @var Modeluser */
    protected $usermanager;

    /** @var Modelpage */
    protected $pagemanager;

    protected $plates;
    
    /** @var DateTimeImmutable */
    protected $now;

	public function __construct($router) {
        $this->setuser();
        $this->router = $router;
        $this->pagemanager = new Modelpage();
        $this->initplates();
        $this->now = new DateTimeImmutable(null, timezone_open("Europe/Paris"));
	}

    public function setuser()
    {
        $this->usermanager = new Modeluser;        
        $this->user = $this->usermanager->readsession();
    }

    public function initplates()
    {
        $router = $this->router;
        $this->plates = new Engine(Model::TEMPLATES_DIR);
        $this->plates->registerFunction('url', function (string $string, array $vars = []) use ($router) {
            return $router->generate($string, $vars);
        });
        $this->plates->registerFunction('upage', function (string $string, string $id) use ($router) {
            return $router->generate($string, ['page' => $id]);
        });
    }

    public function showtemplate($template, $params)
    {
        $params = array_merge($this->commonsparams(), $params);
        echo $this->plates->render($template, $params);
    }

    public function commonsparams()
    {
        $commonsparams = [];
        $commonsparams['router'] = $this->router;
        $commonsparams['user'] = $this->user;
        $commonsparams['pagelist'] = $this->pagemanager->list();
        $commonsparams['css'] = Model::csspath();
        return $commonsparams;
    }





    public function redirect($url)
    {
        header('Location: ' . $url);
    }

    public function routedirect(string $route, array $vars = [])
    {
        $this->redirect($this->router->generate($route, $vars));
    }

    public function routedirectget(string $route, array $vars = [])
    {
        $get = '?';
        foreach ($vars as $key => $value) {
            $get .= $key .'='. $value. '&';
        }
        $get = rtrim($get, '&');
        $this->redirect($this->router->generate($route, []) . $get);
    }

    public function error(int $code)
    {
        http_response_code($code);
        exit;
    }

}





?>