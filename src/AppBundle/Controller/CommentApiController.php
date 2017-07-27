<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentApiController extends FOSRestController
{
    /**
     *  POST /api/bookmarks
     *
     * Добавить комментарий к закладке
     *
     * ```
     * Пример данных на создание
     * {
     *  "url" : "http://google.com"
     * }
     *
     * Пример данных ответа:
     * {
     *   "id": 6
     * }
     *
     *```
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when created",
     *     226 = "Returned when already exist",
     *     404 = "Returned when the page is not found"
     *   },
     *   section = "Bookmarks"
     * )
     *
     * @throws \Exception
     *
     * @param Request $request
     * @return Response
     *
     * @param Request $request
     *
     * @Rest\Post("")
     */
    public function postBookmarkAction(Request $request) : Response
    {
        $bookmark = new Bookmark();
        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            $errorMessage = '';
            foreach ($form->getErrors(true) as $error) {
                $errorMessage .= sprintf('%s, ', $error->getMessage());
            }
            throw new \InvalidArgumentException(sprintf('SV170727-0 [%s]', $errorMessage), Response::HTTP_BAD_REQUEST);
        }

        $bookmark = $this->get('bookmark.manager')->create($bookmark);

        if($bookmark instanceof Bookmark){
            return new JsonResponse(['id' => $bookmark->getId()], Response::HTTP_CREATED);
        }
        return new JsonResponse(['id' => $bookmark], Response::HTTP_CREATED);
    }
}