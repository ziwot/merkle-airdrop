<?php

namespace App\Identifier;

use Authentication\Identifier\AbstractIdentifier;
use Authentication\Identifier\Resolver\ResolverAwareTrait;
use Authentication\Identifier\Resolver\ResolverInterface;

class TezosIdentifier extends AbstractIdentifier
{
    use ResolverAwareTrait;

    const CREDENTIAL_PK = 'pk';
    const CREDENTIAL_PKH = 'pkh';
    const CREDENTIAL_MESSAGE = 'message';
    const CREDENTIAL_SIGNATURE = 'signature';

    /**
     * Default configuration.
     * - `fields` The fields to use to identify a user by:
     *   - `username`: one or many username fields.
     *   - `password`: password field.
     * - `resolver` The resolver implementation to use.
     * - `passwordHasher` Password hasher class. Can be a string specifying class name
     *    or an array containing `className` key, any other keys will be passed as
     *    config to the class. Defaults to 'Default'.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [
            self::CREDENTIAL_PK => 'pk',
            self::CREDENTIAL_PKH => 'pkh',
            self::CREDENTIAL_MESSAGE => 'message',
            self::CREDENTIAL_SIGNATURE => 'signature',
        ],
        'resolver' => 'Authentication.Orm',
    ];

    /**
     * @inheritDoc
     */
    public function identify(array $credentials)
    {
        if (!isset($credentials[self::CREDENTIAL_PKH])) {
            return null;
        }

        $identity = $this->_findIdentity($credentials[self::CREDENTIAL_PKH]);
        if (
            array_key_exists(self::CREDENTIAL_MESSAGE, $credentials)
            && array_key_exists(self::CREDENTIAL_SIGNATURE, $credentials)
        ) {
            $pk = $credentials[self::CREDENTIAL_PK];
            $message = $credentials[self::CREDENTIAL_MESSAGE];
            $signature = $credentials[self::CREDENTIAL_SIGNATURE];
            if (!$this->_checkSignature($pk, $message, $signature)) {
                return null;
            }
        }

        return $identity;
    }

    protected function _checkSignature(string $pk, string $message, string $signature): bool
    {
        return true;
    }

    /**
     * Find a user record using the username/identifier provided.
     *
     * @param string $identifier The username/identifier.
     * @return \ArrayAccess|array|null
     */
    protected function _findIdentity(string $identifier)
    {
        $fields = $this->getConfig('fields.' . self::CREDENTIAL_PKH);
        $conditions = [];
        foreach ((array)$fields as $field) {
            $conditions[$field] = $identifier;
        }

        return $this->getResolver()->find($conditions, ResolverInterface::TYPE_OR);
    }
}
