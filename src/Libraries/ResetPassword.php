<?php

namespace Arispati\LaravelInstaller\Libraries;

use Arispati\LaravelInstaller\Libraries\Contracts\StorageKeySubmission;

class ResetPassword extends StorageKeySubmission
{
    /**
     * Get file name
     *
     * @return string
     */
    protected static function getFileName(): string
    {
        return 'resetpass';
    }

    /**
     * Get key name
     *
     * @return string
     */
    protected static function getKeyName(): string
    {
        return 'RESET KEY';
    }
}
