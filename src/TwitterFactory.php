<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Twitter.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use InvalidArgumentException;

class TwitterFactory
{
    /**
     * Make a new Twitter client.
     *
     * @param array $config
     *
     * @return \Abraham\TwitterOAuth\TwitterOAuth
     */
    public function make(array $config): TwitterOAuth
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
    protected function getConfig(array $config): array
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
    protected function getClient(array $auth): TwitterOAuth
    {
        return new TwitterOAuth(
            $auth['consumer_key'],
            $auth['consumer_secret'],
            $auth['access_token'],
            $auth['access_token_secret']
        );
    }
}
