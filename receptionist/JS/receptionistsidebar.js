var dropdownButtons = document.querySelectorAll(".dropdown-btn");
var currentPage = window.location.pathname.split("/").pop();

dropdownButtons.forEach(function(button) {
    button.addEventListener("click", function() {
        var menu = this.nextElementSibling;
        var icon = this.querySelector("span");
        var isOpen = menu.classList.contains("show");

        dropdownButtons.forEach(function(otherButton) {
            var otherMenu = otherButton.nextElementSibling;
            var otherIcon = otherButton.querySelector("span");

            otherButton.classList.remove("active");
            otherMenu.classList.remove("show");
            otherIcon.textContent = "+";
        });

        if (!isOpen) {
            this.classList.add("active");
            menu.classList.add("show");
            icon.textContent = "-";
        }
    });
});

document.querySelectorAll(".dropdown-container a").forEach(function(link) {
    var linkPage = link.getAttribute("href").split("/").pop();

    if (linkPage === currentPage) {
        link.classList.add("current-page");
        var menu = link.parentElement;
        var button = menu.previousElementSibling;
        var icon = button.querySelector("span");

        menu.classList.add("show");
        button.classList.add("active");
        icon.textContent = "-";
    }
});

document.querySelectorAll(".sidenav > a").forEach(function(link) {
    var linkPage = link.getAttribute("href").split("/").pop();

    if (linkPage === currentPage) {
        link.classList.add("current-page");
    }
});
