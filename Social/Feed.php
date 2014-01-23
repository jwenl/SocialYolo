<?php

namespace Social;

class Feed
{
	protected $cache;
	protected $cachekey;
	protected $feeds = [];

	public function __construct(array $feeds = [], CacheInterface $cache = null, $cachekey = 'social-feeds')
	{
		array_map([$this, 'registerFeed'], $feeds);
		$this->setCache($cache ?: new Cache\Noop($cachekey, null));
		$this->setCacheKey($cachekey);
	}

	public function setCache(CacheInterface $cache)
	{
		$this->cache = $cache;

		return $this;
	}

	public function setCacheKey($key)
	{
		$this->cachekey = $key;

		return $this;
	}

	protected function getCachedFeeds($limit)
	{
		return $this->cache->get($this->cachekey . $limit);
	}

	public function registerFeed(FeedInterface $feed)
	{
		$this->feeds[] = $feed;
	}

	public function getFeeds($limit = null)
	{
		if ($cached = $this->getCachedFeeds($limit)) {
			return $cached;
		}

		$response = [];

		foreach ($this->feeds as $feed) {
			$response = array_merge($response, $feed->getFeed());
		}

		usort($response, [$this, 'compareTimestamp']);

		$this->cacheFeeds($response, $limit);

		return $response;
	}

	protected function compareTimestamp(array $a, array $b)
	{
		$atime = $a['date']->getTimestamp();
		$btime = $b['date']->getTimestamp();

		return $atime - $btime;
	}
}