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
$cfg = Arr::extract(Kohana::$config->load('meta'), array('cache_lifetime', 'slash_at_end', 'title_separator'));
// Check\load cache
if ( ! Fragment::load('meta:'.var_export($tags, TRUE), (int) $cfg['cache_lifetime'], TRUE))
{
	echo '<!-- Meta tags: begin -->'.PHP_EOL;
	// Display title tag, $cfg['title_separator'] uses as separator for parts of title array
	if (isset($tags['title']))
	{
		echo '<title>'.HTML::chars(implode($cfg['title_separator'], (array) $tags['title'])).'</title>'.PHP_EOL;
		unset($tags['title']);
	}
	// Add slash at end of tag?
	$cfg['slash_at_end'] = ($cfg['slash_at_end'] ? '/' : '');
	// Display meta tags
	foreach ($tags as $attributes)
	{
		echo '<meta'.HTML::attributes($attributes).$cfg['slash_at_end'].'>'.PHP_EOL;
	}
	echo '<!-- Meta tags: end -->'.PHP_EOL;
	//Caching displayed tags
	if (Kohana::$caching)
	{
		Fragment::save();
	}
}