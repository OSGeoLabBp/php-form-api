<?php

include_once("import.php");

/**
  * form class to read, store form data and generate html form
  *
  * @property int $id unique id of form
  * @property string $name unique name for this form
  * @property string $mode GET or POST mode
  * @property int $title id of the title of the form in messages
  * @property string $layout layout type for the form (e.g. table), optional 
  * @property array $fields field objects of the form
  * @property array $messages multilingual message array of form
  *
  * @package formapi
  * @author Zoltan Siki <siki@agt.bme.hu> and Zoltan Koppanyi <zoltan.koppanyi@gmail.com>
  * @version 0.1
  */
class Form {
	const horizLayout = 0;
	const vertLayout = 1;

	protected $id;
	protected $target;
	protected $name;
	protected $mode;
	protected $title;
	protected $layout;
	protected $fields = array();
	protected $messages = array();
	
	/**
	  * Default constructor 
	  *
	  */
	public function __construct($id) {

		$this->id = $id;		
		$this->target = NULL;		
		$this->name = NULL;
		$this->mode = "post";
		$this->title = NULL;
		$this->layout = self::vertLayout;
	}

	/**
	 * Get unique ID of form
	 *
	 * @return int Unique ID of form
	 */
	public function getId() {
		return $this->id;		
	}

	/**
	 * Set unique name of form
	 *
	 * @param string $name Unique unique name for this form
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get unique name for this form
	 *
	 * @return string Unique unique name for this form
	 */
	public function getName() {
		return $this->name;		
	}

	/**
	 * Request mode: GET or POST 
	 *
	 * @param string $mode GET or POST mode
	 */
	public function setMode($mode) {
		if (strtolower($mode) == "post" || strtolower($mode) == "get") {
			$this->mode = $mode;
		}
	}

	/**
	 * Get request mode: GET or POST
	 *
	 * @return string GET or POST mode
	 */
	public function getMode() {
		return $this->mode;		
	}

	/**
	 * Set the title of the form
	 *
	 * @param string $title title of the form
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get the title of the form
	 *
	 * @return string title of the form
	 */
	public function getTitle() {
		return $this->title;		
	}

	/**
	 * Set the layout: horizontal, vertical
	 *
	 * @param string $layout layout: horizontal, vertical
	 */
	public function setLayout($layout) {
		if ($layout === self::horizLayout || $layout === self::vertLayout) {
			$this->layout = $layout;
		}
	}

	/**
	 * Get the actual layout
	 *
	 * @return string text of layout
	 */
	public function getLayout() {
		return $this->layout;		
	}

	/**
	 * Set the target file that processes the form and/or handle the request
	 *
	 * @param string $target location of target php file
	 * @todo it can be checked that the target file exists
	 */
	public function setTarget($target) {
		if (! empty($target)) {
			$this->target = $target;		
		}
	}

	/**
	 * Get the target file that processes the form and/or handle the request
	 *
	 * @return string location of target file
	 */
	public function getTarget() {
		return $this->target;		
	}

	/**
	 * Set the list of fields that will be displayed while generating form
	 *
	 * @param Field[] $fields list of fields instances
	 */
	public function setFields($fields) {
		$this->fields = $fields;
	}

	/**
	 * Get the reference of list of fields that will be displayed while generating form
	 *
	 * @return &Field[] list of fields instances
	 */
	public function &getFields() {
		return $this->fields;		
	}

	/**
	 * Set the list of messages that contains the texts in form
	 *
	 * @param string[] $messages string list of messages
	 */
	public function setMessages($messages) {
		$this->messages = $messages;
	}

	/**
	 * Get the reference of list of messages that contains the texts in form
	 *
	 * @return &string[] list of messages
	 */

	public function &getMessages() {
		return $this->messages;		
	}

	/**
	  * Generate the HTML form
	  *
	  * @param string $lang language for the form that has been specified in XML file
	  * @param int $full 0/1 generate only form/generate full html page
	  *
	  * @return HTML form definition as string 
	  */
	public function generate($lang, $full = 1) {
		$formHtml = "";
		if ($full) {
			$formHtml .= "<html><head><title>" .
				$this->getMsg($this->title, $lang) .
				"</title></head></body>";
			// TBD Javascript code
		}
		$formHtml .= "<form id=\"" . $this->id . "\" class=\"formc\" action=\"" . $this->target . "\" enctype=\"multipart/form-data\">";
		$formHtml .= "<p class=\"titlec\">" .
			$this->getMsg($this->title, $lang) . "</P>";

		if($this->layout === self::horizLayout) {
			$formHtml .= "<table class=\"formtable\">";
			$formHtml .= "<tr>";
			foreach ($this->fields as $f) {
				$formHtml .= "<td> <center>";
				$formHtml .= $f->generateLabel($this, $lang);
				$formHtml .= "</center> </td>";
			}
			$formHtml .= "</tr>";
			$formHtml .= "<tr>";
			foreach ($this->fields as $f) {
				$formHtml .= "<td class=\"formfield\"> <center>";
				$formHtml .= $f->generate($this, $lang);
				$formHtml .= "</center> </td>";
			}
			$formHtml .= "</tr>";
			$formHtml .= "</table>";

		} else  {
			$formHtml .= "<table class=\"formtable\">";
			foreach ($this->fields as $f) {
				$formHtml .= "<tr>";
				$formHtml .= "<td>";
				$formHtml .= $f->generateLabel($this, $lang);
				$formHtml .= "</td>";
				$formHtml .= "<td class=\"formfield\">";
				$formHtml .= $f->generate($this, $lang);
				$formHtml .= "</td>";
				$formHtml .= "</tr>";
			}
			$formHtml .= "</table>";
		}

		$formHtml .= "</form>";
		if ($full) {
			$formHtml .= "</body></html>";
		}
		return $formHtml;
	}

	/**
	 * Find a field by name
	 *
	 * @param string $name filed name to search for
	 *
	 * @return field object or NULL if not found
	 */
	private function find($name) {
		foreach($this->fields as $f) {
			if ($f->getName() == $name) {
				return $f;
			}
		}
		return NULL;
	}

	/**
	 * Return a message
	 *
	 * @param int $id message index in messages array
	 * @param string $lang language of the message
	 *
	 * @return text of the message
	 */
	public function getMsg($id, $lang) {
		if (isset($this->messages[$id][$lang])) {
			return $this->messages[$id][$lang];
		}
		return $id;
	}

	/**
	 * Convert form definition to string (only for debugging)
	 *
	 * @return string
	 */
	public function __toString() {
		$w = "<br><br>Form -";
		foreach ($this as $attr => $val) {
			$w .= " " . $attr . ":" . $val;
		}
		foreach ($this->fields as $f) {
			$w .= "<br>" . $f->toString();
		}
		return $w;
	}
}
