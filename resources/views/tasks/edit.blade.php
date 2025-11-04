<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
</head>
<body>

    <h1>Edit Task</h1>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $task->name }}" required>
        <button type="submit">Update Task</button>
    </form>

    <a href="{{ route('tasks.index') }}">Back to Task List</a>
</body>
</html>