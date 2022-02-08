<?php

namespace Langivi\ImportantReminder\Response;

use Langivi\ImportantReminder\Loader;

class HtmlResponse extends AbstractResponse
{

    public function prepare(string $value)
    {
        if (null !== ($container = Loader::get()?->getContainer())) {
            $twig = $container->get('twig');
        }

        $this->response->setHeader("Content-Type", "text/html; charset=utf-8");
        return $twig->render($value);
    }

    public function error(array $value)
    {
        $this->send(.....);
    }
}