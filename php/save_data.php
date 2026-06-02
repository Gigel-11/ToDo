<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

requireLogin();
header('Content-Type: application/json');

$user   = getCurrentUser();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        if (empty($_POST['title'])) {
            echo json_encode(['success' => false, 'error' => 'Titlul este obligatoriu.']);
            exit;
        }
        $task = addItem($user['id'], [
            'title'       => $_POST['title'],
            'description' => $_POST['description'] ?? '',
            'category'    => $_POST['category']    ?? 'Personal',
            'priority'    => $_POST['priority']    ?? 'medie',
            'due_date'    => $_POST['due_date']    ?? '',
        ]);
        echo json_encode(['success' => true, 'task' => $task]);
        break;

    case 'toggle':
        $ok = toggleItem($_POST['id'] ?? '', $user['id']);
        echo json_encode(['success' => $ok]);
        break;

    case 'delete':
        $ok = deleteItem($_POST['id'] ?? '', $user['id']);
        echo json_encode(['success' => $ok]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acțiune necunoscută.']);
}