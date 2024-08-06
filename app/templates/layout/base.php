<?php
/**
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

/**
 * Default `html` block.
 */
if (!$this->fetch('html')) {
	$this->start('html');
	if (Configure::check('App.language')) {
		printf('<html lang="%s">', Configure::read('App.language'));
	} else {
		echo '<html>';
	}
	$this->end();
}

/**
 * Default `title` block.
 */
if (!$this->fetch('title')) {
	$this->start('title');
	echo Configure::read('App.title');
	$this->end();
}

/**
 * Default `footer` block.
 */
if (!$this->fetch('tb_footer')) {
	$this->start('tb_footer');
	if (Configure::check('App.title')) {
		printf('&copy;%s %s', date('Y'), Configure::read('App.title'));
	} else {
		printf('&copy;%s', date('Y'));
	}
	$this->end();
}

/**
 * Default `body` block.
 */
$this->prepend('tb_body_attrs', ' class="d-flex flex-column h-100"');
if (!$this->fetch('tb_body_start')) {
	$this->start('tb_body_start');
	echo '<body' . $this->fetch('tb_body_attrs') . '>';
	$this->end();
}
/**
 * Default `flash` block.
 */
if (!$this->fetch('tb_flash')) {
	$this->start('tb_flash');
	echo $this->Flash->render();
	$this->end();
}
if (!$this->fetch('tb_body_end')) {
	$this->start('tb_body_end');
	echo '</body>';
	$this->end();
}

/**
 * Prepend `meta` block with `author` and `favicon`.
 */
if (Configure::check('App.author')) {
	$this->prepend(
		'meta',
		$this->Html->meta('author', null, [
			'name' => 'author',
			'content' => Configure::read('App.author'),
		]),
	);
}
$this->prepend(
	'meta',
	$this->Html->meta('favicon.ico', '/favicon.ico', ['type' => 'icon']),
);
?>
<!doctype html>
<?php echo $this->fetch('html'); ?>
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo h($this->fetch('title')); ?></title>
        <?php echo $this->fetch('meta'); ?>
        <?php echo $this->fetch('css'); ?>
    </head>

    <?php
	echo $this->fetch('tb_body_start');
	echo $this->fetch('tb_flash');
	echo $this->fetch('content');
	?>
<footer class="footer fixed-bottom py-3 bg-light">
  <div class="container">
    <span class="text-muted"><?= $this->fetch('tb_footer') ?></span>
  </div>
</footer>
    <?php
	echo $this->fetch('script');
	echo $this->fetch('tb_body_end');
	?>

</html>
