<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class for work with HTML meta tags.
 * 
 * @package    Kohana/Meta
 * @category   Base
 * @author     WinterSilence <info@handy-soft.ru>
 * @copyright  2013 © handy-soft.ru
 * @license    MIT
 * @link       http://github.com/WinterSilence/kohana-meta-tags
 * @see        http://wikipedia.org/wiki/Meta_element
 */
abstract class Kohana_Meta {

	/**
	 * @var Meta Class instance
	 */
	protected static $_instance = NULL;

	/**
	 * @var array Configuration options
	 */
	protected $_cfg = array();

	/**
	 * @var array Meta tags
	 */
	protected $_tags = array();

	/**
	 * Get class instance and sets config properties
	 * 
	 * @param  array  $config
	 * @return Meta
	 */
	public static function instance(array $config = array())
	{
		// Create class instance
		if ( ! self::$_instance)
		{
			$class = get_called_class();
			self::$_instance = new $class;
		}
		// Sets new configuration option
		foreach ($config as $key => $value)
		{
			if (isset(self::$_instance->_cfg[$key]))
			{
				self::$_instance->_cfg[$key] = $value;
			}
		}
		// Return instance
		return self::$_instance;
	}

	/**
	 * Load configuration and default tags
	 *
	 * @return void
	 * @uses   Kohana
	 * @uses   Config
	 * @uses   Config_Group
	 */
	protected function __construct()
	{
		$this->_cfg = Kohana::$config->load('meta');
		$this->load_from_config($this->_cfg['tags_config_groups']);
	}

	/**
	 * Load tags from config group(s)
	 * 
	 * @param  string|array  $group
	 * @return Meta
	 * @uses   Kohana
	 * @uses   Config
	 * @uses   Config_Group
	 */
	public function load_from_config($group)
	{
		foreach ( (array) $group as $name)
		{
			$config = Kohana::$config->load($name);
			if ($config instanceof Config_Group)
			{
				$config = $config->as_array();
			}
			$this->_tags = array_merge($this->_tags, (array) $config);
		}
		return $this;
	}

	/**
	 * Sets tags
	 * 
	 * @param  string|array  $name   Name tag or array tags
	 * @param  string        $value  Content attribute
	 * @return Meta
	 */
	public function set($name, $value = NULL)
	{
		if (is_array($name))
		{
			foreach ($name as $tag => $value)
			{
				$this->set($tag, $value);
			}
		}
		else
		{
			$name = strtolower($name);
			$this->_tags[$name] = $value;
		}
		return $this;
	}

	/**
	 * Gets tags
	 * 
	 * @param  string  $name
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
	 * Delete tags
	 * 
	 * @param  string|array  $name
	 * @return Meta
	 */
	public function delete($name)
	{
		$name = (array) $name;
		foreach ($name as $tag)
		{
			unset($this->_tags[$tag]);
		}
		return $this;
	}

	/**
	 * Create meta tags HTML block.
	 * 
	 * @param   string  $file  Template(View) filename
	 * @return  string
	 * @uses    View
	 */
	public function render($file = NULL)
	{
		// Delete empty tags
		$tags = array_filter($this->_tags);
		// Sets tags attributes
		foreach ($tags as $name => $value)
		{
			if ($name != 'title')
			{
				$group = in_array($name, $this->_cfg['http-equiv']) ? 'http-equiv' : 'name';
				$tags[$name] = array($group => $name, 'content' => $value);
			}
		}
		// Render template
		return View::factory($this->_cfg['template'])
			->set(array('tags' => $tags, 'cfg' => $this->_cfg))
			->render($file);
	}

	/**
	 * Opens URL or file path and parses it line by line for <meta> tags. The parsing stops at </head>.
	 * 
	 * @param  string $url  URL or path to file
	 * @param  array  $tags Select current tags
	 * @return array
	 * @uses   Arr::extract
	 */
	public static function parce($url, array $tags = array())
	{
		$result = (array) get_meta_tags($url);
		return empty($tags) ? $result : Arr::extract($result, $tags);
	}

	/**
	 * Utilized for reading data from inaccessible properties. 
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @return voide
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * Get tags
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->get($name);;
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
	 * Delete tag
	 * 
	 * @param  string $name
	 * @return bool
	 */
	public function __unset($name)
	{
		$this->delete($name);
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

} // End Meta