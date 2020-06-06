<?php
/**
* @package plugin load company information into article
* @version 1.0.0
* @copyright Copyright (C) 2011-2020 Jonathan Brain. All rights reserved.
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

      if (!empty($paramsarray[0])) {
     		$module_output = self::$_params->def($paramsarray[0]);
        if (!empty($paramsarray[1])) {
          switch($paramsarray[1]) {
            case 'href':
              switch($paramsarray[0]) {
                case 'facebook':
                case 'twitter':
                  $text = jText::_('PLG_CONTENT_COMPANYINFO_' . $module_output);
                  break;
                default:
                  $text = jText::_($module_output);
                  if (!empty($paramsarray[2])) {
                    switch($paramsarray[2]) {
                      case 'button':
                        $text = '<button>' . $text . '<button>';
                        break;
                    }
                  }
                  break;
              }
              switch($paramsarray[0]) {
                case 'email':
                case 'email2':
                case 'email3':
                case 'email4':
                  $module_output = '<a href="mailto:' . $module_output . '">' . $text . '</a>';
                  break;
                case 'telephone':
                case 'telephone2':
                case 'mobile':
                case 'mobile2':
                  $module_output = '<a href="tel:' . preg_replace('/[^+0-9]/', '', $module_output) . '">' . $text . '</a>';
                  break;
                case 'companyno':
                  $module_output = '<a href="https://beta.companieshouse.gov.uk/company/' . str_pad(preg_replace('/[^0-9]/', '', $module_output), 8, '0', STR_PAD_LEFT) . '">' . $text . '</a>';
                  break;
              }
              break;
          }
        }
      }
      $article = str_replace($match[0], $module_output, $article);
		}
	}

	public function onContentPrepare($context, &$article, &$params, $limitstart){
	  self::prepare($article->text);
	}
}
?>