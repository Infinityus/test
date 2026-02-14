protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\LaunchingSoon::class,
    ],
];
