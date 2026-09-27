<?php

require_once 'controllers/StudentController2.php';

$controller = new StudentController2();

$action = $_GET['action'] ?? 'index';

switch ($action) {

    case 'create':
        $controller->create();
        break;

    case 'edit':
        $controller->edit($_GET['id']);
        break;

    case 'delete':
        $controller->delete($_GET['id']);
        break;

    default:
        $controller->index();
        break;
}

?>