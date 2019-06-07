<?php
	function get_content($url){
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', $url, ['verify' => false]);
		$response = $response->getBody()->getContents();
			
		return $response;
	}
	function teezily_scan($url)
	{
		$response = get_content($url);
		// echo $response;

		$is_customized = teezily_detect_customized($response);
		$is_showoff = teezily_detect_showoff($response);
		$photo = teezily_get_photo($response);
		$price = teezily_get_price($response);
		return array(
			'is_customized' => $is_customized, 
			'is_showoff' => $is_showoff, 
			'photo' => $photo,
			'price' => $price
		);
	}

	function teezily_detect_customized($content)
	{
		if(preg_match("/<script id='templates\/tee-customized-text' type='text\/ng-template'>/is", $content, $matches))
		{
			return true;
		}
		return false;
	}

	function teezily_detect_showoff($content)
	{
		if(preg_match("/<div class='smallInfoCell'>(.*?)<strong>(.*?)<\/strong>(.*?)<\/div>/is", $content, $matches))
		{
			return $matches[2];
		}
		return false;
	}

	function teezily_get_photo($content)
	{
		if(preg_match('/<meta content="https:\/\/rsz\.tzy\.li(.*?)" property="og:image" \/>/is', $content, $matches))
		{
			// print_r($matches);
			$photo = "https://rsz.tzy.li/".str_replace("600/600","816/918",$matches[1]);
			return $photo;
		}
		return false;
	}

	function teezily_get_price($content)
	{
		if(preg_match("/<meta content='eur' itemprop='priceCurrency'>(\r?\n)<meta content='(.*?)' itemprop='price'>/is", $content, $matches))
		{
			$price = $matches[2];
			return $price;
		}
		return false;
	}