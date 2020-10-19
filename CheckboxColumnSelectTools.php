<?php

namespace ichsanmust\grid;

use Closure;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii;

/**

 * @author Ichsan Must <ichsan.must10@gmail.com>
 * @since 2.0
 */
class CheckboxColumnSelectTools extends Column
{

	public $name = 'selection';
	public $checkboxOptions = [];
	public $multiple = true;



	public $valueInit = [];
	public $valueInitSingle = false;
	public $uniqueValue;
	public $disabledCheckboxOnValue = true;
	public $checkedCheckboxOnValue = true;
	public $_pjax = '';

	public function init()
	{
		parent::init();

		$id = $this->grid->options['id'];
		$idPrefix = $id . '-';
		if ($this->checkedCheckboxOnValue) {
			$jsonDataInit = json_encode($this->valueInit);
		} else {
			$jsonDataInit = json_encode(array());
		}
		$js = '
   		
			$("document").ready(function(){ 
			   initiateListChecked();
			   setCheckedChooseAll()
			});
			
			// new
			function removeAll() {
				//alert("ok");
				listChecked = [];
				removeAllSelected();
				setCheckedChooseAll();
			}
			
			// new
			function addSelected(arrayData) {
				//removeAllSelected();
				$.each(arrayData, function(key, value){
				   //console.log(String(value));
				   addListChecked(String(value));
				})  
				retainCheckedSingle();
				setCheckedChooseAll();
			}
			
			function initiateListChecked() {
				
				// kondisi update halaman dimana biasa nya saat halaman pertama kali muncul sudah ada yang terchecked
				var listedUpdateData = ' . $jsonDataInit . ';
				$.each(listedUpdateData, function(key, value){
				   //console.log(String(value));
				   addListChecked(String(value));
				})  
				
			}
			
			function addListChecked(val) {
				listChecked.push(val); 
			}
			
			// new
			function removeAllSelected(){
				$(".' . $idPrefix . 'checkboxSingle:enabled").each(function(i) {
					$(this).prop("checked", false);
				});
			}
			
			function removeListChecked(arr) {
				var what, a = arguments, L = a.length, ax;
				while (L > 1 && arr.length) {
					what = a[--L];
					while ((ax= arr.indexOf(what)) !== -1) {
						arr.splice(ax, 1);
					}
				}
				return arr;
			}
			
			function getListChecked() {
				arr = listChecked.filter( function( item, index, inputArray ) {
					   return inputArray.indexOf(item) == index;
				});
				return arr ;
			}
			

			function isSelectedAll(){
				var status = true ;
				$(".' . $idPrefix . 'checkboxSingle:enabled").each(function(i) {
					var btn = $(this);
					if (btn.is(":checked")){
						
					} else {
						status = false;
						return false;
					}
				});
				
				return status;
			}
			function setCheckedChooseAll(){
				var status = isSelectedAll();
				$(".' . $idPrefix . 'checkboxAll").prop("checked", status);
			}
			function retainCheckedSingle(){
				$.each(listChecked, function(key, value){
				   //console.log(value);
				   $("#' . $idPrefix . 'choose_"+value).prop("checked", true);
				}) 
			}
			
			jQuery(document).on("click",".' . $idPrefix . 'checkboxSingle:enabled", function(){ // on click select Single
				
				// add an remove list checked
				if ($(this).is(":checked")){
					addListChecked($(this).val());
				} else {
					removeListChecked(listChecked,$(this).val());
				}
				
				//console.log(listChecked);
				//console.log(isSelectedAll());
				setCheckedChooseAll();
			});
			
			jQuery(document).on("click",".' . $idPrefix . 'checkboxAll",function(e){ // on click select All
				var table= $(e.target).closest("table");
				$("td input:checkbox:enabled",table).prop("checked",this.checked);
				
				// add an remove list checked
				$(".' . $idPrefix . 'checkboxSingle:enabled").each(function(i) {
					var btn = $(this);
					if (btn.is(":checked")){
						addListChecked(btn.val());
					} else {
						removeListChecked(listChecked,btn.val());
					}
				});
				//console.log(listChecked);
			});
			
		';
		if (!Yii::$app->request->isPjax) {
			$this->grid->getView()->registerJs($js, \yii\web\View::POS_HEAD);
		} else {
			$getData = Yii::$app->request->get();
			if ($this->_pjax != '') {
				if ($getData['_pjax'] != '#' . $this->_pjax) {
					$this->grid->getView()->registerJs($js, \yii\web\View::POS_HEAD);
				}
			} else {
				$this->grid->getView()->registerJs($js, \yii\web\View::POS_HEAD);
			}
		}


		if (empty($this->name)) {
			throw new InvalidConfigException('The "name" property must be set.');
		}
		if (empty($this->uniqueValue)) {
			throw new InvalidConfigException('The "uniqueValue" property must be set.');
		}

		if (!$this->disabledCheckboxOnValue && !$this->checkedCheckboxOnValue) {
			throw new InvalidConfigException('The "disabledCheckboxOnValue" or "checkedCheckboxOnValue" property must be set true.');
		}

		if (!$this->multiple) {
			throw new InvalidConfigException('The "multiple" property must be set true.');
		}


		if (substr_compare($this->name, '[]', -2, 2)) {
			$this->name .= '[]';
		}
	}

