<?php

$page =1;
$hdTrue=0;
$hdFalse =0;
$answer;

callUrl();




function callUrl()
{
	global $page, $answer;
	//create cURL connection
	$curl_connection = curl_init();
	//Set the URL to work with
	$url= "http://api.viki.io/v4/videos.json?app=100250a&per_page=10&page=".$page;
	curl_setopt($curl_connection, CURLOPT_URL, $url);
	curl_setopt($curl_connection, CURLOPT_HEADER, 0);
	curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($curl_connection, CURLOPT_POST, 0);
	curl_setopt($curl_connection, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

	$answer = curl_exec($curl_connection);
	if (curl_error($curl_connection)) {
	    echo curl_error($curl_connection);
	}
	curl_close($curl_connection);

	processPage();

}

function processPage()
{
	global $answer,$hdTrue, $hdFalse, $page;

	$myStr= json_decode($answer);

	//var_dump($myStr);
	//print_r($myStr);

	$nextPage = $myStr->more;
	if($nextPage==1)
	{	
		$response = $myStr->response;
		$flags = $response[0]->flags;
		$hd = $flags->hd;

		if($hd== 1)
			$hdTrue +=1;
		else 
			$hdFalse+=1;

		$page+=1;
		
		callUrl();
	}
	else
	{		print_r("Number of response objects with hd flag as true: ".$hdTrue."<br>");
			echo "Number of response objects with hd flag as false: ".$hdFalse."<br>";
	}
}

?>


