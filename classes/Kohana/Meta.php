<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Helper for create HTML meta tags
 *
 * @package    Meta
 * @category   Base
 * @author     WinterSilence <info@handy-soft.ru>
 * @copyright  2013 © handy-soft.ru
 * @license    http://kohanaframework.org/license
 * @link       http://github.com/WinterSilence/kohana-meta-tags
 */
abstract class Kohana_Meta
{
	/**
	 * @var Meta Class instance
	 */
	protected static $_instance = NULL;

	/**
	 * @var array Meta tags
	 */
	protected $_tags = array();

	/**
	 * @var bool Is XHTML?
	 */
	protected $_xhtml = TRUE;

	/**
	 * @var string Separator for parts of title tag 
	 */
	protected $_separator = ' - ';
	

	/**
	 * Get class instance and sets config properties
	 * 
	 * @param  array $config
	 * @return Meta
	 */
	public static function instance(array $config = array())
	{
		if ( ! self::$_instance)
		{
			self::$_instance = new Meta;
		}
		
		if (isset($config['xhtml']))
		{
			self::$_instance->_xhtml = (bool) $config['xhtml'];
		}
		if (isset($config['separator']))
		{
			self::$_instance->_separator = (string) $config['separator'];
		}
		
		return self::$_instance;
	}

	/**
	 * Class constructor protected from external call
	 *
	 * @return void
	 */
	protected function __construct() {}
	
	/**
	 * Clone method protected from external call
	 * 
	 * @return void
	 */
	protected function __clone() {}

	/**
	 * Wakeup method protected from external call
	 * 
	 * @return void
	 */
	protected function __wakeup() {}

	/**
	 * Sets tags
	 * 
	 * @param  string|array $name       Name or array of tags
	 * @param  string|array $attributes Content attribute or array of attributes
	 * @return Meta
	 */
	public function set($name, $attributes = NULL)
	{
		if (is_array($name))
		{
			foreach ($name as $tag_name => $tag_attributes)
			{
				$this->set($tag_name, $tag_attributes);
			}
		}
		else
		{
			if ( ! is_array($attributes))
			{
				$attributes = array('name' => $name, 'content' => $attributes);
			}
			$this->_tags[$name] = (array) $attributes;
		}
		return $this;
	}

	/**
	 * Utilized for reading data from inaccessible properties. 
	 *
	 * @param  string       $name
	 * @param  string|array $attributes
	 * @return voide
	 */
	public function __set($name, $attributes)
	{
		$this->set($name, $attributes);
	}

	/**
	 * Get current tag or all tags
	 * 
	 * @param  mixed $name
	 * @return mixed
	 */
	public function get($name = NULL)
	{
		if (is_null($name))
		{
			return $this->_tags;
		}
		elseif (isset($this->_tags[$name]))
		{
			return $this->_tags[$name];
		}
	}

	/**
	 * Get tag
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function & __get($name)
	{
		return isset($this->_tags[$name]) ? $this->_tags[$name] : NULL;
	}

	/**
	 * Check isset tag
	 * 
	 * @param  string $name
	 * @return bool
	 */
	public function __isset($name)
	{
		return isset($this->_tags[$name]);
	}

	/**
	 * Create meta tags
	 * 
	 * @return  string
	 * @uses    HTML::attributes
	 */
	public function render()
	{
		$tags = '';
		foreach ($this->_tags as $name => $attributes)
		{
			if ($name == 'title')
			{
				$tags .= '<title>'.implode($this->_separator, $attributes).'</title>';
			}
			else
			{
				$tags .= '<meta'.HTML::attributes($attributes).($this->_xhtml ? '/' : '').'>';
			}
			$tags .= PHP_EOL;
		}
		return $tags;
	}

	/**
	 * Opens filename and parses it line by line for <meta> tags in the file. The parsing stops at </head>. 
	 * 
	 * @param  string $filename Path to file or URL
	 * @return array
	 */
	public static function parce_file($filename)
	{
		return get_meta_tags($filename);
	}

	/**
	 * Allows a class to decide how it will react when it is treated like a string.
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

} // End Meta