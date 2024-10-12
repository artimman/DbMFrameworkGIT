<?php
/*
 * Application: DbM Framework v2.1
 * Author: Arthur Malinowski (Design by Malina)
 * License: MIT
 * Web page: www.dbm.org.pl
 * Contact: biuro@dbm.org.pl
*/

declare(strict_types=1);

namespace Dbm\Classes;

use Dbm\Classes\TemplateEngine;
use Dbm\Interfaces\BaseInterface;
use Dbm\Interfaces\DatabaseInterface;

class BaseController extends TemplateEngine implements BaseInterface
{
    private $database;
    private $remember;

    public function __construct(?DatabaseInterface $database = null)
    {
        $this->database = $database;
        $this->translation = new Translation();

        if (!empty(getenv('DB_NAME'))) {
            $stmt = $this->database->querySql("SHOW TABLES LIKE 'dbm_remember_me'");

            if ($stmt->rowCount() > 0) {
                $this->remember = new RememberMe($this->database, $this);
                $this->remember->checkRememberMe($this);
            }
        }
    }

    // Request data
    public function requestData(string $fieldName): ?string
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        if ($method === "post") {
            if (array_key_exists($fieldName, $_POST)) {
                return trim($_POST[$fieldName]);
            } elseif (array_key_exists($fieldName, $_FILES)) {
                return $_FILES[$fieldName]['name'] ?? null;
            } elseif (array_key_exists($fieldName, $_GET)) {
                return trim($_GET[$fieldName]);
            }
        } elseif ($method === 'get') {
            if (array_key_exists($fieldName, $_GET)) {
                return trim($_GET[$fieldName]);
            }
        }

        return null;
    }

    // Set session
    public function setSession(string $sessionName, string $sessionValue): void
    {
        if (!empty($sessionName) && !empty($sessionValue)) {
            $_SESSION[$sessionName] = $sessionValue;
        }
    }

    // Get session
    public function getSession(string $sessionName): ?string
    {
        if (!empty($_SESSION[$sessionName])) {
            return $_SESSION[$sessionName];
        }

        return null;
    }

    // Unset session
    public function unsetSession(string $sessionName): void
    {
        if (!empty($sessionName)) {
            unset($_SESSION[$sessionName]);
        }
    }

    // Destroy whole sessions
    public function destroySession(): void
    {
        session_unset();
        session_destroy();
    }

    // Set cookie, $expiry = 86400 = 1 day
    public function setCookie(string $cookieName, string $cookieValue, int $expiry = 86400, bool $secure = true, bool $httpOnly = true): void
    {
        if (!empty($cookieName) && !empty($cookieValue)) {
            setcookie($cookieName, $cookieValue, time() + $expiry, '/', '', $secure, $httpOnly);
            $_COOKIE[$cookieName] = $cookieValue;
        }
    }

    // Get cookie
    public function getCookie(string $cookieName): ?string
    {
        if (isset($_COOKIE[$cookieName])) {
            return $_COOKIE[$cookieName];
        }

        return null;
    }

    // Unset cookie
    public function unsetCookie(string $cookieName): void
    {
        if (isset($_COOKIE[$cookieName])) {
            setcookie($cookieName, '', time() - 3600, '/', '', true, true);
            unset($_COOKIE[$cookieName]);
        }
    }

    // Set flash message
    public function setFlash(string $sessionName, string $message): void
    {
        if (!empty($sessionName) && !empty($message)) {
            $_SESSION[$sessionName] = $message;
        }
    }

    // Show flash message
    public function flash(string $sessionName, string $className): void
    {
        if (!empty($sessionName) && !empty($className) && isset($_SESSION[$sessionName])) {
            echo('<div class="container">' . "\n"
                . '    <div class="alert ' . $className . ' alert-dismissible fade show mt-3" role="alert">' . "\n"
                . '        <span>' . $_SESSION[$sessionName] . '</span>' . "\n"
                . '        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' . "\n"
                . '    </div>' . "\n"
                . '</div>' . "\n");

            unset($_SESSION[$sessionName]);
        }
    }

    // Show panel flash message
    public function flashPanel(string $sessionName, string $className): void
    {
        if (!empty($sessionName) && !empty($className) && isset($_SESSION[$sessionName])) {
            echo('    <div class="alert ' . $className . ' alert-dismissible fade show" role="alert">' . "\n"
                . '        <span>' . $_SESSION[$sessionName] . '</span>' . "\n"
                . '        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . "\n"
                . '    </div>' . "\n");

            unset($_SESSION[$sessionName]);
        }
    }

    // Redirect to location
    public function redirect(string $path, array $params = []): void
    {
        if (!empty($params)) {
            $path = $path . '?' . http_build_query($params);
        }

        $dir = dirname($_SERVER['PHP_SELF']);
        $public = '/';

        if (strpos($dir, 'public')) { // for localhost (application in catalog)
            $public = strstr($dir, 'public', true);
        }

        $url = empty($_SERVER['HTTPS']) ? 'http' : 'https';
        $url = $url . '://' . $_SERVER['HTTP_HOST'] . $public . $path;

        header("Location: " . $url);
        exit;
    }

    // Get Database
    public function getDatabase(): DatabaseInterface
    {
        return $this->database;
    }

    // User permissions
    public function userPermissions(int $id): ?string
    {
        $database = $this->database;

        $query = "SELECT roles FROM dbm_user WHERE id = ?";

        $database->queryExecute($query, [$id]);

        if ($database->rowCount() == 0) {
            return null;
        }

        $data = $database->fetchObject();

        return $data->roles;
    }

    // Generowanie tokena CSRF
    public function csrfToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /* TODO! Można dopisać dodatkowe zabezpieczenie dla penelu administracyjnego
    public function isValidSession(int $userId): bool
    {
        // Pobranie rekordu użytkownika z bazy danych (przykład)
        $userSession = $database->getUserSession($userId);

        // Sprawdzenie, czy sesja istnieje i nie wygasła
        if (!$userSession || $userSession['session_expiry'] < time()) {
            return false; // Sesja nie jest ważna (nie istnieje lub wygasła)
        }

        return true; // Sesja jest ważna
    } */
}
