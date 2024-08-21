<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

include_once dirname(__FILE__) . '/components/startup.php';
include_once dirname(__FILE__) . '/components/application.php';
include_once dirname(__FILE__) . '/authorization.php';
include_once dirname(__FILE__) . '/components/page/home_page.php';
include_once dirname(__FILE__) . '/components/error_utils.php';

SetUpUserAuthorization();

// Verificar si el usuario tiene permisos para acceder a la página "index"
$permissions = GetCurrentUserPermissionsForPage("index");

if (!$permissions) {
    // Si el usuario no tiene permisos (es decir, no está autenticado), redirigir al login.php
    header("Location: login.php");
    exit();
}

try {
    $page = new HomePage($permissions, 'UTF-8');
    $renderer = new ViewRenderer($page->GetLocalizerCaptions());
    echo $renderer->Render($page);

} catch(Exception $e) {
    ShowErrorPage($e);
}
