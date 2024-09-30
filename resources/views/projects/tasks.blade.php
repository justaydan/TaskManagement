@extends('layouts.app')

@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>{{$project->name}}</h3>
        <div>
            <!-- Button to trigger modal for adding a new project -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTaskModal">+ New Task
            </button>
        </div>
    </div>

    <div class="row">
        @php
            $statuses = [
                'P' => ['title' => 'Not Started', 'constant' => \App\Constant\StatusConstant::PENDING],
                'I' => ['title' => 'In Progress', 'constant' => \App\Constant\StatusConstant::IN_PROGRESS],
                'C' => ['title' => 'Completed', 'constant' => \App\Constant\StatusConstant::COMPLETED]
            ];
        @endphp

        @foreach($statuses as $statusCode => $statusData)
            <!-- Dynamic Task Column -->
            <div class="col-md-3 task-column">
                <h5>{{ $statusData['title'] }}</h5>
                <div id="{{ strtolower(str_replace(' ', '-', $statusData['title'])) }}-tasks"
                     class="sortable-column">
                    @foreach ($project->tasks->where('status', $statusData['constant']) as $task)
                        <div class="card mb-3 task-card shadow-sm"  style="cursor: pointer;" data-task-id="{{ $task->id }}">
                            <!-- Card Header with Icon -->
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">{{ $task->name }}</h5> <!-- Task Name -->
                                <!-- Icon placed on the right side -->
                                <a href="#" onclick="deleteProject({{ $task->id }});"> <i
                                        class="fas fa-trash"></i></a>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body"
                                 onclick="showTaskDetails({{ $task->id }})">
                                <p>TASK-{{ $task->id }}</p>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        @endforeach
    </div>


    <x-modal-form
        id="newTaskModal"
        title="Create New Task"
        action="{{ route('tasks.store',['project'=>$project]) }}"
        submitLabel="Save Task"
        method="POST"
    >
        <!-- Modal Body Content -->
        <div class="form-group mb-3">
            <label for="taskName">Name</label>
            <input type="text" id="taskName" name="name" class="form-control" required>
            <div class="invalid-feedback" id="nameError"></div>

        </div>
        <div class="form-group mb-3">
            <label for="taskDescription">Description</label>
            <textarea id="taskDescription" name="description" class="form-control" rows="3" ></textarea>
            <div class="invalid-feedback" id="descriptionError"></div>

        </div>
    </x-modal-form>

    <script>
        const newTaskModal = document.querySelector('[data-bs-target="#newTaskModal"]');
        const taskName = document.getElementById('taskName');
        const taskDescription = document.getElementById('taskDescription');
        document.addEventListener('DOMContentLoaded', function () {
            const columns = [
                'not-started-tasks',
                'in-progress-tasks',
                'completed-tasks'
            ];

            columns.forEach(columnId => {
                new Sortable(document.getElementById(columnId), {
                    group: 'tasks',
                    animation: 150,
                    onEnd: function (evt) {
                        const taskId = evt.item.dataset.taskId;
                        let newStatus = '';

                        switch (evt.to.id) {
                            case 'not-started-tasks':
                                newStatus = 'P';
                                break;
                            case 'in-progress-tasks':
                                newStatus = 'I';
                                break;
                            case 'completed-tasks':
                                newStatus = 'C';
                                break;
                        }

                        // Call the function to update the task status
                        updateTaskStatus({{ $project->id }}, taskId, newStatus);
                    }
                });
            });

            document.getElementById('form').addEventListener('submit', (event) => {
                event.preventDefault();

                // Clear previous error messages
                document.getElementById('nameError').innerHTML = '';
                document.getElementById('descriptionError').innerHTML = '';

                const formData = new FormData(event.target);
                const method = form.getAttribute('method').toUpperCase();
                const action = form.getAttribute('action').toLowerCase();

                fetch(action, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            displayValidationErrors(data.errors);
                        } else if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });
            newTaskModal.addEventListener('click', () => {
                form.reset();
                form.setAttribute('action', "{{ route('tasks.store',['project'=>$project]) }}");
                form.setAttribute('method', 'POST');

                // Remove any hidden _method input field
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();

                modalTitle.textContent = 'Create New Task';
                modalFooter.style.display = 'block';  // Show modal footer for save action
            });
        });

        // Function to handle updating task status via an API call
        function updateTaskStatus(projectId, taskId, newStatus) {
            fetch(`/projects/${projectId}/tasks/${taskId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(`Task ${taskId} status updated to ${newStatus}`);
                    } else {
                        console.error('Error updating task status.');
                    }
                });
        }

        // Function to show and edit task details in modal
        function showTaskDetails(taskId) {
            const taskUpdateRoute = "{{ route('tasks.update', ['project'=>$project,'task' => ':id']) }}";

            fetch(`tasks/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate form fields with task details
                    document.getElementById('taskName').value = data.name;
                    document.getElementById('taskDescription').value = data.description;

                    // Set the form action to the task update route
                    const updateRoute = taskUpdateRoute.replace(':id', data.id);

                    // Set the form action to the dynamically generated task update route
                    const form = document.getElementById('newTaskModal').querySelector('form');
                    form.setAttribute('action', updateRoute);

                    form.setAttribute('method', `POST`);

                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        // Create hidden _method input if it doesn't exist
                        methodInput = document.createElement('input');
                        methodInput.setAttribute('type', 'hidden');
                        methodInput.setAttribute('name', '_method');
                        methodInput.setAttribute('value', 'PUT');
                        form.appendChild(methodInput);
                    } else {
                        //     // If it already exists, update the value to PUT
                        methodInput.setAttribute('value', 'PUT');
                    }

                    // Change the modal title to "Edit Task"
                    const modalTitle = document.querySelector('#newTaskModal .modal-title');
                    modalTitle.textContent = 'Edit Task';

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('newTaskModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching task details:', error);
                });
        }

        function showNewTaskModal() {
            // Clear form fields
            document.getElementById('taskName').value = '';
            document.getElementById('taskDescription').value = '';
            document.getElementById('taskStartDate').value = '';
            document.getElementById('taskEndDate').value = '';

            // Set the form action back to the create task route
            document.getElementById('newTaskModal').setAttribute('action', '{{ route('tasks.store', ['project' => $project]) }}');

            // Remove or reset the hidden _method input for POST
            let methodInput = document.querySelector('#newTaskModal input[name="_method"]');
            if (methodInput) {
                methodInput.remove(); // Remove the _method input for new tasks (POST doesn't need it)
            }

            // Change the modal title to "Create New Task"
            document.querySelector('#newTaskModal .modal-title').textContent = 'Create New Task';

            // Show the modal
            new bootstrap.Modal(document.getElementById('newTaskModal')).show();
        }

        function deleteProject(taskId) {
            if (confirm('Are you sure you want to delete this task?')) {
                const taskUpdateRoute = "{{ route('tasks.delete', ['project'=>$project,'task' => ':id']) }}";
                const updateRoute = taskUpdateRoute.replace(':id', taskId);
                fetch(updateRoute, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                        else console.error('Failed to delete the project');
                    })
                    .catch(error => console.error('Error deleting project:', error));
            }
        }

        function displayValidationErrors(errors) {
            if (errors.name) {
                document.getElementById('nameError').innerHTML = errors.name[0];
                taskName.classList.add('is-invalid');
            }
            if (errors.description) {
                document.getElementById('descriptionError').innerHTML = errors.description[0];
                taskDescription.classList.add('is-invalid');
            }
        }
    </script>
@endsection
