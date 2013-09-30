<?php

class TextCaptchaField extends SpamProtectorField {
	
	protected static $api_key;
	
	function Field($properties = array()) {
		$key = self::$api_key;
		
		$question = $this->getQuestion();
			
		$attributes = array(
			'type' => 'text',
			'class' => 'text' . ($this->extraClass() ? $this->extraClass() : ''),
			'id' => $this->id(),
			'name' => $this->getName(),
			'value' => '',
			'title' => $this->Title(),
			'tabindex' => $this->getAttribute('tabindex'),
			'maxlength' => ($this->maxLength) ? $this->maxLength : null,
			'size' => ($this->maxLength) ? min( $this->maxLength, 30 ) : null 
		);
		
		$html = $this->createTag('input', $attributes);
		
		return $html;
	}
	
	function FieldHolder($properties=array()) {
		$Title = $this->XML_val('Title');
		$Message = $this->XML_val('Message');
		$MessageType = $this->XML_val('MessageType');
		$Type = $this->XML_val('Type');
		$extraClass = $this->XML_val('extraClass');
		$Name = $this->XML_val('Name');
		$label = $this->getQuestion();
		$Field = $this->XML_val('Field');

		$messageBlock = (!empty($Message)) ? "<span class=\"message $MessageType\">$Message</span>" : "";

		return <<<HTML
<div id="$Name" class="field $Type $extraClass"><label for="$Name"><strong>Please answer the following question:</strong> $label</label>{$Field}{$messageBlock}</div>
HTML;
	}
	
	public function validate($validator) {
		
		
		$ans = strtolower(trim($this->Value()));
		$ans = md5($ans);
		$captcha = Session::get('captcha');
		
		
		if (in_array($ans,Session::get('captcha'))) {
		    Session::clear('setCaptcha');
		    Session::clear('question');
		    Session::clear('captchaFail');
		    return true;
		} else {
		    Session::clear('setCaptcha');
		    Session::clear('question');
		    Session::set("captchaFail", true);
		    //$this->Message('Captcha', 'Catcha error', 'bad');
		    $validator->validationError(
					$this->name, 
					_t(
						'Captcha.INCORRECTSOLUTION', 
						"Please check the Captcha field and try again" ,
						"Mollom Captcha provides words in an image, and expects a user to type them in a textfield"
					), 
					"validation", 
					false
				);
			return false;
		}
	}
	
	
	function getCaptcha() {
		
		
		$url = 'http://api.textcaptcha.com/'.self::$api_key;
		//if(Session::get('setCaptcha') == true && Session::get('question') === null && Session::get('captchaFail') != true) {}
		
		try {
			$xml = @new SimpleXMLElement($url,null,true);
		} catch (Exception $e) {
			// if there is a problem, use static fallback..
			$fallback = '<captcha>'.
				'<question>Is ice hot or cold?</question>'.
				'<answer>'.md5('cold').'</answer></captcha>';
			$xml = new SimpleXMLElement($fallback);
		}
		
		// display question as part of form
		$question = (string) $xml->question;
		
		// store answers in session
		$ans = array();
		foreach ($xml->answer as $hash)
		$ans[] = (string) $hash;
		
		Session::set('captcha', $ans);
		Session::set('question', $question);
		Session::set('setCaptcha', true);
		Session::clear('captchaFail');
		
		return true;
	}
	
	function getQuestion() {
		if(Session::get('setCaptcha') === true && Session::get('captchaFail') != true) {
			return Session::get('question');
		} else {
			$this->getCaptcha();
			return Session::get('question');
		}
	}
	
	public static function CaptchaAPI($key = "demo") {
		self::$api_key = $key;
	}
}