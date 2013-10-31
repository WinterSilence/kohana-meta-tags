<?php defined('SYSPATH') OR die('No direct script access.') ?>

<!-- meta tags begin -->
<?php foreach ($tags as $name => $attributes): ?>
	<?php if ($name == 'title'): ?>
		<title><?php echo implode(' - ', (array) $attributes) ?></title>
	<?php else: ?>
		<meta<?php echo HTML::attributes($attributes) ?>/>
	<?php endif ?>
<?php endforeach ?>
<!-- meta tags end -->