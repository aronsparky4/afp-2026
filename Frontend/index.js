console.log("az oldal sikeresen betöltődött");

function handleUserOptionChange(selectElement) {
    const selectedOption = selectElement.value;
    console.log("Kiválasztott opció: " + selectedOption);
    if (selectedOption === "logOut") {
        window.location.href = "../Backend/logout.php";
    } else if (selectedOption === "changePassword") {
        window.location.href = "../Frontend/changePassword.php";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const deleteAllButton = document.getElementById("deleteAll");
    deleteAllButton.addEventListener("click", function () {
        if (confirm("Biztosan törölni szeretnéd az összes ToDo-t?")) {
            fetch("../Backend/deleteAllToDo.php", {
                method: "POST",
            })
                .then((response) => {
                    if (response.ok) {
                        alert("Minden ToDo sikeresen törölve!");
                        location.reload();
                    } else {
                        alert("Hiba történt a ToDo-k törlése közben.");
                    }
                })
                .catch((error) => {
                    console.error("Hiba történt a ToDo-k törlése közben:", error);
                    alert("Hiba történt a ToDo-k törlése közben.");
                });
        }
    });
});