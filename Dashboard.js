document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.search');
    const backlogTasks = document.getElementById("backlogTasks");
    const workingTasks = document.getElementById("workingTasks");
    const doneTasks = document.getElementById("doneTasks");




    let tasks = JSON.parse(localStorage.getItem("tasks")) || [
        { title: "Sample Task 1", description: "Description 1", dueDate: "2024-12-31", status: "Not Started" },
        { title: "Sample Task 2", description: "Description 2", dueDate: "2024-12-31", status: "In Progress" },
        { title: "Sample Task 3", description: "Description 3", dueDate: "2024-12-31", status: "Completed" }
    ];




    function renderTasks(filteredTasks = tasks) {
        backlogTasks.innerHTML = "";
        workingTasks.innerHTML = "";
        doneTasks.innerHTML = "";




        filteredTasks.forEach(task => {
            const taskElement = document.createElement("div");
            taskElement.classList.add("task-card");
            taskElement.innerHTML = `
                <h4><a href="#">${task.title}</a></h4>
                <p>${task.description}</p>
                <p>Due: ${task.dueDate}</p>
            `;




            if (task.status === "Not Started") {
                backlogTasks.appendChild(taskElement);
            } else if (task.status === "In Progress") {
                workingTasks.appendChild(taskElement);
            } else if (task.status === "Completed") {
                doneTasks.appendChild(taskElement);
            }
        });
    }




    searchInput.addEventListener('input', (event) => {
        const searchTerm = event.target.value.toLowerCase();




        const filteredTasks = tasks.filter(task =>
            task.title.toLowerCase().includes(searchTerm) ||
            task.description.toLowerCase().includes(searchTerm)
        );




        renderTasks(filteredTasks);
    });




    renderTasks();
});
