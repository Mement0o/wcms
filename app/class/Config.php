<?php


namespace Wcms;

abstract class Config
{
	protected static $pagetable = 'mystore';
	protected static $domain = '';
	protected static $fontsize = 15;
	protected static $basepath = '';
	protected static $route404;	
	protected static $alerttitle = '';
	protected static $alertlink = '';
	protected static $alertlinktext = '';
	protected static $existnot = 'This page does not exist yet';
	protected static $private = 'This page is private';
	protected static $notpublished = 'This page is not published';
	protected static $existnotpass = false;
	protected static $privatepass = false;
	protected static $notpublishedpass = false;
	protected static $alertcss = false;
	protected static $defaultbody = '%HEADER%'. PHP_EOL .PHP_EOL . '%NAV%'. PHP_EOL .PHP_EOL . '%ASIDE%'. PHP_EOL .PHP_EOL . '%MAIN%'. PHP_EOL .PHP_EOL . '%FOOTER%';
	protected static $defaultfavicon = '';
	protected static $defaultthumbnail = '';
	protected static $analytics = '';	
	protected static $externallinkblank = true;
	protected static $internallinkblank = false;
	protected static $reccursiverender = true;
	protected static $defaultprivacy = 0;
	protected static $homepage = 'default';
	protected static $homeredirect = null;
	protected static $interfacecss = null;
	protected static $bookmark = [];
	protected static $sentrydsn = '';


// _______________________________________ F U N _______________________________________



	public static function hydrate(array $datas)
	{
		foreach ($datas as $key => $value) {
			$method = 'set' . $key;
			if (method_exists(get_called_class(), $method)) {
				self::$method($value);
			}
		}
	}

	public static function readconfig()
	{
		if (file_exists(Model::CONFIG_FILE)) {
			$current = file_get_contents(Model::CONFIG_FILE);
			$datas = json_decode($current, true);
			self::hydrate($datas);
			return true;
		} else {
			return false;
		}
	}

	public static function createconfig(array $datas)
	{
		self::hydrate($datas);
	}


	public static function savejson()
	{
		$json = self::tojson();
		return file_put_contents(Model::CONFIG_FILE, $json);
	}


	public static function tojson()
	{
		$arr = get_class_vars(__class__);
		$json = json_encode($arr, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
		return $json;
	}

	public static function checkbasepath()
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . self::basepath() . DIRECTORY_SEPARATOR .  Model::CONFIG_FILE;
		return (file_exists($path));
	}

	/**
	 * Calculate Domain name
	 */
    public static function getdomain()
    {
        self::$domain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
	}

