@extends('layouts.app')

@section('content')

    <!-- Main Content -->
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Projects</h3>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProjectModal">+ New
                Project
            </button>
        </div>
    </div>
    <div id="listView" class="table-responsive">
        <table class="table  table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Project Name</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($projects as $index => $project)
                <tr class="clickable-row" data-url="{{ route('projects.tasks', $project->id) }}">
                    <th scope="row" class="clickable-area" style="cursor: pointer;">
                        {{ $index + 1 }}
                    </th>

                    <!-- Project Name as a Link (clickable area) -->
                    <td class="clickable-area" style="cursor: pointer;">
                        <a href="{{ route('projects.tasks', $project->id) }}" class="text-decoration-none">
                            {{ $project->name }}
                        </a>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <!-- Eye Icon for Viewing -->
                            <a href="#" onclick="showProjectDetails({{ $project->id }},false)" class="me-3"
                               aria-label="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <!-- Edit Icon -->
                            <a href="#" onclick="showProjectDetails({{ $project->id }})" class="me-3" aria-label="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete Icon -->
                            <a href="#" onclick="deleteProject({{ $project->id }});" class="text-danger"
                               aria-label="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <x-modal-form
        id="newProjectModal"
        title="Create New Project"
        action="{{ route('projects.store') }}"
        submitLabel="Save Project"
        showFooter="true"
    >
        <!-- Modal Body Content -->
        <div class="form-group mb-3">
            <label for="projectName">Name</label>
            <input type="text" id="projectName" name="name" class="form-control" required>
            <div class="invalid-feedback" id="nameError"></div>

        </div>
        <div class="form-group mb-3">
            <label for="projectDescription">Description</label>
            <textarea id="projectDescription" name="description" class="form-control"
                      rows="3"></textarea>
            <div class="invalid-feedback" id="descriptionError"></div>

        </div>
    </x-modal-form>

    <script>
        const newProjectButton = document.querySelector('[data-bs-target="#newProjectModal"]');
        const form = document.querySelector('#newProjectModal form');
        const modalTitle = document.querySelector('#newProjectModal .modal-title');
        const modalFooter = document.querySelector('#newProjectModal .modal-footer');
        const projectName = document.getElementById('projectName');
        const projectDescription = document.getElementById('projectDescription');

        document.addEventListener('DOMContentLoaded', () => {
            // Event delegation for clickable areas in rows
            document.querySelector('.table').addEventListener('click', (event) => {
                const clickableElement = event.target.closest('.clickable-area');
                if (clickableElement) {
                    const row = clickableElement.closest('.clickable-row');
                    if (row && row.hasAttribute('data-url')) {
                        window.location = row.getAttribute('data-url');
                    }
                }
            });

            // Reset form and prepare for creating a new project
            newProjectButton.addEventListener('click', () => {
                form.reset();
                form.setAttribute('action', "{{ route('projects.store') }}");
                form.setAttribute('method', 'POST');

                // Remove any hidden _method input field
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();

                modalTitle.textContent = 'Create New Project';
                modalFooter.style.display = 'block';  // Show modal footer for save action
            });

            // Handle form submission with validation
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

        });
        function showProjectDetails(projectId, edit = true) {
            fetch(`/projects/${projectId}`)
                .then(response => response.json())
                .then(data => {
                    projectName.value = data.name;
                    projectDescription.value = data.description;

                    if (edit) {
                        enableEditMode(data.id);
                    } else {
                        disableEditMode();
                    }

                    const modal = new bootstrap.Modal(document.getElementById('newProjectModal'));
                    modal.show();
                })
                .catch(error => console.error('Error fetching project details:', error));
        }
        function enableEditMode(projectId) {
            projectName.readOnly = false;
            projectDescription.readOnly = false;
            modalFooter.style.display = 'block';

            const updateRoute = "{{ route('projects.update', ['project' => ':id']) }}".replace(':id', projectId);
            form.setAttribute('action', updateRoute);
            form.setAttribute('method', 'POST');

            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.setAttribute('type', 'hidden');
                methodInput.setAttribute('name', '_method');
                methodInput.setAttribute('value', 'PUT');
                form.appendChild(methodInput);
            } else {
                methodInput.setAttribute('value', 'PUT');
            }

            modalTitle.textContent = 'Edit Project';
        }
        function disableEditMode() {
            projectName.readOnly = true;
            projectDescription.readOnly = true;
            modalFooter.style.display = 'none';

            modalTitle.textContent = 'Project Details';
        }
        function displayValidationErrors(errors) {
            if (errors.name) {
                document.getElementById('nameError').innerHTML = errors.name[0];
                projectName.classList.add('is-invalid');
            }
            if (errors.description) {
                document.getElementById('descriptionError').innerHTML = errors.description[0];
                projectDescription.classList.add('is-invalid');
            }
        }
        function deleteProject(projectId) {
            if (confirm('Are you sure you want to delete this project?')) {
                fetch(`/projects/${projectId}`, {
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

    </script>

@endsection
