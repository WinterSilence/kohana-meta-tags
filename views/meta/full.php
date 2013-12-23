<?php defined('SYSPATH') OR die('No direct script access.');

// Gets tags, if they are not sended in template
if ( ! isset($meta_tags))
{
	$meta_tags = Meta::instance();
}
// Load configuration options
$config = Kohana::$config->load('meta');
// Checks & loads cache
if ($config['cache_life'] > 0 AND Fragment::load('meta:'.serialize($meta_tags), $config['cache_life'], TRUE))
{
	return TRUE;
}
// Adds slash at end of tag?
$config['html5'] = ($config['html5'] === TRUE ? '/' : '');
echo '		<!-- Meta tags: begin -->'.PHP_EOL;
echo '		<base href="'.Kohana::$base_url.'"'.$config['html5'].'>'.PHP_EOL;
// Displays title tag, $config['title_separator'] uses as separator for parts of title array
if (isset($meta_tags['title']))
{
	$meta_tags['title'] = implode($config['title_separator'], (array) $meta_tags['title']);
	echo '		<title>'.HTML::chars($meta_tags['title']).'</title>'.PHP_EOL;
	unset($meta_tags['title']);
}
// Displays meta tags
foreach ($meta_tags as $attributes)
{
	echo '		<meta'.HTML::attributes($attributes).$config['html5'].'>'.PHP_EOL;
}
echo '		<!-- Meta tags: end -->'.PHP_EOL;
// Caching displayed tags
if ($config['cache_life'] > 0)
{
	Fragment::save();
}