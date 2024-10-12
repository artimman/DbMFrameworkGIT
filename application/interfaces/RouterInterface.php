<?php
/*
 * Application: DbM Framework version 2
 * Author: Arthur Malinowski (C) Design by Malina
 * Web page: www.dbm.org.pl
 * License: MIT
*/

declare(strict_types=1);

namespace Dbm\Interfaces;

interface RouterInterface
{
    public function addRoute(string $route, array $arrayController): void;

    public function dispatch(string $uri): void;
}
