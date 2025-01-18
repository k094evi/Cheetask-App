document.addEventListener('DOMContentLoaded', () => {
    const saveButton = document.querySelector('.save-button');
    const titleInput = document.getElementById('title');
    const due_dateInput = document.getElementById('due_date');
    const taskStatusSelect = document.getElementById('taskStatus');
    const prioritySelect = document.getElementById('priority');
    const descriptionTextarea = document.getElementById('description');

    saveButton.addEventListener('click', (event) => {
        event.preventDefault();

        // Get input values
        const taskTitle = titleInput.value;
        let due_date = due_dateInput.value;
        const taskStatus = taskStatusSelect.value;
        const priority = prioritySelect.value;
        const description = descriptionTextarea.value;

        // Validate inputs
        if (!taskTitle || !due_date || !taskStatus || !priority) {
            alert('Please fill in all required fields!');
            return;
        }

        // Format due date
        due_date = formatDate(due_date);

        // Send data to PHP script
        const taskData = {
            title: taskTitle,
            due_date: due_date,
            status: taskStatus,
            priority: priority,
            description: description
        };

        fetch('save-task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(taskData),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Task saved successfully!');
                setTimeout(() => {
                    window.location.href = 'Task.php';
                }, 2000);
            } else {
                alert('Failed to save task.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
    });
});

// Function to format the date into MM/DD/YYYY format
function formatDate(dateStr) {
    const date = new Date(dateStr);
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
}
