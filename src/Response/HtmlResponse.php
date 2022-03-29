<?php

namespace Langivi\ImportantReminder\Response;

use Error;
use Langivi\ImportantReminder\Loader;

class HtmlResponse extends AbstractResponse
{
    private string $template;

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    public function prepare(array|object $payload)
    {
        if (null === ($container = Loader::get()?->getContainer())) {
            $this->response->setStatusCode(500);
            return 'Error template engine not found';
        }
        $twig = $container->get('twig');
        $this->response->setHeader("Content-Type", "text/html; charset=utf-8");
        return $twig->render($this->template, $payload);
    }

    public function error(array|object $payload)
    {
        $this->template = 'error.twig';
        $this->send(['title' => 'Error', ...$payload]);
    }
}