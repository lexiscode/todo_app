<?php

// Function to load the todo list from JSON file
function loadTodoList($filename) {
    if (file_exists($filename)) {
        $json = file_get_contents($filename);
        $todoList = json_decode($json, true);

    } else {
        $todoList = [];
    }

    return $todoList;
}

// Function to save the todo list to JSON file
function saveTodoList($filename, $todoList) {
    $json = json_encode($todoList); 
    file_put_contents($filename, $json);
}

// Set the filename for the todo list JSON file
$filename = 'todo_list.json';

// Load the todo list from JSON file
$todoList = loadTodoList($filename);

// Check if a new task is submitted and its not empty, then add it to the todo list
if (isset($_POST['addTask'])){
    if (!empty($_POST['task']) && !empty($_POST['due_date'])){
        $task = $_POST['task']; // Get the task
        $dueDate = $_POST['due_date']; // Get the due date

        // Create a new task array with task and due date
        $newTask = ['task' => $task, 'due_date' => $dueDate];

        // Add the new task to the todo list
        $todoList[] = $newTask;

        // Save the updated todo list
        saveTodoList($filename, $todoList);
    }
}

// Check if a task is marked as remove, then delete the task from the list
if (isset($_POST['remove'])) {
    $removeIndex = $_POST['remove'];

    // Remove the marked task from the todo list
    if (isset($todoList[$removeIndex])) { // checks/ensures if there's a value where u wish to delete
        unset($todoList[$removeIndex]); // delete that task

        // Re-index the array
        $todoList = array_values($todoList);

        // Save the updated todo list
        saveTodoList($filename, $todoList);
    }
}


// Check if an updated task is submitted
if (isset($_POST['edit_index']) && isset($_POST['updated_task']) && isset($_POST['updated_due_date'])) {
    $editIndex = $_POST['edit_index']; // Get the index
    $updatedTask = $_POST['updated_task']; // Get the updated task
    $updatedDueDate = $_POST['updated_due_date']; // Get the updated due date

    // Update the task and due date
    if (isset($todoList[$editIndex])) { // checks/ensures if there is a value where u wish to update
        $todoList[$editIndex]['task'] = $updatedTask; // update the task
        $todoList[$editIndex]['due_date'] = $updatedDueDate; // update the due date

        // Save the updated todo list
        saveTodoList($filename, $todoList);
    }
}


// Clear the todo list and delete file content
if (isset($_POST['clearLists'])) {
    $todoList = [];
    saveTodoList($filename, $todoList);
}

// Numbering the ToDo list
function taskNumber(){
    static $a = 1;
    echo $a;
    $a++;
}


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

        <div class="container text-center">
            <div class="row">
                <!-- GRID 1 -->
                <div class="col">
                    
                    <!-- Button trigger modal -->
                    <div align="right">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Add To ToDo
                    </button>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="" method="POST" autocomplete="off">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add To ToDo</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <!--First Top Form-->
                            <div class="w-50 m-auto">
                                <label for="task">Task:</label>
                                <input class="form-control" type="text" name="task" id="task" placeholder="Enter Task To Add in Todo">
                                <br>
                                <label for="due_date">Due Date:</label>
                                <input class="form-control" type="date" name="due_date" id="due_date">
                                <br>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="addTask">Submit</button>
                        </div>
                        </div>
                        </form>
                    </div>
                    </div>

                </div>
                <!-- GRID 2 -->
                <div class="col" align="left">
                    
                    <form action="" method="POST">
                        <button type="submit" class="btn btn-secondary" name="clearLists">Clear Lists</button>
                    </form>
                
                </div>
            </div>
        </div>

        
        <br>

        <!--Horizontal line demacation-->
        <hr class="bg-dark w-50 m-auto">

        <!-- Table class="w-50 m-auto"-->
        <div class="w-50 m-auto">
            <h1>Your Lists</h1>

            <table class="table table-dark table-hover">
                <thead align="center">
                    <tr>
                    <th scope="col">S/N</th>
                    <th scope="col">Tasks</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Action</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>

                <?php if (!empty($todoList)) : ?>

                <tbody align="center">
                    <?php foreach ($todoList as $index => $task) : ?>
                        <tr>
                            <td><?php taskNumber()?></td>
                            <td><?php echo $task['task'];; ?></td>
                            <td><?php echo $task['due_date']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="remove" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                            <!-- Update section -->
                            <form action="" method="POST">
                                <input type="hidden" name="edit_index" value="<?php echo $index; ?>">

                                <td>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $index; ?>">
                                    Update
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdrop<?php echo $index; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?php echo $index; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel<?php echo $index; ?>" style="color: black">Update ToDo</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="w-50 m-auto">
                                            <label style="color: black">Update Task:</label> 
                                            <input class="form-control" type="text" name="updated_task" id="updated_task" size="5" placeholder="Update Task" required>
                                            <br>
                                            <label style="color: black">Update Due Date:</label> 
                                            <input class="form-control" type="date" name="updated_due_date" id="updated_due_date" required>
                                            <br>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>


                                </td>
                        
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
