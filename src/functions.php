<?php

function addTask($task_name) {
    $task_name = trim($task_name);
    if (!$task_name) return;

    $file = 'tasks.txt';
    $tasks = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    foreach ($tasks as $task) {
        if (strtolower($task['name']) === strtolower($task_name)) return;
    }

    $tasks[] = [
        "id" => uniqid(),
        "name" => $task_name,
        "completed" => false
    ];

    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
}

function getAllTasks() {
    $file = 'tasks.txt';
    return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
}

function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = getAllTasks();
    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['completed'] = $is_completed;
            break;
        }
    }
    file_put_contents('tasks.txt', json_encode($tasks, JSON_PRETTY_PRINT));
}

function deleteTask($task_id) {
    $tasks = getAllTasks();
    $tasks = array_filter($tasks, fn($task) => $task['id'] !== $task_id);
    file_put_contents('tasks.txt', json_encode(array_values($tasks), JSON_PRETTY_PRINT));
}

function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function subscribeEmail($email) {
    $email = strtolower(trim($email));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return;

    $pendingFile = 'pending_subscriptions.txt';
    $subscribersFile = 'subscribers.txt';
    $subscribers = file_exists('subscribers.txt') ? json_decode(file_get_contents('subscribers.txt'), true) : [];
    if (!is_array($subscribers)) $subscribers = [];
    if (in_array($email, $subscribers)) return;

    $pending = file_exists($pendingFile) ? json_decode(file_get_contents($pendingFile), true) : [];

    $code = generateVerificationCode();
    $pending[$email] = ["code" => $code, "timestamp" => time()];
    file_put_contents($pendingFile, json_encode($pending, JSON_PRETTY_PRINT));

    $link = "http://localhost:8000/verify.php?email=" . urlencode($email) . "&code=" . $code;
    $subject = "Verify subscription to Task Planner";
    $headers = "From: no-reply@example.com\r\nContent-Type: text/html\r\n";

    $body = "<p>Click the link below to verify your subscription to Task Planner:</p>
         <p><a id='verification-link' href='" . htmlspecialchars($link) . "'>Verify Subscription</a></p>";


    ini_set('SMTP', 'localhost');
    ini_set('smtp_port', 1025);
    mail($email, $subject, $body, $headers);

}


function verifySubscription($email, $code) {
    $email = strtolower(trim($email)); // normalize input
    $pending_file = __DIR__ . '/pending_subscriptions.txt';
    $subscribers_file = __DIR__ . '/subscribers.txt';

    // Check if pending file exists
    if (!file_exists($pending_file)) return false;

    // Read pending subscriptions
    $pending = json_decode(file_get_contents($pending_file), true);
    if (!is_array($pending)) return false;

    // Validate email in pending list
    if (!isset($pending[$email])) return false;

    // Check code match
    if ($pending[$email]['code'] !== $code) return false;

    // Load current subscribers
    $subscribers = file_exists($subscribers_file)
        ? json_decode(file_get_contents($subscribers_file), true)
        : [];

    if (!is_array($subscribers)) $subscribers = [];

    // Add new subscriber if not already present
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        file_put_contents($subscribers_file, json_encode($subscribers, JSON_PRETTY_PRINT));
    }

    // Remove from pending
    unset($pending[$email]);
    file_put_contents($pending_file, json_encode($pending, JSON_PRETTY_PRINT));

    return true;
}



function unsubscribeEmail($email) {
    $email = strtolower(trim($email));
    $subscribersFile = 'subscribers.txt';
    $subscribers = file_exists($subscribersFile) ? json_decode(file_get_contents($subscribersFile), true) : [];

    $subscribers = array_filter($subscribers, fn($e) => $e !== $email);
    file_put_contents($subscribersFile, json_encode(array_values($subscribers), JSON_PRETTY_PRINT));
}

function sendTaskReminders() {
    $subscribers = file_exists('subscribers.txt') ? json_decode(file_get_contents('subscribers.txt'), true) : [];
    $tasks = getAllTasks();
    $pending_tasks = array_filter($tasks, fn($task) => !$task['completed']);

    foreach ($subscribers as $email) {
        sendTaskEmail($email, $pending_tasks);
    }
}

function sendTaskEmail($email, $pending_tasks) {
    $subject = "Task Planner - Pending Tasks Reminder";
    $headers = "From: no-reply@example.com\r\nContent-Type: text/html\r\n";

    $task_list_html = "<ul>";
    foreach ($pending_tasks as $task) {
        $task_list_html .= "<li>" . htmlspecialchars($task['name']) . "</li>";
    }
    $task_list_html .= "</ul>";

    $unsubscribe_link = "http://yourdomain.com/src/unsubscribe.php?email=" . urlencode($email);

    $body = "<h2>Pending Tasks Reminder</h2>
             <p>Here are the current pending tasks:</p>
             $task_list_html
             <p><a id='unsubscribe-link' href='$unsubscribe_link'>Unsubscribe from notifications</a></p>";

    mail($email, $subject, $body, $headers);
}
