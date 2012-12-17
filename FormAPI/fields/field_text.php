<?php
class TextField extends Field {

	protected $label = 0;
	protected $help = -1;
	protected $length = 20;
	protected $maxlength = 50;
	
	public function __construct($id, $name, $label, $length, $maxlength, $help) {
		$this->id = $id;
		$this->name = $name;
		$this->label = $label;
		$this->length = $length;
		$this->maxlength = $maxlength;
		$this->help = $help;
	}

	public function generate($form, $lang) {
			$w = "<td class=\"labelc\">" . 
				$form->getMsg($this->label, $lang) . "</td>";
			$w .= "<td class=\"textc\"><input type=\"text\" maxlength=\"" .
				$this->maxlength . "\" size=\"" .
				$this->length . "\" value=\"" .
				$this->default . "\" name=\"" .
				$this->name . "\" /></td>";
		return $w;
	}

	/**
	 * Get type of field
	 *
	 * @return string type of field
	 */
	public function getType() {
		return "text";
	}

}

?>
