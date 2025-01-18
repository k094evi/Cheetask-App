document.addEventListener('DOMContentLoaded', () => {
    const taskForm = document.querySelector('#task-form');
    const taskList = document.querySelector('#task-list');
    let task = [];

    loadTaskFromServer();

    taskForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const taskId = taskForm.dataset.editingTaskId ? taskForm.dataset.editingTaskId : null;
        const taskData = {
            title: document.getElementById('task-title').value.trim(),
            description: document.getElementById('task-desc').value.trim(),
            priority: document.getElementById('priority').value,
            status: document.getElementById('status').value,
            due_date: document.getElementById('due-date').value,
        };

        if (!taskData.title || !taskData.description || !taskData.due_date) {
            alert("All fields must be filled out!");
            return;
        }

        saveTaskToServer(taskData, taskId);
    });

    function loadTaskFromServer() {
        fetch('fetchTask.php')
            .then((response) => response.json())
            .then((data) => {
                task = data.task;
                displayTask();
            })
            .catch((error) => console.error('Error fetching task:', error));
    }

    function displayTask() {
        taskList.innerHTML = '';
        task.forEach((taskItem) => appendTaskToList(taskItem));
    }

    function appendTaskToList(taskItem) {
        const taskElement = document.createElement('li');
        taskElement.classList.add('task-item');
        taskElement.dataset.taskId = taskItem.id;
        taskElement.innerHTML = `<h4>${taskItem.title}</h4>`;

        taskElement.addEventListener('click', () => populateFormForEdit(taskItem));
        taskList.appendChild(taskElement);
    }

    function populateFormForEdit(taskItem) {
        document.getElementById('task-title').value = taskItem.title;
        document.getElementById('task-desc').value = taskItem.description;
        document.getElementById('priority').value = taskItem.priority;
        document.getElementById('status').value = taskItem.status;
        document.getElementById('due-date').value = taskItem.due_date;

        taskForm.dataset.editingTaskId = taskItem.id;
    }

    function saveTaskToServer(taskData, taskId) {
        const url = taskId ? `updateTask.php?id=${taskId}` : 'save-task.php';
        const method = taskId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(taskData),
        })
            .then((response) => response.json())
            .then((result) => {
                alert(result.message);
                loadTaskFromServer();
                taskForm.reset();
                delete taskForm.dataset.editingTaskId;
            })
            .catch((error) => console.error('Error saving task:', error));
    }
});
