<?php

namespace BrianFaust\Twitter;

use InvalidArgumentException;
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterFactory
{
    /**
     * Make a new Twitter client.
     *
     * @param array $config
     *
     * @return \Twitter\Twitter
     */
    public function make(array $config)
    {
        $config = $this->getConfig($config);

        return $this->getClient($config);
    }

    /**
     * Get the configuration data.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function getConfig(array $config)
    {
        $keys = ['consumer_key', 'consumer_secret'];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $config)) {
                throw new InvalidArgumentException("Missing configuration key [$key].");
            }
        }

        return array_only($config, ['consumer_key', 'consumer_secret', 'access_token', 'access_token_secret']);
    }

    /**
     * Get the Twitter client.
     *
     * @param array $auth
     *
     * @return \Twitter\Twitter
     */
    protected function getClient(array $auth)
    {
        return new TwitterOAuth(
            $auth['consumer_key'],
            $auth['consumer_secret'],
            $auth['access_token'],
            $auth['access_token_secret']
        );
    }
}
