<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors\Relations;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Security;
use \MashinaMashina\Bxmod\Admin\Form\Editors\Field;
use \MashinaMashina\Bxmod\Tools\Html;
use \MashinaMashina\Bxmod\ORM\Fields\Relations;

abstract class Relation extends Field
{
	public static function build(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		$fieldName = htmlspecialcharsbx($field->getTitle());
		
		if ($field->getParameter('required'))
		{
			$fieldName = '<b>' . $fieldName . '</b>'; 
		}
		
		$twoColumns = true;
		if ($field->getParameter('bxmod_relation_view_type') === 'editor' and (
			is_subclass_of(get_called_class(), OneToMany::class)
			or get_called_class() === OneToMany::class
		))
		{
			$twoColumns = false;
		}
		
		$result = '<tr>';
		$result .= $twoColumns ? '<td>' : '<td colspan="2">';
		$result .= $fieldName;
		$result .= $twoColumns ? '</td><td>' : '';
		
		if ($field->getParameter('bxmod_readonly') === true)
		{
			$result .= htmlspecialcharsbx($entity[$field->getName()]);
		}
		else
		{
			$result .= static::buildInput($field, $entity, $table, $tagData);
		}
		$result .= $twoColumns ? ' ' . $field->getDescription() : '';
		$result .= '</td></tr>';
		
		return $result;
	}
	
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		if ($field->getParameter('bxmod_relation_view_type') === 'editor' and (
			is_subclass_of(get_called_class(), OneToMany::class)
			or get_called_class() === OneToMany::class
		))
		{
			return static::buildEditor($field, $entity, $table, $tagData);
		}
		elseif ($field->getParameter('bxmod_relation_view_type') === 'ajax_select')
		{
			return static::buildAjaxSelect($field, $entity, $table, $tagData);
		}
		else
		{
			return static::buildSelect($field, $entity, $table, $tagData);
		}
	}
	
	protected static function buildAjaxSelect($field, $entity, $table, $tagData = [])
	{
		$autocompleteLink = 'superlink';
		if (strpos($autocompleteLink, '?') === false)
			$autocompleteLink .= '?';
		else
			$autocompleteLink .= '&';
		
		$autocompleteLink .= bitrix_sessid_get() . '&query=';
		
		$options = Html::buildTag('option', [
			'value' => 'none',
			'selected' => '',
		], '');
		$selected = $field->getAllReferences(['entity' => $entity]);
		foreach ($selected as $key => $name)
		{
			$options .= Html::buildTag('option', [
				'value' => $key,
				'selected' => '',
			], htmlentities($name));
		}
		
		\CJSCore::Init(['chosen', 'autocomplete']);
		
		$uniqid = 'id'.uniqid();
		$moduleId = str_replace('.', ':', $entity->sysGetEntity()->getModule());
		
		$data = ['id' => $uniqid, 'multiple' => '', 'name' => $field->getName() . '[]'];
		$result = Html::buildTag('select', $data, $options . '<option value="" disabled>Enter name...</option>');
		
		$ajaxParams = [
			'field_name' => $field->getName(),
			'entity' => get_class($table),
		];
		
		$signer = new Security\Sign\Signer;
		$signedParams = $signer->sign(base64_encode(serialize($ajaxParams)), 'bxmod');
		
		$result .= '<script>
			$(function(){
				$("#'.$uniqid.'").chosen({
					width:"300px",
				});
				
				$(".chosen-choices input").autocomplete({
					minLength: 2,
					delay: 500,
					source: function(request, response) {
						BX.ajax.runAction("'.$moduleId.'.ajaxinput.getselectoptions", {
							data: {
								query: request.term,
								signedParameters: "'.$signedParams.'",
								c: "bxmod" // dont remove, needs for sign
							},
						}).then(function (result) {
							$("#'.$uniqid.' option").each(function(){
								if (! $(this).prop("selected"))
									$(this).remove();
							});
							result.data.reverse();
							response($.map(result.data, function(item){
								$("#'.$uniqid.'").prepend(\'<option value="\'+item.id+\'">\' + item.name + \'</option>\');
							}));
							$("#'.$uniqid.'").trigger("chosen:updated");
							$(".chosen-choices input").val(request.term);
						}, function (result) {
							alert("Error. See console for more information");
							console.error("Request error.", result);
						});
						/*
						$.ajax({
							url: "'.$autocompleteLink.'"+request.term,
							dataType: "json",
						}).done(function(result) {
								$("#'.$uniqid.' option").each(function(){
									if (! $(this).prop("selected"))
										$(this).remove();
								});
								result.data.reverse();
								response($.map(result.data, function(item){
									$("#'.$uniqid.'").prepend(\'<option value="\'+item.id+\'">\' + item.name + \'</option>\');
								}));
								$("#'.$uniqid.'").trigger("chosen:updated");
								$(".chosen-choices input").val(request.term);
							});
						*/
					}
				});
			});
		</script>';
		
		return $result;
	}
	
	protected static function buildSelect($field, $entity, $table, $tagData = [])
	{
		$refEntity = $field->getRefEntity();
		
		if (is_null($entity->get($field->getName())) and ! empty(reset($entity->primary)))
		{
			$entity->fill($field->getName() . '.*');
		}
		
		$selectedValues = $entity->get($field->getName());
		
		if (! is_iterable($selectedValues))
		{
			$selectedValues = [$selectedValues];
		}
		
		$primarys = [];
		$primary = $table->getEntity()->getPrimary();
		foreach ($selectedValues as $val)
		{
			$primarys[] = $val['ID'];
		}
		
		$allElements = $field->getAllReferences();
		
		if (! $field->getParameter('required'))
		{
			$options .= Html::buildTag('option', [
				'value' => '',
			], Loc::getMessage('bxmod_not_selected'));
		}
		
		foreach ($allElements as $id => $name)
		{
			$selected = in_array($id, $primarys) ? 'selected' : '';
			
			$options .= Html::buildTag('option', [
				'value' => $id,
				$selected => '',
			], htmlentities($name));
		}
		
		$res = \CJSCore::Init(['chosen']);
		
		$uniqid = 'uniq_'.uniqid() . '_';
		
		$data = $tagData + ['id' => $uniqid, 'name' => $field->getName()];
		if (get_called_class() instanceof Relations\ManyToMany or get_called_class() instanceof Relations\OneToMany)
		{
			$data['multiple'] = '';
			$data['name'] .= '[]';
		}
		
		$result = Html::buildTag('select', $data, $options);
		
		$result .= '<script>
				$(function(){
					$("#'.$uniqid.'").chosen({
						width:"300px",
					});
				});
			</script>';
		
		return $result;
	}
	
	protected static function buildEditor($field, $entity, $entityTable, $tagData = [])
	{
		$refEntity = $field->getRefEntity();
		$editorId = 'editor' . uniqid();
		
		if (! is_subclass_of($refEntity->getDataClass(), \MashinaMashina\Bxmod\Orm\Entity\DataManager::class))
		{
			throw new \Exception('Table ' . $refEntity->getDataClass() . ' must be instance of '
				. \MashinaMashina\Bxmod\Orm\Entity\DataManager::class . ' for use as editable');
		}
		
		if (is_null($entity->get($field->getName())) and ! empty(reset($entity->primary)))
		{
			$entity->fill($field->getName());
		}
		
		$selectedValues = $entity->get($field->getName());
		
		$fields = $refEntity->getFields();
		
		$result = Html::buildSimpleTag('input', [ // триггер поля на случай, если все записи удалены.
			'type' => 'hidden',
			'name' => $field->getName() . "[0][_primary]",
			'value' => 'none',
		]);
		$result .= '<table class="internal" style="width:100%" id="'.$editorId.'"><tbody>';
		
		/* head */
		$result .= '<tr class="heading">';
		foreach ($fields as $fieldChild)
		{
			if ($fieldChild->getParameter('bxmod_hidden') === true)
				continue;
			
			$title = $fieldChild->getTitle();
			
			if ($fieldChild->getParameter('required') === true)
				$title = '<b>' . $title . ' * </b>';
			
			$result .= '<td>' . $title . '</td>';
		}
		$result .= '<td></td>';
		$result .= '</tr>';
		/* end head */
		
		$n = 1;
		foreach ($selectedValues as $value)
		{
			$result .= static::buildEditorLine($n, $value, $field, $fields);
			$n++;
		}
		
		$template = '<template id="template-'.$editorId.'">';
		$template .= static::buildEditorLine('#NUM#', $refEntity->createObject(), $field, $fields);
		$template .= '</template>';
		$template .= '<center style="margin:10px 0">';
		$template .= Html::buildSimpleTag('input', [
			'type' => 'button',
			'class' => 'adm-btn-big',
			'onclick' => "bxMod.addEditorLine('{$editorId}')",
			'value' => 'Еще',
		]);
		$template .= '</center>';
		
		$result .= '</tbody></table>' . $template;
		
		return $result;
	}
	
	protected function buildEditorLine($n, $value, $field, $fields)
	{
		$uniqid = 'uniq_'.uniqid() . '_';
		$line = '<tr id="editorline-'.$uniqid.'">';
		
		foreach ($value->primary as $k => $v)
		{
			$line .= Html::buildSimpleTag('input', [
				'type' => 'hidden',
				'name' => $field->getName() . "[{$n}][_primary]",
				'value' => $v,
			]);
		}
		
		foreach ($fields as $fieldChild)
		{
			if ($fieldChild->getParameter('bxmod_hidden') === true)
				continue;
			
			$name = $fieldChild->getName();
			$childName = $field->getName() . "[{$n}][{$name}]";
			
			$childTable = $field->getRefEntityName() . 'Table';
			$line .= '<td>' . ($fieldChild->getEditorClass())::buildInput($fieldChild, $value, new $childTable, [
				'name' => $childName,
			]) . '</td>';
		}
		$line .= '<td>'.Html::buildTag('a', [
			'href' => '#',
			'onclick' => 'bxMod.removeEditorLine("'.$uniqid.'");return false;',
			'class' => 'bxmod-icon bxmod-icon-delete'
		]) . '</td>';
		$line .= '</tr>';
		
		return $line;
	}
}
