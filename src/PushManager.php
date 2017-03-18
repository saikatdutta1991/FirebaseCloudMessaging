<?php


namespace Saikat\FirebaseCloudMessaging;


/**
* PushNotification classs
* 
* This class is part of FirebaseCloudMessaging package. Can be used to send push notifications.
* This library needs php-curl extension
* If someone using laravel then can set config.firebase for server key and push url
*
* @package    FirebaseCloudMessaging
* @author     SAIKAT DUTTA <saikatdutta1991@gmail.com>
*/



class PushManager
{


	/**
	* firebase server to autenticate and to send 
	*/
	protected $serverKey = "";


	/**
	* firebase server url
	*/
	protected $fcmURL = "https://fcm.googleapis.com/fcm/send";


	/**
	* curl ipv4 address resolve
	*/
	protected $isIPv4Resolve = false;


	/**
	* last error code
	*/
	protected $lastErrorCode = 0;


	/**
	* last error message
	*/
	protected $lastErrorMessage = "";



	/**
	* firebase push notification title
	*/
	protected $notifTitle = "";


	/**
	* firebase push notification body
	*/
	protected $notifBody = "";


	/**
	* firebase push notification icon
	*/
	protected $notifIcon = "";


	/**
	* firebase push notification click action
	*/
	protected $notifClickAction = "";


	/**
	* firebase push notification click action
	*/
	protected $notifCustomPayload = [];


	/**
	* firebase push notification device tokens
	*/
	protected $deviceTokens = [];


	/**
	* firebase push notification respnose raw string
	*/
	const RAW = 1;


	/**
	* firebase push notification respnose php standard class object type
	*/
	const STDCLASS = 2;


	/**
	* firebase push notification respnose php array type
	*/
	const ARRY = 3;


	/**
	* firebase push notification priority constants
	*/
	const HIGH = 'high';
	const LOW = 'low';

	/**
	* firebase push notification priority
	*/
	protected $priority;

	

	/**
	* firebase push notification option content availabe
	*/
	protected $content_available = false;



	/**
   * initialize the sender id and server key
   *
   * @param string $senderID  fcm sender messaging id
   * @param string $serverKey  fcm server key to send authentication
   * @param string $fcmURL  fcm pushnotification url
   */
	public function __construct($serverKey = "", $fcmURL = "")
	{

		if($serverKey !== "") {
			$this->serverKey = $serverKey;
		} else if(function_exists('config')) {
			$this->serverKey = config('firebase_cloud_messaging.server_key');
		}

		
		if($fcmURL !== "") {
			$this->fcmURL = $fcmURL;
		} else if(function_exists('config') && !is_null($url = config('firebase_cloud_messaging.fcm_push_url'))) {
			$this->fcmURL = $url;
		} 

		$this->priority = self::LOW;

	}


	public function setPriority($priority)
	{
		$this->priority = $priority;
		return $this;
	}


	public function getPriority()
	{
		return $this->priority;
	}



	public function setContentAvailable($bool = false)
	{
		$this->content_available = $bool;
		return $this;
	}


	public function isContentAvailable()
	{
		return $this->content_available;
	}



	public function getServerKey()
	{
		return $this->serverKey;
	}


	public function getFcmURL()
	{
		return $this->fcmURL;
	}


	public function setFcmURL($url)
	{
		$this->fcmURL = !is_null($url) ? $url : "https://fcm.googleapis.com/fcm/send";
		return $this;
	}


	public function setDeviceTokens($tokens, $merge = true)
	{
		if(is_array($tokens)) {

			if($merge) {
				$this->deviceTokens = array_merge($this->deviceTokens, $tokens);
			} else {
				$this->deviceTokens = $tokens;
			}
			
		} else if(is_string($tokens)) {

			if($merge) {
				$this->deviceTokens[] = $tokens;
			} else {
				$this->deviceTokens = [$tokens];
			}
		}
		
		return $this;
	}


	public function getDeviceTokens()
	{
		return $this->deviceTokens;
	}


	public function setTitle($title = "")
	{
		$this->notifTitle = $title;
		return $this;
	}


	public function setBody($body = "")
	{
		$this->notifBody = $body;
		return $this;
	}


	public function setIcon($icon = "")
	{
		$this->notifIcon = $icon;
		return $this;
	}


	public function setClickAction($actionUrl = "")
	{
		$this->notifClickAction = $actionUrl;
		return $this;
	}


	public function setCustomPayload($payload = [])
	{
		$this->notifCustomPayload = is_array($payload) ? $payload : [];
		return $this;
	}


	public function setIPv4Resolve($bool)
	{
		$this->isIPv4Resolve = $bool;
		return $this;
	}


	protected function clearLastError()
	{
		$this->lastErrorCode = 0;
		$this->lastErrorMessage = "";
		
		return $this;
	}


	public function getLastErrorCode()
	{
		return $this->lastErrorCode;
	}


	public function getLastErrorMessage()
	{
		return $this->lastErrorMessage;
	}


	/**
   * 
   * Post data to a url and return response
   *
   */
	protected function postURL($url, $headers, $fields)
	{
		try {

			$ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		    if($this->isIPv4Resolve) {
		    	curl_setopt ($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		    }


		    $result = curl_exec($ch);
		    
		    if(curl_errno($ch)){
		    	throw New \Exception(curl_error($ch), curl_errno($ch));
			}

			curl_close($ch);


		    return $result;

		} catch(\Exception $e) {

			$this->clearLastError();

			$this->lastErrorCode = $e->getCode();
			$this->lastErrorMessage = $e->getMessage();

			return null;
		}

	}


	protected function buildNotification()
	{
		return [
			'title'        => $this->notifTitle,
			'body'         => $this->notifBody,
			'icon'         => $this->notifIcon,
			'click_action' => $this->notifClickAction
		];
	}



	public function push($resType = self::RAW)
	{
		$fields = [
			"registration_ids" => $this->deviceTokens,
			"notification" => $this->buildNotification(),
			"priority" => $this->priority,
			'content_available' => $this->content_available,
	        'data' => $this->notifCustomPayload
	    ];

	    $fields = json_encode($fields);

	    $headers = [
	        'Authorization: key=' . $this->serverKey,
	        'Content-Type: application/json'
	    ];

	    $response = $this->postURL($this->fcmURL, $headers, $fields);


	    if($resType == self::STDCLASS) {
	    	return json_decode($response);
	    } else if($resType == self::ARRY) {
	    	return json_decode($response, true);
	    }
	    
	    return $response;
	}

}