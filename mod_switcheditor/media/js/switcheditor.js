/**
 * @package    Switch Editor
 * @subpackage mod_switcheditor
 * @copyright  Copyright (C) 2023 ConseilGouz. All rights reserved.
 * From anything-digital.com Switch Editor
 * @license    GNU/GPLv2
 */

document.addEventListener("DOMContentLoaded", function(){
const els = document.querySelectorAll('.adEditor');	
for (let i = 0; i < els.length; i++) {
	els[i].addEventListener('change', function (ev) {
			var f = ev.srcElement.closest('form');
			// $.post(f.attr('action'), f.serialize());
			isSite = false;
			if (f.name == "adEditorFormSite") isSite = true;
			msg = "";
			if (isSite) {
				url = "?option=com_ajax&module=switcheditor&adEditor="+ev.srcElement.value+"&task=switch&format=json";
				Joomla.request({
					method : 'POST',
						url : url,
						onSuccess: function(data, xhr) {
						},
						onError: function(message) {console.log(message.responseText)}
					}) 
			} else {
				var httpRequest = new XMLHttpRequest()
				httpRequest.open('POST',f.getAttribute('action'))
				httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
				msg = 'adEditor='+ev.srcElement.value;
				msg += '&task=switch';
				httpRequest.send(msg);
			}
		});
	}
})