<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormReCaptcha renders a ReCaptcha widget.
 *
 * This widget uses ReCaptcha: http://recaptcha.net/
 *
 * The ReCaptcha API documentation can be found at http://recaptcha.net/apidocs/captcha/
 *
 * To be able to use this widget, you need an API key: http://recaptcha.net/api/getkey
 *
 * As it's not possible to change the name of ReCaptcha fields, you will have to add them manually
 * when binding a form from an HTTP request.
 *
 * Here's a typical usage when embedding a captcha in a form with a contact[%s] name format:
 *
 *    $captcha = array(
 *      'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
 *      'recaptcha_response_field'  => $request->getParameter('recaptcha_response_field'),
 *    );
 *    $this->form->bind(array_merge($request->getParameter('contact'), array('captcha' => $captcha)));
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormReCaptcha.class.php 30758 2010-08-25 11:39:13Z fabien $
 */
class sfWidgetFormReCaptcha extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * public_key:     The ReCaptcha public key
   *  * use_ssl:        Whether to use SSL or not (false by default)
   *  * server_url:     The URL for the HTTP API
   *  * server_url_ssl: The URL for the HTTPS API (when use_ssl is true)
   *  * theme:          The ReCaptcha theme
   *  * culture:        The ReCaptcha language
   *
   * @see sfWidgetForm
   */
  public function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('public_key');

    $this->addOption('use_ssl', false);
    $this->addOption('server_url', 'http://api.recaptcha.net');
    $this->addOption('server_url_ssl', 'https://api-secure.recaptcha.net');
    $this->addOption('theme', 'clean');
    $this->addOption('culture', 'en');
  }

  /**
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $server = $this->getServerUrl();
    $key = $this->getOption('public_key');
    $theme = $this->getOption('theme');
    $culture = $this->getOption('culture');

    return sprintf('
    <script type="text/javascript">
    var RecaptchaOptions = {
    theme : \'%s\',
    lang : \'%s\'
    };
    </script>
    <script type="text/javascript" src="%s/challenge?k=%s"></script>
    <noscript>
      <iframe src="%s/noscript?k=%s" height="300" width="500" frameborder="0"></iframe><br />
      <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
      <input type="hidden" name="recaptcha_response_field" value="manual_challenge" />
    </noscript>
    ', $theme, $culture, $server, $key, $server, $key);
  }

  protected function getServerUrl()
  {
    return $this->getOption('use_ssl') ? $this->getOption('server_url_ssl') : $this->getOption('server_url');
  }
}
