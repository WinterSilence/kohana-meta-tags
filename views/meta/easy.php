<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Easy\light\alternative version.
 */
// Sets cache lifetime in seconds
$cache_lifetime = (Kohana::$enviropment == Kohana::PRODUCTION ? 300 : 0);
// Check\load cache (work only in production version)
if ( ! Fragment::load('meta:'.Request::initial()->url(), $cache_lifetime, TRUE))
{
	// Get tags, if they are not sended in View
	if ( ! isset($tags))
	{
		$tags = Meta::instance()->get();
	}
	echo '<!-- Meta tags: begin -->'.PHP_EOL;
	// Display title tag, ' - ' uses as separator for parts of title array
	if (isset($tags['title']))
	{
		echo '<title>'.HTML::chars(implode(' - ', (array) $tags['title'])).'</title>'.PHP_EOL;
		unset($tags['title']);
	}
	// Display meta tags
	foreach ($tags as $attributes)
	{
		echo '<meta'.HTML::attributes($attributes).'/>'.PHP_EOL;
	}
	echo '<!-- Meta tags: end -->'.PHP_EOL;
	//Caching displayed tags (work only in production version)
	if ($cache_lifetime)
	{
		Fragment::save();
	}
}
