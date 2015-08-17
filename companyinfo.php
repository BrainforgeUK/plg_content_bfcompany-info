<?php
/**
* @package plugin load company information into article
* @version 1.0.0
* @copyright Copyright (C) 2011 Jonathan Brain. All rights reserved.
* @license GPL
* @author http://www.brainforge.co.uk
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentCompanyInfo extends JPlugin{
  public static $_params = null;
  
	public function __construct(&$subject, $config = array())	{
		parent::__construct($subject, $config);
    self::$_params = $this->params;
  }

	public static function prepare(&$article){		
		$matches = array();
		preg_match_all('/{(companyinfo)\s*(.*?)}/i', $article, $matches, PREG_SET_ORDER);  
		
		foreach ($matches as $match){   		
			$module = '';
			$arguments = array();
			$module = preg_replace("/\[|]/", '', $match[2]);
			$paramsarray = explode('|',$module);			
			
      $module_output = null;

      if (count($paramsarray) && $paramsarray[0]) {
     		$module_output = self::$_params->def($paramsarray[0]);
      }
      $article = str_replace($match[0], $module_output, $article);
		} 		
	}

	public function onContentPrepare($context, &$article, &$params, $limitstart){
	  self::prepare($article->text);
	}
}
?>