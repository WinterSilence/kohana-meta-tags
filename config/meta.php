<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	// Default template(View) for render tags
	'template' => 'meta'.DIRECTORY_SEPARATOR.'default',
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
	// Default tag values ​. See http://en.wikipedia.org/wiki/Meta_element
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
		'document-state'	=> 'Dynamic',
		'revisit'			=> '3 days',
		'revisit-after'		=> '3 days',
		'robots'			=> 'all',
		'content-type'		=> Kohana::$content_type.'; charset='.Kohana::$charset,
		'content-language'	=> I18n::$lang,
		'x-ua-compatible'	=> 'IE=edge',
		'viewport'			=> 'width=device-width, initial-scale=1.0',
	),
);