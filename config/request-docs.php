<?php

return [
    // change it to true will make lrd to throw exception if rules in request class need to be changed
    // keep it false
    'debug' => false,
    'document_name' => 'API docs',

    /*
    * Route where request docs will be served from
    * localhost:8080/request-docs
    */
    'url' => 'developer/api/request-docs',
    //'middlewares' => ['web', 'auth', 'can:developer'],
    'middlewares' => ['web'],

    /*
    * Default headers shown on the request headers editor
    */
    'default_request_headers' => [
        'Accept' => 'application/json',
        'Authorization' => (env('APP_ENV') == 'staging' ? 'Basic c2NhbmRpbmF2aWFudHJhdmVsOmpiZi1leHcyZmJnOXRoZ0BaTko=, ' : '') .'Bearer your_token_here'
    ],

    /*
    * Show development relevant metadata on endpoints
    */
    'show_development_metadata' => false,

    /**
     * Path to to static HTML if using command line.
     */
    'docs_path' => base_path('resources/views/developer/api/'),

    /**
     * Sorting route by and there is two types default(route methods), route_names.
     */
    'sort_by' => 'route_names',

    //Use only routes where ->uri start with next string Using Str::startWith( . e.g. - /api/mobile
    'only_route_uri_start_with' => 'api',

    'hide_matching' => [
        '#^telescope#',
        '#^docs#',
        '#^request-docs#',
        '#^api-docs#',
        '#^sanctum#',
        '#^_ignition#',
        '#^_tt#',
    ],

    'request_methods' => [
        'rules',
        'onCreate',
        'onUpdate',
    ],

    'open_api' => [
        // default version that this library provides
        'version' => '3.0.0',
        // changeable
        'document_version' => '1.0.0',
        // license that you want to display
        'license' => 'Apache 2.0',
        'license_url' => 'https://www.apache.org/licenses/LICENSE-2.0.html',
        'server_url' => env('APP_URL', 'http://localhost'),

        // for now putting default responses for all. This can be changed later based on specific needs
        'responses' => [
            '200' => [
                'description' => 'Successful operation',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '400' => [
                'description' => 'Bad Request',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '401' => [
                'description' => 'Unauthorized',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '403' => [
                'description' => 'Forbidden',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '404' => [
                'description' => 'Not Found',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '422' => [
                'description' => 'Unprocessable Entity',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            '500' => [
                'description' => 'Internal Server Error',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
            'default' => [
                'description' => 'Unexpected error',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
