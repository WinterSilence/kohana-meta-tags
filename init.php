<?php defined('SYSPATH') OR die('No direct script access.');

if ( ! Route::cache())
{
	Route::set('meta', 'meta(/<tag>)', array('tag' => '[-\w]+'))
		->defaults(array(
			'directory'  => 'rest',
			'controller' => 'meta',
			'action'     => 'get',
			'tag'        => NULL,
		));
}
