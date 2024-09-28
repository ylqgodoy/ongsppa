<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Doação | SPPA</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta name="keywords" content="SPPA, atividades, eventos, workshops, participação, iniciativas, educação, comunidade, animais, adocao, adoção, adotar animal, adotar">
        <meta name="author" content="Sociedade Piracicabana de Proteção aos Animais">
        <meta name="robots" content="index, follow">

        <meta property="og:title" content="Doação - Sociedade Piracicabana de Proteção aos Animais">
        <meta property="og:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta property="og:image" content="https://ongsppa.org/src/assets/images/sppa.webp">
        <meta property="og:url" content="https://ongsppa.org/">
        <meta property="og:type" content="website">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Doação - Sociedade Piracicabana de Proteção aos Animais">
        <meta name="twitter:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta name="twitter:image" content="https://ongsppa.org/src/assets/images/sppa.webp">

        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="shortcut icon" type="imagex/png" href="/src/assets/images/1.ico">
        <link rel="stylesheet" type="text/css" href="/src/assets/css/tip.css">
    </head>
    <body class="bg-gray-60 bg-cover bg-center" style="background-image: url('/src/assets/images/patinhas.webp'); background-size: 900px; background-repeat: repeat;">
        <div class="z-50">
            <?php include 'navbar.php';?>
        <header class="bg-gradient text-white py-6 mt-20">
            <div class="container mx-auto text-center">
                <h1 class="text-3xl font-bold">Ajude-nos a Fazer a Diferença!</h1>
            </div>
        </header>
        <main class="container mx-auto py-8 px-4">
            <section class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4 text-blue-600">Por que Doar?</h2>
                <p class="text-lg mb-6 leading-relaxed">A SPPA depende da sua generosidade para continuar ajudando animais necessitados. Com sua contribuição, oferecemos cuidados médicos, abrigo e alimentação para animais abandonados e feridos.</p>
                <h2 class="text-2xl font-semibold mb-4 text-blue-600">Como sua Doação Ajuda:</h2>
                <ul class="list-disc pl-6 mb-6 text-lg space-y-4">
                    <li class="flex items-center">
                        <i class="fas fa-stethoscope text-blue-600 w-6 h-6 mr-4 animate"></i> Cuidados veterinários, incluindo vacinação e tratamentos.
                    </li>
                    <li class="flex items-center">
                        <i class="fa-solid fa-house text-blue-600 w-6 h-6 mr-4 animate"></i> Alimentação e abrigo seguro para os animais.
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-book text-blue-600 w-6 h-6 mr-4 animate"></i> Programas de castração e educação sobre posse responsável.
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-tools text-blue-600 w-6 h-6 mr-4 animate"></i> Manutenção das instalações e suporte às equipes.
                    </li>
                </ul>
                <h2 class="text-2xl font-semibold mb-6 text-blue-600">Formas de Doar:</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 text-lg">
                    <div class="bg-blue-100 p-4 rounded-lg shadow-md transform hover:scale-105 transition duration-500 ease-in-out hover:bg-blue-200">
                        <h3 class="text-xl font-semibold text-blue-700">Doação Única</h3>
                        <p class="mt-2">Contribua uma vez através do nosso site seguro.</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg shadow-md transform hover:scale-105 transition duration-500 ease-in-out hover:bg-blue-200">
                        <h3 class="text-xl font-semibold text-blue-700">Doação Recorrente</h3>
                        <p class="mt-2">Apoie-nos mensalmente para um impacto contínuo.</p>
                    </div>
                </div>
                <div class="text-center mb-6">
                    <img src="/src/assets/images/qr.webp" alt="QR Code para Doação" class="mx-auto w-48 h-48 rounded-lg shadow-lg">
                    <p class="mt-4 text-lg">Use o QR Code acima para fazer sua doação.</p>
                </div>
                <div class="text-center">
                    <p class="text-lg mb-4"><strong>CNPJ:</strong> 56.986.342/0001-87</p>
                    <p class="text-lg mb-4"><strong>Nome:</strong> Sociedade Piracicabana de Proteção Aos Animais</p>
                    <p class="text-lg mb-4"><strong>Banco:</strong> Banco do Brasil S.A.</p>
                    <button onclick="Copiar()" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700">Copiar CNPJ</button>
                </div>
            </section>
        </main>
        <footer class="bg-gradient text-white py-4 mt-8">
            <div class="container mx-auto text-center">
                <p class="text-base">Agradecemos seu apoio e generosidade. Juntos, podemos fazer um mundo melhor para os animais!
                </p>
            </div>
        </footer>
        <?php include 'footer.php';?>
        </div>
        <div id="success-animation" class="fixed bottom-4 right-4 text-white px-5 py-2 rounded-lg flex items-center space-x-2 opacity-0 transform translate-x-10 transition-all duration-900 z-50">
            <i class="fas fa-check-circle"></i>
            <span>CNPJ copiado com sucesso!</span>
        </div>
        <script type="text/javascript" src="/src/assets/js/tip.js"></script>
    </body>
</html>