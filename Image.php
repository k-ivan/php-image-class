<?php

/**
 * https://github.com/lampaa/php-image-class :: 2013
 */

class Image {
	private $source = false, $temp = false;
	
	/**
	 * hook file
	 */
	public function __construct($image) {
		if(!$info = getimagesize($image)) {
			throw new Exception("error image");
		}
		
		
		$createFunc = 'imagecreatefrom' . strtolower(str_replace('image/', '', $info['mime']));
		$this->source = $createFunc($image);
		
		return $this; //->source = $createFunc($image);
	}
	
	/**
	 * resize file
	 */
	public function resize($width, $height, $proportions = true) {
		if(!$this->source) {
			throw new Exception("error image");
		}	
		
		if(!$proportions) {
			$ratio = $height / imagesy($this->source);
			$width = imagesx($this->source) * $ratio;
		}
		
		$this->temp = imagecreatetruecolor($width, $height); 
		imagecopyresampled($this->temp, $this->source, 0, 0, 0, 0, $width, $height, imagesx($this->source), imagesy($this->source)); 
		$this->source = $this->temp;
		
		return $this;
	}
	
	/**
	 * save
	 */
	public function save($filename, $type = 'jpeg') {
		$info = imagejpeg($this->source, $filename);
		
		if(is_resource($this->temp)) {
			imagedestroy($this->temp);
		} 
		
		if(is_resource($this->source)) {
			imagedestroy($this->source);
		}
		
		return $info;
	}
	
	/**
	 * hook
	 */
	public static function hook($image) {
		$className = get_called_class();
		return new $className($image);
	}
}