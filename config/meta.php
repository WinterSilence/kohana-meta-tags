<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	// In HTML5 tags are not single use slash at the end.
	'html5' => FALSE,
	// Add indent before tags
	'indent' => PHP_EOL.'	',
	// Separator for parts of title tag, uses then tag content sets as array.
	'title_separator' => ' - ',
	// HTTP-EQUIV tags group 
	'http-equiv' => array(
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
	),
	// Tag values ​​by default
	'tags' => array(
		'title'				=> '',
		'description'		=> '',
		'keywords'			=> '',
		'author'			=> '',
		'copyright'			=> '',
		'generator'			=> '',
		'revisit'			=> '3 days',
		'revisit-after'		=> '3 days',
		'robots'			=> 'all',
		'viewport'			=> 'width=device-width, initial-scale=1.0',
		'content-type'		=> Kohana::$content_type.'; charset='.Kohana::$charset,
		'content-language'	=> I18n::$lang,
	),
	// Auto load tags from config groups. Example: array('meta.tags', 'site_meta', 'blog.meta_tags')
	'tags_config_groups' => array('meta.tags'),
);