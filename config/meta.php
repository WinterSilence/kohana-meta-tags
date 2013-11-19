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
	// Load tags from config groups. Ex: array('meta.tags', 'site_meta', 'blog.meta_tags')
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
		'content-language'	=> I18n::$lang,
		'x-ua-compatible'	=> 'IE=edge,chrome=1',
		'viewport'			=> 'width=device-width, initial-scale=1.0',
	),
	// Cache lifetime in seconds. Used in production version. Default value = 300(5 minutes)
	'cache_lifetime' => (Kohana::$enviropment == Kohana::PRODUCTION ? 300 : 0), 
	// Add slash at end of tag? Set as FALSE for HTML5
	'slash_at_end' => TRUE, 
	// A separator is added between the parts of the title array
	'title_separator' => ' - ',
);