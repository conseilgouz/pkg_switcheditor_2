<?php
/**
 * @package    Switch Editor
 * @subpackage mod_switcheditor
 * @copyright  Copyright (C) 2025 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */
 
namespace ConseilGouz\Module\Switcheditor\Site\Dispatcher;

use Joomla\CMS\Factory;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Version;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dispatcher class for mod_guidedtours
 *
 * @since  4.3.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Runs the dispatcher.
     *
     * @return  void
     *
     * @since   4.3.0
     */
    public function dispatch()
    {
        $user = $this->getApplication()->getIdentity();
        if ($user === null || $user->id === 0) {
            return;
        }
        parent::dispatch();
    }

    /**
     * Returns the layout data.
     *
     * @return  array
     *
     * @since   4.3.0
     */
    protected function getLayoutData()
    {
        $module = "media/switcheditor";
        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        $wa->registerAndUseStyle('switcheditor', $module.'/css/switcheditor_j4.css');
        $wa->registerAndUseScript('switcheditor', $module.'/js/switcheditor.js');

        $j = new Version();
        $version = substr($j->getShortVersion(), 0, 1);

        $document 		= Factory::getApplication()->getDocument();
        $document->addScriptOptions(
            'mod_switcheditor',
            array('version' => $version,'is_admin' => 1)
        );

        $data = parent::getLayoutData();

        $data['options'] = $this->getHelperFactory()->getHelper('SwitcheditorHelper')->getEditorOptions($data['params']);
        $data['value'] =  Factory::getApplication()->getIdentity()->getParam('editor');

        return $data;
    }
}
