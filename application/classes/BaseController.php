<?php
/*
 * Application: DbM Framework v2.1
 * Author: Arthur Malinowsky (Design by Malina)
 * License: MIT
 * Web page: www.dbm.org.pl
 * Contact: biuro@dbm.org.pl
*/

declare(strict_types=1);

namespace Dbm\Classes;

use Dbm\Classes\ExceptionHandler;

class BaseController
{
    private const PATH_VIEW = BASE_DIRECTORY . 'templates/';
    private const FILE_BASE = 'base.phtml';
    private const FILE_BASE_PANEL = 'base_panel.phtml';
    private const FILE_BASE_OFFER = 'base_offer.phtml';

    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    protected function render(string $fileName, array $data = [])
    {
        extract($data);

        $pathBasename = self::PATH_VIEW . self::FILE_BASE;
        $dirname = explode('/', $fileName);

        if ($dirname[0] == 'panel') {
            $pathBasename = self::PATH_VIEW . self::FILE_BASE_PANEL;
        } elseif (!empty($dirname[1]) && substr($dirname[1], 0, strpos($dirname[1], '.')) == 'offer') {
            $pathBasename = self::PATH_VIEW . self::FILE_BASE_OFFER;
        }

        $pathViewName = self::PATH_VIEW . $fileName;
        $pathHeadInc = self::PATH_VIEW . '_include/head_' . basename($fileName);
        $pathBodyInc = self::PATH_VIEW . '_include/body_' . basename($fileName);

        if (file_exists($pathBasename)) {
            if (!file_exists($pathViewName)) {
                throw new ExceptionHandler('View file ' . $pathViewName . ' is required. File not found!', 404);
            }

            if (!file_exists($pathHeadInc)) {
                $pathHeadInc = null; // used in the template
            }

            if (!file_exists($pathBodyInc)) {
                $pathBodyInc = null; // used in the template
            }

            // Include base template width content page
            include($pathBasename);
        } else {
            throw new ExceptionHandler('Base file ' . $pathBasename . ' is required. File not found!', 404);
        }
    }

    // Request data
    public function requestData(string $fieldName)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == 'post') {
            if (array_key_exists($fieldName, $_POST)) {
                return trim($_POST[$fieldName]);
            } elseif (array_key_exists($fieldName, $_GET)) {
                return trim($_GET[$fieldName]);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'get') {
            if (array_key_exists($fieldName, $_GET)) {
                return trim($_GET[$fieldName]);
            }
        }
    }

    // Set session
    public function setSession(string $sessionName, string $sessionValue): void
    {
        if (!empty($sessionName) && !empty($sessionValue)) {
            $_SESSION[$sessionName] = $sessionValue;
        }
    }

    // Get session
    public function getSession(string $sessionName)
    {
        if (isset($_SESSION['dbmUserId']) && !empty($sessionName)) {
            return $_SESSION[$sessionName];
        }
    }

    // Unset session
    public function unsetSession(string $sessionName)
    {
        if (!empty($sessionName)) {
            unset($_SESSION[$sessionName]);
        }
    }

    // Destroy whole sessions
    public function destroySession()
    {
        session_destroy();
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

        if (strpos($dir, 'public')) { // for localhost (application in catalog)
            $public = substr($dir, 0, strpos($dir, 'public'));
        } else {
            $public = '/';
        }

        $url = empty($_SERVER['HTTPS']) ? 'http' : 'https';
        $url = $url . '://' . $_SERVER['HTTP_HOST'] . $public . $path;

        header("Location: " . $url);
    }

    // User permissions
    public function userPermissions(int $id): ?string
    {
        //$database = $this->database; // TODO!
        $database = new Database();

        $query = "SELECT roles FROM dbm_user WHERE id = ?"; // TODO! Jak to jest z :id lub znakiem zapytania, czy tak samo jest bezpieczne?

        $database->queryExecute($query, [$id]);

        if ($database->rowCount() == 0) {
            return null;
        }

        $data = $database->fetchObject();

        return $data->roles;
    }
}
