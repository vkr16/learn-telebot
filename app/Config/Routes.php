<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/telebot/getupdates', 'TelebotController::getUpdates');
$routes->get('/telebot/whfeedback', 'TelebotController::webhookFeedback');
