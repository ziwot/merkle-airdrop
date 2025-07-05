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
$this->prepend('tb_body_attrs', ' class="d-flex flex-column min-vh-100"');
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
    $this->Html->meta('favicon.png', '/favicon.png', ['type' => 'icon']),
);

/**
 * Prepend `css` block with Bootstrap stylesheets
 * Change to bootstrap.min to use the compressed version
 */
$this->prepend('css', $this->Html->css([
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'BootstrapUI./font/bootstrap-icon-sizes',
    'app'
]));

/**
 * Prepend `script` block with Popper and Bootstrap scripts
 * Change popper.min and bootstrap.min to use the compressed version
 */
$this->prepend( 'script', $this->Html->script(
    'https://unpkg.com/alpinejs@3.14.3/dist/cdn.min.js', ['defer' => true]
));
$this->prepend('script', $this->Html->script([
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://unpkg.com/htmx.org@2.0.3/dist/htmx.min.js',
]));
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
<footer class="mt-auto border-top py-3">
  <div class="container">
    <?= $this->fetch('tb_footer') ?>
  </div>
</footer>
    <?php
    echo $this->fetch('script');
    echo $this->fetch('tb_body_end');
    ?>

</html>
