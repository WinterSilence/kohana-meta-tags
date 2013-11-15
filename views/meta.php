<?php defined('SYSPATH') OR die('No direct script access.');

if ( ! Fragment::load('meta:'.Request::initial()->url(), Date::MINUTE * 5, TRUE))
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
