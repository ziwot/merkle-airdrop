<?php

namespace App\Identifier;

use Authentication\Identifier\AbstractIdentifier;
use Authentication\Identifier\Resolver\ResolverAwareTrait;
use Authentication\Identifier\Resolver\ResolverInterface;
use Bzzhh\Pezos\Keys\PubKey;

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
     *   - `pk`: a public key
     *   - `pkh`: the matching public key hash
     *   - `message` the payload
     *   - `signature` the signed payload
     * - `resolver` The resolver implementation to use.
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

    /**
     * TODO: additional message verification?
     * is the csrf enough to make sure nothing has been tampered?
     */
    protected function _checkSignature(string $pk, string $message, string $signature): bool
    {
        $pubKey = PubKey::fromBase58($pk);

        if (
            !$pubKey->verifySignature($signature, $message) &&
            !$pubKey->verifySignature($signature, bin2hex($message))
        ) {
            return false;
        }

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
        return $this->getResolver()->find(['address' => $identifier]);
    }
}
