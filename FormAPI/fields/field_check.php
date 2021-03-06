<?php

/**
  * Field class to generate and check CheckBoxes.
  *
  * @property string $type the type of the field (i.e. check).
  * @property int $label label ID of Field
  * @property int $length number of items in one row, 0 all items in a sigle row
  * @property string[] $options label IDs for check items
  *
  * @package formapi
  * @author Zoltan Siki <siki@agt.bme.hu> and Zoltan Koppanyi <zoltan.koppanyi@gmail.com>
  * @version 0.1
  * @todo default value not handled
  */
class CheckField extends Field {

	protected $length;
	protected $options = array();
	
	public function __construct($id, $name, $label, $options, $length=5, 
		$requested=false, $default=NULL, $help=NULL) {

		parent::__construct($id, $name, $requested, $default, NULL, $help);
		$this->label = $label;
		$this->length = $length;
		$this->options = $options;
		$this->type = "check";
	}

	public function getOption($i) {
		if (! isset($this->options[$i])) {
			return "?";
		}
		return $this->options[$i];
	}

	public function generate($form, $lang) {
		$w = "<div class=\"labelc\"><table border=\"0\"><tr>";
		for ($i=0; $i < count($this->options);$i++) {
			if ($this->length > 0 && $i > 0 && $i % $this->length == 0) {
				$w .= "</tr><tr>";
			}
			$w .= "<td><input type=\"checkbox\"" .
				" name=\"" . $this->name . "[]\"" .
				" value=\"" . $i . "\"" .
				" title=\"" . $form->getMsg($this->help, $lang) . "\"" .
				" />" .
				$form->getMsg($this->options[$i], $lang) . "</td>";
		}
		$w .= "</tr></table></div>";
		return $w;
	}
}

?>
