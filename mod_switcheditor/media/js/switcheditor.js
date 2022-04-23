/**
 * @package    Switch Editor
 * @subpackage mod_switcheditor
 * @copyright  Copyright (C) 2021 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */

document.addEventListener("DOMContentLoaded", function(){
const els = document.querySelectorAll('.adEditor');	
for (let i = 0; i < els.length; i++) {
	els[i].addEventListener('change', function (ev) {
			var f = ev.srcElement.closest('form');
			// $.post(f.attr('action'), f.serialize());
			var httpRequest = new XMLHttpRequest()
			httpRequest.open('POST',f.getAttribute('action'))
			httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
            msg = 'adEditor='+ev.srcElement.value;
			msg += '&task=switch';
			httpRequest.send(msg);

		});
	}
})