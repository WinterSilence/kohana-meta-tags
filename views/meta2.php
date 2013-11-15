<?php defined('SYSPATH') OR die('No direct script access.');

if ( ! isset($tags))
{
	$tags = Meta::instance()->get();
}
if ( ! Fragment::load('meta:'.var_export($tags, TRUE), Date::MINUTE * 5, TRUE))
{
	echo PHP_EOL . '<!-- meta tags begin -->' . PHP_EOL;
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
