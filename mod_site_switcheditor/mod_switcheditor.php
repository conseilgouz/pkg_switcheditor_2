<?php
/**
 * @package    Switch Editor
 * @subpackage mod_switcheditor
 * @copyright  Copyright (C) 2023 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */
// no direct access
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Version;
use ConseilGouz\Module\SwitchEditor\Site\Helper\SwitchEditorHelper;
use Joomla\CMS\Uri\Uri;

$j = new Version();
$version=substr($j->getShortVersion(), 0,1); 
if ($version == "3") { // Joomla 3.X
	JLoader::registerNamespace('ConseilGouz\Module\SwitchEditor\Site', JPATH_SITE . '/modules/mod_switcheditor/src', false, false, 'psr4');
}
$modulefield	= ''.URI::base(true).'/media/switcheditor/';
if (SwitchEditorHelper::isPluginEnabled())
{
	$options = SwitchEditorHelper::getEditorOptions($params);
	$value = Factory::getUser()->getParam('editor');
	$path = ModuleHelper::getLayoutPath('mod_switcheditor', $params->get('layout', 'default'));
	if (is_file($path))
	{
		// HTMLHelper::_('jquery.framework', true);
		$doc = Factory::getDocument();
		$doc->addScript($modulefield.'js/switcheditor.js');
		if ($version >= "4") {
			$doc->addStyleSheet($modulefield.'css/switcheditor_j4.css');
		} else {
			$doc->addStyleSheet($modulefield.'css/switcheditor.css');
		}
		require $path;
	}
}
