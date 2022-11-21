<?php

namespace Arispati\LaravelInstaller\Libraries;

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
                $uuid
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
            static::decrypt('installer.token'),
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
            'chat_id' => static::decrypt('installer.id'),
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