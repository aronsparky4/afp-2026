// Placeholder
function alertPlace() {
    alert("Jelenleg ez a funkció nincs implementálva.");
}
// Kategória létrehozása modal
function openCategoryModal(event) {
    event.preventDefault();
    document.getElementById("categoryModal").classList.add('active');
}
document.getElementById("closeCategoryModal").onclick = function() {
    document.getElementById("categoryModal").classList.remove('active');
}
window.onclick = function(event) {
    if (event.target == document.getElementById("categoryModal")) {
        document.getElementById("categoryModal").classList.remove('active');
    }
}
// Új ToDo létrehozása modal
function openTodoAddModal(event) {
    event.preventDefault();
    document.getElementById("todoAddModal").classList.add('active');
}
document.getElementById("closeTodoAddModal").onclick = function() {
    document.getElementById("todoAddModal").classList.remove('active');
}
window.onclick = function(event) {
    if (event.target == document.getElementById("todoAddModal")) {
        document.getElementById("todoAddModal").classList.remove('active');
    }
}
// Kategória lista modal
function openCategoryListModal(event) {
    event.preventDefault();
    document.getElementById("categoryListModal").classList.add('active');
}
document.getElementById("closeCategoryListModal").onclick = function() {
    document.getElementById("categoryListModal").classList.remove('active');
}
window.onclick = function(event) {
    if (event.target == document.getElementById("categoryListModal")) {
        document.getElementById("categoryListModal").classList.remove('active');
    }
}
// ToDo szerkesztése modal
function openEditTodoModal(taskId, taskText, priorityId, categoryId) {
    document.getElementById("editTaskId").value = taskId;
    document.getElementById("editToDoInput").value = taskText;
    document.getElementById("editFontossagi").value = priorityId;
    document.getElementById("editCategory").value = categoryId;
    document.getElementById("editTodoModal").classList.add('active');
}
document.getElementById("closeEditTodoModal").onclick = function() {
    document.getElementById("editTodoModal").classList.remove('active');
}
document.getElementById("closeEditTodoModalBtn").onclick = function() {
    document.getElementById("editTodoModal").classList.remove('active');
}