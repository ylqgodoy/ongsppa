<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Início | SPPA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
    <meta name="keywords" content="SPPA, atividades, eventos, workshops, participação, iniciativas, educação, comunidade, animais, adoção, adotar animal">
    <meta name="author" content="Sociedade Piracicabana de Proteção aos Animais">
    <meta name="robots" content="index, follow">
    
    <meta property="og:title" content="Sociedade Piracicabana de Proteção aos Animais">
    <meta property="og:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
    <meta property="og:image" content="https://ongsppa.org/src/assets/images/sppa.webp">
    <meta property="og:url" content="https://ongsppa.org/">
    <meta property="og:type" content="website">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Sociedade Piracicabana de Proteção aos Animais">
    <meta name="twitter:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
    <meta name="twitter:image" content="https://ongsppa.org/src/assets/images/sppa.webp">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="src/assets/images/1.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        @media (max-width: 910px) {
            .hiddenlowres {
                display: none;
            }
        }
        @media (max-width: 1360px) {
            .hiddenlowresmain {
                display: none;
            }
        }
        .nav-link {
            position: relative;
            overflow: hidden;
            display: inline-block;
            padding-bottom: 2px;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #000;
            transition: width 0.3s ease, left 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            padding: 10px;
            transition: background-color 0.3s ease, filter 0.3s ease;
        }
        .icon-instagram {
            background-color: #C13584;
        }
        .icon-facebook {
            background-color: #1877F2;
        }
        .icon-email {
            background-color: #D44638;
        }
        .icon-wrapper:hover {
            filter: brightness(0.6);
        }
        .icon-wrapper a {
            color: #ffffff;
        }
        .hamburger-menu {
            transition: transform 0.3s ease-in-out;
            transform: translateX(100%);
        }
        .hamburger-menu.active {
            transform: translateX(0);
        }
        .overlay {
            background: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
        }
        .overlay.active {
            opacity: 1;
        }
        #success-animation {
            background-color: rgba(34, 197, 94, 0.9);
            transition: all 0.7s ease;
        }
    </style>
</head>
<body class="bg-gray-50 bg-cover bg-center" style="background-image: url('src/assets/images/patinhas.webp'); background-size: 900px; background-repeat: repeat;">
    <div class="z-50">
        <?php include 'src/pages/navbar.php';?>
    <section class="h-screen flex items-center bg-cover bg-center p-5 z-10">
        <div class="max-w-6xl mx-auto w-full text-black text-center lg:text-left">
            <h2 class="text-5xl lg:text-7xl font-bold leading-tight lg:leading-[87.5px]">Resgatando <span class="text-blue-800 font-bold">esperança</span><br> e construindo <span class="text-blue-800 font-bold">lares</span></h2>
            <p class="text-lg lg:text-xl mt-4">Sociedade Piracicabana de Proteção aos Animais</p>
            <a href="adotar" class="inline-block z-70">
                <button class="mt-12 bg-red-600 text-white py-4 px-8 text-lg font-bold rounded-lg shadow-md hover:scale-110 z-70 transition-transform duration-500">Adote um peludinho <img src="src/assets/images/pata.webp" class="inline w-6 ml-2" alt="Ícone de pata"></button>
            </a>
        </div>
    </section>
    <img class="absolute bottom-0 right-0 max-w-lg z-10 hiddenlowresmain" src="src/assets/images/gato.webp" alt="Gato" loading="lazy">
    <img class="absolute bottom-0 right-[18%] max-w-sm hiddenlowresmain" src="src/assets/images/cachorro.webp" alt="Cachorro" loading="lazy">
    <img src="src/assets/images/linetr.webp" alt="Detalhe Animal" class="absolute mt-20 top-0 right-0 w-64 h-auto hidden md:block" loading="lazy">
    <img src="src/assets/images/linebl.webp" alt="Detalhe Animal" class="absolute bottom-0 left-0 right-0 w-64 hidden md:block" loading="lazy">
    <?php include 'src/pages/footer.php';?>
    <div id="success-animation" class="fixed bottom-4 right-4 text-white px-5 py-2 rounded-lg flex items-center space-x-2 opacity-0 transform translate-x-10 transition-all duration-900 z-50">
        <i class="fas fa-check-circle"></i>
        <span>CNPJ copiado com sucesso!</span>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const closeBtn = document.getElementById('close-btn');
            const hamburgerMenu = document.getElementById('hamburger-menu');
            const overlay = document.getElementById('overlay');

            hamburgerBtn.addEventListener('click', () => {
                hamburgerMenu.classList.remove('hidden');
                hamburgerMenu.classList.add('active');
                overlay.classList.remove('hidden');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            closeBtn.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);

            function closeMenu() {
                hamburgerMenu.classList.remove('active');
                hamburgerMenu.classList.add('hidden');
                overlay.classList.remove('active');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        function Copiar() {
            const cnpj = document.getElementById("cnpj").innerText;
            const tempInput = document.createElement("input");
            tempInput.value = cnpj;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            const successAnimation = document.getElementById("success-animation");
            successAnimation.classList.remove('opacity-0', 'translate-x-10');
            successAnimation.classList.add('opacity-100', 'translate-x-0');
            setTimeout(() => {
                successAnimation.classList.remove('opacity-100', 'translate-x-0');
                successAnimation.classList.add('opacity-0', 'translate-x-10');
            }, 2000);
        }
    </script>
</body>
</html>