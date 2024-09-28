<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Sobre nós | SPPA</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta name="keywords" content="SPPA, atividades, eventos, workshops, participação, iniciativas, educação, comunidade, animais, adocao, adoção, adotar animal, adotar">
        <meta name="author" content="Sociedade Piracicabana de Proteção aos Animais">
        <meta name="robots" content="index, follow">

        <meta property="og:title" content="Sobre nós - Sociedade Piracicabana de Proteção aos Animais">
        <meta property="og:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta property="og:image" content="https://ongsppa.org/src/assets/images/sppa.webp">
        <meta property="og:url" content="https://ongsppa.org/">
        <meta property="og:type" content="website">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Sobre nós - Sociedade Piracicabana de Proteção aos Animais">
        <meta name="twitter:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta name="twitter:image" content="https://ongsppa.org/src/assets/images/sppa.webp">

        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="shortcut icon" type="imagex/png" href="/src/assets/images/1.ico">
        <link rel="stylesheet" type="text/css" href="/src/assets/css/info.css">
    </head>
    <body class="bg-gray-50 bg-cover bg-center">
        <div class="z-50">
          <?php include 'navbar.php';?>
        <section id="sobre-nos" class="py-16 bg-white parallax-bg" style="background-image: url('/src/assets/images/patinhas2.webp'); background-size: 900px; background-repeat: repeat;">
            <div class="container mx-auto px-4">
                <header class="bg-gradient text-white py-6 mt-20">
                    <div class="container mx-auto text-center">
                        <h1 class="text-3xl font-bold">Sobre Nós</h1>
                    </div>
                </header>
                <div class="flex flex-col lg:flex-row items-center lg:items-start lg:justify-between">
                    <div class="lg:w-1/2 flex flex-col gap-6">
                        <img src="/src/assets/images/dog.webp" alt="Imagem de cachorro" class="w-full h-1/2 object-cover rounded-lg mb-6">
                    </div>
                    <div class="lg:w-1/2 lg:pl-12">
                        <h3 class="text-2xl font-semibold mt-8">Quem Somos?</h3>
                        <p class="text-gray-700 mt-4"> A <span class="underline font-bold">Sociedade Piracicabana de Proteção aos Animais</span> (SPPA), fundada em 1989 e formada exclusivamente por voluntários, é uma entidade sem fins lucrativos situada em Piracicaba. Declarada de Utilidade
                            Pública, a SPPA promove o trabalho voluntário local e apoia a criação de ONGs em todo o Brasil. Contamos com uma equipe dedicada e o apoio de sócios contribuintes para continuar nosso trabalho em prol dos animais necessitados.
                            Somos afiliados à Sociedade Mundial de Proteção Animal (WSPA), que atua em defesa do bem-estar animal há mais de 70 anos. Para ajudar, clique em "<a href="doacao" class="text-blue-700 font-bold underline">Doações</a>". </p>
                        <h3 class="text-2xl font-semibold mt-8">Nossa Missão</h3>
                        <p class="text-gray-700 mt-4"> Ser uma referência nacional em proteção e <span class="text-emerald-600 font-bold">bem-estar</span> animal, promovendo a educação e a conscientização sobre posse responsável e cuidados adequados com os animais. Estamos comprometidos
                            em combater o abandono, os maus-tratos e a proliferação descontrolada de animais sem dono. Para isso, trabalhamos com legislações eficazes, implementamos programas de castração e incentivamos a posse responsável. </p>
                        <h3 class="text-2xl font-semibold mt-8">Equipe Diretiva</h3>
                        <p class="text-gray-700 mt-4">A equipe diretiva da SPPA é composta por <span class="text-red-400 font-bold">profissionais de diversas áreas</span>, todos voluntários dedicados que doam parte de seu tempo para liderar e conduzir os projetos da entidade. </p>
                        <h3 class="text-2xl font-semibold mt-8">Visão</h3>
                        <p class="text-gray-700 mt-4">Aspiramos a um futuro em que o abandono, os maus-tratos e a proliferação descontrolada de animais sejam erradicados por meio de legislações eficazes, programas de castração e da atuação de voluntários comprometidos. </p>
                    </div>
                </div>
            </div>
        </section>
        <p class="text-center py-4 italic text-lg">"O que nos une é o amor aos animais de forma racional, transformando nossa sensibilização em ações em favor dos animais."</p>
        <?php include 'footer.php';?>
        <div id="success-animation" class="fixed bottom-4 right-4 text-white px-5 py-2 rounded-lg flex items-center space-x-2 opacity-0 transform translate-x-10 transition-all duration-900 z-50">
            <i class="fas fa-check-circle"></i>
            <span>CNPJ copiado com sucesso!</span>
        </div>
        <script type="text/javascript" src="/src/assets/js/info.js"></script>
    </body>
</html>