<?php
include('autoloader.php');
use Social\Feed;

$feed = new Feed;
$feed->registerFeed(new Social\Providers\Twitter([]));
$feed->registerFeed(new Social\Providers\Facebook([]));
$feed->registerFeed(new Social\Providers\GooglePlus([]));
$feed->registerFeed(new Social\Providers\Youtube([]));