<?php

namespace Arispati\LaravelInstaller\Libraries;

class License extends StorageKeySubmission
{
    /**
     * Get file name
     *
     * @return string
     */
    protected static function getFileName(): string
    {
        return 'license';
    }

    /**
     * Get key name
     *
     * @return string
     */
    protected static function getKeyName(): string
    {
        return 'LICENSE KEY';
    }
}
