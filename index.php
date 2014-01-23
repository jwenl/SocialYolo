<?php
include('autoloader.php');
use Social\Feed;

$feed = new Feed;
$feed->registerFeed(new Social\Feeds\Twitter([]));
$feed->registerFeed(new Social\Facebook([]));
$feed->registerFeed(new Social\GooglePlus([]));
$feed->registerFeed(new Social\Youtube([]));