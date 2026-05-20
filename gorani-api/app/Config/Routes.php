<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setAutoRoute(false);

$routes->group('api', function ($routes) {
    
    // Public routes
    $routes->post('auth/register', 'Api\AuthController::register');
    $routes->post('auth/login', 'Api\AuthController::login');
    $routes->get('auth/verify-email/(:segment)', 'Api\AuthController::verifyEmail/$1');
    $routes->post('auth/resend-verification', 'Api\AuthController::resendVerification');
    $routes->post('auth/forgot-password', 'Api\AuthController::forgotPassword');
    $routes->post('auth/reset-password', 'Api\AuthController::resetPassword');

    // Catalogs (public)
    $routes->get('catalogs/countries', 'Api\CatalogsController::countries');
    $routes->get('catalogs/languages', 'Api\CatalogsController::languages');
    $routes->get('catalogs/certificates', 'Api\CatalogsController::certificates');
    $routes->get('catalogs/greetings', 'Api\CatalogsController::greetings');
    $routes->get('catalogs/units', 'Api\CatalogsController::units');
    $routes->get('catalogs/sectors', 'Api\CatalogsController::sectors');
    $routes->get('catalogs/subsectors/(:num)', 'Api\CatalogsController::subSectors/$1');
    $routes->get('catalogs/subpartidas', 'Api\CatalogsController::subpartidas');

    // Protected routes (JWT required)
    $routes->group('', ['filter' => 'jwt'], function ($routes) {
        
        // Auth
        $routes->get('auth/me', 'Api\AuthController::me');
        $routes->post('auth/logout', 'Api\AuthController::logout');
        $routes->put('auth/password', 'Api\AuthController::changePassword');

        // Profile
        $routes->get('profile', 'Api\ProfileController::show');
        $routes->put('profile', 'Api\ProfileController::update');
        $routes->post('profile/logo', 'Api\ProfileController::uploadLogo');

        // Events
        $routes->get('events', 'Api\EventsController::index');
        $routes->get('events/my-registrations', 'Api\EventsController::myRegistrations');
        $routes->get('events/(:num)', 'Api\EventsController::show/$1');
        $routes->post('events/(:num)/register', 'Api\EventsController::register/$1');
        $routes->get('events/(:num)/schedule', 'Api\EventsController::getSchedule/$1');
        $routes->get('events/(:num)/statistics', 'Api\EventsController::getStatistics/$1');

        // Admin - Events
        $routes->post('admin/events', 'Api\AdminController::createEvent');
        $routes->put('admin/events/(:num)', 'Api\AdminController::updateEvent/$1');
        $routes->put('admin/events/(:num)/status', 'Api\AdminController::updateEventStatus/$1');
        
        // Admin - Schedule
        $routes->post('admin/events/(:num)/days', 'Api\AdminController::addScheduleDay/$1');
        $routes->put('admin/events/(:num)/days/(:num)', 'Api\AdminController::updateScheduleDay/$1/$2');
        $routes->delete('admin/events/(:num)/days/(:num)', 'Api\AdminController::deleteScheduleDay/$1/$2');
        $routes->post('admin/events/(:num)/days/(:num)/slots', 'Api\AdminController::addScheduleSlot/$1/$2');
        $routes->put('admin/events/(:num)/slots/(:num)', 'Api\AdminController::updateScheduleSlot/$1/$2');
        $routes->delete('admin/events/(:num)/slots/(:num)', 'Api\AdminController::deleteScheduleSlot/$1/$2');

        // Admin - Users
        $routes->get('admin/events/(:num)/users', 'Api\AdminController::getEventUsers/$1');
        $routes->put('admin/events/(:num)/users/(:num)/approve', 'Api\AdminController::approveUser/$1/$2');
        $routes->put('admin/events/(:num)/users/(:num)/reject', 'Api\AdminController::rejectUser/$1/$2');

        // Admin - Reports
        $routes->get('admin/events/(:num)/reports/sellers', 'Api\AdminController::getSellersReport/$1');
        $routes->get('admin/events/(:num)/reports/buyers', 'Api\AdminController::getBuyersReport/$1');
        $routes->get('admin/events/(:num)/reports/agenda', 'Api\AdminController::getAgendaReport/$1');
    });
});