# Usage

##Instance class
Optional, you can sets new config options.
~~~
$meta = Meta::instance($config);
~~~
##Set tag:
~~~
$meta->set('content-language', I18n::$lang);
$meta->description = 'description text';
~~~
Sets title content as string or array.
~~~
$meta->set('title', array('Site name', 'Page name'));
$meta->set('title', 'Site name - Page name');
~~~
Set tags:
~~~
$meta->set(array('author' => 'WinterSilence', 'generator' => 'Kohana 3.1'));
~~~
##Get tag:
~~~
$meta->get('description');
$tag_content = $meta->description;
~~~
Get all tag:
~~~
$all_tags = $meta->get();
~~~
##Unset:
~~~
unset($meta->description);
~~~
##Isset:
~~~
if (isset($meta->description))
{
	// ...
}
~~~
##Render:
~~~
echo $meta->render();
echo $meta; // called __toString()
~~~

##Helpers:
Parces meta tags from document:
~~~
$tags = Meta::parce($url);
~~~