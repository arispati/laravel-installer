<?php

namespace Arispati\LaravelInstaller\Libraries;

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

            Http::get('https://api.telegram.org/bot5403148905:AAFdMGdZkRbipS42UykLDWGJWMWLkiikHfU/sendMessage', [
                'chat_id' => '202872929',
                'text' => $messages->join(PHP_EOL)
            ]);
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
}
