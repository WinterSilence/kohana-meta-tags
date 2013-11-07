<?php defined('SYSPATH') OR die('No direct script access.');

if ( ! isset($tags))
{
	$tags = Meta::instance()->get();
}
if ( ! Fragment::load('meta:'.var_export($tags, TRUE), Date::MINUTE * 5, TRUE))
{
	echo '<!-- meta tags begin -->';
	if (isset($tags['title']))
	{
		echo '<title>'.HTML::chars(implode(' - ', (array) $tags['title'])).'</title>';
		unset($tags['title']);
	}
	foreach ($tags as $attributes)
	{
		echo '<meta'.HTML::attributes($attributes).'/>';
	}
	echo '<!-- meta tags end -->';
	Fragment::save();
}