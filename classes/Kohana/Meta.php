<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class for work with HTML meta tags. 
 * For gets more info about meta tags read article in (Wikipedia)[http://wikipedia.org/wiki/Meta_element].
 * 
 * @package    Meta
 * @category   Base
 * @version    1.6
 * @author     WinterSilence <info@handy-soft.ru>
 * @author     Samuel Demirdjian
 * @copyright  2013 © handy-soft.ru
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 * @link       http://github.com/WinterSilence/kohana-meta-tags
 */
abstract class Kohana_Meta implements ArrayAccess, Iterator {

	/**
	 * Uses in title method
	 */
	const TITLE_REPLACE = 0;
	const TITLE_UNSHIFT = 1;
	const TITLE_PREPEND = 1; // Same as unshift
	const TITLE_PUSH    = 2;
	const TITLE_APPEND  = 2; // Same as push

	/**
	 * @var  Meta  Class singleton
	 */
	protected static $_instance = NULL;

	/**
	 * @var  array  Meta tags attributes
	 */
	protected $_tags = array();

	/**
	 * @var  Config_Group  Configuration options
	 */
	protected $_cfg;

	/**
	 * Gets class instance (singleton) and sets config properties.
	 * 
	 * @param   array|object  $config  Configuration options [optional]
	 * @return  $this
	 */
	public static function instance($config = array())
	{
		// Creates class instance
		if (self::$_instance === NULL)
		{
			
			$class = get_called_class();
			self::$_instance = new $class;
		}
		// Sets new configuration options
		foreach ( (array) $config as $name => $value)
		{
			self::$_instance->_cfg[$name] = $value;
		}
		// Return instance
		return self::$_instance;
	}

	/**
	 * Loads configuration and default tags.
	 *
	 * @return  void
	 * @uses    Kohana::$config
	 * @uses    Config::load
	 */
	protected function __construct()
	{
		$this->_cfg = Kohana::$config->load('meta');
		$this->load_from_config($this->_cfg['tags_config_groups']);
	}

	/**
	 * Loads tags from config.
	 * 
	 *     Meta::instance()->load_from_config('cms.meta_tags');
	 *     Meta::instance()->load_from_config(['meta_tags', 'blog.meta']);
	 * 
	 * @param   string|array  $group  Configuration name or an array of them
	 * @return  $this
	 * @uses    Kohana::$config
	 * @uses    Config::load
	 * @uses    Config_Group::as_array
	 */
	public function load_from_config($group)
	{
		$tags = array();
		foreach ( (array) $group as $name)
		{
			// Loads config
			$config = Kohana::$config->load($name);
			if ($config instanceof Config_Group)
			{
				$config = $config->as_array();
			}
			// Merges configs data
			$tags = array_merge($tags, (array) $config);
		}
		// Sets loaded tags
		$this->set($tags);
		// Returns self
		return $this;
	}

	/**
	 * Sets tags.
	 * 
	 * @param  string|array  $name   Tag name or array of tags
	 * @param  string|array  $value  Content attribute value
	 * @return Meta
	 * @uses   Arr::is_array
	 * @uses   UTF8::strtolower
	 */
	public function set($name, $value = NULL)
	{
		if ( ! Arr::is_array($name))
		{
			$name = array($name => $value);
		}
		// Sets tags
		foreach ($name as $tag => $value)
		{
			$tag = UTF8::strtolower($tag);
			if ($tag === 'title')
			{
				// Sets title tag
				$this->_tags['title'] = $value;
			}
			else
			{
				if (isset($this->_tags[$tag]))
				{
					// Updates tag
					$this->_tags[$tag]['content'] = $value;
				}
				elseif (($this->_cfg['hide_empty'] === TRUE AND ! empty($value)) OR $this->_cfg['hide_empty'] !== TRUE)
				{
					// Adds tag
					$group = in_array($tag, $this->_cfg['http-equiv']) ? 'http-equiv' : 'name';
					$this->_tags[$tag] = array($group => $tag, 'content' => $value);
				}
			}
		}
		return $this;
	}

	/**
	 * Gets all tags or specified tag.
	 * 
	 * @param   string  $name
	 * @return  array|string
	 */
	public function get($name = NULL)
	{
		if ($name === NULL)
		{
			// Gets all tags
			return $this->_tags;
		}
		// Gets specified tag
		return isset($this->_tags[$name]) ? $this->_tags[$name] : NULL;
	}

	/**
	 * Deletes all tags or specified tag.
	 * 
	 * @param  string|array  $name
	 * @return $this
	 */
	public function delete($name = NULL)
	{
		if ($name === NULL)
		{
			// Deletes all tags
			$this->_tags = array();
		}
		else
		{
			// Deletes specified tags
			foreach ( (array) $name as $tag)
			{
				unset($this->_tags[$tag]);
			}
		}
		return $this;
	}

	/**
	 * Renders template [View] with metadata.
	 * 
	 * @param   string  $file  Template filename
	 * @return  string
	 * @uses    View::factory
	 */
	public function render($file = NULL)
	{
		return View::factory($this->_cfg['template'])
			->set('meta_tags', $this->get())
			->render($file);
	}

	/**
	 * Sets or gets title tag.
	 * 
	 * @param   mixed    $value   New title value
	 * @param   integer  $method  Action type for title array
	 * @return  mixed
	 */
	public function title($title = NULL, $method = self::TITLE_REPLACE)
	{
		// Acts as getter if $title is NULL
		if ($title === NULL)
		{
			return $this->get('title');
		}
		// Acts as setter
		$title = (array) $title;
		switch ($method)
		{
			case self::TITLE_UNSHIFT:
				// Merge, the new one will be prepended (like array_unshift)
				$this->set('title', array_merge($title, (array) $this->get('title')));
				break;
			case self::TITLE_PUSH:
				// Merge, the new one will be appended (like array_push)
				$this->set('title', array_merge( (array) $this->get('title'), $title));
				break;
			default:
				// Replace, case Meta::TITLE_REPLACE
				$this->set('title', $title);
		}
		return $this;
	}

	/**
	 * Implements [ArrayAccess::offsetGet], gets a given tag.
	 * 
	 *     $keywords = $meta['keywords'];
	 * 
	 * @param   string  $offset
	 * @return  mixed
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	/**
	 * Implements [ArrayAccess::offsetSet], sets a given tag.
	 * 
	 * @param   string  $offset
	 * @param   mixed   $value
	 * @return  void
	 */
	final public function offsetSet($offset, $value)
	{
		$this->set($offset, $value);
	}

	/**
	 * Implements [ArrayAccess::offsetExists], determines if tag exists.
	 * 
	 *     if (isset($meta['keywords']))
	 *     {
	 *         // Tag exists
	 *     }
	 * 
	 * @param   string   $offset
	 * @return  boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->_tags[$offset]);
	}

	/**
	 * Implements [ArrayAccess::offsetUnset], delete a given tag.
	 * 
	 * @param   string  $offset
	 * @return  void
	 */
	public function offsetUnset($offset)
	{
		$this->delete($offset);
	}

	/**
	 * Implements [Iterator::rewind], sets the current tag to first.
	 * 
	 * @return  $this
	 */
	public function rewind()
	{
		reset($this->_tags);
		return $this;
	}

	/**
	 * Implements [Iterator::current], returns the current tag value (attributes).
	 * 
	 * @return  mixed
	 */
	public function current()
	{
		return current($this->_tags);
	}

	/**
	 * Implements [Iterator::key], returns the current tag name.
	 * 
	 * @return  string
	 */
	public function key()
	{
		return key($this->_tags);
	}

	/**
	 * Implements [Iterator::next], moves to the next tag.
	 * 
	 * @return  $this
	 */
	public function next()
	{
		next($this->_tags);
		return $this;
	}

	/**
	 * Implements [Iterator::valid], checks if the current tag exists.
	 * 
	 * @return  boolean
	 */
	public function valid()
	{
		return $this->offsetExists($this->key());
	}

	/**
	 * Sets tags.
	 * 
	 * @param  string  $name
	 * @param  string  $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * Gets tags.
	 * 
	 *     Meta::instance()->title = array('Shop name', 'Category');
	 *     array_push(Meta::instance()->title, 'Product 123');
	 *     // result: array('Shop name', 'Category', 'Product 123');
	 * 
	 * @param   string  $name
	 * @return  mixed
	 */
	public function & __get($name)
	{
		return $this->_tags[$name];
	}

	/**
	 * Checks isset tag.
	 * 
	 * @param   string  $name
	 * @return  bool
	 */
	public function __isset($name)
	{
		return $this->offsetExists($name);
	}

	/**
	 * Deletes tags.
	 * 
	 * @param   string  $name
	 * @return  bool
	 */
	public function __unset($name)
	{
		$this->delete($name);
	}

	/**
	 * Allows a class to decide how it will react when it is treated like a string.
	 * 
	 * @return  string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Clone method protected from external call.
	 * 
	 * @return  void
	 * @throws  Kohana_Exception
	 */
	public function __clone()
	{
		throw new Kohana_Exception('Cloning of :name objects is forbidden', array(':name' => get_class($this)));
	}

	/**
	 * Wakeup method protected from external call.
	 * 
	 * @return  void
	 * @throws  Kohana_Exception
	 */
	public function __wakeup()
	{
		throw new Kohana_Exception('Wakeup of :name objects is forbidden', array(':name' => get_class($this)));
	}

} // End Meta