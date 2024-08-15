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

        function Copiar() {
            const textToCopy = "56.986.342/0001-87";
            navigator.clipboard.writeText(textToCopy).then(() => {
                alert("Chave PIX copiada: " + textToCopy);
            }).catch(err => {
                console.error("Erro ao copiar: ", err);
            });
        }