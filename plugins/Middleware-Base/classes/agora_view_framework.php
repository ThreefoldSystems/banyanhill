<?php

/**
 * Agora View Framework
 *
 * A simple class to load and display view files. Almost like part of a really dumbed-down MVC framework
 *
 */
class agora_view_framework {

	/**
	 * The default path to the template files you want to load.
	 * @var string
	 */
	private $template_path;


	/**
	 * Constructor Method
	 */
	public function __construct(){

		$this->log = new agora_log_framework();

		$this->template_path[] =  dirname( __FILE__ ) . '/../views/';
		$this->template_path[] = get_template_directory();
		$this->template_path[] = get_stylesheet_directory();
	}

	/**
	 * Function to load view templates
	 *
	 * @param string $template The name of a template to load. Corresponds to the file name without the .php extension
	 * @param string|array $content A string or associative array of content for the template to display
	 * @param boolean $return Optional parameter to have the content of the view file returned instead of output
	 * @return string
	 *
	 **/
	function load($template, $content = null, $return = false){

		if(is_array($content)) extract($content);

		$path = 'not set';

		try {
			$path = $this->_get_template($template);

			// Check to see if we need to echo the template directly, or just return its contents
			if($return === false){
				include($path);
			}else{
				ob_start();
				include($path);
				return ob_get_clean();
			}
		} catch (Exception $e) {
			echo __('Unable to Load Template ') . $path .'\n';
		}
	}

	/**
	 * Prepends an item to the template path array.
	 *
	 * @param string $path
	 */
	public function set_template_path($path){
		if(is_dir($path)){
			array_unshift($this->template_path, $path);
		}
	}

	/**
	 * Helper function to cycle through the array of registered template paths and return the first match.
	 *
	 * @param  string $template_name The name of the template file you want to load. Without the .php extension
	 * @return string The full path to the template file. Returns false if no matching file was found.
	 */
	public function _get_template($template_name){

		$reversed = array_reverse($this->template_path);

		foreach($reversed as $t){
			if(file_exists($t . '/' . $template_name .'.php')){
				return $t . '/' . $template_name . '.php';
			}
		}
		return false;
	}
}
