<?php

/* 
 * Copyright (C) 2016 Wolfgang Lorenz <wolfgang.a.lorenz@arcor.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class Wolf_Twitter_Model_TweetList extends Mage_Core_Block_Template {

  // Abfrage-Parameter für die Twitter-Anfrage
  public $hashTag = '#magento';
  public $maxTweets = 1;

  // Objekt mit den Abgefragten Tweets
  public $tweets;

  // Die beiden Schlüssel gehören zu der Twitter-App "GetTweetsOnMagento"
  protected $key = 'g5GHWa8N4lhkEgZUYvGXQtdJG';
  protected $secret = 'KfVlMg5dtGfImXTViIkAPkVKLDjIqHE6nrrAbEv0e23REonqDq';

  // URLs für die Anfragen bei Twitter
  protected $requestUrl = 'https://api.twitter.com/1.1/search/tweets.json?q=';  // Anfrage-URL
  protected $tokenUrl = 'https://api.twitter.com/oauth2/token';                 // Abfragen Autorisierungs-Codes

  // Reaktivierung des Autorisierungs-Codes,
  // falls er im Verlauf von Tests von Twitter gesperrt wurde
  protected $reactivationUrl = 'https://api.twitter.com/oauth2/invalidate_token';  

  // zeitlich begrenzt ("einige Minuten") gültiger Autorisierungs-Code für die Twitter-Anfrage
  protected $bearerToken;

  
  public function Wolf_Twitter_Model_TweetList() {

    // Maximum der anzuzeigenden Tweests einlesen
    $this->maxTweets = Mage::helper('wolf_twitter')->getMaxTweets();
    // HashTag der abgefragten Tweets
    $this->hashTag = Mage::helper('wolf_twitter')->getHashTag();

    // BearerToken anforden, der für die Anfrage bei Twitter autorisiert
    $authCode = base64_encode(urlencode($this->key).':'.urlencode($this->secret));
    $httpHeader = ['Authorization: Basic '.$authCode];
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_URL, $this->tokenUrl);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    $token = json_decode(curl_exec($curl));
    curl_close($curl);

    $this->bearerToken = $token->access_token;

  }

  public function getTweetList($decodeJsonString=FALSE) {

    $request = $this->requestUrl.urlencode($this->hashTag);
    $request .= '&count='.$this->maxTweets;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_URL, $request);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->bearerToken));
    $tweetsAsJsonString = curl_exec($curl);
    curl_close($curl);

    $returnValue = $tweetsAsJsonString;
    if ( $decodeJsonString ) {
      $tmp = json_decode($tweetsAsJsonString);
      $returnValue = $tmp->statuses;
    }
    $this->tweets = $returnValue;

    return $returnValue;

  }

  public function reactivateAuthCode() {

    // BearerToken anforden, der für die Anfrage bei Twitter autorisiert
    $authCode = base64_encode(urlencode($this->key).':'.urlencode($this->secret));
    $httpHeader = array();
    $httpHeader[] = 'Authorization: Basic '.$authCode;
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_URL, $this->reactivationUrl);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'access_token='.$this->bearerToken);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    $result = json_decode(curl_exec($curl));
    curl_close($curl);
    
    return json_decode($result);

    
  }

}


