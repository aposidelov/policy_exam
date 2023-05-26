<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\UserPolicyRepository;
use App\Repository\InsurancePolicyRepository;
use App\Repository\InsurancePolicyTypeRepository;
use App\Service\ServiceException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PolicyClaimController extends AbstractController
{
    public function __construct(
        private UserPolicyRepository $userPolicyRepository,
        private InsurancePolicyRepository $insurancePolicyRepository,
        private InsurancePolicyTypeRepository $insurancePolicyTypeRepository,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/policy-claim/{uid}/{pid}', name: 'policy_claim', methods: ['POST'])]
    public function claimPolicy($uid, $pid, Request $request, SerializerInterface $serializer): Response
    {
        $userPolicy = $this->userPolicyRepository->loadEntity($uid, $pid);
        $response_data = (array) json_decode($request->getContent());
        if ($userPolicy) {
            $insurancePolicy = $this->insurancePolicyRepository->find($pid);
            $user = $this->userRepository->find($uid);
            $price = $insurancePolicy->getPrice();
            $type_id = $insurancePolicy->getTypeId();
            $insurancePolicyType = $this->insurancePolicyTypeRepository->find($type_id);
            $age = $user->getAge();
            $data = [
                'price' => $price,
                'type' => $insurancePolicyType->getMachineName(),
                'age' => $age,
                'data' => $response_data,
            ];
            throw new ServiceException(422, serialize($data));
        }
        $json_values = $serializer->serialize($userPolicy, 'json');
        return new Response($json_values);
    }
}
