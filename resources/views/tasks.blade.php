<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="{{ asset('./app.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Task Manager</h2>
        <!-- Add Task Form -->
        <form id="taskForm">
            @csrf
            <input type="text" id="title" name="title" placeholder="Enter Task Title">
            <button type="submit">Add Task</button>
        </form>

        <!-- Task List -->
        <ul id="taskList"></ul>
    </div>

    <script>
        $(document).ready(function() {
            function loadTasks() {
                $.get("{{ route('tasks.index') }}", function(tasks) {
                    $('#taskList').empty();
                    $.each(tasks, function(key, task) {
                        $('#taskList').append(`
                            <li id="task-${task.id}">
                                <span>${task.title}</span>
                                <div class="task-buttons">
                                    <button class="edit-btn" onclick="editTask(${task.id}, '${task.title}')">Edit</button>
                                    <button class="delete-btn" onclick="deleteTask(${task.id})">Delete</button>
                                </div>
                            </li>
                        `);
                    });
                });
            }

            loadTasks();

            $('#taskForm').submit(function(e) {
                e.preventDefault();
                let title = $('#title').val();
                let _token = $('input[name="_token"]').val();

                $.post("{{ route('tasks.store') }}", {
                    title: title,
                    _token: _token
                }, function(response) {
                    alert(response.message);
                    $('#title').val('');
                    loadTasks();
                });
            });

            window.editTask = function(id, oldTitle) {
                let newTitle = prompt("Edit Task:", oldTitle);
                if (newTitle) {
                    $.ajax({
                        url: "/tasks/" + id,
                        type: "PUT",
                        data: {
                            title: newTitle,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            alert(response.message);
                            loadTasks();
                        }
                    });
                }
            };

            window.deleteTask = function(id) {
                if (confirm("Are you sure?")) {
                    $.ajax({
                        url: "/tasks/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            alert(response.message);
                            loadTasks();

                        }
                    });
                }
            };
        });
    </script>

</body>

</html>
