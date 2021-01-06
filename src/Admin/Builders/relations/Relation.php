<?php

namespace MashinaMashina\Bxmod\Admin\Builders\Relations;

use \MashinaMashina\Bxmod\Admin\Builders\Field;
use \MashinaMashina\Bxmod\Tools\Html;

abstract class Relation extends Field
{
	public static function build($field, $entity, $table)
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
			$result .= static::buildInput($field, $entity, $table);
		}
		
		$result .= '</td></tr>';
		
		return $result;
	}
	
	public static function buildInput($field, $entity, $table)
	{
		switch ($field->getParameter('relation_view_type'))
		{
			case 'editor':
				return self::buildEditor($field, $entity, $table);
			
			case 'ajax_select':
				return self::buildAjaxSelect($field, $entity, $table);
			
			default:
				return self::buildSelect($field, $entity, $table);
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
	
	protected static function buildSelect($field, $entity, $table)
	{
		$allElements = $field->getAllReferences();
		
		if (! $field->getParameter('required'))
		{
			$options .= Html::buildTag('option', [
				'value' => '',
			], ' ');
		}
		
		foreach ($allElements as $id => $name)
		{
			$options .= Html::buildTag('option', [
				'value' => $id,
			], htmlentities($name));
		}
		
		$res = \CJSCore::Init(['chosen']);
		
		$uniqid = 'id'.uniqid();
			
		// $data = ['id' => $uniqid, 'multiple' => '', 'name' => $field->getName() . '[]'];
		$data = ['id' => $uniqid, 'name' => $field->getName() . '[]'];
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
	
	protected static function buildEditor($field, $entity, $table)
	{
		// $selected = $entity->get($field->getName());
		
		$fields = $field->getRefEntity()->getFields();
		
		$result = '<table class="internal" style="width:100%"><tbody>';
		
		/* head */
		$result .= '<tr class="heading">';
		foreach ($fields as $field)
		{
			if ($field->getParameter('bxmod_hidden') === true)
				continue;
			
			$result .= '<td>' . $field->getTitle() . '</td>';
		}
		$result .= '</tr>';
		/* end head */
		
		/* empty row */
		$result .= '<tr>';
		$n = 1;
		foreach ($fields as $fieldChild)
		{
			if ($fieldChild->getParameter('bxmod_hidden') === true)
				continue;
			
			$class = str_replace('MashinaMashina\Bxmod\ORM\Fields', 'MashinaMashina\Bxmod\Admin\Builders', get_class($fieldChild));
			
			$name = $fieldChild->getName();
			$fieldChild->setName($field->getName() . "[{$n}]." . $name);
			
			// $fieldChild->primary
			
			$result .= '<td>' . ($class)::buildInput($fieldChild, $table->createObject(), $table) . '</td>';
		}
		$result .= '</tr>';
		/* end empty row */
		
		$result .= '</tbody></table>';
		
		return $result;
	}
}
