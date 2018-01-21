<?php
//============================= START OF CLASS ==============================//
//CLASS: Plugins                                                             //
//===========================================================================//
class Plugins{
  private static $data = array('initialized'=>array());
  private static $plugins = array();
  private function __construct(){}
  
  //========================= START OF METHOD ===========================//
  //  METHOD: get                                                        //
  //=====================================================================//
  public static function get($plugin_name){
    return array_key_exists($plugin_name, self::$plugins) ? self::$plugins[$plugin_name] : false;
  }
  //=====================================================================//
  //  METHOD: get                                                        //
  //========================== END OF METHOD ============================//
  
  //========================= START OF METHOD ===========================//
  //  METHOD: get_active                                                 //
  //=====================================================================//
  public static function get_active(){
  	$active = array();
    foreach (self::$plugins as $plugin){
      if ($plugin->get('active') == true) $active[] = $plugin;
    }
    return $active;
  }
  //=====================================================================//
  //  METHOD: get_active                                                 //
  //========================== END OF METHOD ============================//
  
  //========================= START OF METHOD ===========================//
  //  METHOD: get_all                                                    //
  //=====================================================================//
  public static function get_all(){
    return self::$plugins;
  }
  //=====================================================================//
  //  METHOD: get_all                                                    //
  //========================== END OF METHOD ============================//
  
  public static function initialize ($dir_plugins) {
  	self::$data['dir_plugins'] = $dir_plugins;
  	$plugins = utils::dir_list_dirs( $dir_plugins );
  	foreach($plugins as $dir_plugin){
  		self::register_plugin($dir_plugin);
  	}
  }
  
  public static function hook ($checkpoint , $params = array()) {
  	foreach(self::$plugins as $plugin){
  		if ( method_exists($plugin, $checkpoint) ){
  			if(!call_user_func_array(array( $plugin, $checkpoint ) , $params)) {
  				$error = "Cannot hook plugin ($plugin) at checkpoint ($checkpoint)";
  			    throw new RuntimeException( $error );
  			}  			
        }
  	}
  }
  
  //========================= START OF METHOD ===========================//
  //  METHOD: register_plugin                                            //
  //=====================================================================//
  public static function register_plugin( $dir_plugin ){
  	// START: Register only once
  	if(in_array($dir_plugin, self::$data['initialized'])){
  		return true;
  	}
  	self::$data['initialized'][] = $dir_plugin;
  	// END: Register only once
  	
  	$plugin_name = basename($dir_plugin);
  	$plugin_class_name = 'plugin_'.$plugin_name;
  	$file_plugin_class_name = 'class.'.$plugin_class_name.'.php';
  	$file_plugin = $dir_plugin.DIRECTORY_SEPARATOR.$file_plugin_class_name;
  	if (file_exists($file_plugin)) {
  		s::load($file_plugin);
  	} else {
  		$error = 'Plugin <b>'.$plugin_name.'</b> MUST HAVE plugin file <b>'.$file_plugin_class_name.'</b> in directory '.$dir_plugin;
  		throw new RuntimeException($error);
  	}
  	
  	
  	$plugin_class_name = self::str_camelize($plugin_class_name,'_');
  	if (class_exists($plugin_class_name,false) == false) {
  		$error = 'A class with name <b>'.$plugin_class_name.'</b> MUST BE DEFINED in file <b>'.$file_plugin.'</b>';
  		throw new RuntimeException($error);  	
  	}
  	
  	$plugin = new $plugin_class_name( $dir_plugin );
  	self::$plugins[$plugin_name] = $plugin;
  }
  //=====================================================================//
  //  METHOD: register_plugin                                            //
  //========================== END OF METHOD ============================//
  
  //========================= START OF METHOD ===========================//
  // METHOD: str_camelize                                                //
  //=====================================================================//
  private static function str_camelize($string,$separator=" ",$remove_separator=false){
  	$string = str_replace($separator, " ", $string);
  	$string = ucwords($string);
  	if($remove_separator){
  		$string = str_replace(" ", "", $string);
  	}else{
  		$string = str_replace(" ", $separator, $string);
  	}
  	return $string;
  }
  //=====================================================================//
  //  METHOD: str_camelize                                               //
  //========================== END OF METHOD ============================//
}
//===========================================================================//
// CLASS: Plugins                                                            //
//============================== END OF CLASS ===============================//
?>
