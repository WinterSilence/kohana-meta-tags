#Usage

##Instance
~~~
$meta = Meta::instance();
~~~
Change instance configuration [Optional]:
~~~
$meta = Meta::instance($new_config_array);
~~~

##Work with tags
Meta object supports several ways for manipulations with contents:
- Special methods: [Meta::set], [Meta::get], [Meta::delete].
- As object properties via [magical methods](http://php.net/manual/language.oop5.overloading.php).
- As array items via interface [ArrayAccess](http://php.net/manual/class.arrayaccess.php).

###Set

~~~
$meta->set('content-language', substr(I18n::lang(), 0, 2));
// Sets a few:
$meta->set(array('author' => 'WinterSilence', 'description' => '...'));
$meta->description = 'description content';
$meta['description'] = 'description content';
~~~

###Get
~~~
$description = $meta->get('description');
// Gets all:
$all_tags = $meta->get();
$description = $meta->description;
$description = $meta['description'];
~~~

###Delete
~~~
$meta->delete('description');
// Delete a few:
$meta->delete(array('author', 'description'));
// Delete all:
$meta->delete();
unset($meta->description);
unset($meta['description']);
~~~

###Check exist
~~~
if ($meta->get('description') !== NULL)
   echo $meta->get('description');
if ($meta->offsetExists('description'))
   echo $meta->offsetGet('description');
if (isset($meta['description'])) 
   echo $meta['description'];
if (isset($meta->description)) 
   echo $meta->description;
~~~

###Title as array
~~~
$meta->title = array('Shop name', 'Category #3');
array_push($meta->title, 'Product #12');
// result: array('Shop name', 'Category #3', 'Product #12');
~~~

##Display tags
Include meta view in your template [View](../kohana/mvc/views) or use method [Meta::render].
~~~
<html>
	<head>
		<?=View::factory('meta/full')?>
		...
	</head>
...
~~~
Module includes 2 predefined templates: `full.php` and `easy.php` in `MODPATH/meta/views/meta/`.
You can create custom versions based on standard views (they contains detailed comments).

##Helpers

###Method [Meta::render]
Renders the [View] to string with HTML code of tags;
Default view stored in config option `template`.
~~~
// Render default view:
echo $meta->render();
// Render custom view:
echo $meta->render('path/to/custom');
~~~
Supported 'magical' method [Meta::__toString], uses render method for convert to string.
~~~
//  Render default view:
echo Meta::instance();
~~~

###Method [Meta::load_from_config]
~~~
$meta->load_from_config('cms.meta_tags');
$meta->load_from_config(array('theme.meta_tags', 'blog.meta'));
~~~
To automatically loading tag info from multiple configurations or groups, 
change `tags_config_groups` in module [configuration](config). 
By default, loads tags from congif group `../configs/meta.tags`.

###Method [Meta::title]
~~~
$title = $meta->title();
// Sets as string:
$meta->title('My site - articles - latest'); 
// Sets as array:
$meta->title(array('My site', 'articles', 'latest')); 
~~~

##Function [get_meta_tags](http://php.net/manual/function.get-meta-tags.php)

Extracts from the document contents of all meta tags and returns associative array.
~~~
// Assuming the above tags are at www.example.com
$tags = get_meta_tags('http://www.example.com/');
// Notice how the keys are all lowercase now, and how . was replaced by _ in the key.
echo $tags['author'];       // name
echo $tags['keywords'];     // php documentation
echo $tags['description'];  // a php manual
echo $tags['geo_position']; // 49.33;-86.59
~~~
