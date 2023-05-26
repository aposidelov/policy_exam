<?php

namespace App\Controller;

use App\Entity\InsurancePolicyType;
use App\Repository\InsurancePolicyTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class InsurancePolicyTypeController extends AbstractController
{
    public function __construct(
        private InsurancePolicyTypeRepository $repository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/insurance_policy_type/{id}', name: 'insurance_policy_type_view', methods: ['GET'])]
    public function viewInsurancePolicyType($id, SerializerInterface $serializer): Response
    {
        $entity = $this->repository->find($id);
        $json_values = $serializer->serialize($entity, 'json');
        return new Response($json_values);
    }

    #[Route('/insurance_policy_type/{id}/delete', name: 'insurance_policy_type_delete', methods: ['DELETE'])]
    public function deleteInsurancePolicyType($id): JsonResponse
    {
        $entity = $this->repository->find($id);
        $this->repository->remove($entity);
        $this->entityManager->flush();
        return $this->json(['message' => "Insurance Policy Type ({$id}) has been deleted"]);
    }

    #[Route('/insurance_policy_type/{id}/update', name: 'insurance_policy_type_update', methods: ['PUT'])]
    public function updateInsurancePolicyType(Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent());
        $entity = $this->repository->find($id);
        if (isset($data->machineName)) {
            $entity->setMachineName($data->machineName);
        }
        if (isset($data->title)) {
            $entity->setTitle($data->title);
        }
        $this->repository->save($entity, TRUE);
        return $this->json(['message' => "Insurance Policy Type ({$id}) has been updated"]);
    }

    #[Route('/insurance_policy_type', name: 'insurance_policy_type_create', methods: ['POST'])]
    public function createInsurancePolicyType(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $entity = new InsurancePolicyType();
        $entity->setMachineName($data->machineName);
        $entity->setTitle($data->title);
        $this->repository->save($entity, TRUE);

        return $this->json(['message' => 'The Insurance Policy Type has been inserted.']);
    }
}
