<?php

namespace CredentialsBundle\Services;

use Symfony\Component\Yaml\Parser;

/**
 * Search
 */
class CredentialsService
{
    /**
     * @var Kernel
     */
    private $_kernel;

    /**
     * @var array
     */
    private $_tokens;

    /**
     * @var array
     */
    private $_users;

    public function __construct($kernel, $userTokens)
    {
        $this->_kernel = $kernel;
        $this->_tokens = $userTokens;
        
    }

    public function canAccessResource($token, $resource, $method)
    {
        $canAccess = false;

        /* First we load the users */
        $this->_users = $this->_loadUsers();

        /* Replace user tokens with the real ones */
        $this->_users = $this->_replaceTokens();

        /* Then we get the user with that token */
        $userConfig = $this->_getUserConfigByToken($token);

        /* Now we get the methods the user can access for that resource */
        if (!empty($userConfig)) {
            $allowedMethods = $this->_getResourceAllowedMethods($userConfig, $resource);

            if (!empty($allowedMethods)) {
                if (in_array($method, $allowedMethods)) {
                    $canAccess = true;
                }
            }
        }

        return $canAccess;
    }

    /**
     * Get the Configuration of the users stored in the file
     * @return array
     */
    private function _loadUsers()
    {
        $yaml = new Parser();
        $path = $this->_kernel->locateResource('@CredentialsBundle/Resources/config/users.yml');

        $value = $yaml->parse(file_get_contents($path));

        return $value['users'];
    }

    /**
     * Replaces the tokens with the ones in the parameters file
     * @return array
     */
    private function _replaceTokens()
    {
        $newUsers = array();
        foreach ($this->_users as $user => $config) {
            if ($config['token'] === 'replaceMe') {
                if (!empty($this->_tokens[$user])) {
                    $config['token'] = $this->_tokens[$user];
                }
                $newUsers[$user] = $config;
            }
        }

        return $newUsers;
    }

    /**
     * Returns the user config for the user with the current token
     *
     * @param string $token
     * @return array
     */
    private function _getUserConfigByToken($token)
    {
        $userConfig = null;

        foreach ($this->_users as $user => $config) {
            if ($config['token'] === $token) {
                $userConfig = $config;
                break;
            }
        }

        return $userConfig;
    }

    /**
     * Returns the methods the user is allowed to use for this resource
     *
     * @param array $userConfig
     * @param string $resource
     * @return array
     */
    private function _getResourceAllowedMethods($userConfig, $resource)
    {
        $allowedMethods = null;

        foreach ($userConfig['resources'] as $currentResource => $methods) {
            if ($currentResource === $resource) {
                $allowedMethods = $methods;
                break;
            }
        }

        return $allowedMethods;
    }
}
