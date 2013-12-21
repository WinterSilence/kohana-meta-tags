<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	// Default template(View) for render tags
	'template' => 'meta/full',
	// HTTP-EQUIV group tags 
	'http-equiv' => array(
		'resource-type',
		'content-language', 
		'content-script-type', 
		'content-style-type', 
		'content-type', 
		'expires', 
		'pics-label', 
		'pragma', 
		'refresh', 
		'set-cookie', 
		'window-target',
		'x-ua-compatible',
		'window-target',
	),
	// Auto load tags from config groups. Ex: array('meta.tags', 'site_meta', 'blog.meta_tags')
	'tags_config_groups' => array('meta.tags'),
	// Default tag values. See http://en.wikipedia.org/wiki/Meta_element
	'tags' => array(
		'title'				=> '',
		'description'		=> '',
		'abstract'			=> '',
		'keywords'			=> '',
		'author'			=> '',
		'copyright'			=> '',
		'generator'			=> '',
		'subject'			=> '',
		'url'				=> '',
		'rating'			=> 'general',
		'audience'			=> 'all',
		'document-state'	=> 'dynamic',
		'revisit'			=> '3 days',
		'revisit-after'		=> '3 days',
		'robots'			=> 'all',
		'content-type'		=> Kohana::$content_type.'; charset='.Kohana::$charset,
		'content-language'	=> substr(I18n::$lang, -2),
		'x-ua-compatible'	=> 'IE=edge,chrome=1',
		'viewport'			=> 'width=device-width,initial-scale=1.0',
	),
	// Dont outputs tags with empty content attribute? 
	'hide_empty' => TRUE,
	// Cache lifetime in seconds. Default value = 300(5 minutes)
	'cache_life' => (Kohana::$caching ? 300 : 0), 
	// Uses HTML5? Then not added slash at end of tag
	'html5' => TRUE, 
	// A separator is added between the parts of the title array
	'title_separator' => ' - ',
);