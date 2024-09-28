document.getElementById('sppa-footer').onclick = function () {
			document.getElementById('login-panel').style.display = 'flex';
		};

		document.getElementById('close-login-panel').onclick = function () {
			document.getElementById('login-panel').style.display = 'none';
		};

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
   
		let shownCount = 6;
		const animalsPerPage = 6;
		const animalGrid = document.getElementById('animalGrid');
		const viewMoreButton = document.getElementById('viewMoreButton');
		const viewMoreSection = document.getElementById('viewMoreSection');

		window.openModal = function (modalId, image, name, type, gender, age, description) {
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

		window.closeModal = function (modalId) {
			document.getElementById(modalId).style.display = 'none';
			document.body.classList.remove('modal-open');
		}

		function populateAnimalGrid() {
			animalGrid.innerHTML = '';
			animals.slice(0, shownCount).forEach(animal => {
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
				window.location.href = `adocao?delete=${id}`;
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