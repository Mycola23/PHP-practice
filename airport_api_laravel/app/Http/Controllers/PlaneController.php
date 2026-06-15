<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlaneController extends Controller
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
    public function index(): JsonResponse
    {
        return response()->json([
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
    public function create(Request $request): JsonResponse
    {
        try {
            // Laravel автоматично розпізнає JSON тіло
            $data = $request->json()->all();

            if (empty($data)) {
                return response()->json(['error' => 'Invalid JSON format'], Response::HTTP_BAD_REQUEST);
            }

            if (empty($data['model']) || empty($data['airline'])) {
                return response()->json([
                    'error' => 'Validation failed',
                    'details' => 'Fields "model" and "airline" are required'
                ], Response::HTTP_BAD_REQUEST);
            }

            $newPlane = [
                'id' => count($this->planes) + 1,
                'model' => $data['model'],
                'airline' => $data['airline']
            ];

            return response()->json($newPlane, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route: GET /api/planes/{id}
     * @param int $id The unique identifier of the plane
     * @return JsonResponse Plane data or resource not found message
     * @status 200 OK - if resource is found
     * @status 404 Not Found - if plane with given ID does not exist
     */
    public function show(int $id): JsonResponse
    {
        $plane = $this->findPlaneById($id);

        if (!$plane) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => "Plane with ID $id does not exist"
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($plane);
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
    public function update(int $id, Request $request): JsonResponse
    {
        $plane = $this->findPlaneById($id);

        if (!$plane) {
            return response()->json(['error' => 'Plane not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->json()->all();

        if (empty($data)) {
            return response()->json(['error' => 'No data provided'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
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
    public function delete(int $id): JsonResponse
    {
        $plane = $this->findPlaneById($id);

        if (!$plane) {
            return response()->json(['error' => 'Plane not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
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