	/**
	 * Renders the header cell content.
	 * The default implementation simply renders [[header]].
	 * This method may be overridden to customize the rendering of the header cell.
	 * @return string the rendering result
	 */
	protected function renderHeaderCellContent()
	{
		$name = $this->name;
		if (substr_compare($name, '[]', -2, 2) === 0) {
			$name = substr($name, 0, -2);
		}
		if (substr_compare($name, ']', -1, 1) === 0) {
			$name = substr($name, 0, -1) . '_all]';
		} else {
			$name .= '_all';
		}


		if ($this->header !== null) {
			return parent::renderHeaderCellContent();
		} else {
			$idGRid = $this->grid->options['id'];
			return Html::checkBox($idGRid . "-" . 'checkboxAll', false, [
				'class' => $idGRid . "-" . 'checkboxAll',
				'id' => $idGRid . "-" . 'chooseAll',
				//'label' => '<label for="'.$idGRid.'-chooseAll"></label>',
			]);
			//return Html::checkBox($name, false, ['class' => 'select-on-check-all']);
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function renderDataCellContent($model, $key, $index)
	{
		$idGRid = $this->grid->options['id'];
		$uniqueValue = $this->uniqueValue;
		$disabled = false;
		$checked = false;

		if ($this->valueInitSingle instanceof Closure) {
			$valueInitSingle = call_user_func($this->valueInitSingle, $model, $key, $index, $this);
		} else {
			$valueInitSingle = $this->valueInitSingle;
		}

		if (count($this->valueInit) > 0) {
			if (in_array($model->$uniqueValue, $this->valueInit)) {
				if ($this->disabledCheckboxOnValue) {
					$disabled = true;
				}
				if ($this->checkedCheckboxOnValue) {
					$checked = true;
				}
			} else {
				if ($this->disabledCheckboxOnValue) {
					$disabled = false;
				}
				if ($this->checkedCheckboxOnValue) {
					$checked = false;
				}
			}
		} else {
			if ($valueInitSingle === true) {
				if ($this->disabledCheckboxOnValue) {
					$disabled = true;
				}
				if ($this->checkedCheckboxOnValue) {
					$checked = true;
				}
			} else {
				if ($this->disabledCheckboxOnValue) {
					$disabled = false;
				}
				if ($this->checkedCheckboxOnValue) {
					$checked = false;
				}
			}
		}

		$id = $idGRid . "-" . "choose_" . $model->$uniqueValue;
		$optionsDefault =
			[
				'value' => $model->$uniqueValue,
				'disabled' => $disabled,
				'checked' => $checked,
				'class' => $idGRid . "-" . 'checkboxSingle',
				'id' => $id,
				// 'label' => '<label for="'.$id.'"></label>'
			];

		if ($this->checkboxOptions instanceof Closure) {
			$options = call_user_func($this->checkboxOptions, $model, $key, $index, $this);
			$options = array_merge($options, $optionsDefault);
		} else { // default checkboxOptions;
			$options = $this->checkboxOptions;
			$options = array_merge($options, $optionsDefault);
			/*  if (!isset($options['value'])) {
                $options['value'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $key;
            } */
		}

		return Html::checkbox($this->name, !empty($options['checked']), $options);
	}
}
