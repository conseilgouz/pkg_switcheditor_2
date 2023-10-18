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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;
use ConseilGouz\Module\Switcheditor\Administrator\Helper\SwitcheditorHelper;

$j = new Version();
$version=substr($j->getShortVersion(), 0,1); 
if ($version == "3") { // Joomla 3.X
	JLoader::registerNamespace('ConseilGouz\Module\Switcheditor\Administrator', JPATH_ADMINISTRATOR . '/modules/mod_switcheditor/src', false, false, 'psr4');
}

$options = SwitcheditorHelper::getEditorOptions($params);
$value = Factory::getUser()->getParam('editor');
$path = ModuleHelper::getLayoutPath('mod_switcheditor', $params->get('layout', 'default'));
if (is_file($path)) {
	$doc = Factory::getDocument();
	$doc->addScript('../media/switcheditor/js/switcheditor.js');
	if ($version >= "4") {
		$doc->addStyleSheet('../media/switcheditor/css/switcheditor_j4.css');
	} else {
		$doc->addStyleSheet('../media/switcheditor/css/switcheditor.css');
	}
	require $path;
}
$document 		= Factory::getDocument();
$document->addScriptOptions('mod_switcheditor', 
array('version' => $version,'is_admin' => 1));
