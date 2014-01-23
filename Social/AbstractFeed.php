<?php

namespace Social;

use Guzzle\Client as GuzzleClient;

abstract class AbstractFeed implements FeedInterface
{
	protected $required = [];
	protected $name;

	public function __construct(array $settings, GuzzleClient $client) 
	{
		$this->setCredentials($settings);
		$this->setGuzzleClient($client);
	}

	public function setGuzzleClient(GuzzleClient $client)
	{
		$this->guzzle = $client;

		return $this;
	}

	public function getName()
	{
		if ( ! $this->name) {
			throw new \LogicException('A feed provider should have a name');
		}

		return $this->name;
	}

	public function setCredentials(array $settings)
	{
		$this->validateCredentials($settings);
		$this->settings = $settings;
	}

	public function validateCredentials(array $settings)
	{
		foreach ($this->required as $field) {
			if ( ! isset($settings[$field])) {
				throw new MissingConfigException(get_class($this) . ' needs a ' . $field);
			}
		}
	}

	protected function filterInput($json_response)
	{
		$response = json_decode($json_response, true);
		$responses = array_map([$this, 'normalizeItem'], $response);

		return array_map([$this, 'setFeedType'], $responses);
	}

	protected function setFeedType(array $response)
	{
		$response['provider'] = $this->getName();

		return $response;
	}

	abstract protected function normalizeItem(array $item);

}