<?php
/**
 * @package    Switch Editor
 * @subpackage plg_system_switcheditor
 * @copyright  Copyright (C) 2021 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */
// no direct access
defined('_JEXEC') or die;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Version;
use ConseilGouz\Module\SwitchEditor\Administrator\Helper\SwitchEditorHelper;

class plgSystemSwitchEditor extends CMSPlugin
{

	protected $app;

	const EXT = 'switcheditor';

	public function onAfterInitialise()
	{
		if (!$this->app->isClient('administrator') )
		{
			return;
		}
		
		$input = $this->app->input;
		// don't execute if we're not firing one of our own tasks
		if (self::EXT != $input->get('option'))
		{
			return;
		}
		$j = new Version();
		$version=substr($j->getShortVersion(), 0,1); 
		if ($version != "4") { // Joomla 4.0
			JLoader::registerNamespace('ConseilGouz\Module\SwitchEditor\Administrator', JPATH_ADMINISTRATOR . '/modules/mod_switcheditor/src', false, false, 'psr4');
		}
		// execute the requested task
		$task = $input->get('task');
		switch ($task)
		{
			case 'switch':
				SwitchEditorHelper::setEditor();
				jexit();
				break;
			default: return null;
		}
	}

}
