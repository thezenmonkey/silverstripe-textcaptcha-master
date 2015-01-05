# TextCaptcha SpamProtecter Module
SpamProtector Module that uses [TextCaptcha](http://textcaptcha.com) for Spam Protection

## Requirements
* SilverStripe 3.1+
* The SpamProtection Module https://github.com/silverstripe/silverstripe-spamprotection

## Installation
Unzip to your project and add the following to your _config.php
```yaml
---
name: spamprotection
---
FormSpamProtectionExtension:
  default_spam_protector: TextCaptchaProtector
NoCaptchaField:
  ApiKey : '<Your Api Key>'
```

if you omit the CaptchaAPI it declaration it will default to 'demo' providing you with a smaller subset of questions for testing

## To Do
* It still needs to be prettied up to render better in with the UserForm module
* Unit Test (if I get around to learning them)
* Composer intigration