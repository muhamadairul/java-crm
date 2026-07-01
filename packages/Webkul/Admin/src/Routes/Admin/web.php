<?php

/**
 * Auth routes.
 */
require 'auth-routes.php';

/**
 * Leads routes.
 */
require 'leads-routes.php';

/**
 * Email routes.
 */
require 'mail-routes.php';

/**
 * Settings routes.
 */
require 'settings-routes.php';

/**
 * Products routes.
 */
require 'products-routes.php';

/**
 * Contacts routes.
 */
require 'contacts-routes.php';

/**
 * Activities routes.
 */
require 'activities-routes.php';

/**
 * Quotes routes.
 */
require 'quote-routes.php';

/**
 * Configuration routes.
 */
require 'configuration-routes.php';

/**
 * Rest routes.
 */
require 'rest-routes.php';

/**
 * Notifications routes.
 */
Route::controller(\Webkul\Admin\Http\Controllers\NotificationController::class)->prefix('notifications')->group(function () {
    Route::get('', 'index')->name('admin.notifications.index');
    Route::post('read-all', 'readAll')->name('admin.notifications.read_all');
});

