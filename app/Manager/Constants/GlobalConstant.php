<?php

namespace App\Manager\Constants;

class GlobalConstant
{
    public const MAXIMUM_ALLOWED_LOGIN_ATTEMPTS_PER_MINUTE = 12;
    public const DEFAULT_PAGINATION = 10;

    public const CATEGORY_TYPE_EVENT    = 'agency';
    public const CATEGORY_TYPE_CATEGORY = 'vendor';
    public const CATEGORY_TYPE_BUSINESS = 'BusinessType';

    public const CATEGORY_TYPE_LIST = [
        self::CATEGORY_TYPE_CATEGORY => 'Vendor',
        self::CATEGORY_TYPE_EVENT    => 'Agency',
        self::CATEGORY_TYPE_BUSINESS => 'Business Type',
    ];

    public const LOCATION_TYPE_DIVISION = 'Division';
    public const LOCATION_TYPE_DISTRICT = 'District';
    public const LOCATION_TYPE_THANA    = 'Thana';

    public const LOCATION_TYPE_LIST = [
        self::LOCATION_TYPE_DIVISION => 'Division',
        self::LOCATION_TYPE_DISTRICT => 'District',
        self::LOCATION_TYPE_THANA    => 'Thana',
    ];

    public const TYPE_EVENT   = 'event';
    public const TYPE_SERVICE = 'service';
    public const TYPE_GALLERY = 'gallery';
}
