<?php
define('USERS_FILE', __DIR__ . '/../data/users.json');
define('ITEMS_FILE', __DIR__ . '/../data/items.json');

function loadUsers(): array {
    if (!file_exists(USERS_FILE)) return [];
    $content = file_get_contents(USERS_FILE);
    return json_decode($content, true) ?? [];
}

function saveUsers(array $users): void {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function findUserByEmail(string $email): ?array {
    foreach (loadUsers() as $user) {
        if ($user['email'] === $email) return $user;
    }
    return null;
}

function registerUser(string $name, string $email, string $password): bool {
    if (findUserByEmail($email)) return false;
    $users = loadUsers();
    $users[] = [
        'id'       => uniqid('u_', true),
        'name'     => $name,
        'email'    => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created'  => date('Y-m-d H:i:s'),
    ];
    saveUsers($users);
    return true;
}

function loginUser(string $email, string $password): ?array {
    $user = findUserByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return null;
}

function loadItems(): array {
    if (!file_exists(ITEMS_FILE)) return [];
    $content = file_get_contents(ITEMS_FILE);
    return json_decode($content, true) ?? [];
}

function saveItems(array $items): void {
    file_put_contents(ITEMS_FILE, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function getItemsByUser(string $userId): array {
    return array_values(array_filter(loadItems(), fn($t) => $t['user_id'] === $userId));
}

function addItem(string $userId, array $data): array {
    $items = loadItems();
    $task = [
        'id'          => uniqid('t_', true),
        'user_id'     => $userId,
        'title'       => trim($data['title']),
        'description' => trim($data['description'] ?? ''),
        'category'    => $data['category'] ?? 'Personal',
        'priority'    => $data['priority'] ?? 'medie',
        'due_date'    => $data['due_date'] ?? '',
        'done'        => false,
        'created'     => date('Y-m-d H:i:s'),
    ];
    $items[] = $task;
    saveItems($items);
    return $task;
}

function toggleItem(string $taskId, string $userId): bool {
    $items = loadItems();
    foreach ($items as &$task) {
        if ($task['id'] === $taskId && $task['user_id'] === $userId) {
            $task['done'] = !$task['done'];
            saveItems($items);
            return true;
        }
    }
    return false;
}

function deleteItem(string $taskId, string $userId): bool {
    $items = loadItems();
    $filtered = array_values(array_filter($items, fn($t) => !($t['id'] === $taskId && $t['user_id'] === $userId)));
    if (count($filtered) === count($items)) return false;
    saveItems($filtered);
    return true;
}

function getStats(array $tasks): array {
    $total     = count($tasks);
    $done      = count(array_filter($tasks, fn($t) => $t['done']));
    $active    = $total - $done;
    $expired   = count(array_filter($tasks, function($t) {
        return !$t['done'] && !empty($t['due_date']) && $t['due_date'] < date('Y-m-d');
    }));
    $todayList = array_filter($tasks, fn($t) => !$t['done'] && $t['due_date'] === date('Y-m-d'));
    return compact('total', 'done', 'active', 'expired', 'todayList');
}