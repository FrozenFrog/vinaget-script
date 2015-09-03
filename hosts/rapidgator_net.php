<?php

class dl_rapidgator_net extends Download {

	public function CheckAcc($cookie){
		$data = curl("http://rapidgator.net/profile/index", "lang=en;{$cookie}", "");
		$account_type = cut_str(cut_str($data, "Account type</td>", '<tr>'), '<td>', '</td>');
		if(stristr(trim($account_type), '<a class="orange" href="/article/premium">Upgrade to premium</a>')) return array(false, "accfree");
		elseif(stristr(trim($account_type), "Premium")) {
			$oob = curl("http://rapidgator.net/file/30703c88109d2a7b61a301eb6b324a95", "lang=en;{$cookie}", "");
			if(stristr($oob, 'You have reached quota of downloaded information')) return array(true, "Until ".cut_str($data, 'Premium till','<span'). "<br> Account out of BW");
			else return array(true, "Until ".cut_str($data, 'Premium till','<span')." <br/>Bandwith available:" .cut_str(cut_str($data, 'Bandwith available</td>','<div style='), '<td>','</br>'));
		}
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("https://rapidgator.net/auth/login", "lang=en", "LoginForm[email]={$user}&LoginForm[password]={$pass}&LoginForm[rememberMe]=1");
		$cookie = "lang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,"lang=en;".$this->lib->cookie,"");
		if(stristr($data, "You have reached quota of downloaded information") || stristr($data, "You have reached daily quota")) $this->error("LimitAcc");
		elseif(stristr($data,'File not found</div>'))  $this->error("dead", true, false, 2);
		elseif(preg_match('@https?:\/\/\w+\.rapidgator\.net\/\/\?r=download\/index[^"\'><\r\n\t]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Rapidgator Download Plugin 
* Downloader Class By [FZ]
* Add check account by giaythuytinh176 19.7.2013
*/
?>