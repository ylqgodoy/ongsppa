<?php
session_start();
include '../lib/database.php';
date_default_timezone_set('America/Sao_Paulo');

$one_year_ago = date('Y-m-d H:i:s', strtotime('-1 year'));
$delete_query = "DELETE FROM ads WHERE criado < ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("s", $one_year_ago);
$stmt->execute();
$stmt->close();

function check_login_attempts($username)
{
    if (!isset($_SESSION['login_attempts'][$username])) {
        $_SESSION['login_attempts'][$username] = 0;
    }
    $_SESSION['login_attempts'][$username]++;
    return $_SESSION['login_attempts'][$username] <= 5;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        if (check_login_attempts($username)) {
            $stored_hashed_password = password_hash('ongsppa1', PASSWORD_DEFAULT);

            if ($username === 'ongsppa1' && password_verify($password, $stored_hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['logged_in'] = true;
                $_SESSION['login_attempts'][$username] = 0;
            } else {
                $_SESSION['logged_in'] = false;
                $login_error = 'Invalid credentials';
            }
        } else {
            $login_error = 'Too many login attempts';
        }
    } elseif (isset($_POST['logout'])) {
    		session_unset();
    		session_destroy();
    		header("Location: adotar");
    		exit();
    	} elseif (isset($_POST['nome'])) {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $login_error = 'You must be logged in to create an ad.';
        } else {
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
            $idade = filter_input(INPUT_POST, 'idade', FILTER_SANITIZE_STRING);
            $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_STRING);
            $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
            $imagem = $_FILES['imagem']['tmp_name'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $mime_type = mime_content_type($imagem);

            if ($imagem && in_array($mime_type, $allowed_types)) {
                $conteudoimg = file_get_contents($imagem);
                $exclusao = date('Y-m-d H:i:s', strtotime('+1 year'));

                $sql = "INSERT INTO ads (nome, descricao, idade, tipo, sexo, imagem, criado, exclusao) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $nome, $descricao, $idade, $tipo, $sexo, $conteudoimg, $exclusao);
                $stmt->execute();
                $stmt->close();
            } else {
                $login_error = 'Invalid image file type. Only JPEG, PNG, and GIF are allowed.';
            }
        }
    }
}

if (isset($_GET['delete'])) {
	if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
		header("Location: adotar");
		exit();
	}
	$id = $_GET['delete'];

	$sql = "DELETE FROM ads WHERE id = ?";
	$stmtdel2 = mysqli_prepare($conn, $sql);

	if ($stmtdel2) {
		mysqli_stmt_bind_param($stmtdel2, "i", $id);
		mysqli_stmt_execute($stmtdel2);
		mysqli_stmt_close($stmtdel2);
		header("Location: adotar");
		exit();
	}
}

