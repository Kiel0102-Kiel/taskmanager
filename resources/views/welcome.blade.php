<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focus Desk | Personal Task Manager</title>
    @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
</head>
<body>
    <div class="desk-shell">
                <aside class="desk-rail">
                    <a class="desk-brand" href="{{ route('tasks.index') }}"><span class="desk-brand-mark"><i data-lucide="square-check-big" aria-hidden="true"></i></span><span>Focus Desk</span></a>
                    <div class="rail-label">Workspace</div>
                    <nav class="desk-nav" aria-label="Workspace navigation">
                        <a class="active" href="{{ route('tasks.index') }}"><i data-lucide="sun" aria-hidden="true"></i> Today <span>{{ $pendingCount }}</span></a>
                        <a href="#task-queue"><i data-lucide="list-todo" aria-hidden="true"></i> All Tasks <span>{{ $tasks->count() }}</span></a>
                        <a href="#completed"><i data-lucide="circle-check" aria-hidden="true"></i> Completed <span>{{ $completedCount }}</span></a>
                    </nav>
                    <div class="rail-note"><i data-lucide="quote" aria-hidden="true"></i><p><i>“The key is not to prioritize what’s on your schedule, but to schedule your priorities.”</p><span>Stephen Covey</span></i></div>
                    <div class="rail-footer"><span class="online-dot"></span> Local workspace</div>
                </aside>

                <main class="desk-main">
                    <header class="desk-header"><div><span class="desk-kicker">{{ now()->format('l, F j') }}</span><span class="desk-location">/ personal command center</span></div><div class="desk-header-actions"><span class="desk-time">{{ now()->format('g:i A') }}</span><a href="#capture" class="header-add"><i data-lucide="plus" aria-hidden="true"></i> New task</a></div></header>
                    @if (session('success'))<div class="desk-flash" role="status"><i data-lucide="circle-check" aria-hidden="true"></i>{{ session('success') }}</div>@endif
                    @if ($errors->any())<div class="desk-flash desk-error" role="alert"><i data-lucide="triangle-alert" aria-hidden="true"></i>Please check the highlighted fields and try again.</div>@endif

                    <section class="desk-intro"><div><p class="desk-overline">One clear direction</p><h1>Lock In!<br><em>Get it Done!</em></h1></div><p class="desk-intro-copy">Lock into the work. Stay with it, one considered step at a time.

</p></section>

                    <section class="desk-summary" aria-label="Task summary">
                        <div class="summary-lead"><span class="summary-number">{{ $pendingCount }}</span><div><strong>{{ $pendingCount === 1 ? 'task' : 'tasks' }} in motion</strong><span>Keep your attention where it counts.</span></div></div>
                        <div class="summary-stat"><span>Completed</span><strong>{{ $completedCount }}</strong></div>
                        <div class="summary-stat"><span>Total Tracked</span><strong>{{ $tasks->count() }}</strong></div>
                        <div class="completion-line"><span style="width: {{ $tasks->count() ? round(($completedCount / $tasks->count()) * 100) : 0 }}%"></span></div>
                    </section>

                    <section class="desk-layout">
                        <div class="queue-panel" id="task-queue">
                            <div class="queue-heading"><div><p class="desk-overline">Your queue</p><h2>In focus</h2></div><span>{{ $tasks->count() }} total</span></div>
                            @forelse ($tasks as $task)
                                <article class="queue-item {{ $task->status === 'Completed' ? 'queue-done' : '' }}" id="{{ $task->status === 'Completed' ? 'completed' : '' }}">
                                    <form method="POST" action="{{ route('tasks.toggle-status', $task) }}" class="queue-check-form">@csrf @method('PATCH')<button class="queue-check" type="submit" aria-label="Mark task as {{ $task->status === 'Pending' ? 'completed' : 'pending' }}">@if ($task->status === 'Completed')<i data-lucide="check" aria-hidden="true"></i>@endif</button></form>
                                    <div class="queue-body"><div class="queue-title-row"><h3>{{ $task->task_name }}</h3><span class="queue-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></div>@if ($task->description)<p>{{ $task->description }}</p>@endif<div class="queue-meta"><span class="queue-status {{ strtolower($task->status) }}"><i data-lucide="{{ $task->status === 'Completed' ? 'circle-check' : 'circle-dashed' }}" aria-hidden="true"></i>{{ $task->status }}</span>@if ($task->due_date)<span><i data-lucide="calendar-days" aria-hidden="true"></i>{{ $task->due_date->format('M d, Y') }}</span>@endif</div></div>
                                    <div class="queue-actions"><a href="{{ route('tasks.edit', $task) }}" class="desk-icon" title="Edit task" aria-label="Edit {{ $task->task_name }}"><i data-lucide="pencil" aria-hidden="true"></i></a><form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">@csrf @method('DELETE')<button type="submit" class="desk-icon delete-icon" title="Delete task" aria-label="Delete {{ $task->task_name }}"><i data-lucide="trash-2" aria-hidden="true"></i></button></form></div>
                                </article>
                            @empty
                                <div class="desk-empty"><i data-lucide="inbox" aria-hidden="true"></i><h3>Nothing competing for your attention.</h3><p>Capture your next meaningful step on the right.</p></div>
                            @endforelse
                        </div>

                        <aside class="capture-panel" id="capture"><div class="capture-heading"><span class="capture-number">01</span><div><p class="desk-overline">Capture</p><h2>Put it down.</h2></div></div><p class="capture-copy">Give the task a name, then let the details follow.</p>
                            <form method="POST" action="{{ route('tasks.store') }}" class="desk-form">@csrf
                                <label for="task_name">Task Name</label><input id="task_name" name="task_name" type="text" value="{{ old('task_name') }}" placeholder="e.g. Prepare project outline" required>@error('task_name')<small>{{ $message }}</small>@enderror
                                <label for="description">Context <span>optional</span></label><textarea id="description" name="description" rows="4" placeholder="What will make this easier to finish?">{{ old('description') }}</textarea>
                                <div class="desk-form-row"><div><label for="due_date">Deadline</label><input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}"></div><div><label for="status">State</label><select id="status" name="status"><option value="Pending">Pending</option><option value="Completed">Completed</option></select></div></div>
                                <button class="desk-submit" type="submit"><i data-lucide="arrow-up-right" aria-hidden="true"></i> Add to queue</button>
                            </form>
                        </aside>
                    </section>
                    <footer class="desk-footer"><span>Designed for deliberate progress</span></footer>
                </main>
    </div>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
