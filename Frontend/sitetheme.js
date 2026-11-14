const button = document.getElementById("theme-toggle");
const root = document.documentElement;
const savedTheme = localStorage.getItem("theme");

function applyTheme(theme) {
  root.dataset.theme = theme;
  root.classList.toggle("dark-mode", theme === "dark");
  root.classList.toggle("light-mode", theme === "light");
}

if (savedTheme === "dark" || savedTheme === "light") {
  applyTheme(savedTheme);
}

function updateButton() {
  const isDark = root.dataset.theme === "dark";

  button.textContent = isDark
    ? "Világos mód"
    : "Sötét mód";
}

updateButton();

button.addEventListener("click", () => {
  const isDark = root.dataset.theme === "dark";

  const newTheme = isDark ? "light" : "dark";

  applyTheme(newTheme);
  localStorage.setItem("theme", newTheme);

  updateButton();
});