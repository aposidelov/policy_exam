<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserPolicy;
use App\Entity\InsurancePolicy;
use App\Entity\InsurancePolicyType;
use App\Repository\UserRepository;
use App\Repository\UserPolicyRepository;
use App\Repository\InsurancePolicyRepository;
use App\Repository\InsurancePolicyTypeRepository;
use App\Service\ServiceException;
use Cassandra\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class InsurancePolicyController extends AbstractController
{
    public function __construct(
        private InsurancePolicyRepository $repository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/insurance_policy/{id}', name: 'insurance_policy_view', methods: ['GET'])]
    public function viewInsurancePolicy($id, SerializerInterface $serializer): Response
    {
        $entity = $this->repository->find($id);
        $json_values = $serializer->serialize($entity, 'json');
        return new Response($json_values);
    }

    #[Route('/insurance_policy/{id}/delete', name: 'insurance_policy_delete', methods: ['DELETE'])]
    public function deleteInsurancePolicy($id): JsonResponse
    {
        $entity = $this->repository->find($id);
        $this->repository->remove($entity);
        $this->entityManager->flush();
        return $this->json(['message' => "Insurance Policy ({$id}) has been deleted"]);
    }

    #[Route('/insurance_policy/{id}/update', name: 'insurance_policy_update', methods: ['PUT'])]
    public function updateInsurancePolicy(Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent());
        $entity = $this->repository->find($id);
        if (isset($data->title)) {
            $entity->setTitle($data->title);
        }
        if (isset($data->type_id)) {
            $entity->setTypeId($data->type_id);
        }
        if (isset($data->price)) {
            $entity->setPrice($data->price);
        }
        $this->repository->save($entity, TRUE);
        return $this->json(['message' => "Insurance Policy ({$id}) has been updated"]);
    }

    #[Route('/insurance_policy', name: 'insurance_policy_create', methods: ['POST'])]
    public function createInsurancePolicy(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $entity = new InsurancePolicy();
        $entity->setTitle($data->title);
        $entity->setTypeId($data->type_id);
        $entity->setPrice($data->price);
        $this->repository->save($entity, TRUE);

        return $this->json(['message' => 'The Insurance Policy has been inserted.']);
    }
}
