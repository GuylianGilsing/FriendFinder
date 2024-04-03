<?php

declare(strict_types=1);

use Spiral\RoadRunner\Http\PSR7WorkerInterface;

require_once __DIR__.'/../../vendor/autoload.php';

$api = get_rest_api_instance();
$app = get_application_instance();

/** @var PSR7WorkerInterface $worker */
$worker = $app->getContainer()->get(PSR7WorkerInterface::class);

while ($request = $worker->waitRequest()) {
    try {
        $response = $api->handle($request);
        $worker->respond($response);
    } catch (Throwable $err) {
        $worker->getWorker()->error((string) $err);
    }
}
