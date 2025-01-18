document.addEventListener('DOMContentLoaded', () => {
    const taskForm = document.querySelector('#task-form');
    const taskList = document.querySelector('#task-list');
    let tasks = [];

    loadTasksFromServer();

    taskForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const taskId = taskForm.dataset.editingTaskId ? taskForm.dataset.editingTaskId : null;
        const taskData = {
            title: document.getElementById('task-title').value.trim(),
            description: document.getElementById('task-desc').value.trim(),
            priority: document.getElementById('priority').value,
            status: document.getElementById('status').value,
            due_date: document.getElementById('due-date').value,
            assigned_to: document.getElementById('developer').value
        };

        if (!taskData.title || !taskData.description || !taskData.due_date || !taskData.assigned_to) {
            alert("All fields must be filled out!");
            return;
        }

        saveTaskToServer(taskData, taskId);
    });

    function loadTasksFromServer() {
        fetch('fetchAdminTask.php')
            .then(response => response.json())
            .then(data => {
                tasks = data.tasks;
                displayTasks();
            })
            .catch(error => console.error('Error fetching tasks:', error));
    }

    function displayTasks() {
        taskList.innerHTML = '';
        tasks.forEach((taskItem) => appendTaskToList(taskItem));
    }

    function appendTaskToList(taskItem) {
        const taskElement = document.createElement('li');
        taskElement.classList.add('task-item');
        taskElement.dataset.taskId = taskItem.id;
        taskElement.innerHTML = `
            <h4>${taskItem.title}</h4>
            <p><strong>Assigned to:</strong> ${taskItem.assigned_to_name || 'Not Assigned'}</p>
        `;

        taskElement.addEventListener('click', () => populateFormForEdit(taskItem));
        taskList.appendChild(taskElement);
    }

    function populateFormForEdit(taskItem) {
        document.getElementById('task-title').value = taskItem.title;
        document.getElementById('task-desc').value = taskItem.description;
        document.getElementById('priority').value = taskItem.priority;
        document.getElementById('status').value = taskItem.status;
        document.getElementById('due-date').value = taskItem.due_date;

        document.getElementById('developer').value = taskItem.assigned_to;

        taskForm.dataset.editingTaskId = taskItem.id;
    }

    function saveTaskToServer(taskData, taskId) {
        const url = taskId ? `updateAdminTask.php?id=${taskId}` : 'save-AdminTask.php';
        const method = taskId ? 'PUT' : 'POST';
    
        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(taskData),
        })
        .then(response => response.json())
        .then(result => {
            alert(result.message);
            loadTasksFromServer();
            taskForm.reset();
            delete taskForm.dataset.editingTaskId;
        })
        .catch(error => console.error('Error saving task:', error));
    }    
});
