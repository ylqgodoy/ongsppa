      document.addEventListener("DOMContentLoaded", function() {
        const header = document.querySelector("header");
        const hamburgerBtn = document.querySelector("#hamburger-btn");
        const closeMenuBtn = document.querySelector("#close-menu-btn");

        hamburgerBtn.addEventListener("click", toggleMenu);
        closeMenuBtn.addEventListener("click", toggleMenu);

        function toggleMenu() {
          header.classList.toggle("show-mobile-menu");
        }

        document.addEventListener("click", function(event) {
          if (!header.contains(event.target)) {
            header.classList.remove("show-mobile-menu");
          }
        });
      });