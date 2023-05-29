<?php

// Define the file path
$filePath = 'todo.txt';

// Check if the file exists, and create it if it doesn't
if (!file_exists($filePath)) {
    $file = fopen($filePath, 'w');
    fclose($file);
}

// Function to read the todo list from the file
function readTodoList()
{
    global $filePath;
    $todoList = [];

    $file = fopen($filePath, 'r');
    while (($line = fgets($file)) !== false) {
        $todoList[] = trim($line);
    }
    fclose($file);

    return $todoList;
}

// Function to write the todo list to the file
function writeTodoList($todoList)
{
    global $filePath;

    $file = fopen($filePath, 'w');
    foreach ($todoList as $todo) {
        fwrite($file, $todo . PHP_EOL);
    }
    fclose($file);
}

// Add a task to the todo list
if (isset($_POST['addTask'])) {  // the button is pushed to True
    $task = trim($_POST['task']); // the value of the input is received due to the pushed button

    if (!empty($task)) {
        $todoList = readTodoList(); //bringing out the list from the .txt file
        $todoList[] = $task; // adding to the array list
        writeTodoList($todoList); // taking it back (overwriting/updating) into the .txt file
    }
}

// Remove a task from the todo list
if (isset($_POST['removeTask'])) {  // the button is pushed to True
    $index = $_POST['index']; // the value of the input is received due to the pushed button

    $todoList = readTodoList(); //bringing out the list from the .txt file
    if (isset($todoList[$index])) {
        unset($todoList[$index]); // removing from the array list
        writeTodoList($todoList); // taking it back (overwriting/updating) into the .txt file
    }
}

// Update the todo list
if (isset($_POST['index']) && isset($_POST['updated_task'])) {
    $editIndex = $_POST['index'];
    $updatedTask = $_POST['updated_task'];

    $todoList = readTodoList();

    // Update the task
    if (isset($todoList[$editIndex])) { 
        $todoList[$editIndex] = $updatedTask;

    // Save the updated todo list back to the txt file
    writeTodoList($todoList);
    }
}



// Clear the todo list and delete file content
if (isset($_POST['clearLists'])) {
    $todoList = [];
    writeTodoList($todoList);
}

// Numbering the ToDo list
function taskNumber(){
    static $a = 1;
    echo $a;
    $a++;
}

// Retrieve the current todo list
$todoList = readTodoList(); // its this current list we are viewing in our html page

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TODO-LIST APP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <!--Introduction header-->
        <h1 class="text-center my-4 py-4" style="font-family: Tahoma, Verdana, Segoe, sans-serif">Welcome To My ToDo List</h1>

        <!--First Top Form-->
        <div class="w-50 m-auto">
            <form action="" method="POST" autocomplete="off">
                <label for="title">Task:</label>
                <input class="form-control" type="text" name="task" id="task" placeholder="Enter Task To Add in Todo" required>
                <br>
                <button type="submit" class="btn btn-success" name="addTask">Add To ToDo</button>
                <button type="submit" class="btn btn-secondary" name="clearLists">Clear Lists</button>
            </form>
        </div>
        <br>

        <!--Horizontal line demacation-->
        <hr class="bg-dark w-50 m-auto">

        <!-- Table -->
        <div class="w-50 m-auto">
            <h1>Your Lists</h1>

            <table class="table table-dark table-hover">
                <thead align="center">
                    <tr>
                    <th scope="col">S/N</th>
                    <th scope="col">Tasks</th>
                    <th scope="col">Action</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>

                <?php if (!empty($todoList)) : ?>

                <tbody align="center">
                    <?php foreach ($todoList as $index => $task) : ?>
                        <tr>
                            <td><?php taskNumber()?></td>
                            <td id="toggle"><?php echo $task; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" name="removeTask" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                            <!-- Update section -->
                            <form action="" method="POST">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <td><input class="form-control" type="text" name="updated_task" id="updated_task" size="5" placeholder="Update Task" required></td>
                                <td><button type="submit" class="btn btn-primary btn-sm">Update</button></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

                <?php else : ?>
                    <p>No lists found.</p>
                <?php endif; ?>

            </table>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

  </body>
</html>
