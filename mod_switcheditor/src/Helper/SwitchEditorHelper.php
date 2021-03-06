<?php
/**
 * @package    Switch Editor
 * @subpackage mod_switcheditor
 * @copyright  Copyright (C) 2021 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */
namespace ConseilGouz\Module\SwitchEditor\Administrator\Helper;
// no direct access
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

abstract class SwitchEditorHelper
{

	/**
	 * static method to determine if our plugin is enabled
	 * 
	 * @return  bool
	 */
	static public function isPluginEnabled()
	{
		// jimport('joomla.plugin.helper');
		return PluginHelper::isEnabled('system', 'switcheditor');
	}

	/**
	 * static method to get the list of available editors
	 * 
	 * @return  mixed
	 */
	static public function getEditorOptions($params)
	{
		static $editors;
		if (is_null($editors))
		{
			$db = Factory::getDBO();
			$db->setQuery((string) $db->getQuery(true)
					->select('element, name')
					->from('#__extensions')
					->where($db->quoteName('type') . ' = ' . $db->Quote('plugin'))
					->where($db->quoteName('folder') . ' = ' . $db->Quote('editors'))
					->where($db->quoteName('enabled') . ' = 1')
			);
			$editors = $db->loadObjectList();
			// load the language files
			if (!empty($editors))
			{
				foreach ($editors as &$editor)
				{
					Factory::getLanguage()->load($editor->name . '.sys', JPATH_ADMINISTRATOR);
					$editor->name = Text::_($editor->name);
					// strip of any prefixed "Editor - " bits
					if ($params->get('compact',0) == 1) { // compact view : remove word editor
						if (false === strpos('-', $editor->name)) {
							list($tmp, $name) = explode('-', $editor->name, 2);
							if (isset($name) && !empty($name)) {
								$editor->name = trim($name);
							}
						}
					} else { // standard view
						if (false !== strpos('-', $editor->name)) {
							list($tmp, $name) = explode('-', $editor->name, 2);
							if (isset($name) && !empty($name)) {
								$editor->name = trim($name);
							}
						}
					}
				}
			}
			// add the "default"
			if (!is_array($editors))
			{
				$editors = array();
			}
			if ($params->get('compact',0) == 1) { // compact view : remove word editor
				array_unshift($editors, HTMLHelper::_('select.option', '', Text::_('MOD_SWITCHEDITOR_SELECT_EDITOR_COMPACT'), 'element', 'name'));
			} else  {
				array_unshift($editors, HTMLHelper::_('select.option', '', Text::_('MOD_SWITCHEDITOR_SELECT_EDITOR'), 'element', 'name'));
			}
		}
		return $editors;
	}

	/**
	 * static method to save the user's editor preferences
	 */
	static public function setEditor()
	{
		$user   = Factory::getUser();
		$editor = Factory::getApplication()->input->get('adEditor');
		if (!empty($editor) && !$user->guest)
		{
			$user->setParam('editor', $editor);
			return $user->save(true);
		}
		return false;
	}

}
