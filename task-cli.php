<?php
const FILE_NAME = 'tasks.json';
function loadTasks() {
    if(!file_exists(FILE_NAME)) {
        return[];
    }
    $json = file_get_contents(FILE_NAME);
    return json_decode($json, true) ?? [];

}
function saveTasks($tasks) {
    file_put_contents(FILE_NAME, json_encode($tasks, JSON_PRETTY_PRINT));
}

function addTasks($description) {
    $tasks = loadTasks();
    $id = count($tasks) + 1;
    $tasks[] =[
        'id' => $id,
        'description' => $description,
        'status' => 'todo',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    saveTasks($tasks);
    echo "Task added successfully (ID: $id)\n";
    }

    function updateTask($id, $description) {
        $tasks = loadTasks();
        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['description'] = $description;
                $task['updatedAt'] = date('Y-m-d H:i:s');
                saveTasks($tasks);
                echo "Task updated successfully (ID: $id)\n";
                return;
            }
        }
        echo "Task not found (ID: $id)\n";
    }

    function deleteTask($id) {
        $tasks = loadTasks();
        foreach($tasks as $key => $task) {
            if($task['id'] == $id) {
                unset($tasks[$key]);
                saveTasks(array_values($tasks));
                echo "Task deleted succesfully (ID: $id)\n";
                return;
            }
        }
        echo "Task not found (ID: $id)\n";
    }

    function markTask($id, $status) {
        $tasks = loadTasks();
        foreach($tasks as &$task) {
            if($tasks['id'] == $id) {
                $task['status'] = $status;
                $task['updated_at'] = date ('Y-m-d H:i:s');
                saveTasks($tasks);
                echo "Task marked as $status (ID: $id)\n";
                return;
            }
        }
        echo "Task not found (ID: $id)\n";
    }

    function listTasks($filter = null) {
        $tasks = loadTasks();
        foreach($tasks as $task) {
            if($filter === null || $task['status'] === $filter) {
                echo "{$task['id']} - {$task['description']} - {$task['status']} - {$task['created_at']} - {$task['updated_at']}\n";

            }

        
        }
    }


    if ($argc < 2) {
        echo "Usage: php task-cli.php [command] [options]\n";
        exit(1);
    }

    $command = $argv[1];

switch ($command) {
    case 'add':
        if (isset($argv[2])) {
            addTasks($argv[2]);
        } else {
            echo "Please provide a task description.\n";
        }
        break;
    case 'update':
        if (isset($argv[2]) && isset($argv[3])) {
            updateTask((int)$argv[2], $argv[3]);
        } else {
            echo "Please provide a task ID and description.\n";
        }
        break;
    case 'delete':
        if (isset($argv[2])) {
            deleteTask((int)$argv[2]);
        } else {
            echo "Please provide a task ID.\n";
        }
        break;
    case 'mark-in-progress':
        if (isset($argv[2])) {
            markTask((int)$argv[2], 'in-progress');
        } else {
            echo "Please provide a task ID.\n";
        }
        break;
    case 'mark-done':
        if (isset($argv[2])) {
            markTask((int)$argv[2], 'done');
        } else {
            echo "Please provide a task ID.\n";
        }
        break;
    case 'list':
        if (isset($argv[2])) {
            listTasks($argv[2]);
        } else {
            listTasks();
        }
        break;
    default:
        echo "Unknown command: $command\n";
        break;
}