$sql = "SELECT * FROM ads ORDER BY criado DESC";
$result = $conn->query($sql);
$animals = [];
while ($row = $result->fetch_assoc()) {
    $animals[] = [
        'id' => $row['id'],
        'name' => $row['nome'],
        'type' => $row['tipo'],
        'gender' => $row['sexo'],
        'age' => $row['idade'],
        'description' => $row['descricao'],
        'image' => 'data:image/jpeg;base64,' . base64_encode($row['imagem'])
    ];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Pets para adoção | SPPA</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta name="keywords" content="SPPA, atividades, eventos, workshops, participação, iniciativas, educação, comunidade, animais, adocao, adoção, adotar animal, adotar">
        <meta name="author" content="Sociedade Piracicabana de Proteção aos Animais">
        <meta name="robots" content="index, follow">

        <meta property="og:title" content="Pets para adoção - Sociedade Piracicabana de Proteção aos Animais">
        <meta property="og:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta property="og:image" content="https://ongsppa.org/src/assets/images/sppa.webp">
        <meta property="og:url" content="https://ongsppa.org/">
        <meta property="og:type" content="website">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Pets para adoção - Sociedade Piracicabana de Proteção aos Animais">
        <meta name="twitter:description" content="Descubra as atividades do SPPA, incluindo eventos, workshops e muito mais. Fique por dentro das nossas iniciativas e participe!">
        <meta name="twitter:image" content="https://ongsppa.org/src/assets/images/sppa.webp">

        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="shortcut icon" type="imagex/png" href="/src/assets/images/1.ico">
        <link rel="stylesheet" type="text/css" href="/src/assets/css/adoption.css">
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 bg-cover bg-center scroll-smooth">
        <div class="z-50">
            <?php include 'navbar.php';?>
        <div class="bg-white/60 pt-12">
            <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                <div class="lg:text-center flex flex-col items-center">
                    <p class="mt-10 text-3xl leading-8 font-extrabold text-center tracking-tight text-gray-900 sm:text-4xl">
                        Encontre um Companheiro para Sua Vida</p>
                    <p class="mt-4 mb-6 max-w-2xl text-xl text-gray-500 text-center">Adote um amigo de quatro patas e faça a diferença na vida de um animal hoje mesmo. <span class="text-red-500 font-bold">Eles estão esperando por você!</span></p>
                    </p>
                </div>
                <div class="mt-10">
                    <hr class="mb-10">
                    <dl class="space-y-10 pt-6 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        <div class="relative">
                            <dt>
                                <div
                                    class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-emerald-600 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Ensina Responsabilidade</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500"> Cuidar de um animal de estimação ensina responsabilidade, empatia e compaixão, especialmente em crianças. </dd>
                        </div>
                        <div class="relative">
                            <dt>
                                <div
                                    class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-yellow-400 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3">
                                        </path>
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Proporciona Alegria e Diversão
                                </p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500"> Animais de estimação trazem alegria e diversão ao lar com suas travessuras, proporcionando felicidade à família. </dd>
                        </div>
                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-red-400 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M20 9v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9" />
                                        <path d="M9 22V12h6v10M2 10.6L12 2l10 8.6" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Salva Vidas</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500"> Adotar um animal de um abrigo pode salvar uma vida, dando a ele uma segunda chance em um lar amoroso. </dd>
                        </div>
                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-pink-400 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Companheirismo e Amor</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500"> Animais de estimação oferecem amor incondicional e apoio emocional, ajudando a reduzir o estresse e a solidão. </dd>
                        </div>
                    </dl>
                    <br>
                    <hr class="mt-10">
                    <div class="flex justify-center">
                        <a href="#animais">
                            <button id="buttonanimal" class="mt-12 bg-blue-600 text-white py-4 px-8 text-lg font-bold rounded-2xl shadow-md hover:scale-110 transition-transform duration-500">
                                Adotar um peludinho <img src="/src/assets/images/btn.webp" class="inline w-6 ml-2">
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <section id="animais" class="py-16 bg-gray-100" style="background-image: url('/src/assets/images/patinhas.webp'); background-size: 900px; background-repeat: repeat;">
            <div class="container mx-auto px-4">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold mb-4">Animais Disponíveis</h2>
                    <p class="text-lg text-gray-700 mb-8">Conheça alguns dos nossos adoráveis animais à espera de um novo lar.
                    </p>
                </div>
                <div id="notification" class="fixed top-4 right-4 z-50 hidden bg-red-500 text-white p-4 ml-4 mt-20 rounded-lg shadow-lg">
                    <p id="notification-message"></p>
                </div>
                <div class="container mx-auto p-4">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <div class="ad-creation-section mt-8">
                            <div id="botao" class="flex items-center justify-between mb-6">
                                <button id="toggleButton" class="btn bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" onclick="toggleForm()">Criar Anúncio +</button>
                                <form action="adotar" method="post">
                                    <input type="submit" name="logout" class="btn-logout bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded cursor-pointer" value="Logout">
                                </form>
                            </div>
                            <div id="formulario-criar-anuncio" class="ad-form hidden bg-white p-6 rounded-lg shadow-md mt-4">
                                <h2 class="text-2xl font-bold mb-4">Criar Anúncio</h2>
                                <form id="formanimal" action="adotar" method="post" enctype="multipart/form-data" class="space-y-4">
                                    <div>
                                        <label for="nome" class="block text-sm font-medium text-gray-700">Título do anúncio:
                                        </label>
                                        <input type="text" name="nome" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição do anúncio:
                                        </label>
                                        <textarea name="descricao" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
                                    </div>
                                    <div>
                                        <label for="imagem" class="block text-sm font-medium text-gray-700">Imagem do anúncio:
                                        </label>
                                        <input type="file" name="imagem" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                                    </div>
                                    <div>
                                        <label for="tipo" class="block text-sm font-medium text-gray-700">Espécie do animal:
                                        </label>
                                        <select name="tipo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            <option value="Cachorro">Cachorro</option>
                                            <option value="Gato">Gato</option>
                                            <option value="Outros">Outros</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="idade" class="block text-sm font-medium text-gray-700">Idade aproximada:
                                        </label>
                                        <input type="text" name="idade" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    </div>
                                    <div>
                                        <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo do animal:
                                        </label>
                                        <select name="sexo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            <option value="Macho">Macho</option>
                                            <option value="Femea">Fêmea</option>
                                        </select>
                                    </div>
                                    <input type="submit" value="Criar Anúncio" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                </div>
                <div id="animalGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 mb-8">
                </div>
                <div id="viewMoreSection" class="view-more">
                    <a href="#footer" id="viewMoreButton">Ver Mais <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg></a>
                </div>
            </div>
        </section>
        <div id="animalModal" class="modal z-50">
            <div class="modal-content z-50 inset-0 px-5">
                <span class="close" onclick="window.closeModal('animalModal')">&times;</span>
                <p><strong>Nome:</strong> <span id="animalName" class="break-words whitespace-normal"></span></p>
                <p><strong>Espécie:</strong> <span id="animalType" class="break-words whitespace-normal"></span></p>
                <p><strong>Sexo:</strong> <span id="animalGender" class="break-words whitespace-normal"></span></p>
                <p><strong>Idade aproximada:</strong> <span id="animalAge" class="break-words whitespace-normal"></span></p>
                <p><strong>Descrição:</strong> <span id="animalDescription" class="break-words whitespace-normal"></span>
                </p>
                <a href="https://www.instagram.com/direct/t/17842131356675478" class="instagram-button" target="_blank">Chamar no Instagram</a>
            </div>
        </div>
        <?php include 'footer.php';?>
        <div class="container mx-auto">
            <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
                <div id="login-panel" class="mt-20 login-panel hidden fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
                    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
                        <h2 class="text-2xl font-bold mb-4">Login</h2>
                        <form action="adotar" method="post" class="login-form space-y-4">
                            <div>
                                Nome:
                                <input type="text" name="username" id="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required autocomplete="off">
                            </div>
                            <div>
                                Senha:
                                <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            <input type="submit" name="login" value="Login" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            <button id="close-login-panel" class="mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded cursor-pointer">Fechar</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button onclick="scrollToTop()" class="back-to-top">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19V6M5 12l7-7 7 7" />
            </svg>
        </button>
        <div id="success-animation" class="fixed bottom-20 right-4 text-white px-5 py-2 rounded-lg flex items-center space-x-2 opacity-0 transform translate-x-10 transition-all duration-900 z-50">
            <i class="fas fa-check-circle"></i>
            <span>CNPJ copiado com sucesso!</span>
        </div>
        <script>
            document.getElementById('sppa-footer').onclick = function () {
                document.getElementById('login-panel').style.display = 'flex';
            };
            
            document.getElementById('close-login-panel').onclick = function () {
                document.getElementById('login-panel').style.display = 'none';
            };
        </script>
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
                });
            
                closeBtn.addEventListener('click', () => {
                    hamburgerMenu.classList.remove('active');
                    hamburgerMenu.classList.add('hidden');
                    overlay.classList.remove('active');
                    overlay.classList.add('hidden');
                });
            
                overlay.addEventListener('click', () => {
                    hamburgerMenu.classList.remove('active');
                    hamburgerMenu.classList.add('hidden');
                    overlay.classList.remove('active');
                    overlay.classList.add('hidden');
                });
            });

            function Copiar() {
                var cnpj = document.getElementById("cnpj").innerText;
                var tempInput = document.createElement("input");
                tempInput.value = cnpj;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                var successAnimation = document.getElementById("success-animation");
                successAnimation.classList.remove('opacity-0', 'translate-x-10');
                successAnimation.classList.add('opacity-100', 'translate-x-0');
                setTimeout(function () {
                    successAnimation.classList.remove('opacity-100', 'translate-x-0');
                    successAnimation.classList.add('opacity-0', 'translate-x-10');
                }, 2000);
            }

            function toggleForm() {
                var form = document.getElementById("formulario-criar-anuncio");
                var button = document.getElementById("toggleButton");
                if (form.classList.contains("hidden")) {
                    form.classList.remove("hidden");
                    button.innerHTML = "Criar Anúncio -";
                } else {
                    form.classList.add("hidden");
                    button.innerHTML = "Criar Anúncio +";
                }
            }
    
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const closeMenuBtn = document.getElementById('close-menu-btn');
            const navbar = document.querySelector('header');
    
            hamburgerBtn.addEventListener('click', () => {
                navbar.classList.add('show-mobile-menu');
            });
    
            closeMenuBtn.addEventListener('click', () => {
                navbar.classList.remove('show-mobile-menu');
            });
            const animals = <?php echo json_encode($animals); ?>;
    
            let shownCount = 6;
            const animalsPerPage = 6;
            const animalGrid = document.getElementById('animalGrid');
            const viewMoreButton = document.getElementById('viewMoreButton');
            const viewMoreSection = document.getElementById('viewMoreSection');
    
            window.openModal = function (modalId, image, name, type, gender, age, description) {
                const modal = document.getElementById(modalId);
                modal.style.display = 'block';
    
                document.getElementById('animalName').textContent = name;
                document.getElementById('animalType').textContent = type;
                document.getElementById('animalGender').textContent = gender;
                document.getElementById('animalAge').textContent = age;
                document.getElementById('animalDescription').textContent = description;
    
                document.body.classList.add('modal-open');
            }
    
            window.closeModal = function (modalId) {
                document.getElementById(modalId).style.display = 'none';
                document.body.classList.remove('modal-open');
            }
    
            function populateAnimalGrid() {
                animalGrid.innerHTML = '';
                animals.slice(0, shownCount).forEach(animal => {
                    const isLoggedIn = <?php echo json_encode(isset($_SESSION['logged_in']) && $_SESSION['logged_in']); ?>;
                    animalGrid.innerHTML += `
                            <div class="animal bg-white rounded-lg shadow-xl border border-indigo-100">
                                <div class="text-center">
                                    <h2 class="py-2 px-4 bg-blue-600 text-xl rounded-t-lg font-bold text-white">Para Adoção</h2>
                                    <img src="${animal.image}" alt="${animal.name}" class="w-full h-80 object-cover mb-4">
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold mb-2">${animal.name}</h3>
                                    <p class="text-gray-700 break-words mb-4">${animal.description}</p>
                                    <button onclick="window.openModal('animalModal', '${animal.image}', '${animal.name}', '${animal.type}', '${animal.gender}', '${animal.age}', '${animal.description}')" class="bg-blue-500 text-white hover:bg-blue-600 py-2 px-4 rounded-lg">Saiba Mais</button>
                                    ${isLoggedIn ? `<button onclick="deleteAnimal(${animal.id})" class="bg-red-700 text-white hover:bg-red-800 py-2 px-4 rounded-lg mt-2">Deletar</button>` : ''}
                                </div>
                            </div>
                        `;
                });
            }
    
            function toggleAnimals() {
                if (shownCount >= animals.length) {
                    shownCount = 6;
                    viewMoreButton.innerHTML = 'Ver Mais <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                    populateAnimalGrid();
                } else {
                    shownCount += animalsPerPage;
                    if (shownCount >= animals.length) {
                        shownCount = animals.length;
                        viewMoreButton.innerHTML = 'Ver Menos <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>';
                    }
                    populateAnimalGrid();
                }
            }
    
            viewMoreButton.addEventListener('click', toggleAnimals);
            populateAnimalGrid();
            viewMoreButton.innerHTML = 'Ver Mais <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
    
            function scrollToTop() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
    
            function deleteAnimal(id) {
                if (confirm('Tem certeza que deseja deletar este item?')) {
                    window.location.href = `adotar?delete=${id}`;
                }
            }
    
            function showNotification(message) {
                const notification = document.getElementById('notification');
                const messageElement = document.getElementById('notification-message');
    
                messageElement.textContent = message;
                notification.classList.remove('hidden');
                notification.classList.add('show-notification');
    
                setTimeout(() => {
                    notification.classList.remove('show-notification');
                    notification.classList.add('hide-notification');
    
                    setTimeout(() => {
                        notification.classList.add('hidden');
                        notification.classList.remove('hide-notification');
                    }, 500);
                }, 5000);
            }
    
            <?php if (isset($login_error)): ?>
                document.addEventListener('DOMContentLoaded', () => {
                    showNotification('<?php echo addslashes($login_error); ?>');
                });
            <?php endif; ?>
        </script>
    </body>
</html>