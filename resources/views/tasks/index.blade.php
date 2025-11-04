<!DOCTYPE html>
<html>

<head>
    <title>Task Manager</title>
</head>

<body>
    <h1>Task List</h1>

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Enter a new task" required>
        <button type="submit">Add Task</button>
    </form>

    <ul>
        @foreach ($tasks as $task)
            <li>
                {{ $task->name }}

                <!-- Edit button -->
                <a href="{{ route('tasks.edit', $task->id) }}">Edit</a>

                <!-- Delete button -->
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this task?')">Delete</button>
                </form>

            </li>
        @endforeach
    </ul>

</body>

</html>