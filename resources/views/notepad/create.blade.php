<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notepad demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
        <div class="bg-dark py-3 border border-white rounded">
            <h3 class="text-white text-center">NotePad</h3>
        </div>

        <div class="mt-3 d-flex flex-column align-items-center">
            @if (Session::has('success'))
                <div class="col-md-10">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                    </div>
                </div>
            @endif

            <!-- Form Section -->
            <div class="col-md-4">
                <form action="{{ route('notepad.store') }}" method="post" class="border border-primary p-4 rounded shadow">
                    @csrf
                    <label for="task" class="form-label">Task</label>
                    <div class="input-group gap-2">
                        <input type="text" class="form-control @error('task') is-invalid @enderror" id="task" name="task" required>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                        <button class="btn btn-primary">Add Task</button>
                    </div>
                    @error('task')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </form>                
            </div>

            <!-- Task List Section -->
            <div class="col-md-8 mt-4">
                <div class="bg-dark py-2 border border-white rounded">
                    <h2 class="text-white text-center">Task ToDo</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>ID</th>
                            <th>Task</th>
                            <th>Priority</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        @if ($notepad->isNotEmpty())
                        @foreach ($notepad as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->task }}</td>
                                <td>
                                    @if ($item->priority == 'high')
                                        <span class="badge bg-danger">High</span>
                                    @elseif ($item->priority == 'medium')
                                        <span class="badge bg-warning text-dark">Medium</span>
                                    @else
                                        <span class="badge bg-success">Low</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editTaskModal" onclick="editTask({{ $item->id }}, '{{ $item->task }}', '{{ $item->priority }}')">Edit</a>
                                    <form action="{{ route('notepad.delete', $item->id) }}" method="POST" class="d-inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this task?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('notepad.update', ':id') }}" method="POST" id="editTaskForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editTask" class="form-label">Task</label>
                            <input type="text" class="form-control" id="editTask" name="task" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPriority" class="form-label">Priority</label>
                            <select class="form-select" id="editPriority" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Section -->
    <script>
        function editTask(id, task, priority) {
            var formAction = "{{ route('notepad.update', ':id') }}";
            formAction = formAction.replace(':id', id);
            document.getElementById('editTaskForm').action = formAction;
            document.getElementById('editTask').value = task;
            document.getElementById('editPriority').value = priority;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
  </body>
</html>
