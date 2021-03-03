<?php

require 'vendor/autoload.php';
require 'core/bootstrap.php';

use App\Core\App;
use App\Core\AppRequest;
use App\Core\AppResponse;

try {
    App::get('router')->direct(AppRequest::uri(), AppRequest::method());
} catch (\Exception $e) {
    AppResponse::json([], $e->getCode(), $e->getMessage());
}
