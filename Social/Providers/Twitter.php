<?php
namespace Social\Providers;

use Social\AbstractProvider as AbstractProvider;

class Twitter extends AbstractProvider
{
	protected $required = ['consumerKey', 'consumerSecret', 'accessToken', 'accessTokenSecret'];

	protected $settings;

	/**
	 * {@inheritDocs}
	 */
	public function getFeed($limit = null)
	{
		$consumerKey = $this->settings['consumerKey'];
		$consumerSecret = $this->settings['consumerSecret'];
		$accessToken = $this->settings['accessToken'];
		$accessTokenSecret = $this->settings['accessTokenSecret'];
		$username = $this->settings['username'];

		$maximum = 10;
		$filetime = time();

		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$base = 'GET&'.rawurlencode($url).'&'.rawurlencode("count={$maximum}&oauth_consumer_key={$consumerKey}&oauth_nonce={$filetime}&oauth_signature_method=HMAC-SHA1&oauth_timestamp={$filetime}&oauth_token={$accessToken}&oauth_version=1.0&screen_name={$username}");
		$key = rawurlencode($consumerSecret).'&'.rawurlencode($accessTokenSecret);
		$signature = rawurlencode(base64_encode(hash_hmac('sha1', $base, $key, true)));
		$oauth_header = "oauth_consumer_key=\"{$consumerKey}\", oauth_nonce=\"{$filetime}\", oauth_signature=\"{$signature}\", oauth_signature_method=\"HMAC-SHA1\", oauth_timestamp=\"{$filetime}\", oauth_token=\"{$accessToken}\", oauth_version=\"1.0\", ";

		$curl_request = curl_init();
		curl_setopt($curl_request, CURLOPT_HTTPHEADER, array("Authorization: Oauth {$oauth_header}", 'Expect:'));
		curl_setopt($curl_request, CURLOPT_HEADER, false);
		curl_setopt($curl_request, CURLOPT_URL, $url."?screen_name={$username}&count={$maximum}");
		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl_request);
		curl_close($curl_request);

		return $this->filterInput($response);
	}

	protected function normalizeItem(array $response)
	{
		$date = new DateTime($item['created_at']);

		return [
			'date' => $date,
			'content' => $item['text'],
			'url' => 'http://twitter.com/' . $this->settings['username']. '/status/' . $item['id'],
		];
	}
}
