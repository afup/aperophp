<?php

class MRBase
{
  protected $format = 'xml';
  protected $secure = false;
  protected $url;
  protected $timeout = 30;
  protected $maxRedirs = 10;
  protected $key;
  protected $methodName = '';
  protected $verb = 'GET';
  protected $requestBody = null;
  protected $requestLength = 0;
  protected $debug = false;
  protected $responseBody = false;
  protected $responseInfo = false;
  protected $error = null;
  protected $errno = null;

  public function __construct()
  {
    $this->url               = 'api.mailingreport.com';
  }

  public function flush()
  {
    $this->requestBody       = null;
    $this->requestLength     = 0;
    $this->verb              = 'GET';
    $this->methodName        = null;
    $this->responseBody      = null;
    $this->responseInfo      = null;
    $this->error             = null;
    $this->errno             = null;
  }

  public function setFormat($format)
  {
    if (!in_array($format, array('json', 'xml')))
    {
      throw new Exception('The response format '.$format.' is not supported.');
    }

    $this->format = $format;
  }

  public function setVerb($verb)
  {
    if (!in_array($verb, array('GET', 'POST')))
    {
      throw new InvalidArgumentException('Current verb ('.$this->verb.') is invalid.');
    }

    $this->verb = $verb;
  }

  public function setMethodName($methodName)
  {
    $this->methodName = $methodName;
  }

  public function setKey($key)
  {
    $this->key = $key;
  }

  public function setParams(Array $params)
  {
    $this->requestBody = $params;
  }

  public function setSecure($secure)
  {
    if ($secure === true)
    {
      $this->secure = true;
    }
    else
    {
      $this->secure = false;
    }
  }

  public function execute()
  {
    $curlHandle = curl_init();
    $this->setAuth($curlHandle);

    try
    {
      switch(strtoupper($this->verb))
      {
        case 'GET':
          $this->executeGet($curlHandle);
        break;
        case 'POST':
          $this->executePost($curlHandle);
        break;
        case 'PUT':
          $this->executePut($curlHandle);
        break;
        case 'DELETE':
          $this->executeDelete($curlHandle);
        break;
        default:
          throw new InvalidArgumentException('Current verb ('.$this->verb.') is invalid.');
        break;
      }
    }
    catch(InvalidArgumentException $e)
    {
       curl_close($$curlHandlech);
       throw $e;
    }
    catch(Exception $e)
    {
       curl_close($curlHandle);
       throw $e;
    }

    return $this->responseBody;
  }

  public function buildPostBody($data = null)
  {
    $data =($data !== null) ? $data : $this->requestBody;

    if(!is_array($data))
    {
      throw new InvalidArgumentException('Invalid data input for parameters. Array expected.');
    }

    $data = http_build_query($data, '', '&');
    $this->requestBody = $data;
  }

  protected function executeGet($curlHandle)
  {
    $this->doExecute($curlHandle);
  }

  protected function executePost($curlHandle)
  {
    if(!is_string($this->requestBody))
    {
       $this->buildPostBody();
    }

    curl_setopt($curlHandle, CURLOPT_POST, true);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $this->requestBody);
    
    $this->doExecute($curlHandle);
  }

  protected function executePut($curlHandle)
  {
    if(!is_string($this->requestBody))
    {
       $this->buildPostBody();
    }

    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $this->requestBody);
    curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'PUT');

    $this->doExecute($curlHandle);
  }

  protected function executeDelete($curlHandle)
  {
    curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'DELETE');

    $this->doExecute($curlHandle);
  }

  protected function doExecute(&$curlHandle)
  {
    $this->setCurlOpts($curlHandle);

    $this->responseBody =   curl_exec($curlHandle);
    $this->responseInfo =   curl_getinfo($curlHandle);
    $this->error =          curl_error($curlHandle);
    $this->errno =          curl_errno($curlHandle);

    curl_close($curlHandle);
  }

  protected function setCurlOpts(&$curlHandle)
  {
    curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION,  true);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER,  true);
    curl_setopt($curlHandle, CURLOPT_MAXREDIRS,       $this->timeout);
    curl_setopt($curlHandle, CURLOPT_MAXREDIRS,       $this->maxRedirs);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT,         $this->timeout);
    curl_setopt($curlHandle, CURLOPT_URL,             (($this->secure)?'https://':'http://').$this->url.'/'.$this->format.'/'.$this->methodName.'/');
    curl_setopt($curlHandle, CURLOPT_COOKIEJAR,       realpath('cookie.txt'));
    curl_setopt($curlHandle, CURLOPT_COOKIEFILE,      realpath('cookie.txt'));
    if ($this->secure)
    {
      curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER,  false);
    }
  }

  protected function setAuth(&$curlHandle)
  {
    curl_setopt($curlHandle, CURLOPT_HTTPAUTH,        CURLAUTH_BASIC);
    curl_setopt($curlHandle, CURLOPT_USERPWD,         $this->key.':');
  }
}

