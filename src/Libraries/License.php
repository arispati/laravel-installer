<?php

namespace Arispati\LaravelInstaller\Libraries;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class License
{
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
                'LICENSE KEY (' . $identifier->join('-') . ')',
                '',
                $uuid
            ]);

            /**
             * Sent message
             */
            Http::get(self::getUrl(), self::message($messages->join(PHP_EOL)));
        } catch (\Exception $e) {
            // do nothing
        }

        Storage::put('license', Hash::make($uuid));

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
            $hash = Storage::get('license');
            $validate = Hash::check($license, $hash);

            if ($validate) {
                Storage::delete('license');
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
            self::decrypt('installer.token'),
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
            'chat_id' => self::decrypt('installer.id'),
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
