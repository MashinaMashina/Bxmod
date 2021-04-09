(function($, win){
	win.bxMod = {};
	win.bxMod.appendedLine = -1;
	win.bxMod.addTemplateToContainer = function (containerId, templateId)
	{
		var content = $('#'+templateId).html();
		content = content.split('#NUM#').join(win.bxMod.appendedLine);
		var matches = content.match(/uniq_(.+?)_/g);
		if (matches)
		{
			var matches = $.unique(matches);
			
			for (var i=0; i<matches.length; i++)
			{
				content = content.split(matches[i]).join('uniq_' + win.bxMod.makeid(10) + '_');
				console.log(matches[i]);
			}
		}
		
		$('#'+containerId).append(content);
		win.bxMod.appendedLine--;
	}
	win.bxMod.addEditorLine = function (uniqueID)
	{
		return win.bxMod.addTemplateToContainer(uniqueID, 'template-'+uniqueID);
	}
	win.bxMod.removeEditorLine = function (uniqueID)
	{
		$('#editorline-' + uniqueID).remove();
	}
	win.bxMod.makeid = function (length) {
		var result			 = '';
		var characters		 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		var charactersLength = characters.length;
		for ( var i = 0; i < length; i++ ) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
		return result;
	}
})(jQuery, window)