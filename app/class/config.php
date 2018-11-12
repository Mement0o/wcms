<?php



abstract class Config
{
	protected static $arttable = 'artstore';
	protected static $domain;
	protected static $admin;
	protected static $editor;
	protected static $invite;
	protected static $read;
	protected static $color4;
	protected static $fontsize = 15;
	protected static $basepath = '';
	protected static $route404 = '';



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

// ________________________________________ G E T _______________________________________

	public static function arttable()
	{
		return self::$arttable;
	}

	public static function domain()
	{
		return self::$domain;
	}

	public static function admin()
	{
		return self::$admin;
	}

	public static function editor()
	{
		return self::$editor;
	}

	public static function invite()
	{
		return self::$invite;
	}

	public static function read()
	{
		return self::$read;
	}

	public static function color4()
	{
		return self::$color4;
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



// __________________________________________ S E T ______________________________________

	public static function setarttable($arttable)
	{
		self::$arttable = strip_tags($arttable);
	}

	public static function setdomain($domain)
	{
		self::$domain = strip_tags($domain);
	}

	public static function setadmin($admin)
	{
		if(is_string($admin) && strlen($admin) >= 4 && strlen($admin) <= 64) {
			self::$admin = strip_tags($admin);
		}
	}

	public static function seteditor($editor)
	{
		self::$editor = strip_tags($editor);
	}

	public static function setinvite($invite)
	{
		self::$invite = strip_tags($invite);
	}

	public static function setread($read)
	{
		self::$read = strip_tags($read);
	}

	public static function setcolor4($color4)
	{
		if (strlen($color4) <= 8) {
			self::$color4 = $color4;
		}
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




}









?>