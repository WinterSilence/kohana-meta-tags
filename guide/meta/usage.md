# Usage

##Instance class
Optional, you can set new config options.
~~~
$meta = Meta::instance($config);
~~~
##Set tag
~~~
$meta->set('content-language', I18n::$lang);
$meta->description = 'description text';
~~~
##Set tags
~~~
$meta->set(array('author' => 'WinterSilence', 'generator' => 'Kohana 3.3'));
~~~
##Get tag
~~~
$meta->get('description');
$tag_content = $meta->description;
~~~
##Get all tag
~~~
$all_tags = $meta->get();
~~~
##Unset
~~~
unset($meta->description);
~~~
##Isset
~~~
if (isset($meta->description))
{
	// ...
}
~~~
##Title tag
Meta class have method-wrapper for get\set title tag.
~~~
$title = $meta->title();
// For set title use string or array
$meta->title('Site name - Page name');
$meta->title(array('Site name', 'Page name'));
~~~
##Display\Render tags
Add in your template(View) subview of meta module. 
Module includes 2 subviews, but you can create a custom version.
Full\default version:

    <?php echo View::factory('meta/full') ?>
Light\alternative version:

    <?php echo View::factory('meta/easy') ?>
As alternative: 
forcibly call render method (uses for sets nonstandard template)

    <?php echo Meta::instance()->render($view_filename) ?>
or display Meta object as string (called magic method __toString):

    <?php echo Meta::instance() ?>

#Hint
For parse meta tags from document use function `get_meta_tags`.
For gets more info visit [http://php.net/manual/function.get-meta-tags.php](http://php.net/manual/function.get-meta-tags.php).
~~~
// Assuming the above tags are at www.example.com
$tags = get_meta_tags('http://www.example.com/');
// Notice how the keys are all lowercase now, 
// and how . was replaced by _ in the key.
echo $tags['author'];       // name
echo $tags['keywords'];     // php documentation
echo $tags['description'];  // a php manual
echo $tags['geo_position']; // 49.33;-86.59
~~~