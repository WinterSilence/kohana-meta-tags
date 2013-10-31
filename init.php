<?php defined('SYSPATH') OR die('No direct script access.');

if ( ! Route::cache())
{
	Route::set('meta-tags', 'meta-tags(/tag)', array(
			'tag' => '[-\w]+',
		))
		->defaults(array(
			'directory'  => 'REST',
			'controller' => 'Meta',
			'action'     => 'get',
			'tag'         => NULL,
		));
}