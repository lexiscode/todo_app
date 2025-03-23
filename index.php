<?php

// Function to load the todo list from JSON file
function loadRecordList($filename) {
    if (file_exists($filename)) {
        $json = file_get_contents($filename);
        $recordList = json_decode($json, true);

    } else {
        $recordList = [];
    }

    return $recordList;
}

// Function to save the todo list to JSON file
function saveRecordList($filename, $recordList) {
    $json = json_encode($recordList); 
    file_put_contents($filename, $json);
}

// Set the filename for the todo list JSON file
$filename = 'record_list.json';

// Load th from JSON file
$recordList = loadRecordList($filename);

// Check if a new task is submitted and its not empty, then add it to the 
if (isset($_POST['save'])){
    if (!empty($_POST['level']) && !empty($_POST['course'])){
        $level = $_POST['level']; 
        $course = $_POST['course']; 

        // Create a new task array 
        $newRecord = ['level' => $level, 'course' => $course];

        // Add the new task to the 
        $recordList[] = $newRecord;

        // Save the updated
        saveRecordList($filename, $recordList);
    }
}

// Check if an updated task is submitted
if (isset($_POST['edit_index']) && isset($_POST['updateLevel']) && isset($_POST['updateCourse'])) {
    $editIndex = $_POST['edit_index']; // Get the index
    $updateLevel = $_POST['updateLevel']; 
    $updateCourse = $_POST['updateCourse']; 

    // Update the task and due date
    if (isset($recordList[$editIndex])) { // checks/ensures if there is a value where u wish to update
        $recordList[$editIndex]['level'] = $updateLevel; 
        $recordList[$editIndex]['course'] = $updateCourse; 

        // Save the updated record list
        saveRecordList($filename, $recordList);
    }
}


// Numbering the ToDo list
function recordNumber(){
    static $a = 1;
    echo $a;
    $a++;
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Add Student Record
    </button>

    
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Input Your Data</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <label for="">Your School Level:</label>
                            <select class="form-select" aria-label="Default select example" name="level">
                                <option value="100L">100L</option>
                                <option value="200L" selected>200L</option>
                            </select>
                            <br>

                            <label for="">Course of Study:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Computer Science" name="course"  id="flexRadioDefault1" checked>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Computer Science
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Computer Engineering" name="course" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Computer Engineering
                                </label>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="save">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <br> <br>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Student Level</th>
                <th scope="col">Course of Study</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <?php if (!empty($recordList)) : ?>
            <tbody>
                <?php foreach ($recordList as $index => $record) : ?>
                <tr>
                <th scope="row"><?php recordNumber()?></th>
                <td><?php echo $record['level']; ?></td>
                <td><?php echo $record['course']; ?></td>
                <!-- Update section -->
                <form action="" method="POST">
                    <input type="hidden" name="edit_index" value="<?php echo $index; ?>">
                    <td>
                    <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $index; ?>">
                        Update
                        </button>
                    </td>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop<?php echo $index; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?php echo $index; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                            <label for="">Your School Level:</label>
                            <select class="form-select" aria-label="Default select example" name="updateLevel">
                                <option value="100L" <?php echo $record['level'] == '100L' ? 'selected' : '';?>>100L</option>
                                <option value="200L" <?php echo $record['level'] == '200L' ? 'selected' : '';?>>200L</option>
                            </select>
                            <br>

                            <label for="">Course of Study:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Computer Science" name="updateCourse"  id="flexRadioDefault1" <?php echo $record['course'] == 'Computer Science' ? 'checked' : '';?>>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Computer Science
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Computer Engineering" name="updateCourse" id="flexRadioDefault2" <?php echo $record['course'] == 'Computer Engineering' ? 'checked' : '';?>>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Computer Engineering
                                </label>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Record</button>
                        </div>
                        </div>
                    </div>
                    </div>
                </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <?php else : ?>
                    <p>No lists found.</p>
                <?php endif; ?>
            </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>