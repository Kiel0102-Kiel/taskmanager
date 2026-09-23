<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit task | taskday</title>
    @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
</head>
<body>
    <div class="app-shell compact-shell">
        <header class="topbar"><a class="brand" href="{{ route('tasks.index') }}"><span class="brand-mark"><i data-lucide="check" aria-hidden="true"></i></span><span>taskday</span></a><a class="back-link" href="{{ route('tasks.index') }}"><i data-lucide="arrow-left" aria-hidden="true"></i> Back to tasks</a></header>
        <main class="edit-page"><p class="eyebrow">Refine your plan</p><h1>Edit task</h1><p class="intro-copy">Keep the details current so your next step stays clear.</p>
            <form method="POST" action="{{ route('tasks.update', $task) }}" class="task-form edit-form">@csrf @method('PUT')
                <label for="task_name">Task name</label><input id="task_name" name="task_name" type="text" value="{{ old('task_name', $task->task_name) }}" required>@error('task_name')<small class="field-error">{{ $message }}</small>@enderror
                <label for="description">Description <span>(optional)</span></label><textarea id="description" name="description" rows="5">{{ old('description', $task->description) }}</textarea>
                <div class="form-row"><div><label for="due_date">Due date</label><input id="due_date" name="due_date" type="date" value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"></div><div><label for="status">Status</label><select id="status" name="status"><option value="Pending" @selected(old('status', $task->status) === 'Pending')>Pending</option><option value="Completed" @selected(old('status', $task->status) === 'Completed')>Completed</option></select></div></div>
                <div class="edit-actions"><a class="secondary-button" href="{{ route('tasks.index') }}">Cancel</a><button class="primary-button" type="submit">Save changes</button></div>
            </form>
        </main>
    </div>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
