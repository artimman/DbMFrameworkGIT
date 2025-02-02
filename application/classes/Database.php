<?php
/*
 * Application: DbM Framework version 2
 * Author: Arthur Malinowski (C) Design by Malina
 * Web page: www.dbm.org.pl
 * License: MIT
*/

declare(strict_types=1);

namespace Dbm\Classes;

use Dbm\Classes\ExceptionHandler;
use Dbm\Interfaces\DatabaseInterface;
use PDO;
use PDOException;
use PDOStatement;

class Database implements DatabaseInterface
{
    private $connect;
    private $statement;

    public function __construct()
    {
        $dbHost = getenv('DB_HOST');
        $dbName = getenv('DB_NAME');
        $dbUser = getenv('DB_USER');
        $dbPassword = getenv('DB_PASSWORD');
        $dbCharset = getenv('DB_CHARSET') ?: 'utf8mb4';

        try {
            $dbDSN = "mysql:host=" . $dbHost . ";dbname=" . $dbName;
            $dbOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $dbCharset,
            ];

            $this->connect = new PDO($dbDSN, $dbUser, $dbPassword, $dbOptions);
        } catch (PDOException $exception) {
            throw new ExceptionHandler($exception->getMessage(), $exception->getCode());
        }
    }

    public function querySql(string $query, string $fetch = 'assoc'): PDOStatement
    {
        try {
            if ($fetch == 'assoc') {
                return $this->connect->query($query, PDO::FETCH_ASSOC);
            }

            return $this->connect->query($query);
        } catch (PDOException $exception) {
            throw new ExceptionHandler($exception->getMessage(), $exception->errorInfo[1]);
        }
    }

    public function queryExecute(string $query, ?array $params = [], bool $reference = false): bool
    {
        try {
            $this->statement = $this->connect->prepare($query);

            if (empty($params)) {
                return $this->statement->execute();
            }

            $first = array_key_first($params);

            if (!is_string($first)) {
                return $this->statement->execute($params);
            }

            foreach ($params as $key => &$value) {
                if (is_int($value)) {
                    $type = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $type = PDO::PARAM_BOOL;
                } elseif (is_null($value)) {
                    $type = PDO::PARAM_NULL;
                } else {
                    $type = PDO::PARAM_STR;
                }

                if ($type) {
                    if (!$reference) {
                        // TODO! Check insert, update null item!? $this->statement->bindValue(':' . $key, $value, $type);
                        $this->statement->bindValue($key, $value, $type);
                    } else {
                        // TODO! $this->statement->bindParam(':' . $key, $value, $type);
                        $this->statement->bindParam($key, $value, $type);
                    }
                } else {
                    throw new ExceptionHandler('No param type for bindValue() in queryExecute().', 400);
                }
            }

            return $this->statement->execute();
        } catch (PDOException $exception) {
            throw new ExceptionHandler($exception->getMessage(), $exception->errorInfo[1]);
        }
    }

    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    public function fetch(string $fetch = 'assoc'): array
    {
        if ($fetch == 'assoc') {
            return $this->statement->fetch(PDO::FETCH_ASSOC);
        }

        return $this->statement->fetch();
    }

    public function fetchAll(string $fetch = 'assoc'): array
    {
        if ($fetch == 'assoc') {
            return $this->statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $this->statement->fetchAll();
    }

    public function fetchObject(): object
    {
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    public function fetchAllObject(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function debugDumpParams(): ?string
    {
        return $this->statement->debugDumpParams();
    }

    public function getLastInsertId(): ?string
    {
        return $this->connect->lastInsertId();
    }

    public function beginTransaction(): void
    {
        $this->connect->beginTransaction();
    }

    public function commit(): void
    {
        $this->connect->commit();
    }

    public function rollback(): void
    {
        $this->connect->rollBack();
    }

    /**
     * Method for building an INSERT Query
     *
     * How to use - full query with optional parameters
     * [$filteredQuery, $filteredData] = $this->database->buildInsertQuery($data, 'dbm_invoice');
     * $this->database->queryExecute($filteredQuery, $filteredData);
     * or basic usage
     * [$columns, $placeholders, $filteredData] = $this->database->buildInsertQuery($data);
     * $filteredQuery = "INSERT INTO table_name ($columns) VALUES ($placeholders)";
     * $this->database->queryExecute($filteredQuery, $filteredData);
     */
    public function buildInsertQuery(array $data, ?string $table = null): array
    {
        $filteredData = array_filter($data, function ($value) {
            return !is_null($value);
        });

        $columns = implode(", ", array_keys($filteredData));
        $placeholders = ':' . implode(", :", array_keys($filteredData));

        // Jeśli podano $table, budujemy pełne zapytanie
        if ($table) {
            $filteredQuery = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            return [$filteredQuery, $filteredData];
        }

        // Jeśli nie podano $table, zwracamy tylko kolumny i wartości
        return [$columns, $placeholders, $filteredData];
    }

    /**
     * Method for building an UPDATE Query
     *
     * How to use - full query with optional parameters
     * [$filteredQuery, $filteredData] = $this->database->buildUpdateQuery($data, 'dbm_invoice', 'id=:id');
     * $this->database->queryExecute($filteredQuery, $filteredData);
     * or basic usage
     * [$setClause, $filteredData] = $this->database->buildUpdateQuery($data);
     * $filteredQuery = "UPDATE table_name SET $setClause WHERE id=:id";
     * $this->database->queryExecute($filteredQuery, $filteredData);
     */
    public function buildUpdateQuery(array $data, ?string $table = null, ?string $condition = null): array
    {
        $filteredData = array_filter($data, function ($value) {
            return !is_null($value);
        });

        $setClause = implode(", ", array_map(function ($key) {
            return "$key=:$key";
        }, array_keys($filteredData)));

        // Jeśli podano $table, budujemy pełne zapytanie
        if ($table) {
            $filteredQuery = "UPDATE $table SET $setClause";

            // Dodajemy warunek WHERE, jeśli podano $condition
            if ($condition) {
                $filteredQuery .= " WHERE $condition";
            }

            return [$filteredQuery, $filteredData];
        }

        // Jeśli nie podano $table, zwracamy tylko część `SET`
        return [$setClause, $filteredData];
    }
}
