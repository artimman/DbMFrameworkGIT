<?php
/*
 * Application: DbM Framework version 2
 * Author: Arthur Malinowski (C) Design by Malina
 * Web page: www.dbm.org.pl
 * License: MIT
*/

declare(strict_types=1);

namespace Dbm\Interfaces;

interface BaseInterface
{
    public function requestData(string $fieldName): ?string;

    public function setSession(string $sessionName, string $sessionValue): void;

    public function getSession(string $sessionName): ?string;

    public function unsetSession(string $sessionName): void;

    public function destroySession(): void;

    public function setFlash(string $sessionName, string $message): void;

    public function flash(string $sessionName, string $className): void;

    public function flashPanel(string $sessionName, string $className): void;

    public function redirect(string $path, array $params = []): void;

    public function userPermissions(int $id): ?string;
}
