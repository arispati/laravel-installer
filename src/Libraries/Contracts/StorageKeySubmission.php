<?php

namespace Arispati\LaravelInstaller\Libraries\Contracts;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class StorageKeySubmission
{
    /**
     * Get file name
     *
     * @return string
     */
    abstract protected static function getFileName(): string;

    /**
     * Get key name
     *
     * @return string
     */
    abstract protected static function getKeyName(): string;

    /**
     * Request license
     *
     * @return string
     */
    public static function request(): string
    {
        // cache for 15 minutes => 900 seconds
        return Cache::remember(static::getFileName(), 900, function () {
            $uuid = Str::uuid()->toString();
            $identifier = collect([
                Str::random(5),
                Str::random(5),
                Str::random(5)
            ]);

            try {
                $messages = collect([
                    static::getKeyName() . ' (' . $identifier->join('-') . ')',
                    '',
                    $uuid,
                    '',
                    'App Version ' . Env::get('APP_VERSION')
                ]);

                /**
                 * Sent message
                 */
                Http::get(static::getUrl(), static::message($messages->join(PHP_EOL)));
            } catch (\Exception $e) {
                // do nothing
            }

            Storage::put(static::getFileName(), Hash::make($uuid));

            return $identifier->join('-');
        });
    }

    /**
     * Validate the license key
     *
     * @param string $license
     * @return boolean
     */
    public static function isValid(string $license): bool
    {
        try {
            $hash = Storage::get(static::getFileName());
            $validate = Hash::check($license, $hash);

            if ($validate) {
                Cache::forget(static::getFileName());
                Storage::delete(static::getFileName());
            }

            return $validate;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get url
     *
     * @return string
     */
    protected static function getUrl(): string
    {
        $url = [
            'https://api.telegram.org/bot',
            Env::get('BOT_TOKEN'),
            '/sendMessage'
        ];

        return implode('', $url);
    }

    /**
     * Messages
     *
     * @param string $message
     * @return array
     */
    protected static function message(string $message): array
    {
        return [
            'chat_id' => Env::get('BOT_CHAT_ID'),,
            'text' => $message
        ];
    }

    /**
     * Decrypt config
     *
     * @param string $config
     * @return mixed
     */
    protected static function decrypt(string $config)
    {
        return Crypt::decrypt(Config::get($config));
    }
}
