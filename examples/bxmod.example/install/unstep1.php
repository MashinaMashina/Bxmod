<?if(!check_bitrix_sessid()) return;?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="get">
	<?= bitrix_sessid_post();?>
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="hidden" name="id" value="<?=htmlspecialcharsEx($_REQUEST['id'])?>" />
	<input type="hidden" name="uninstall" value="Y" />
	<input type="hidden" name="step" value="2" />
	<? CAdminMessage::ShowMessage(GetMessage('BXMOD_UNINSTALL_INFO')) ?>
	<p><?=GetMessage('MOD_UNINST_SAVE')?></p>
	 <p><input type="checkbox" name="savedata" id="savedata" value="Y" checked="checked" /><label for="savedata"><?=GetMessage('MOD_UNINST_SAVE_TABLES')?></label><br /></p>
	<input type="submit" value="<?=GetMessage('MOD_UNINST_DEL');?>" />
</form>