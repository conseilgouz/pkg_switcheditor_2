<?php
/**
 * @package    Switch Editor
 * @subpackage mod_switcheditor site
 * @copyright  Copyright (C) 2023 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use ConseilGouz\Module\SwitchEditor\Site\Helper\SwitchEditorHelper;
//==========================> Joomla 3.10 compatibility <======================================
class ModSwitchEditorHelper
{
// ==============================================    AJAX Request 	============================================================
	public static function getAjax() {
		JLoader::registerNamespace('ConseilGouz\Module\SwitchEditor\Site', JPATH_SITE . '/modules/mod_switcheditor/src', false, false, 'psr4');
		return SwitchEditorHelper::getAjax();
	}
}
