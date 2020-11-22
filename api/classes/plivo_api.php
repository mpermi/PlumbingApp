
<?php
class Plivo_API {
  private $auth_id;
  private $auth_token;
  private $api_url;

  public function __construct(){
    $this->auth_id = 'MAMMFKZTFJMZQ2OGFLM2';
    $this->auth_token = 'N2JkYmYzYWUxYzdhZDdhYjU0ODA5NTNlZDc5NTc1';
    $this->api_url = 'https://api.plivo.com/v1/Account/MAMMFKZTFJMZQ2OGFLM2/Message/';
  }

  //send an SMS message
	public function send_sms($params=array()) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->api_url);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $this->auth_id . ':' . $this->auth_token);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
	}
}
?>