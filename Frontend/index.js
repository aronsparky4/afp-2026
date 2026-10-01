console.log("az oldal sikeresen betöltődött");

function handleUserOptionChange(selectElement) {
    const selectedOption = selectElement.value;
    console.log("Kiválasztott opció: " + selectedOption);
    if (selectedOption === "logOut") {
        window.location.href = "../Backend/logout.php";
    } else if (selectedOption === "changePassword") {
        window.location.href = "../Frontend/changePassword.php";
    } else if (selectedOption === "DragDropPage") {
        window.location.href = "../Frontend/DragDropPage.php";
    }
}