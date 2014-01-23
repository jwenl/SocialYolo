<?php

require_once( dirname(__FILE__) . '/../aws/s3.php' );

class Socialwidget
{
	protected $settings;

	protected $channels = ['twitter','facebook','googleplus','youtube'];

	public function __construct ($settings)
	{
		$this->settings = $settings;
	}



	public function combine_streams($max = false) 
	{
		$feeds = [];
		foreach($this->channels as $channel) {
			$feeds = array_merge($feeds, $this->{'get_' . $channel}());
		}

		usort($feeds, function($date1, $date2) 
		{
			$time1 = strtotime($date1['date']);
			$time2 = strtotime($date2['date']);
			return ($time2 - $time1); 
		});

		if($max !== false) 
			return array_slice($feeds, 0, $max);

		return $feeds;
	}

}
