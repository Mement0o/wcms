<?php

class Modelmedia extends Model
{

	const MEDIA_SORTBY = ['id', 'size', 'type'];


	public function basedircheck()
	{
		if (!is_dir(Model::MEDIA_DIR)) {
			return mkdir(Model::MEDIA_DIR);
		} else {
			return true;
		}
	}

	public function favicondircheck()
	{
		if (!is_dir(Model::FAVICON_DIR)) {
			return mkdir(Model::FAVICON_DIR);
		} else {
			return true;
		}
	}

	public function thumbnaildircheck()
	{
		if (!is_dir(Model::THUMBNAIL_DIR)) {
			return mkdir(Model::THUMBNAIL_DIR);
		} else {
			return true;
		}
	}

	/**
	 * Get the Media Object
	 * 
	 * @param string $entry Id of the file
	 * @param string $dir Directory of media file
	 * 
	 * @return Media|bool
	 */
	public function getmedia(string $entry, string $dir)
	{
		$fileinfo = pathinfo($entry);

		if (isset($fileinfo['extension'])) {
			$datas = array(
				'id' => str_replace('.' . $fileinfo['extension'], '', $fileinfo['filename']),
				'path' => $dir,
				'extension' => $fileinfo['extension']
			);
			return new Media($datas);
		} else {
			return false;
		}
	}

	/**
	 * Display a list of media
	 * 
	 * @param string $path
	 * @param string $sortby
	 * @param string $order
	 * 
	 * @return array of Media objects
	 */
	public function getlistermedia($dir, $type = "all")
	{
		if (is_dir($dir)) {
			if ($handle = opendir($dir)) {
				$list = [];
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {

						$media = $this->getmedia($entry, $dir);

						if ($media != false) {

							$media->analyse();

							if (in_array($type, self::MEDIA_TYPES)) {
								if ($media->type() == $type) {
									$list[] = $media;
								}
							} else {
								$list[] = $media;
							}
						}
					}
				}
				return $list;
			}
		} else {
			return false;
		}
	}



	public function mediacompare($media1, $media2, $method = 'id', $order = 1)
	{
		$result = ($media1->$method() <=> $media2->$method());
		return $result * $order;
	}

	public function buildsorter($sortby, $order)
	{
		return function ($media1, $media2) use ($sortby, $order) {
			$result = $this->mediacompare($media1, $media2, $sortby, $order);
			return $result;
		};
	}


	/**
	 * Sort an array of media
	 * 
	 * @param array $medialist
	 * @param string $sortby
	 * @param int order Can be 1 or -1
	 */
	public function medialistsort(array &$medialist, string $sortby = 'id', int $order = 1): bool
	{
		$sortby = (in_array($sortby, self::MEDIA_SORTBY)) ? $sortby : 'id';
		$order = ($order === 1 || $order === -1) ? $order : 1;
		return usort($medialist, $this->buildsorter($sortby, $order));
	}




	public function listfavicon()
	{
		$glob = Model::FAVICON_DIR . '*.png';
		$faviconlist = glob($glob);
		$faviconlist = array_map(function ($input){
			return basename($input);
		}, $faviconlist);
		return $faviconlist;
	}


	public function listinterfacecss()
	{
		$glob = Model::CSS_DIR . '*.css';
		$listinterfacecss = glob($glob);
		$listinterfacecss = array_map(function ($input) {
			return basename($input);
		}, $listinterfacecss);
		$listinterfacecss = array_diff($listinterfacecss, ['edit.css', 'home.css']);
		return $listinterfacecss;
	}


	public function listdir($dir)
	{


		$result = array();

		$cdir = scandir($dir);
		$result['dirfilecount'] = 0;
		foreach ($cdir as $key => $value) {
			if (!in_array($value, array(".", ".."))) {
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
					$result[$value] = $this->listdir($dir . DIRECTORY_SEPARATOR . $value);
				} else {
					$result['dirfilecount']++;
				}
			}
		}

		return $result;
	}

	/**
	 * Upload single file
	 * 
	 * @param string $index The file id
	 * @param string $destination File final destination
	 * @param bool|int $maxsize Max file size in octets
	 * @param bool|array $extensions List of authorized extensions
	 * @param bool $jpgrename Change the file exentension to .jpg
	 * 
	 * @return bool If upload process is a succes or not
	 */
	function simpleupload(string $index, string $destination, $maxsize = false, $extensions = false, bool $jpgrename = false): bool
	{
		//Test1: if the file is corectly uploaded
		if (!isset($_FILES[$index]) || $_FILES[$index]['error'] > 0) return false;
		//Test2: check file size
		if ($maxsize !== false && $_FILES[$index]['size'] > $maxsize) return false;
		//Test3: check extension
		$ext = substr(strrchr($_FILES[$index]['name'], '.'), 1);
		if ($extensions !== false && !in_array($ext, $extensions)) return false;
		if ($jpgrename !== false) {
			$destination .= '.jpg';
		} else {
			$destination .= '.' . $ext;
		}
		//Move to dir
		return move_uploaded_file($_FILES[$index]['tmp_name'], $destination);
	}

	/**
	 * Upload multiple files
	 * 
	 * @param string $index Id of the file input
	 * @param string $target direction to save the files
	 */
	public function multiupload(string $index, string $target)
	{
		if ($target[strlen($target) - 1] != DIRECTORY_SEPARATOR)
			$target .= DIRECTORY_SEPARATOR;
		$count = 0;
		foreach ($_FILES[$index]['name'] as $filename) {
			$fileinfo = pathinfo($filename);
			$extension = idclean($fileinfo['extension']);
			$id = idclean($fileinfo['filename']);

			$tmp = $_FILES['file']['tmp_name'][$count];
			$count = $count + 1;
			$temp = $target . $id . '.' . $extension;
			move_uploaded_file($tmp, $temp);
			$temp = '';
			$tmp = '';
		}
	}

	public function adddir($dir, $name)
	{
		$newdir = $dir . DIRECTORY_SEPARATOR . $name;
		if (!is_dir($newdir)) {
			return mkdir($newdir);
		} else {
			return false;
		}
	}
}
