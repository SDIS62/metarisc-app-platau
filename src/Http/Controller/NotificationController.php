<?php

namespace App\Http\Controller;

use Twig\Environment;
use Metarisc\Metarisc;
use Laminas\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotificationController
{
    public function __construct(
        private Metarisc $metarisc,
        private Environment $twig,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args = []) : ResponseInterface
    {
        $template = $this->twig->load('notifications.twig');

        $notificationsPager = $this->metarisc->notifications->paginateNotifications();
        $notifications      = $notificationsPager->getCurrentPageResults();

        $html = $template->render([
            'notifications' => $notifications,
        ]);

        $responseFactory = new ResponseFactory();
        $response        = $responseFactory->createResponse();

        $response->getBody()->write($html);

        return $response;
    }
}
