<?php
/**
 * @package    Switch Editor
 * @copyright  Copyright (C) 2025 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */
// no direct access
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

class pkg_SwitchEditorInstallerScript
{
	protected $db;
	
	public function __construct()
	{
		$this->db = Factory::getContainer()->get(DatabaseInterface::class);
	}

	public function postflight($type, $parent)
	{
		if ('uninstall' == $type)
		{
			return;
		}
		$j = new Version();
		$version=substr($j->getShortVersion(), 0,1); 
		// remove obsolete files
		if ($version >= "4") { // Joomla 4.X
			$obsloteFiles = ["helper.php","mod_switcheditor.php"];
			foreach ($obsloteFiles as $file) {
				$f = JPATH_ADMINISTRATOR . '/modules/mod_switcheditor/' . $file;
				if (@is_file($f)) {
					File::delete($f);
				}
				$f = JPATH_ROOT . '/modules/mod_switcheditor/' . $file;
				if (@is_file($f)) {
					File::delete($f);
				}
			}
		} 
		// plugin not used anymore
		$query = $this->db->createQuery()
			->delete('#__extensions')
			->where($this->db->quoteName('element') . ' like "switcheditor" AND '
					.$this->db->quoteName('type'). 'like "plugin"');
		$this->db->setQuery($query);
		$this->db->execute();
		$f = JPATH_ROOT . '/plugins/system/switcheditor';
		if (@is_dir($f)) {
			Folder::delete($f);
		}
		// update admin module if not enabled : access = registered, position = status, enable it
		$query = $this->db->createQuery()
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
			$query = $this->db->createQuery()
					->update('#__modules')
					->set($this->db->quoteName('published') . '=1')
					->set($this->db->quoteName('position') . '= "status"')
					->set($this->db->quoteName('access') . '=2') // registred mini
					->where($this->db->quoteName('id') . '=' . $id);
			$this->db->setQuery($query);
			$this->db->execute();
			// remove any previous module menu entries
			$query = $this->db->createQuery()->delete('#__modules_menu')->where($this->db->quoteName('moduleid') . '=' . $id);
			$this->db->setQuery($query);
	        $this->db->execute();
			// insert a new module menu entry
			$query = $this->db->createQuery()->insert('#__modules_menu')->values($id . ', 0');
			$this->db->setQuery($query);
			$this->db->execute(); 
		}
		// update site module if not enabled : access = registered, enable it
		$query = $this->db->createQuery()
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
			$query = $this->db->createQuery()
					->update('#__modules')
					->set($this->db->quoteName('published') . '=1')
					->set($this->db->quoteName('access') . '=2') // registred mini
					->where($this->db->quoteName('id') . '=' . $id);
			$this->db->setQuery($query);
			$this->db->execute();
			// enable module on all menus
			$menu = new stdClass();
			$menu->menuid = 0;
			$menu->moduleid=$id;
			// Insert the object into the modules_menu table.
			$result = $this->db->insertObject('#__modules_menu', $menu);
		}
		// SwitchEditor is now on Github
		$query = $this->db->createQuery()
			->delete('#__update_sites')
			->where($this->db->quoteName('location') . ' like "%conseilgouz.com/updates/pkg_switcheditor%"');
		$this->db->setQuery($query);
		$this->db->execute();
	}
}
