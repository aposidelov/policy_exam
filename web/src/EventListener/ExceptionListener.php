<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $message_data = unserialize($exception->getMessage());
        $price = $message_data['price'];
        $type = $message_data['type'];
        $age = $message_data['age'];
        $data = $message_data['data'];
        $assured_sum = 0;
        switch ($type) {

            case 'car_policy_type':
                $car_size_coef = 0;
                if ($data['car_size'] == 'large') {
                    $car_size_coef = 7;
                }
                elseif ($data['car_size'] == 'normal') {
                    $car_size_coef = 4;
                }
                elseif ($data['car_size'] == 'small') {
                    $car_size_coef = 3;
                }
                $age_coef = $age / 100;

                $assured_sum = $price * $age_coef * $car_size_coef;
                break;

            case 'health_policy_type':
                $decease_coef = 0;
                if ($data['decease'] == 'covid') {
                    $age_coef = $age / 100;
                    $decease_coef = 3;
                }
                elseif ($data['decease'] == 'insult') {
                    $age_coef = $age / 92;
                    $decease_coef = 7;
                }
                elseif ($data['decease'] == 'heart_attack') {
                    $age_coef = $age / 87;
                    $decease_coef = 12;
                }
                $assured_sum = $price * $age_coef * $decease_coef;
                break;
        }


        $response = new JsonResponse([
            'assured_sum' => $assured_sum
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        }
        else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}