<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $repository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/user/{uid}', name: 'user_view', methods: ['GET'])]
    public function viewUser($uid, SerializerInterface $serializer): Response
    {
        $entity = $this->repository->find($uid);
        $json_values = $serializer->serialize($entity, 'json');
        return new Response($json_values);
    }

    #[Route('/user/{id}/delete', name: 'user_delete', methods: ['DELETE'])]
    public function deleteUser($id): JsonResponse
    {
        $entity = $this->repository->find($id);
        $this->repository->remove($entity);
        $this->entityManager->flush();
        return $this->json(['message' => "User ({$id}) has been deleted"]);
    }

    #[Route('/user/{id}/update', name: 'user_update', methods: ['PUT'])]
    public function updateUser(Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent());
        $entity = $this->repository->find($id);
        if (isset($data->username)) {
            $entity->setUsername($data->username);
        }
        if (isset($data->firstName)) {
            $entity->setFirstName($data->firstName);
        }
        if (isset($data->lastName)) {
            $entity->setLastName($data->lastName);
        }
        if (isset($data->phone)) {
            $entity->setPhone($data->phone);
        }
        if (isset($data->age)) {
            $entity->setAge($data->age);
        }
        $this->repository->save($entity, TRUE);
        return $this->json(['message' => "User ({$id}) has been updated"]);
    }

    #[Route('/user', name: 'user_create', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $entity = new User();
        $entity->setUsername($data->username);
        $entity->setFirstName($data->firstName);
        $entity->setLastName($data->lastName);
        $entity->setPhone($data->phone);
        $entity->setAge($data->age);
        $this->repository->save($entity, TRUE);

        return $this->json(['message' => 'The User has been inserted.']);
    }
}