	/**
	 * Verify Domain name
	 */
	public static function checkdomain()
	{
		return (self::$domain === $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
	}

	/**
	 * Generate full url adress where W is installed
	 * @return string url adress finished by a slash "/"
	 */
	public static function url($endslash = true) : string
	{
		return self::$domain . (!empty(self::$basepath) ? '/' . self::$basepath : "") . ($endslash ? '/' : '');
	}

// ________________________________________ G E T _______________________________________

	public static function pagetable()
	{
		return self::$pagetable;
	}

	public static function domain()
	{
		return self::$domain;
	}

	public static function fontsize()
	{
		return self::$fontsize;
	}

	public static function basepath()
	{
		return self::$basepath;
	}

	public static function route404()
	{
		return self::$route404;
	}

	public static function alerttitle()
	{
		return self::$alerttitle;
	}

	public static function alertlink()
	{
		return self::$alertlink;
	}

	public static function alertlinktext()
	{
		return self::$alertlinktext;
	}

	public static function existnot()
	{
		return self::$existnot;
	}

	public static function private()
	{
		return self::$private;
	}

	public static function notpublished()
	{
		return self::$notpublished;
	}

	public static function existnotpass()
	{
		return self::$existnotpass;
	}

	public static function privatepass()
	{
		return self::$privatepass;
	}
	
	public static function notpublishedpass()
	{
		return self::$notpublishedpass;
	}
		
	public static function alertcss()
	{
		return self::$alertcss;
	}

	public static function defaultbody()
	{
		return self::$defaultbody;
	}

	public static function defaultfavicon()
	{
		return self::$defaultfavicon;
	}

	public static function defaultthumbnail()
	{
		return self::$defaultthumbnail;
	}

	public static function analytics()
	{
		return self::$analytics;
	}

	public static function externallinkblank()
	{
		return self::$externallinkblank;
	}

	public static function internallinkblank()
	{
		return self::$internallinkblank;
	}

	public static function reccursiverender()
	{
		return self::$reccursiverender;
	}

	public static function defaultprivacy()
	{
		return self::$defaultprivacy;
	}

	public static function homepage()
	{
		return self::$homepage;
	}

	public static function homeredirect()
	{
		return self::$homeredirect;
	}

	public static function interfacecss()
	{
		return self::$interfacecss;
	}

	public static function bookmark()
	{
		return self::$bookmark;
	}

	public static function sentrydsn()
	{
		return self::$sentrydsn;
	}


// __________________________________________ S E T ______________________________________

	public static function setpagetable($pagetable)
	{
		self::$pagetable = strip_tags($pagetable);
	}

	public static function setdomain($domain)
	{
		self::$domain = strip_tags(strtolower($domain));
	}

	public static function setfontsize($fontsize)
	{
		$fontsize = intval($fontsize);
		if ($fontsize > 1) {
			self::$fontsize = $fontsize;
		}
	}

	public static function setbasepath($basepath)
	{
		self::$basepath = strip_tags($basepath);
	}

	public static function setroute404($id)
	{
		if(is_string($id)) {
			self::$route404 = idclean($id);
		}
	}

	public static function setalerttitle($alerttitle)
	{
		if(is_string($alerttitle)) {
			self::$alerttitle = strip_tags($alerttitle);
		}
	}

	public static function setalertlink($alertlink)
	{
		if(is_string($alertlink)) {
			self::$alertlink = idclean(strip_tags($alertlink));
		}
	}

	public static function setalertlinktext($alertlinktext)
	{
		if(is_string($alertlinktext)) {
			self::$alertlinktext = strip_tags($alertlinktext);
		}
	}

	public static function setexistnot($existnot)
	{
		if(is_string($existnot)) {
			self::$existnot = strip_tags($existnot);
		}
	}

	public static function setprivate($private)
	{
		if(is_string($private)) {
			self::$private = strip_tags($private);
		}
	}

	public static function setnotpublished($notpublished)
	{
		if(is_string($notpublished)) {
			self::$notpublished = strip_tags($notpublished);
		}
	}
	
	public static function setexistnotpass($existnotpass)
	{
		self::$existnotpass = boolval($existnotpass);
	}
	
	public static function setprivatepass($privatepass)
	{
		self::$privatepass = boolval($privatepass);
	}
	
	public static function setnotpublishedpass($notpublishedpass)
	{
		self::$notpublishedpass = boolval($notpublishedpass);
	}
	
	public static function setalertcss($alertcss)
	{
		self::$alertcss = boolval($alertcss);
	}

	public static function setdefaultbody($defaultbody)
	{
		if(is_string($defaultbody)) {
			self::$defaultbody = $defaultbody;
		}
	}

	public static function setdefaultfavicon($defaultfavicon)
	{
		if(is_string($defaultfavicon)) {
			self::$defaultfavicon = $defaultfavicon;
		}
	}

	public static function setdefaultthumbnail($defaultthumbnail)
	{
		if(is_string($defaultthumbnail)) {
			self::$defaultthumbnail = $defaultthumbnail;
		}
	}

	public static function setanalytics($analytics)
	{
		if(is_string($analytics) && strlen($analytics) < 25) {
			self::$analytics = $analytics;
		}
	}
	
	public static function setexternallinkblank($externallinkblank)
	{
		self::$externallinkblank = boolval($externallinkblank);
	}

	public static function setinternallinkblank($internallinkblank)
	{
		self::$internallinkblank = boolval($internallinkblank);
	}

	public static function setreccursiverender($reccursiverender)
	{
		self::$reccursiverender = boolval($reccursiverender);
	}

	public static function setdefaultprivacy($defaultprivacy)
	{
		$defaultprivacy = intval($defaultprivacy);
		if($defaultprivacy >= 0 && $defaultprivacy <= 2) {
			self::$defaultprivacy = $defaultprivacy;
		}
	}

	public static function sethomepage($homepage)
	{
		if(in_array($homepage, Model::HOMEPAGE)) {
			self::$homepage = $homepage;
		}
	}

	public static function sethomeredirect($homeredirect)
	{
		if(is_string($homeredirect) && strlen($homeredirect) > 0) {
			self::$homeredirect = idclean($homeredirect);
		} else {
			self::$homeredirect = null;
		}
	}

	public static function setinterfacecss($interfacecss)
	{
		if(is_string($interfacecss) && file_exists(Model::CSS_DIR . $interfacecss)) {
			self::$interfacecss = $interfacecss;
		} else {
			self::$interfacecss = null;
		}
	}

	public static function setbookmark($bookmark)
	{
		if(is_array($bookmark)) {
			self::$bookmark = $bookmark;
		}
	}

	public static function setsentrydsn($sentrydsn)
	{
		if (is_string($sentrydsn)) {
			self::$sentrydsn = $sentrydsn;
		}
	}






	// ______________________________________ F U N _________________________________________

	public static function addbookmark(string $id, string $query)
	{
		if(!empty($id) && !empty($query)) {
            $id = idclean($id);
            $id = substr($id, 0, 16);
		self::$bookmark[$id] = $query;
		}
	}

	public static function deletebookmark(string $id)
	{
		if(key_exists($id, self::$bookmark)) {
			unset(self::$bookmark[$id]);
		}
	}
	



}









?>