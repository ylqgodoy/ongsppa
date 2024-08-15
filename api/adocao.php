<?php
    session_start();

    include '../lib/database.php';
    date_default_timezone_set('America/Sao_Paulo');

    $um_ano = date('Y-m-d H:i:s', strtotime('-1 year'));
    $deletar = "DELETE FROM ads WHERE criado < ?";
    $stmtdel = mysqli_prepare($conn, $deletar);
    if ($stmtdel) {
        mysqli_stmt_bind_param($stmtdel, "s", $um_ano);
        mysqli_stmt_execute($stmtdel);
        mysqli_stmt_close($stmtdel);
    }

	function check_login_attempts($username) {
		if (!isset($_SESSION['login_attempts'][$username])) {
			$_SESSION['login_attempts'][$username] = 0;
		}
		$_SESSION['login_attempts'][$username]++;
		if ($_SESSION['login_attempts'][$username] > 5) {
			return false;
		}
		return true;
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['login'])) {
			$username = htmlspecialchars($_POST['username']);
			$password = $_POST['password'];

			if (check_login_attempts($username)) {
				$stored_hashed_password = password_hash('ongsppa', PASSWORD_DEFAULT);

				if ($username === 'ongsppa' && password_verify($password, $stored_hashed_password)) {
					session_regenerate_id();
					$_SESSION['logged_in'] = true;
					$_SESSION['login_attempts'][$username] = 0;
				} else {
					$_SESSION['logged_in'] = false;
				}
			}
		} elseif (isset($_POST['logout'])) {
			session_unset();
			session_destroy();
			header("Location: adocao.php");
			exit();
		} elseif (isset($_POST['nome'])) {
			if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
				$login_error = 'Você precisa estar logado para criar um anúncio.';
			} else {
				$nome = htmlspecialchars($_POST['nome']);
				$tipo = htmlspecialchars($_POST['tipo']);
				$idade = htmlspecialchars($_POST['idade']);
				$sexo = htmlspecialchars($_POST['sexo']);
				$descricao = htmlspecialchars($_POST['descricao']);
				$imagem = $_FILES['imagem']['tmp_name'];
				$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
				$mime_type = mime_content_type($imagem);

				if ($imagem && in_array($mime_type, $allowed_types)) {
					$conteudoimg = file_get_contents($imagem);
					$exclusao = date('Y-m-d H:i:s', strtotime('+1 year')); 

					$sql = "INSERT INTO ads (nome, descricao, idade, tipo, sexo, imagem, criado, exclusao) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
					$stmtcriar = mysqli_prepare($conn, $sql);

					if ($stmtcriar) {
						mysqli_stmt_bind_param($stmtcriar, "sssssss", $nome, $descricao, $idade, $tipo, $sexo, $conteudoimg, $exclusao);
						mysqli_stmt_execute($stmtcriar);
						mysqli_stmt_close($stmtcriar);
					}
				} else {
					$login_error = 'Tipo de arquivo de imagem inválido. Apenas JPEG, PNG, e GIF são permitidos.';
				}
			}
		}
	}

	// Garantir HTTPS
	// if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
	//     die('Conexão insegura. Utilize HTTPS.');
	// }

    if (isset($_GET['delete'])) {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header("Location: adocao.php");
            exit();
        }
        $id = $_GET['delete'];

        $sql = "DELETE FROM ads WHERE id = ?";
        $stmtdel2 = mysqli_prepare($conn, $sql);

        if ($stmtdel2) {
            mysqli_stmt_bind_param($stmtdel2, "i", $id);
            mysqli_stmt_execute($stmtdel2);
            mysqli_stmt_close($stmtdel2);
            header("Location: adocao.php");
            exit();
        }
    }

    $sql = "SELECT * FROM ads ORDER BY criado DESC";
    $result = mysqli_query($conn, $sql);

    $animals = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
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
    }
	
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Adoção de Animais Abandonados</title>
		<link href="../../dist/styles.css" rel="stylesheet">
		<script src="https://cdn.tailwindcss.com"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
		<style>
			#login-panel {
				max-height: 90vh;
				overflow: auto;   
			}
			
			body.modal-open {
				overflow: hidden;
			}
			
			.modal {
				display: none; 
				position: fixed; 
				z-index: 1; 
				left: 0;
				top: 0;
				width: 100%; 
				height: 100%; 
				overflow: auto; 
				background-color: rgba(0, 0, 0, 0.4); 
			}
		
			.modal-content {
				background-color: #fefefe;
				margin: 15% auto; 
				padding: 20px;
				border: 1px solid #888;
				width: 80%; 
				max-width: 600px; 
				border-radius: 10px;
				position: relative;
			}
		
			.modal-image {
				width: 100%;
				height: auto;
				border-radius: 10px;
				margin-bottom: 15px;
			}
		
			.close {
				color: #aaa;
				float: right;
				font-size: 28px;
				font-weight: bold;
				cursor: pointer;
			}
		
			.close:hover,
			.close:focus {
				color: black;
				text-decoration: none;
			}
		
			.instagram-button {
				display: inline-block;
				margin-top: 15px;
				padding: 10px 20px;
				background-color: #E4405F;
				color: white;
				font-weight: bold;
				border-radius: 5px;
				text-align: center;
				text-decoration: none;
			}
		
			.instagram-button:hover {
				background-color: #C13584;
			}
		
			.view-more {
				display: flex;
				justify-content: center;
				margin: 20px 0;
			}
		
			.view-more a {
				display: flex;
				align-items: center;
				font-size: 18px;
				color: #3182CE;
				text-decoration: none;
				font-weight: bold;
			}
		
			.view-more a:hover {
				color: #2B6CB0;
			}
		
			.view-more a svg {
				margin-left: 10px;
				width: 24px;
				height: 24px;
			}
		
			.back-to-top {
				position: fixed;
				bottom: 20px;
				right: 20px;
				background-color: #3182CE;
				color: white;
				border: none;
				border-radius: 50%;
				width: 50px;
				height: 50px;
				display: flex;
				align-items: center;
				justify-content: center;
				box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
				cursor: pointer;
				font-size: 24px;
			}
		
			.back-to-top:hover {
				background-color: #2B6CB0;
			}
			
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
							
			.hidden {
				display: none;
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
			
			@keyframes fadeIn {
				0% {
					opacity: 0;
					transform: translateY(-80px);
				}
				100% {
					opacity: 1;
					transform: translateY(0);
				}
			}

			@keyframes fadeOut {
				0% {
					opacity: 1;
					transform: translateY(0);
				}
				100% {
					opacity: 0;
					transform: translateY(-80px);
				}
			}

			.show-notification {
				animation: fadeIn 0.5s ease-out;
			}

			.hide-notification {
				animation: fadeOut 0.5s ease-out;
			}
		</style>
	</head>
	<body class="bg-gray-100 font-sans text-gray-900 bg-cover bg-center scroll-smooth">
		<header class="fixed top-0 left-0 w-full z-20">
			<nav class="flex items-center justify-between mx-auto bg-white shadow-md p-3 h-full">
				<a class="flex items-center" href="../../index.html">
					<img class="w-18 h-14 ml-2" src="../assets/images/sppa.png" alt="Logo SPPA">
				</a>
				<span id="hamburger-btn" class="text-2xl cursor-pointer block lg:hidden order-last">☰</span>
				<ul class="hidden lg:flex items-center gap-11 mx-auto h-full">
					<li class="py-1 h-full flex items-center">
						<a href="sobrenos.html" class="nav-link text-black text-lg rounded transition duration-300">QUEM SOMOS</a>
					</li>
					<li class="py-1 h-full flex items-center">
						<a href="#" class="nav-link text-black text-lg rounded transition duration-300">NOSSAS ATIVIDADES</a>
					</li>
					<li class="py-1 h-full flex items-center">
						<a href="adocao.php" class="nav-link text-black text-lg rounded transition duration-300">ADOÇÃO</a>
					</li>
					<li class="py-1 h-full flex items-center">
						<a href="#" class="nav-link text-black text-lg rounded transition duration-300">EVENTOS</a>
					</li>
					<li class="py-1 h-full flex items-center">
						<a href="#" class="nav-link text-black text-lg rounded transition duration-300">DOAÇÕES</a>
					</li>
					<li class="py-1 h-full flex items-center">
						<a href="#" class="nav-link text-black text-lg rounded transition duration-300">CONTATO</a>
					</li>
				</ul>
				<div class="hidden lg:flex gap-3">
					<a href="https://www.instagram.com/sppaongpiracicaba" target="_blank" class="text-pink-500 text-3xl">
						<i class="fab fa-instagram"></i>
					</a>
					<a href="https://www.facebook.com/groups/298032553718906/" target="_blank" class="text-blue-700 text-3xl">
						<i class="fab fa-facebook"></i>
					</a>
				</div>
				<span id="close-menu-btn" class="text-2xl cursor-pointer hidden lg:hidden">✖</span>
			</nav>
		</header>
		<div id="overlay" class="fixed inset-0 z-10 bg-black bg-opacity-50 hidden"></div>
		<div id="hamburger-menu" class="fixed inset-y-0 z-10 left-0 bg-white w-64 h-full shadow-lg hamburger-menu hidden flex items-center justify-center">
			<div class="w-full p-4 relative">
				<button id="close-btn" class="absolute top-[-30px] right-2 text-xl">✖</button>
				<ul class="flex flex-col gap-4 text-center">
					<li><a href="../../index.html" class="text-black text-lg rounded transition duration-300">INÍCIO</a></li>
					<li><a href="sobrenos.html" class="text-black text-lg rounded transition duration-300">QUEM SOMOS</a></li>
					<li><a href="#" class="text-black text-lg rounded transition duration-300">NOSSAS ATIVIDADES</a></li>
					<li><a href="adocao.php" class="text-black text-lg rounded transition duration-300">ADOÇÃO</a></li>
					<li><a href="#" class="text-black text-lg rounded transition duration-300">EVENTOS</a></li>
					<li><a href="#" class="text-black text-lg rounded transition duration-300">DOAÇÕES</a></li>
					<li><a href="#" class="text-black text-lg rounded transition duration-300">CONTATO</a></li>
				</ul>
			</div>
		</div>
		<div class="bg-white pt-12">
			<div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
				<div class="lg:text-center flex flex-col items-center">
					<p class="mt-2 text-3xl leading-8 font-extrabold text-center tracking-tight text-gray-900 sm:text-4xl">Encontre um Companheiro para Sua Vida</p>
					<p class="mt-4 max-w-2xl text-xl text-gray-500 text-center">Adote um amigo de quatro patas e faça a diferença na vida de um animal hoje mesmo. <span class="text-red-500 font-bold">Eles estão esperando por você!</span></p>
					</p>
				</div>
				<div class="mt-10">
					<hr>
					<dl class="space-y-10 pt-6 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
						<div class="relative">
							<dt>
								<div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="12" cy="12" r="10"></circle>
										<line x1="12" y1="8" x2="12" y2="12"></line>
										<line x1="12" y1="16" x2="12.01" y2="16"></line>
									</svg>
								</div>
								<p class="ml-16 text-lg leading-6 font-medium text-gray-900">Ensina Responsabilidade</p>
							</dt>
							<dd class="mt-2 ml-16 text-base text-gray-500"> Cuidar de um animal de estimação ensina responsabilidade, empatia e compaixão, especialmente para crianças. Eles aprendem a importância de alimentar, exercitar e cuidar de outro ser vivo. </dd>
						</div>
						<div class="relative">
							<dt>
								<div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
									</svg>
								</div>
								<p class="ml-16 text-lg leading-6 font-medium text-gray-900">Proporciona Alegria e Diversão</p>
							</dt>
							<dd class="mt-2 ml-16 text-base text-gray-500"> Animais de estimação trazem muita alegria e diversão para o lar. Suas travessuras engraçadas e personalidades únicas podem trazer momentos de felicidade e risos para toda a família. </dd>
						</div>
						<div class="relative">
							<dt>
								<div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 9v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9" />
										<path d="M9 22V12h6v10M2 10.6L12 2l10 8.6" />
									</svg>
								</div>
								<p class="ml-16 text-lg leading-6 font-medium text-gray-900">Salva Vidas</p>
							</dt>
							<dd class="mt-2 ml-16 text-base text-gray-500"> Ao adotar um animal de um abrigo ou resgate, você está potencialmente salvando uma vida. Muitos animais em abrigos estão à espera de um lar amoroso e uma segunda chance de vida. </dd>
						</div>
						<div class="relative">
							<dt>
								<div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
									</svg>
								</div>
								<p class="ml-16 text-lg leading-6 font-medium text-gray-900">Companheirismo e Amor</p>
							</dt>
							<dd class="mt-2 ml-16 text-base text-gray-500"> Animais de estimação são ótimos companheiros e podem oferecer amor incondicional. Eles podem ajudar a reduzir o estresse e a solidão, proporcionando uma fonte constante de apoio emocional. </dd>
						</div>
					</dl>
					<br>
					<hr>
					<div class="flex justify-center">
						<a href="#animais">
							<button id="buttonanimal" class="mt-12 bg-blue-600 text-white py-4 px-8 text-lg font-bold rounded-lg shadow-md hover:scale-110 transition-transform duration-500"> Adotar um peludinho </button>
						</a>
					</div>
				</div>
			</div>
		</div>
		<section id="animais" class="py-16 bg-[#FBFDFF]" style="background-image: url('../assets/images/patinhas.png'); background-size: 900px; background-repeat: repeat;">
			<div class="container mx-auto px-4">
				<div class="text-center mb-8">
					<h2 class="text-3xl font-bold mb-4">Animais Disponíveis</h2>
					<p class="text-lg text-gray-700 mb-8">Conheça alguns dos nossos adoráveis animais à espera de um novo lar.</p>
				</div>
				<div id="notification" class="fixed top-4 right-4 z-50 hidden bg-red-500 text-white p-4 ml-4 mt-20 rounded-lg shadow-lg">
					<p id="notification-message"></p>
				</div>
				<div class="container mx-auto p-4"> <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?> <div class="ad-creation-section mt-8">
						<div id="botao" class="flex items-center justify-between mb-6">
							<button id="toggleButton" class="btn bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" onclick="toggleForm()">Criar Anúncio +</button>
							<form action="adocao.php" method="post">
								<input type="submit" name="logout" class="btn-logout bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded cursor-pointer" value="Logout">
							</form>
						</div>
						<div id="formulario-criar-anuncio" class="ad-form hidden bg-white p-6 rounded-lg shadow-md mt-4">
							<h2 class="text-2xl font-bold mb-4">Criar Anúncio</h2>
							<form id="formanimal" action="adocao.php" method="post" enctype="multipart/form-data" class="space-y-4">
								<div>
									<label for="nome" class="block text-sm font-medium text-gray-700">Título do anúncio:</label>
									<input type="text" name="nome" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
								</div>
								<div>
									<label for="descricao" class="block text-sm font-medium text-gray-700">Descrição do anúncio:</label>
									<textarea name="descricao" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
								</div>
								<div>
									<label for="imagem" class="block text-sm font-medium text-gray-700">Imagem do anúncio:</label>
									<input type="file" name="imagem" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
								</div>
								<div>
									<label for="tipo" class="block text-sm font-medium text-gray-700">Tipo do animal:</label>
									<select name="tipo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
										<option value="Cachorro">Cachorro</option>
										<option value="Gato">Gato</option>
										<option value="Outros">Outros</option>
									</select>
								</div>
								<div>
									<label for="idade" class="block text-sm font-medium text-gray-700">Idade do animal:</label>
									<input type="text" name="idade" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
								</div>
								<div>
									<label for="sexo" class="block text-sm font-medium text-gray-700">Sexo do animal:</label>
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
				<div id="animalGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
				</div>
				<div id="viewMoreSection" class="view-more">
					<a href="#footer" id="viewMoreButton">Ver Mais <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
						</svg></a>
				</div>
			</div>
		</section>
		<div id="animalModal" class="modal">
			<div class="modal-content z-50 inset-0 px-5">
				<span class="close" onclick="window.closeModal('animalModal')">&times;</span>
				<!-- <img id="animalImage" class="modal-image" src="" alt="Imagem do Animal"> -->
				<p><strong>Nome:</strong> <span id="animalName" class="break-words whitespace-normal"></span></p>
				<p><strong>Tipo:</strong> <span id="animalType" class="break-words whitespace-normal"></span></p>
				<p><strong>Sexo:</strong> <span id="animalGender" class="break-words whitespace-normal"></span></p>
				<p><strong>Idade:</strong> <span id="animalAge" class="break-words whitespace-normal"></span></p>
				<p><strong>Descrição:</strong> <span id="animalDescription" class="break-words whitespace-normal"></span></p>
				<a href="https://www.instagram.com/direct/t/17842131356675478" class="instagram-button" target="_blank">Chamar no Instagram</a>
			</div>
		</div>
		<footer class="bg-[#021452]">
			<div class="container mx-auto py-10 px-4">
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
					<div>
						<h4 id="sppa-footer" class="text-lg text-white">SPPA</h4>
						<ul class="text-gray-300 mt-4 space-y-2">
							<li>Transformando vidas</li>
							<li class="flex space-x-4 mt-4">
								<div class="icon-wrapper icon-instagram">
									<a href="https://www.instagram.com/sppaongpiracicaba/" class="text-xl"><i class="fab fa-instagram"></i></a>
								</div>
								<div class="icon-wrapper icon-facebook">
									<a href="https://www.facebook.com/groups/298032553718906/" class="text-xl"><i class="fab fa-facebook-f"></i></a>
								</div>
								<div class="icon-wrapper icon-email">
									<a href="mailto:sppaong@gmail.com" class="text-xl"><i class="fas fa-envelope"></i></a>
								</div>
							</li>
						</ul>
					</div>
					<div>
						<h4 class="text-lg text-white">ONG</h4>
						<ul class="mt-4 space-y-2">
							<li><a href="sobrenos.html" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">QUEM SOMOS</a></li>
							<li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">NOSSAS ATIVIDADES</a></li>
							<li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">CONTATO</a></li>
						</ul>
					</div>
					<div>
						<h4 class="text-lg text-white">ANIMAIS</h4>
						<ul class="mt-4 space-y-2">
							<li><a href="adocao.php" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">ADOTE UM PELUDINHO</a></li>
							<li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">PONTOS DE ADOÇÃO</a></li>
							<li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">DOE AGORA</a></li>
						</ul>
					</div>
					<div class="flex items-start space-x-4">
						<div>
							<h4 class="text-lg text-white">DOE AGORA</h4>
							<div class="mt-4">
								<p class="text-gray-300"> CHAVE <span class="text-blue-400 font-bold">PIX</span> CNPJ:<br>
									<span id="cnpj">56.986.342/0001-87</span>
									<button class="ml-2 text-blue-400" onclick="Copiar()">
										<i class="far fa-copy"></i>
									</button>
								</p>
							</div>
						</div>
						<img src="../assets/images/qr.png" alt="QR Code" class="w-24 hiddenlowres">
					</div>
				</div>
			</div>
			<div class="bg-[#000b30] text-center text-[#808080] py-4"> &copy; 2024 Todos os direitos reservados </div>
		</footer>
		<div class="container mx-auto">
		<?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
			<div id="login-panel" class="login-panel hidden fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
				<div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
					<h2 class="text-2xl font-bold mb-4">Login</h2>
					<form action="adocao.php" method="post" class="login-form space-y-4">
						<div>
							<label for="username" class="block text-sm font-medium text-gray-700">Nome:</label>
							<input type="text" name="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
						</div>
						<div>
							<label for="password" class="block text-sm font-medium text-gray-700">Senha:</label>
							<input type="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
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
		<script>
			document.getElementById('sppa-footer').onclick = function() {
				document.getElementById('login-panel').style.display = 'flex';
			};
			
			document.getElementById('close-login-panel').onclick = function() {
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
				alert("CNPJ copiado: " + cnpj);
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
			
			window.openModal = function(modalId, image, name, type, gender, age, description) {
				const modal = document.getElementById(modalId);
				modal.style.display = 'block';
				
				/* document.getElementById('animalImage').src = image; */
				document.getElementById('animalName').textContent = name;
				document.getElementById('animalType').textContent = type;
				document.getElementById('animalGender').textContent = gender;
				document.getElementById('animalAge').textContent = age;
				document.getElementById('animalDescription').textContent = description;
				
				document.body.classList.add('modal-open');
			}
			
			window.closeModal = function(modalId) {
				document.getElementById(modalId).style.display = 'none';
				document.body.classList.remove('modal-open');
			}
			
			function populateAnimalGrid() {
		animalGrid.innerHTML = '';
		animals.slice(0, shownCount).forEach(animal => {
			const isLoggedIn = <?php echo json_encode(isset($_SESSION['logged_in']) && $_SESSION['logged_in']); ?>;
			animalGrid.innerHTML += `
				<div class="animal bg-indigo-50 rounded-lg shadow-lg border border-indigo-100">
					<img src="${animal.image}" alt="${animal.name}" class="w-full h-80 object-cover rounded-t-lg mb-4">
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
					window.location.href = `adocao.php?delete=${id}`;
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
					}, 500); // Tempo da animação de desaparecimento
				}, 5000); // Tempo de exibição da notificação
			}

			<?php if (isset($login_error)): ?>
				document.addEventListener('DOMContentLoaded', () => {
					showNotification('<?php echo addslashes($login_error); ?>');
				});
			<?php endif; ?>
		</script>
	</body>
</html>