<?php
/*
 * Application: DbM Framework version 2
 * Author: Arthur Malinowski (C) Design by Malina
 * Web page: www.dbm.org.pl
 * License: MIT
*/

declare(strict_types=1);

namespace Dbm\Interfaces;

use PDOStatement;

interface DatabaseInterface
{
    public function querySql(string $query, string $fetch = 'assoc'): PDOStatement;

    public function queryExecute(string $query, ?array $params = [], bool $reference = false): bool;

    public function rowCount(): int;

    public function fetch(string $fetch = 'assoc'): array;

    public function fetchAll(string $fetch = 'assoc'): array;

    public function fetchObject(): object;

    public function fetchAllObject(): array;

    public function debugDumpParams(): ?string;

    public function getLastInsertId(): ?string;

    public function buildInsertQuery(array $data): array;

    public function buildUpdateQuery(array $data): array;
}
