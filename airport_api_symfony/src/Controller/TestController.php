<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class TestController extends AbstractController
{
    private array $planes = [
        ['id' => 1, 'model' => 'Boeing 747', 'airline' => 'Ukraine International'],
        ['id' => 2, 'model' => 'Airbus A320', 'airline' => 'Wizz Air'],
        ['id' => 3, 'model' => 'Antonov 225', 'airline' => 'Antonov Dream Airlines'],
        ['id' => 4, 'model' => 'Antonov 124', 'airline' => 'Antonov Dream Airlines'],
    ];

    /**
     * @Route: GET /api/planes
     * @return JsonResponse Returns an array of all planes.
     * @status 200 OK
     */
    #[Route('/planes', name: 'planes_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'data' => $this->planes
        ], Response::HTTP_OK);
    }

    /**
     * @Route: POST /api/planes
     * @param Request $request JSON body: {"model": "string", "airline": "string"}
     * @return JsonResponse Returns created object or validation error
     * @status 201 Created  successful creation
     * @status 400 Bad Request invalid JSON or missing required fields
     * @status 500 Internal Server Error  system error
     */
    #[Route('/planes', name: 'planes_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json(['error' => 'Invalid JSON format'], Response::HTTP_BAD_REQUEST);
            }

            if (empty($data['model']) || empty($data['airline'])) {
                return $this->json([
                    'error' => 'Validation failed',
                    'details' => 'Fields "model" and "airline" are required'
                ], Response::HTTP_BAD_REQUEST);
            }

            $newPlane = [
                'id' => count($this->planes) + 1,
                'model' => $data['model'],
                'airline' => $data['airline']
            ];

            return $this->json($newPlane, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**

     * @Route: GET /api/planes/{id}
     * @param int $id The unique identifier of the plane
     * @return JsonResponse Plane data or resource not found message
     * @status 200 OK - if resource is found
     * @status 404 Not Found - if plane with given ID does not exist
     */
    #[Route('/planes/{id}', name: 'planes_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $plane = $this->findPlaneById($id);

        if (!$plane) {
            return $this->json([
                'error' => 'Resource not found',
                'message' => "Plane with ID $id does not exist"
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($plane);
    }

    /**
     * @Route: PUT/PATCH /api/planes/{id}
     * @param int $id Plane ID to update.
     * @param Request $request JSON body with fields to be updated
     * @return JsonResponse Success message and updated data
     * @status 200 OK - successful update
     * @status 400 Bad Request  if no data is provided
     * @status 404 Not Found if plane does not exist
     */
    #[Route('/planes/{id}', name: 'planes_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $plane = $this->findPlaneById($id);

        if (!$plane) {
            return $this->json(['error' => 'Plane not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'No data provided'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => "Plane $id updated successfully",
            'data' => array_merge($plane, $data)
        ], Response::HTTP_OK);
    }

    /**
     * @Route: DELETE /api/planes/{id}
     * @param int $id Plane ID to delete
     * @return JsonResponse Success or error message
     * @status 200 OK  record deleted successfully
     * @status 404 Not Found  record not found
     */
    #[Route('/planes/{id}', name: 'planes_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $plane = $this->findPlaneById($id);

        if (!$plane) {
            return $this->json(['error' => 'Plane not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'message' => "Plane $id has been deleted"
        ], Response::HTTP_OK);
    }

    private function findPlaneById(int $id): ?array
    {
        foreach ($this->planes as $plane) {
            if ($plane['id'] === $id) {
                return $plane;
            }
        }
        return null;
    }
}