class MailingReport extends MRBase
{
  public function __construct($key)
  {
    parent::__construct();

    $this->setKey($key);
  }

  public function GlobalCountries()
  {
    $this->setMethodName('GlobalCountries');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function GlobalLanguages()
  {
    $this->setMethodName('GlobalLanguages');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function GlobalPing()
  {
    $this->setMethodName('GlobalPing');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function ContactsList()
  {
    $this->setMethodName('ContactsList');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function ContactsShow($email)
  {
    $this->setMethodName('ContactsShow');
    $this->setVerb('POST');
    $this->setParams(array('email' => $email));

    return $this->execute();
  }

  public function ContactsCreate($params)
  {
    $this->setMethodName('ContactsCreate');
    $this->setVerb('POST');
    $this->setParams($params);

    return $this->execute();
  }

  public function ContactsUpdate($params)
  {
    $this->setMethodName('ContactsUpdate');
    $this->setVerb('POST');
    $this->setParams($params);

    return $this->execute();
  }

  public function ContactsUnsubscribe($email)
  {
    $this->setMethodName('ContactsUnsubscribe');
    $this->setVerb('POST');
    $this->setParams(array('email' => $email));

    return $this->execute();
  }

  public function ContactsDelete($email)
  {
    $this->setMethodName('ContactsDelete');
    $this->setVerb('POST');
    $this->setParams(array('email' => $email));

    return $this->execute();
  }

  public function CustomFieldsList()
  {
    $this->setMethodName('CustomFieldsList');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function CustomFieldsShow($apiKey)
  {
    $this->setMethodName('CustomFieldsShow');
    $this->setVerb('POST');
    $this->setParams(array('apiKey' => $apiKey));

    return $this->execute();
  }

  public function CustomFieldsCreate($name, $type, $isVisible)
  {
    $this->setMethodName('CustomFieldsCreate');
    $this->setVerb('POST');
    $this->setParams(array('name' => $name, 'type' => $type, 'is_visible' => $isVisible));

    return $this->execute();
  }

  public function CustomFieldsUpdate($apiKey, $name, $type, $isVisible)
  {
    $this->setMethodName('CustomFieldsUpdate');
    $this->setVerb('POST');
    $this->setParams(array('apiKey' => $apiKey, 'name' => $name, 'type' => $type, 'is_visible' => $isVisible));

    return $this->execute();
  }

  public function CustomFieldsDelete($apiKey)
  {
    $this->setMethodName('CustomFieldsDelete');
    $this->setVerb('POST');
    $this->setParams(array('apiKey' => $apiKey));

    return $this->execute();
  }

  public function MailingListsList()
  {
    $this->setMethodName('MailingListsList');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function MailingListsShow($apiKey)
  {
    $this->setMethodName('MailingListsShow');
    $this->setVerb('POST');
    $this->setParams(array('apiKey' => $apiKey));

    return $this->execute();
  }

  public function MailingListsCreate($name)
  {
    $this->setMethodName('MailingListsCreate');
    $this->setVerb('POST');
    $this->setParams(array('name' => $name));

    return $this->execute();
  }

  public function MailingListsUpdate($apiKey, $name)
  {
    $this->setMethodName('MailingListsUpdate');
    $this->setVerb('POST');
    $this->setParams(array('apiKey' => $apiKey, 'name' => $name));

    return $this->execute();
  }

  public function MailingListsDelete($apiKey)
  {
    $this->setMethodName('MailingListsDelete');
    $this->setVerb('POST');
    $this->setParams(array('apiKey' => $apiKey));

    return $this->execute();
  }

  public function CampaignsListDraft()
  {
    $this->setMethodName('CampaignsListDraft');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function CampaignsListScheduled()
  {
    $this->setMethodName('CampaignsListScheduled');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function CampaignsListSent()
  {
    $this->setMethodName('CampaignsListSent');
    $this->setVerb('GET');

    return $this->execute();
  }

  public function CampaignsSummary($id)
  {
    $this->setMethodName('CampaignsSummary');
    $this->setVerb('POST');
    $this->setParams(array('id' => $id));

    return $this->execute();
  }

  public function AccountsShow()
  {
    $this->setMethodName('AccountsShow');
    $this->setVerb('GET');

    return $this->execute();
  }
}

?>