<?php

return [
    /*
     * A result store is responsible for saving the results of the checks. The
     * `EloquentHealthResultStore` will save results in the database. You
     * can use multiple stores at the same time.
     */
    'result_stores' => [
        Spatie\Health\ResultStores\EloquentHealthResultStore::class => [
            'connection' => env('HEALTH_DB_CONNECTION', env('DB_CONNECTION')),
            'model' => Spatie\Health\Models\HealthCheckResultHistoryItem::class,
            'keep_history_for_days' => 5, // Number of days to keep historical data
        ],
    ],

    /*
     * Define the checks that should run for monitoring the health of your application.
     * Make sure to use the correct namespaces for each check.
     */
    'checks' => [
    \Spatie\Health\Checks\Checks\DatabaseCheck::new(),
    \Spatie\Health\Checks\Checks\UsedDiskSpaceCheck::new()
        ->warnWhenUsedSpaceIsAbovePercentage(80),
],


    /*
     * You can configure additional result stores here if necessary, such as storing 
     * the results in memory, cache, or a JSON file.
     * Example:
     * Spatie\Health\ResultStores\CacheHealthResultStore::class => [
     *     'store' => 'file', 
     * ],
     * Spatie\Health\ResultStores\JsonFileHealthResultStore::class => [
     *     'disk' => 's3',
     *     'path' => 'health.json',
     * ],
     * Spatie\Health\ResultStores\InMemoryHealthResultStore::class,
     */
    // 'result_stores' => [
    //     Spatie\Health\ResultStores\InMemoryHealthResultStore::class,
    // ],

    /*
     * Notifications settings. You can get notified when specific checks fail or pass.
     * You can configure different notification channels like mail and Slack.
     */
    'notifications' => [
        'enabled' => true, // Enable/disable notifications

        'notifications' => [
            Spatie\Health\Notifications\CheckFailedNotification::class => ['mail'], // Notifications for failed checks
        ],

        // Define the notifiable class for notifications
        'notifiable' => Spatie\Health\Notifications\Notifiable::class,

        // Throttle notifications to avoid spamming when failures occur repeatedly
        'throttle_notifications_for_minutes' => 60, // Default to 60 minutes
        'throttle_notifications_key' => 'health:latestNotificationSentAt:',

        'mail' => [
            'to' => 'your@example.com', // Replace with your recipient email
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
        ],

        'slack' => [
            'webhook_url' => env('HEALTH_SLACK_WEBHOOK_URL', ''),
            'channel' => null, // Default channel will be used if set to null
            'username' => null, // Optional username for Slack
            'icon' => null, // Optional icon for Slack
        ],
    ],

    /*
     * Configure the Oh Dear monitoring integration if you'd like to monitor your checks.
     * Set the URL and secret for secure access to health check results.
     */
    'oh_dear_endpoint' => [
        'enabled' => false, // Set to true to enable Oh Dear monitoring

        'always_send_fresh_results' => true, // Always send fresh results to Oh Dear
        'secret' => env('OH_DEAR_HEALTH_CHECK_SECRET'), // Secret token for Oh Dear
        'url' => '/oh-dear-health-check-results', // URL endpoint for health check results
    ],

    /*
     * Specify heartbeat URLs for Horizon and Schedule checks. These will be pinged
     * when the corresponding checks are successful.
     */
    'horizon' => [
        'heartbeat_url' => env('HORIZON_HEARTBEAT_URL', null),
    ],

    'schedule' => [
        'heartbeat_url' => env('SCHEDULE_HEARTBEAT_URL', null),
    ],

    /*
     * Theme for the local health check results page.
     * - light: light mode
     * - dark: dark mode
     */
    'theme' => 'light',

    /*
     * When enabled, completed `HealthQueueJob`s will be displayed in Horizon's silenced jobs screen.
     */
    'silence_health_queue_job' => true,

    /*
     * The response code to use for HealthCheckJsonResultsController when a health check fails.
     */
    'json_results_failure_status' => 200,

    /*
     * You can specify a secret token that needs to be sent in the X-Secret-Token header for secured access.
     */
    'secret_token' => env('HEALTH_SECRET_TOKEN') ?? null,

    /**
     * By default, conditionally skipped health checks are treated as failures.
     * You can override this behavior by uncommenting the configuration below.
     * @link https://spatie.be/docs/laravel-health/v1/basic-usage/conditionally-running-or-modifying-checks
     */
    //'treat_skipped_as_failure' => false
];
