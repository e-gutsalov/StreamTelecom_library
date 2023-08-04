<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\Library;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route('/api/all/books', name: 'all_books')]
    public function allBooks(Library $library): JsonResponse
    {
        $response = new JsonResponse();

        try {
            $result = $library->allBooks();
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');

        } catch (Exception $e) {
            $response->setContent(json_encode(['error' => $e->getMessage()]));
        }

        return $response;
    }

    #[Route('/api/search/book', name: 'search_books')]
    public function searchBookByAuthor(
        Request $request,
        Library $library
    ): JsonResponse
    {
        $response = new JsonResponse();

        try {
            $data = $request->toArray();
            $result = $library->searchBookByAuthor($data);
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');

        } catch (Exception $e) {
            $response->setContent(json_encode(['error' => $e->getMessage()]));
        }

        return $response;
    }

    #[Route('/api/add/book', name: 'add_books')]
    public function addBook(
        Request $request,
        Library $library
    ): JsonResponse
    {
        $response = new JsonResponse();

        try {
            $data = $request->toArray();
            $result = $library->addBook($data);
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');

        } catch (Exception $e) {
            $response->setContent(json_encode(['error' => $e->getMessage()]));
        }

        return $response;
    }

    #[Route('/api/give/book', name: 'give_book')]
    public function giveBookReader(
        Request $request,
        Library $library
    ): JsonResponse
    {
        $response = new JsonResponse();

        try {
            $data = $request->toArray();
            $result = $library->giveBook($data);
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');

        } catch (Exception $e) {
            $response->setContent(json_encode(['error' => $e->getMessage()]));
        }

        return $response;
    }

    #[Route('/api/discard/book', name: 'discard_book')]
    public function discardBook(
        Request $request,
        Library $library
    ): JsonResponse
    {
        $response = new JsonResponse();

        try {
            $data = $request->toArray();
            $result = $library->discardBook($data);
            $response->setContent(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');

        } catch (Exception $e) {
            $response->setContent(json_encode(['error' => $e->getMessage()]));
        }

        return $response;
    }
}
