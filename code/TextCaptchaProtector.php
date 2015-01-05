<?php

class TextCaptchaProtector implements SpamProtector {
	function getFormField($name = "TextCaptchaField", $title = "Captcha", $value = null, $form = null, $rightTitle = null) {

		// load servers. Needs to be called before validKeys() 
		

		return new TextCaptchaField($name, $title, $value, $form, $rightTitle);
	}
	
	public function setFieldMapping($fieldMapping){
		return true;
	}
}