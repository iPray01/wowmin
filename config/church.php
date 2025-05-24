<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Church Information
    |--------------------------------------------------------------------------
    |
    | This file contains general information about the church that is used
    | throughout the application.
    |
    */

    'name' => env('CHURCH_NAME', 'Your Church Name'),

    'address' => env('CHURCH_ADDRESS', '123 Church Street, City, State 12345'),

    'phone' => env('CHURCH_PHONE', '(555) 123-4567'),

    'email' => env('CHURCH_EMAIL', 'info@yourchurch.org'),

    'website' => env('CHURCH_WEBSITE', 'https://www.yourchurch.org'),

    'tax_id' => env('CHURCH_TAX_ID', '12-3456789'),

    /*
    |--------------------------------------------------------------------------
    | Church Service Times
    |--------------------------------------------------------------------------
    |
    | Default service times for the church. These can be overridden by
    | specific service entries in the database.
    |
    */

    'service_times' => [
        'sunday_morning' => '10:00 AM',
        'sunday_evening' => '6:00 PM',
        'wednesday_evening' => '7:00 PM',
    ],

    /*
    |--------------------------------------------------------------------------
    | Financial Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to financial operations including donations,
    | campaigns, and reporting.
    |
    */

    'finance' => [
        'currency' => env('CHURCH_CURRENCY', 'GHS'),
        'currency_symbol' => env('CHURCH_CURRENCY_SYMBOL', 'GH₵'),
        'fiscal_year_start' => env('CHURCH_FISCAL_YEAR_START', '01-01'),
        'donation_categories' => [
            'tithe' => 'Tithe',
            'offering' => 'Offering',
            'building_fund' => 'Building Fund',
            'missions' => 'Missions',
            'youth_ministry' => 'Youth Ministry',
            'children_ministry' => "Children's Ministry",
            'special_project' => 'Special Project',
            'other' => 'Other',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for church-specific email communications.
    |
    */

    'email' => [
        'from_address' => env('CHURCH_EMAIL_FROM_ADDRESS', 'no-reply@yourchurch.org'),
        'from_name' => env('CHURCH_EMAIL_FROM_NAME', 'Your Church'),
        'footer_text' => env('CHURCH_EMAIL_FOOTER_TEXT', 'Thank you for being part of our church family.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media
    |--------------------------------------------------------------------------
    |
    | Social media links for the church.
    |
    */

    'social_media' => [
        'facebook' => env('CHURCH_FACEBOOK_URL'),
        'twitter' => env('CHURCH_TWITTER_URL'),
        'instagram' => env('CHURCH_INSTAGRAM_URL'),
        'youtube' => env('CHURCH_YOUTUBE_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Church Management System Configuration
    |--------------------------------------------------------------------------
    */

    'branding' => [
        'colors' => [
            'primary' => '#D4AF37',    // Metal Gold
            'secondary' => '#008080',   // Teal
            'accent' => '#DC143C',      // Crimson
            'complementary' => [
                '#2C3E50',             // Dark Blue
                '#34495E',             // Slate Gray
                '#E8F6F3',             // Light Teal
                '#FAF0E6',             // Linen
            ]
        ],
    ],

    'currency' => [
        'code' => 'GHS',
        'symbol' => '₵',
        'name' => 'Ghana Cedis',
        'decimal_places' => 2,
    ],

    'date_format' => [
        'display' => 'd M Y',
        'input' => 'Y-m-d',
        'time' => 'H:i',
        'datetime' => 'd M Y H:i',
    ],

    'pagination' => [
        'per_page' => 15,
    ],

    'uploads' => [
        'profile_photos' => 'public/profile-photos',
        'receipts' => 'public/receipts',
        'attachments' => 'public/attachments',
    ],
]; 