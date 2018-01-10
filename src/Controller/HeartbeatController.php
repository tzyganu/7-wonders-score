<?php
namespace Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class HeartbeatController extends BaseController
{
    public function execute()
    {
        return new JsonResponse(['success' => true]);
    }
}
