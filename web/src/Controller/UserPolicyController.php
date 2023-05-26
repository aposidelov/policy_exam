<?php

namespace App\Controller;

use App\Entity\UserPolicy;
use App\Repository\UserPolicyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserPolicyController extends AbstractController
{
    public function __construct(
        private UserPolicyRepository $repository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/user_policy/{id}', name: 'user_policy_view', methods: ['GET'])]
    public function viewUserPolicy($id, SerializerInterface $serializer): Response
    {
        $entity = $this->repository->find($id);
        $json_values = $serializer->serialize($entity, 'json');
        return new Response($json_values);
    }

    #[Route('/user_policy/{id}/delete', name: 'user_policy_delete', methods: ['DELETE'])]
    public function deleteUserPolicy($id): JsonResponse
    {
        $entity = $this->repository->find($id);
        $this->repository->remove($entity);
        $this->entityManager->flush();
        return $this->json(['message' => "User Policy ({$id}) has been deleted"]);
    }

    #[Route('/user_policy/{id}/update', name: 'user_policy_update', methods: ['PUT'])]
    public function updateUserPolicy(Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent());
        $entity = $this->repository->find($id);
        if (isset($data->uid)) {
            $entity->setUid($data->uid);
        }
        if (isset($data->pid)) {
            $entity->setPid($data->pid);
        }
        $this->repository->save($entity, TRUE);
        return $this->json(['message' => "User Policy ({$id}) has been updated"]);
    }

    #[Route('/user_policy', name: 'user_policy_create', methods: ['POST'])]
    public function createUserPolicy(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $entity = new UserPolicy();
        $entity->setUid($data->uid);
        $entity->setPid($data->pid);
        $this->repository->save($entity, TRUE);

        return $this->json(['message' => 'The User Policy has been inserted.']);
    }
}
