<?php
declare(strict_types=1);

return [
    /*
     * 小程序
     */
    'mini_program' => [
        'default' => [
            'app_id'     => env('WECHAT_MINI_PROGRAM_APP_ID', ''),
            'secret'     => env('WECHAT_MINI_PROGRAM_SECRET', ''),
            'token'      => env('WECHAT_MINI_PROGRAM_TOKEN', ''),
            'aes_key'    => env('WECHAT_MINI_PROGRAM_AES_KEY', ''),
        ],
    ],
];