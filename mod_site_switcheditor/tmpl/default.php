<?php
/**
 * @package    Switch Editor
 * @subpackage mod_switcheditor
 * @copyright  Copyright (C) 2021 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */
// no direct access
defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
// Joomla 4.0 custom field conflict 
if (($app->input->get('option') == 'com_fields') && ($app->input->get('view') == 'field') && ($app->input->get('layout') == 'edit')) return;
?>
<div class="adEditorFormBox btn-group">
	<form name="adEditorFormSite">
		<?php echo str_replace(' id="adEditor"', '', HTMLHelper::_('select.genericlist', $options,'adEditor', ' class="adEditor chzn-done" data-chosen="done"','element', 'name',$value)); ?>
		<input type="hidden" name="task" value="switch" />
	</form>
</div>
