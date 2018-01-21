<?php
//============================= START OF CLASS ==============================//
//CLASS: Plugin                                                              //
//===========================================================================//
class Plugin{
	public $directory;
	private $_settings=array();
	private $_file_settings;
	private $_file_settings_read = false;
	//======================= START OF CONSTRUCTOR ========================//
    //  CONSTRUCTOR: __construct                                           //
    //=====================================================================//
	public function __construct($dir_plugin){
		$this->directory = $dir_plugin;
		$this->_file_settings = $this->directory.DIRECTORY_SEPARATOR.'settings.txt';		
	}
	//=====================================================================//
    //  CONSTRUCTOR: __construct                                           //
    //======================== END OF CONSTRUCTOR =========================//    

    //========================= START OF METHOD ===========================//
    //  METHOD: set                                                        //
    //=====================================================================//
	function set($key,$value){
		if($this->key_exists($key)){
			if($this->get($key) == $value) return true;
		}
		$key = base64_encode($key);
		$value = base64_encode($value);
		$this->_settings[$key]=$value;
		return $this->save();
	}
    //=====================================================================//
    //  METHOD: set                                                        //
    //========================== END OF METHOD ============================//
    
    //========================= START OF METHOD ===========================//
    //  METHOD: get                                                        //
    //=====================================================================//
	function get($key){
	    if (file_exists($this->_file_settings) == false) { $this->save(); }
	    if ($this->_file_settings_read == false) { $this->read(); }
		$key = base64_encode($key);
		if (isset($this->_settings[$key]) == false) {
			return null;
		}
		$value = $this->_settings[$key];
		return base64_decode($value);
	}
	//=====================================================================//
    //  METHOD: get                                                        //
    //========================== END OF METHOD ============================//
    
    //========================= START OF METHOD ===========================//
    //  METHOD: key_exists                                                 //
    //=====================================================================//
	function key_exists($key){
		if (file_exists($this->_file_settings) == false) { $this->save(); }
	    if ($this->_file_settings_read == false) { $this->read(); }
		$key = base64_encode($key);
		return isset($this->_settings[$key]) ? true : false;
	}
    //=====================================================================//
    //  METHOD: key_exists                                                 //
    //========================== END OF METHOD ============================//
    
    //========================= START OF METHOD ===========================//
    //  METHOD: read                                                        //
    //=====================================================================//
	private function read(){
		$this->_settings = unserialize(file_get_contents($this->_file_settings));
		$this->_file_settings_read = true;
	}
    //=====================================================================//
    //  METHOD: read                                                       //
    //========================== END OF METHOD ============================//
    
    //========================= START OF METHOD ===========================//
    //  METHOD: save                                                        //
    //=====================================================================//
	private function save(){
		return file_put_contents($this->_file_settings,serialize($this->_settings));
	}
    //=====================================================================//
    //  METHOD: save                                                        //
    //========================== END OF METHOD ============================//
}
//===========================================================================//
// CLASS: Plugin                                                             //
//============================== END OF CLASS ===============================//
?>
