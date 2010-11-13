<?php
$plugin 	= array("name" => "Twitter", "fun_name" => "Twitter", "version" => "1.0", "type" => "sidebar", "author" => "Thomas Chatting", "url" => "http://spoolio.co.cc/", "description" => "Adds your Twitter feed to your sidebar");

function Twitter() {
	$settings	= array("username" => "phenomenontom", "limit" => 3);
	
	class Twitter {
		public $tweets = array();
		public function __construct($user, $limit = 5) {
			$user = str_replace(' OR ', '%20OR%20', $user);
			$feed = curl_init('http://search.twitter.com/search.atom?q=from:'. $user .'&rpp='. $limit);
			curl_setopt($feed, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($feed, CURLOPT_HEADER, 0);
			$xml = curl_exec($feed);
			curl_close($feed);
			$result = new SimpleXMLElement($xml);
			foreach($result->entry as $entry) {
				$tweet = new stdClass();
				$tweet->id = (string) $entry->id;
				$user = explode(' ', $entry->author->name);
				$tweet->user = (string) $user[0];
				$tweet->author = (string) substr($entry->author->name, strlen($user[0])+2, -1);
				$tweet->title = (string) $entry->title;
				$tweet->content = (string) $entry->content;
				$tweet->updated = (int) strtotime($entry->updated);
				$tweet->permalink = (string) $entry->link[0]->attributes()->href;
				$tweet->avatar = (string) $entry->link[1]->attributes()->href;
				array_push($this->tweets, $tweet);
			}
			unset($feed, $xml, $result, $tweet);
		}
		public function getTweets() { return $this->tweets; }
	}
	
	function time_since($original) {
		$chunks = array(
			array(60 * 60 * 24 * 365 , 'year'),
			array(60 * 60 * 24 * 30 , 'month'),
			array(60 * 60 * 24 * 7, 'week'),
			array(60 * 60 * 24 , 'day'),
			array(60 * 60 , 'hour'),
			array(60 , 'minute'),
		);
	
		$today = time(); /* Current unix time  */
		$since = $today - $original;
	
		if($since > 604800) {
			$print = date("M jS", $original);
			if($since > 31536000) {
					$print .= ", " . date("Y", $original);
				}
			return $print;
		}
	
		for ($i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			$name = $chunks[$i][1];
			if (($count = floor($since / $seconds)) != 0) {
				break;
			}
		}
		$print = ($count == 1) ? '1 '.$name : "$count {$name}s";
		return $print;
	
	}
	
	if (!defined('PHP_VERSION_ID')) {
		$version = explode('.', PHP_VERSION);
	
		define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
	}
	if (PHP_VERSION_ID < 50000) {
		die("The plugin '".$plugin["name"]."' requires PHP version 5.0.0 or greater");
	}
	
	$feed = new Twitter($settings["username"], $settings["limit"]);
	$tweets = $feed->getTweets();
	
	$li = "";

	foreach ($tweets as $tweet) {
		$li .= '<li style="border-bottom: 1px solid #ccc; padding-bottom: 10px; padding-top: 10px;">'. $tweet->content .' <a href="'. $tweet->permalink .'" style="font-size: 10px; color: rgb(194,40,40);">'. time_since($tweet->updated) .' ago</a></li>';
	}
	
	return '<div class="tweet_box"><h1 style="background: url(\'http://twitter-badges.s3.amazonaws.com/t_mini-a.png\') no-repeat; height: 16px; padding-left: 18px; font-size: 15px; font-weight: normal; text-transform: uppercase;">Latest Tweets</h1><ul style="border: 1px solid #ccc; background: #fff; list-style: none; padding-left: 10px; padding-right: 10px; margin-left: 0;">'.$li.'<li style="border: 0;">Follow me on Twitter <a href="http://twitter.com/'.$tweets[0]->user.'">here</a>.</li></ul></div>';
}