<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Eventos | SPPA</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta name="keywords" content="SPPA, atividades, eventos, workshops, participação, iniciativas, educação, comunidade, animais, adocao, adoção, adotar animal, adotar">
        <meta name="author" content="Sociedade Piracicabana de Proteção aos Animais">
        <meta name="robots" content="index, follow">

        <meta property="og:title" content="Eventos - Sociedade Piracicabana de Proteção aos Animais">
        <meta property="og:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta property="og:image" content="https://ongsppa.org/src/assets/images/sppa.webp">
        <meta property="og:url" content="https://ongsppa.org/">
        <meta property="og:type" content="website">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Eventos - Sociedade Piracicabana de Proteção aos Animais">
        <meta name="twitter:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta name="twitter:image" content="https://ongsppa.org/src/assets/images/sppa.webp">

        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="shortcut icon" type="imagex/png" href="/src/assets/images/1.ico">
        <link rel="stylesheet" type="text/css" href="/src/assets/css/events.css">
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    </head>
    <body class="bg-gray-50 bg-cover bg-center">
        <div class="z-50">
            <?php include 'navbar.php';?>
        <header class="bg-gradient text-white py-6 mt-20">
            <div class="container mx-auto text-center">
                <h1 class="text-3xl font-bold">Localização e Eventos</h1>
            </div>
        </header>
        
        <section class="bg-white p-8 rounded-lg shadow-lg z-0 mt-10 max-w-7xl mx-auto mb-10 ">
            <div class="mb-12">
                <h3 class="text-3xl font-semibold flex z-0 items-center text-blue-600 mb-6">
                        <i class="fas fa-map-marker-alt text-red-600 mr-4"></i>
                        Localização das Feirinhas de Adoção
                    </h3>
                <div class="w-full">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3675.7852955902255!2d-47.6609176!3d-22.7169375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c631fbfeb3fbef%3A0x5f76902a883d666f!2sAgropecu%C3%A1ria+do+Man%C3%A9+-+Nova+Piracicaba!5e0!3m2!1spt-BR!2sbr!4v1691940184425!5m2!1spt-BR!2sbr"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" class="rounded-lg shadow-lg"></iframe>
                </div>
            </div>
        </section>
        <?php include 'footer.php';?>
        <div id="success-animation" class="fixed bottom-4 right-4 text-white px-5 py-2 rounded-lg flex items-center space-x-2 opacity-0 transform translate-x-10 transition-all duration-900 z-50">
            <i class="fas fa-check-circle"></i>
            <span>CNPJ copiado com sucesso!</span>
        </div>
        <script type="text/javascript" src="/src/assets/js/events.js"></script>
    </body>
</html>