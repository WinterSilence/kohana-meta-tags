<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Full\hard\default version
 */
// Get tags, if they are not sended in View
if ( ! isset($tags))
{
	$tags = Meta::instance()->get();
}

// Load configuration options
$cfg = Arr::extract(Kohana::$config->load('meta'), array('cache_life', 'html5', 'title_separator'));

// Check & load cache
if ($cfg['cache_life'] > 0 AND Fragment::load('meta:'.serialize($tags), $cfg['cache_life'], TRUE))
{
	return TRUE;
}

// Add slash at end of tag?
$cfg['html5'] = ( (bool) $cfg['html5'] ? '/' : '');

echo '<!-- Meta tags: begin -->'.PHP_EOL;
echo '		<base href="'.Kohana::$base_url.'"'.$cfg['html5'].'>'.PHP_EOL;
// Display title tag, $cfg['title_separator'] uses as separator for parts of title array
if (isset($tags['title']))
{
	$tags['title'] = implode($cfg['title_separator'], (array) $tags['title']);
	echo '		<title>'.HTML::chars($tags['title']).'</title>'.PHP_EOL;
	unset($tags['title']);
}
// Display meta tags
foreach ($tags as $attributes)
{
	echo '		<meta'.HTML::attributes($attributes).$cfg['html5'].'>'.PHP_EOL;
}
echo '<!-- Meta tags: end -->'.PHP_EOL;

//Caching displayed tags
if ($cfg['cache_life'] > 0)
{
	Fragment::save();
}