<?php

class ContentElement {

	private $rawOutput = "";
	private $output = "";

	// Constructor
	public function __construct($rawOutput) {
		$this->setRawOutput($rawOutput);
	}

	// Set raw output
	private function setRawOutput($rawOutput) {
		$this->rawOutput = $rawOutput;
	}

	// Get raw output
	public function getRawOutput() {
		return $this->rawOutput;
	}

	// Set output
	private function setOutput($output) {
		$this->output = $output;
	}

	// Get output
	public function getOutput() {
		return $this->output;
	}

	// Replace content element xml
	private function replaceElementPlaceholder($matches) {
		$contentElementXML = str_replace(array("<y:", "</y:"), array("<", "</"), $matches[0]);
		$contentElementName = $matches[1];

		$xmlDocument = simplexml_load_string($contentElementXML);

		$contentElement = $xmlDocument;

		ob_start();

		if(file_exists("custom/elements/{$contentElementName}.php")) {
			include "custom/elements/{$contentElementName}.php";
		} else {
			echo "Content element \"{$contentElementName}\" not found!";
		}

		return ob_get_clean();
	}

	// Parse output
	public function parseOutput() {
		$parsedOutput = $this->getRawOutput();

		$parsedOutput = preg_replace_callback("/<y:element.*name=\"(.+)\".*>.*<\/y:element>/smiU", array($this, 'replaceElementPlaceholder'), $parsedOutput);

		$this->setOutput($parsedOutput);
	}

}

?>