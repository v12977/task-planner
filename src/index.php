<?php
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task-name'])) {
        addTask($_POST['task-name']);
    } elseif (isset($_POST['toggle-id'])) {
        $completed = isset($_POST['toggle-completed']);
        markTaskAsCompleted($_POST['toggle-id'], $completed);
    } elseif (isset($_POST['delete-id'])) {
        deleteTask($_POST['delete-id']);
    } elseif (isset($_POST['email'])) {
        subscribeEmail($_POST['email']);
    }
}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Task Planner</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Task Planner</h1>

  <!-- Task Form -->
<form method="POST">
    <div class="form-row">
        <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
        <button type="submit" id="add-task">Add Task</button>
    </div>
</form>


  <!-- Task List -->
  <ul class="tasks-list">
    <?php foreach (getAllTasks() as $task): ?>
      <li class="task-item<?= $task['completed'] ? ' completed' : '' ?>">
        <!-- Toggle Task Status -->
        <form method="POST" style="display:inline">
          <input type="hidden" name="toggle-id" value="<?= $task['id'] ?>">
          <!-- <input type="checkbox" class="task-status"
            onchange="this.form.submit()" <?= $task['completed'] ? 'checked' : '' ?>> -->
            <input type="checkbox" class="task-status" name="toggle-completed" onChange="this.form.submit()" <?= $task['completed'] ? 'checked' : '' ?>>
        </form>

        <?= htmlspecialchars($task['name']) ?>

        <!-- Delete Task -->
        <form method="POST" style="display:inline">
          <input type="hidden" name="delete-id" value="<?= $task['id'] ?>">
          <button class="delete-task" type="submit">Delete</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

  <!-- Email Subscription Form -->
  <h2>Subscribe for Reminders</h2>
  <form method="POST">
    <div class="form-row">
        <input type="email" name="email" placeholder="Enter your email" required />
        <button id="submit-email" type="submit">Submit</button>
    </div>
</form>

</body>
</html>
