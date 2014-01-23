<?php

namespace Social;

interface FeedInterface {

	/**
	 * Get a social feed
	 * 
	 * @return array
	 */
	public function getFeed($limit = null);

	/**
	 * Get the feed name
	 * @return string feed name
	 */
	public function getName();
}