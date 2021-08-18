<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @var int
     */
    protected $statusCode=200;

    /**
     * @return int
     */
    public function getStatusCode(){
        return $this->statusCode;
    }
    public function setStatusCode($statusCode){
        $this->statusCode =$statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $headers
     * @return JsonResponse
     */
    public function respond($data, $headers=[]){
        return new JsonResponse($data, $this->getStatusCode(),$headers);
    }

    /**
     * @param $errors
     * @param array $headers
     * @return JsonResponse
     */
    public function respondWithErrors($errors, $headers=[]){
        $data=[
            'errors'=>$errors
        ];
        return new JsonResponse($data ,$this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * Returns a 422 Unprocessable Entity
     * @param string $message
     * @return JsonResponse
     */
    public function respondValidationError($message="Validation errors"){
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * Returns a 404 Not Found
     * @param string $message
     * @return JsonResponse
     */
    public function respondNotFound($message="Not found"){
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    public function respondCreated($data=[]){
        return $this->setStatusCode(201)->respond($data);
    }

}
