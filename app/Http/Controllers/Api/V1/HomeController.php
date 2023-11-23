<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __invoke(): array
    {
        return [
            'success' => true,
            'message' => __('messages.welcome'),
            'data' => [
                'version' => '1.0.0',
                'service' => 'CAURIS API',
                'language' => app()->getLocale(),
                'support' => 'contact@agencecauris.com'
            ]

        ];
    }
}
