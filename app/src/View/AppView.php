<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link     https://book.cakephp.org/4/en/views.html#the-app-view
 * @property \Authentication\View\Helper\IdentityHelper $Identity
 * @property \AssetMix\View\Helper\AssetMixHelper $AssetMix
 * @property \App\View\Helper\TzHelper $Tz
 * @property \BootstrapUI\View\Helper\HtmlHelper $Html
 * @property \BootstrapUI\View\Helper\FormHelper $Form
 * @property \BootstrapUI\View\Helper\FlashHelper $Flash
 * @property \BootstrapUI\View\Helper\PaginatorHelper $Paginator
 * @property \BootstrapUI\View\Helper\BreadcrumbsHelper $Breadcrumbs
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $helpers = [
            'Html' => ['className' => 'BootstrapUI.Html'],
            'Form' => ['className' => 'BootstrapUI.Form'],
            'Flash' => ['className' => 'BootstrapUI.Flash'],
            'Paginator' => ['className' => 'BootstrapUI.Paginator'],
            'Breadcrumbs' => ['className' => 'BootstrapUI.Breadcrumbs'],
        ];

        $this->helpers = array_merge($helpers, $this->helpers);
        $this->loadHelper('ViteHelper.ViteScripts');
        $this->addHelper('Authentication.Identity');
        $this->addHelper('Tz');
    }
}
