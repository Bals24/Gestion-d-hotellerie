protected $routeMiddleware = [
    // ...
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    // 'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
];