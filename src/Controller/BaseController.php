<?php
namespace Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $apiMode = false;

    /**
     * BaseController constructor.
     * @param Request $request
     */
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function isApiMode()
    {
        return $this->apiMode;
    }

    /**
     * @param bool $apiMode
     */
    public function setApiMode($apiMode)
    {
        $this->apiMode = $apiMode;
    }

    /**
     * @return mixed
     */
    abstract public function execute();

//    public function respond()
//    {
//        try {
//            $responseData = $this->getResponseData(true);
//            $response = [
//                'success' => true,
//                'data' => $responseData
//            ];
//        } catch (\Exception $e) {
//            $response = [
//                'success' => false,
//                'data' => [
//                    'message' => $e->getMessage()
//                ]
//            ];
//        }
//        //TODO: add status to response
//        return JsonResponse::create($response);
//    }
//
//    /**
//     * @param bool $forApi
//     * @return mixed
//     */
//    abstract protected function getResponseData($forApi = false);
}
