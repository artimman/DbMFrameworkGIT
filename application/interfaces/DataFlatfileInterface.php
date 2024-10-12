<?php
/*
 * Application: DbM Framework version 2
 * Author: Arthur Malinowski (C) Design by Malina
 * Web page: www.dbm.org.pl
 * License: MIT
*/

declare(strict_types=1);

namespace Dbm\Interfaces;

interface DataFlatfileInterface
{
    public function dataFlatFile(string $type = 'content', string $sign = ''): string;
}
