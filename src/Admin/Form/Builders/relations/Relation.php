<?php

namespace MashinaMashina\Bxmod\Admin\Form\Builders\Relations;

use \MashinaMashina\Bxmod\Admin\Form\Builders\Field;
use \MashinaMashina\Bxmod\Tools\Html;
use \MashinaMashina\Bxmod\ORM\Fields\Relations;

abstract class Relation extends Field
{
	public static function build($field, $entity, $table, $tagData = [])
	{
		$fieldName = htmlspecialcharsbx($field->getTitle());
		
		if ($field->getParameter('required'))
		{
			$fieldName = '<b>' . $fieldName . '</b>'; 
		}
		
		$result = '<tr><td colspan="2">';
		$result .= $fieldName;
		
		if ($field->getParameter('bxmod_readonly') === true)
		{
			$result .= htmlspecialcharsbx($entity[$field->getName()]);
		}
		else
		{
			$result .= static::buildInput($field, $entity, $table, $tagData);
		}
		
		$result .= '</td></tr>';
		
		return $result;
	}
	
	public static function buildInput($field, $entity, $table, $tagData = [])
	{
		switch ($field->getParameter('relation_view_type'))
		{
			case 'editor':
				return self::buildEditor($field, $entity, $table, $tagData);
			
			case 'ajax_select':
				return self::buildAjaxSelect($field, $entity, $table, $tagData);
			
			default:
				return self::buildSelect($field, $entity, $table, $tagData);
		}
		
		// if ($autocompleteLink = $field->getParameter('bxmod_input_ajax_autocomplete_link'))
		// {
			// if (strpos($autocompleteLink, '?') === false)
				// $autocompleteLink .= '?';
			// else
				// $autocompleteLink .= '&';
			
			// $autocompleteLink .= bitrix_sessid_get() . '&query=';
			
			// $options = '';
			// $selected = $this->getEntity()->get($field->getName());
			// foreach ($selected as $select)
			// {
				// $options .= Html::buildTag('option', [
					// 'value' => $select['id'],
					// 'selected' => '',
				// ], htmlentities($select['name']));
			// }
			
			// \CJSCore::Init(['chosen', 'autocomplete']);
			
			// $uniqid = 'id'.uniqid();
			
			// $data = ['id' => $uniqid, 'multiple' => '', 'name' => $field->getName() . '[]'];
			// $result = Html::buildTag('select', $data, $options . '<option value="" disabled>Enter name...</option>');
			
			// $result .= '<script>
				// $(function(){
					// $("#'.$uniqid.'").chosen({
						// width:"300px",
					// });
					
					// $(".chosen-choices input").autocomplete({
						// minLength: 2,
						// delay: 500,
						// source: function( request, response ) {
							// $.ajax({
								// url: "'.$autocompleteLink.'"+request.term,
								// dataType: "json",
							// }).done(function(data) {
									// $("#'.$uniqid.' option").each(function(){
										// if (! $(this).prop("selected"))
											// $(this).remove();
									// });
									// data.reverse();
									// response( $.map( data, function( item ) {
										// $("#'.$uniqid.'").prepend(\'<option value="\'+item.id+\'">\' + item.name + \'</option>\');
									// }));
									// $("#'.$uniqid.'").trigger("chosen:updated");
									// $(".chosen-choices input").val(request.term);
								// });
						// }
					// });
				// });
			// </script>';
		// }
	}
	
	protected static function buildSelect($field, $entity, $table, $tagData = [])
	{
		$refEntity = $field->getRefEntity();
		echo PHP_EOL . PHP_EOL . PHP_EOL;
		
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
		
		// var_dump($primarys, $table->getEntity()->getPrimary());
		// exit;
		
		$allElements = $field->getAllReferences();
		
		if (! $field->getParameter('required'))
		{
			$options .= Html::buildTag('option', [
				'value' => '',
			], ' ');
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
		
		if (is_null($entity->get($field->getName())) and ! empty(reset($entity->primary)))
		{
			$entity->fill($field->getName());
		}
		
		$selectedValues = $entity->get($field->getName());
		
		$fields = $refEntity->getFields();
		
		$result = '<table class="internal" style="width:100%" id="'.$editorId.'"><tbody>';
		
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
			
			$class = str_replace('MashinaMashina\Bxmod\ORM\Fields', 'MashinaMashina\Bxmod\Admin\Form\Builders', get_class($fieldChild));
			
			$name = $fieldChild->getName();
			$childName = $field->getName() . "[{$n}][{$name}]";
			
			$childTable = $field->getRefEntityName() . 'Table';
			$line .= '<td>' . ($class)::buildInput($fieldChild, $value, new $childTable, [
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
