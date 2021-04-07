document.getElementById("btn_dark_light").onclick = function () {
    dark_light();
};
start();
dark_light();
let i = false;

function dark_light() {
    let darkMode = localStorage.getItem("Dark-Mode");
    logoWhite = document.getElementsByClassName("logowhite");
    logoBlack = document.getElementsByClassName("logoblack");
    if (darkMode == "true") {
        document.body.classList.add("light-mode");
        document.getElementById("dark_light").classList.add("fa-moon-o");
        document.getElementById("dark_light").classList.remove("fa-sun-o");
        logoWhite[0].classList.add("d-none");
        logoBlack[0].classList.remove("d-none");
        logoWhite[1].classList.add("d-none");
        logoBlack[1].classList.remove("d-none");
        localStorage.setItem("Dark-Mode", "false");
    } else {
        document.body.classList.remove("light-mode");
        document.getElementById("dark_light").classList.add("fa-sun-o");
        document.getElementById("dark_light").classList.remove("fa-moon-o");
        logoBlack[0].classList.add("d-none");
        logoWhite[0].classList.remove("d-none");
        logoBlack[1].classList.add("d-none");
        logoWhite[1].classList.remove("d-none");
        localStorage.setItem("Dark-Mode", "true");
    }
}

function start() {
    if (localStorage.getItem("Dark-Mode") == "true") {
        localStorage.setItem("Dark-Mode", "false");
    } else {
        localStorage.setItem("Dark-Mode", "true");
    }
}
