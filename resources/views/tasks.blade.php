<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @tailwind base;
        @tailwind components;
        @tailwind utilities;

        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* Body Styling */
        body {
            background-color: #4e4242;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Heading */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #444;
        }

        /* Form Styling */
        form {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            background: #0056b3;
        }

        /* Task List */
        #taskList {
            list-style: none;
            margin-top: 20px;
        }

        #taskList li {
            background: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        #taskList li span {
            flex: 1;
            font-size: 16px;
            color: #333;
        }

        /* Task Buttons */
        .task-buttons {
            display: flex;
            gap: 8px;
        }

        .edit-btn,
        .delete-btn {
            padding: 6px 10px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s ease-in-out;
        }

        .edit-btn {
            background: #ffc107;
            color: white;
        }

        .edit-btn:hover {
            background: #d39e00;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #b02a37;
        }

        /* Responsive Design */
        @media (max-width: 500px) {
            .container {
                width: 90%;
            }

            form {
                flex-direction: column;
            }

            button {
                width: 100%;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Task Manager</h2>
        <x-alert type="success">Operation completed successfully!</x-alert>


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
