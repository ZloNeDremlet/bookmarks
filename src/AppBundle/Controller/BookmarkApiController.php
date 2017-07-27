<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bookmark;
use AppBundle\Entity\Comment;
use AppBundle\Form\BookmarkType;
use AppBundle\Form\CommentType;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class BookmarkApiController
 * @Rest\NamePrefix("api_bookmarks_")
 * @Rest\Prefix("/v1/bookmarks")
 */
class BookmarkApiController extends FOSRestController
{
    /**
     * Список закладок. Сначала новые.
     *
     *```
     *  Response Example
     *[
     *      {
     *         "id": 6,
     *         "created_at": "2017-07-27T10:35:38+0300",
     *         "url": "111"
     *      },
     *      {
     *         "id": 5,
     *         "created_at": "2017-07-27T10:34:39+0300",
     *         "url": "ddddasdasd"
     *      }
     *]
     *
     * ```
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",     *
     *     404 = "Returned when the page is not found"
     *   },
     *  filters={
     *     {"name"="limit", "dataType"="integer", "description"="Сколько записей выбрать"},
     *  },
     *   section = "Bookmarks"
     * )
     *
     * @throws \Exception
     *
     * @param Request $request
     * @return Response
     *
     * @Rest\Get("")
     */
    public function getBookmarksAction(Request $request): Response
    {
        $bookmarks = $this->get('bookmark.manager')->getLastBookmarks($request->get('limit'));

        $view = $this->view($bookmarks, Response::HTTP_OK);

        return $this->handleView(
            $view->setContext((new Context())->addGroup('bookmark_list'))
        );
    }

    /**
     * Добавить закладку
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
    public function postBookmarkAction(Request $request): Response
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

        if ($bookmark instanceof Bookmark) {
            return new JsonResponse(['id' => $bookmark->getId()], Response::HTTP_CREATED);
        }
        return new JsonResponse(['id' => $bookmark], Response::HTTP_IM_USED);
    }

    /**
     * Получить закладку по url
     *
     * ```
     * Пример ответа
     * {
     *   "id": 3,
     *   "created_at": "2017-07-26T22:45:59+0300",
     *   "url": "dasdas",
     *   "comments": [
     *          {
     *          "id": 1,
     *          "created_at": "2017-12-12T00:00:00+0300",
     *          "ip_address": "10.10.10",
     *          "text": "dasdsad"
     *          }
     *      ]
     * }
     * ```
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   },
     *   section = "Bookmarks"
     * )
     *
     * @ParamConverter("bookmark", class="AppBundle:Bookmark", options={
     *    "repository_method" = "findByUrl",
     *    "mapping": {"url": "url"},
     *    "map_method_signature" = true
     * })
     *
     * @throws \Exception
     *
     * @param Bookmark $bookmark
     * @return Response
     *
     * @Rest\Get("/{url}")
     */
    public function getBookmarkAction(Bookmark $bookmark): Response
    {
        $view = $this->view($bookmark, Response::HTTP_OK);

        return $this->handleView(
            $view->setContext((new Context())->addGroup('bookmark_show'))
        );
    }

    /**
     * Добавить комментарий
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
     *     200 = "Returned when created",
     *     404 = "Returned when the page is not found"
     *   },
     *   section = "Bookmarks"
     * )
     *
     * @throws \Exception
     *
     * @param Request $request
     * @param Bookmark $bookmark
     * @return Response
     *
     * @param Request $request
     *
     * @Rest\Post("/{bookmark}/comment")
     */
    public function postBookmarkCommentAction(Request $request, Bookmark $bookmark): Response
    {
        $comment = new Comment();
        $comment->setBookmark($bookmark);
        $comment->setIpAddress($request->getClientIp());

        $form = $this->createForm(CommentType::class, $comment);
        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            $errorMessage = '';
            foreach ($form->getErrors(true) as $error) {
                $errorMessage .= sprintf('%s, ', $error->getMessage());
            }
            throw new \InvalidArgumentException(sprintf('SV170727-1 [%s]', $errorMessage), Response::HTTP_BAD_REQUEST);
        }
        $comment = $this->get('comment.manager')->create($comment);

        $view = $this->view($comment, Response::HTTP_OK);

        return $this->handleView(
            $view->setContext((new Context())->addGroup('comment_show'))
        );
    }
}
