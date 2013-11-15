<?php defined('SYSPATH') OR die('No direct script access.');

$cache_lifetime =
  Kohana::$environment === Kohana::PRODUCTION ? // test the environment
  $cache_lifetime : // use the cache lifetime value from config
  0; // no caching for DEVELOPMENT environment


if ( ! Fragment::load('meta:'.Request::initial()->url(), $cache_lifetime, TRUE))
{
	if ( ! isset($tags))
	{
		$tags = Meta::instance()->get();
	}
	echo PHP_EOL. '<!-- meta tags begin -->' . PHP_EOL;
	if (isset($tags['title']))
	{
		echo '<title>'.HTML::chars(implode(' - ', (array) $tags['title'])).'</title>' . PHP_EOL;
		unset($tags['title']);
	}
	foreach ($tags as $attributes)
	{
		echo '<meta'.HTML::attributes($attributes).'/>' . PHP_EOL;
	}
	echo '<!-- meta tags end -->' . PHP_EOL;
	Fragment::save();
}
