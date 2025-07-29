<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * @property \CakeTezos\Controller\Component\NetworkComponent $Network
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('CakeTezos.Network');
        $this->loadComponent('Authentication.Authentication');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    /**
     * Returns true if 'HX-Request' header is present, false otherwise
     *
     * @return bool
     */
    protected function isHTMXRequest(): bool
    {
        return $this->request->hasHeader('HX-Request') &&
            $this->request->getHeaderLine('HX-Request') === 'true';
    }
}
