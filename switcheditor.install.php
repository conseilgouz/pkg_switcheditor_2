<?php
/**
 * @package    Switch Editor
 * @copyright  Copyright (C) 2023 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */
// no direct access
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;

class pkg_SwitchEditorInstallerScript
{
	protected $db;
	
	public function __construct()
	{
		$this->db = Factory::getDbo();
	}

	public function postflight($type, $parent)
	{
		if ('uninstall' == $type)
		{
			return;
		}
		// remove obsolete files
		$obsloteFiles = ["helper.php"];
		foreach ($obsloteFiles as $file)
		{
			$f = JPATH_ADMINISTRATOR . '/modules/mod_switcheditor/' . $file;
			if (@is_file($f))
			{
				File::delete($f);
			}
		}
		// update the plugin
		$query = $this->db->getQuery(true)
			->update('#__extensions')
			->set($this->db->quoteName('enabled') . '=1')
			->where($this->db->quoteName('element') . '="switcheditor"')
			->where($this->db->quoteName('type') . '="plugin"');
		$this->db->setQuery($query);
		$this->db->execute();
		// update admin module if not enabled : access = registered, position = status, enable it
		$query = $this->db->getQuery(true)
				->select($this->db->quoteName('id'))
				->from('#__modules')
				->where($this->db->quoteName('module') . '="mod_switcheditor"')
				->where($this->db->quoteName('client_id') . '=1')
				->where($this->db->quoteName('published') . '=0'); // not yet published
		$this->db->setQuery($query);
		$id = $this->db->loadResult();
		if ($id) {
			$id = (int) $id;
			// update the module position & publication
			$query = $this->db->getQuery(true)
					->update('#__modules')
					->set($this->db->quoteName('published') . '=1')
					->set($this->db->quoteName('position') . '= "status"')
					->set($this->db->quoteName('access') . '=2') // registred mini
					->where($this->db->quoteName('id') . '=' . $id);
			$this->db->setQuery($query);
			$this->db->execute();
			// remove any previous module menu entries
			$query = $this->db->getQuery(true)->delete('#__modules_menu')->where($this->db->quoteName('moduleid') . '=' . $id);
			$this->db->setQuery($query);
	        $this->db->execute();
			// insert a new module menu entry
			$query = $this->db->getQuery(true)->insert('#__modules_menu')->values($id . ', 0');
			$this->db->setQuery($query);
			$this->db->execute(); 
		}
		// update site module if not enabled : access = registered, enable it
		$query = $this->db->getQuery(true)
				->select($this->db->quoteName('id'))
				->from('#__modules')
				->where($this->db->quoteName('module') . '="mod_switcheditor"')
				->where($this->db->quoteName('client_id') . '=0')
				->where($this->db->quoteName('published') . '=0'); // not yet published
		$this->db->setQuery($query);
		$id = $this->db->loadResult();
		if ($id) {
			$id = (int) $id;
			// update the module position & publication
			$query = $this->db->getQuery(true)
					->update('#__modules')
					->set($this->db->quoteName('published') . '=1')
					->set($this->db->quoteName('access') . '=2') // registred mini
					->where($this->db->quoteName('id') . '=' . $id);
			$this->db->setQuery($query);
			$this->db->execute();
			// enable module on all menus
			$query = $this->db->getQuery(true);
			$columns = array('menuid', 'moduleid');
			$query->insert($this->db->quoteName('#__modules_menu'))
				   ->columns($this->db->quoteName($columns))
				   ->values(':menuid, :moduleid');
			// Bind values
			$menuid = 0; // all 
			$query->bind(':menuid', $menuid, Joomla\Database\ParameterType::INTEGER)
		          ->bind(':moduleid', $id, Joomla\Database\ParameterType::INTEGER);
			// Set the query using our newly populated query object and execute it.
			$this->db->setQuery($query);
			$this->db->execute();
		}
		// SwitchEditor is now on Github
		$query = $this->db->getQuery(true)
			->delete('#__update_sites')
			->where($this->db->quoteName('location') . ' like "%conseilgouz.com/updates/pkg_switcheditor%"');
		$this->db->setQuery($query);
		$this->db->execute();
	}
}
