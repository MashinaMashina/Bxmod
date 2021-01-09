(function($, win){
	win.bxMod = {};
	win.bxMod.editorLine = -1;
	win.bxMod.addEditorLine = function (uniqueID)
	{
		var content = $('#template-'+uniqueID).html();
		content = content.split('#NUM#').join(win.bxMod.editorLine);
		var matches = $.unique(content.match(/uniq_(.+?)_/g));
		
		for (var i=0; i<matches.length; i++)
		{
			content = content.split(matches[i]).join('uniq_' + win.bxMod.makeid(10) + '_');
			console.log(matches[i]);
		}
		
		$('#'+uniqueID).append(content);
		win.bxMod.editorLine--;
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