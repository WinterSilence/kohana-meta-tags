<?php defined('SYSPATH') OR die('No direct script access.');

// Sets cache lifetime in seconds
$cache_life = Kohana::$caching ? 300 : 0;
// Checks & loads cache
if ($cache_life > 0 AND Fragment::load('meta:'.Request::initial()->uri(), $cache_life, TRUE))
{
	return TRUE;
}
// Gets tags, if they are not sended in template
if ( ! isset($meta_tags))
{
	$meta_tags = Meta::instance();
}
echo '		<!-- Meta meta_tags: begin -->'.PHP_EOL;
echo '		<base href="'.Kohana::$base_url.'"/>'.PHP_EOL;
// Displays title tag, ' - ' uses as separator for parts of title array
if (isset($meta_tags['title']))
{
	echo '		<title>'.HTML::chars(implode(' - ', (array) $meta_tags['title'])).'</title>'.PHP_EOL;
	unset($meta_tags['title']);
}
// Displays tags
foreach ($meta_tags as $attributes)
{
	echo '		<meta'.HTML::attributes($attributes).'/>'.PHP_EOL;
}
echo '		<!-- Meta meta_tags: end -->'.PHP_EOL;
// Caching displayed tags
if ($cache_life > 0)
{
	Fragment::save();
}