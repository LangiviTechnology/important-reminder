<?php

namespace Langivi\ImportantReminder\Routing;

enum HttpMethods:string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
}