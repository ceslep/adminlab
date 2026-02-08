<script lang="ts">
	import { createEventDispatcher } from 'svelte';
	import { fade, fly } from 'svelte/transition'; // Corrected import to use fly
	import type { User } from '../types';
	import { login } from '../api';
	import { LOGO_URL } from '../constants';

	const dispatch = createEventDispatcher<{ loginSuccess: User }>();

	let username = '';
	let password = '';
	let errorMessage = '';
	let loading = false;

	async function handleSubmit(): Promise<void> {
		errorMessage = '';
		loading = true;

		try {
			// Simulate network delay for cinematic effect
			await new Promise((resolve) => setTimeout(resolve, 1500));
			// Enviamos el usuario y contraseña al backend
			const data = await login({ username: username.trim(), password });

			if (data.success && data.user) {
				dispatch('loginSuccess', data.user);
			} else {
				errorMessage = data.message || 'Error de autenticación.';
			}
		} catch (error) {
			console.error('Error durante la solicitud de login:', error);
			errorMessage = 'Error de conexión con el servidor.';
		} finally {
			loading = false;
		}
	}

	const formElements = [
		{ type: 'header' },
		{
			type: 'input',
			id: 'username',
			label: 'Usuario'
		},
		{
			type: 'input',
			id: 'password',
			label: 'Clave'
		},
		{ type: 'button', label: 'INICIAR SESIÓN' }
	];
</script>

<div class="relative flex flex-col items-center justify-center w-full h-screen p-4 overflow-hidden">
	<div in:fade={{ duration: 500, delay: 200 }}>
		<div
			in:fly={{ y: 50, duration: 600, delay: 200 }}
			class="card-levitate relative z-10 w-full max-w-sm p-8 bg-[var(--bg-slate-blue)] border border-[var(--dark-blue)] rounded-3xl shadow-2xl shadow-[var(--glow-blue)] backdrop-blur-2xl"
		>
			<form on:submit|preventDefault={handleSubmit} class="space-y-8">
				<!-- Header Element -->
				<div in:fade={{ duration: 400, delay: 500 }}>
					<div in:fly={{ y: 20, duration: 400, delay: 500 }}>
						<div class="text-center mb-4">
							<div
								class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden bg-white ring-2 ring-cyan-400 shadow-lg"
							>
								<img
									src={LOGO_URL}
									alt="Logo Laboratorio"
									class="w-full h-full object-contain p-2"
								/>
							</div>
							<h1 class="text-2xl font-bold text-white">Laboratorio Clínico</h1>
							<p class="text-cyan-300 text-sm">Ingrese sus credenciales de acceso</p>
						</div>
					</div>
				</div>

				<!-- Error Message -->
				{#if errorMessage}
					<div
						class="bg-red-500/20 text-red-300 p-3 rounded-lg text-sm flex items-center gap-2 border border-red-500/30 mb-4"
					>
						<svg
							xmlns="http://www.w3.org/2000/svg"
							class="h-5 w-5 flex-shrink-0"
							viewBox="0 0 20 20"
							fill="currentColor"
						>
							<path
								fill-rule="evenodd"
								d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
								clip-rule="evenodd"
							/>
						</svg>
						<span>{errorMessage}</span>
					</div>
				{/if}

				<!-- Username Input Element -->
				<div in:fade={{ duration: 400, delay: 620 }}>
					<div in:fly={{ y: 20, duration: 400, delay: 620 }}>
						<div class="relative group">
							<input
								type="text"
								id={formElements[1].id}
								bind:value={username}
								class="peer w-full px-4 py-3 bg-slate-800 rounded-xl border-2 border-slate-600 outline-none focus:ring-0 focus:border-transparent text-white placeholder-slate-400 transition-all duration-300 disabled:opacity-50"
								placeholder=" "
								required
								disabled={loading}
							/>
							<label
								for={formElements[1].id}
								class="absolute left-4 pointer-events-none text-cyan-300 transition-all duration-300 transform
                            peer-placeholder-shown:top-3 peer-placeholder-shown:text-base
                            peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-cyan-400
                            -top-2.5 text-xs"
							>
								{formElements[1].label}
							</label>
							<div
								class="absolute inset-0 rounded-xl border-2 border-transparent group-focus-within:border-cyan-400 group-focus-within:shadow-[0_0_15px_#06b6d4] transition-all duration-300 pointer-events-none"
							></div>
						</div>
					</div>
				</div>

				<!-- Password Input Element -->
				<div in:fade={{ duration: 400, delay: 740 }}>
					<div in:fly={{ y: 20, duration: 400, delay: 740 }}>
						<div class="relative group">
							<input
								type="password"
								id={formElements[2].id}
								bind:value={password}
								class="peer w-full px-4 py-3 bg-slate-800 rounded-xl border-2 border-slate-600 outline-none focus:ring-0 focus:border-transparent text-white placeholder-slate-400 transition-all duration-300 disabled:opacity-50"
								placeholder=" "
								required
								disabled={loading}
							/>
							<label
								for={formElements[2].id}
								class="absolute left-4 pointer-events-none text-cyan-300 transition-all duration-300 transform
                            peer-placeholder-shown:top-3 peer-placeholder-shown:text-base
                            peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-cyan-400
                            -top-2.5 text-xs"
							>
								{formElements[2].label}
							</label>
							<div
								class="absolute inset-0 rounded-xl border-2 border-transparent group-focus-within:border-cyan-400 group-focus-within:shadow-[0_0_15px_#06b6d4] transition-all duration-300 pointer-events-none"
							></div>
						</div>
					</div>
				</div>

				<!-- Button Element -->
				<div in:fade={{ duration: 400, delay: 860 }}>
					<div in:fly={{ y: 20, duration: 400, delay: 860 }}>
						<button
							type="submit"
							class="relative w-full py-3.5 px-6 rounded-xl font-bold text-white overflow-hidden
                       bg-gradient-to-r from-[var(--electric-blue)] to-[var(--tech-purple)]
                       transition-all duration-300 transform-gpu
                       hover:scale-105 hover:shadow-[0_0_25px_var(--glow-blue),0_0_40px_var(--glow-purple)]
                       active:scale-95 active:shadow-[0_0_10px_var(--glow-blue)]
                       disabled:opacity-60 disabled:cursor-not-allowed disabled:scale-100"
							disabled={loading}
						>
							<span class="relative z-10 flex items-center justify-center gap-3">
								{#if loading}
									<div class="loader"></div>
								{/if}
								{loading ? 'VERIFICANDO...' : formElements[3].label}
							</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<style>
	@keyframes levitate {
		0%,
		100% {
			transform: translateY(0);
		}
		50% {
			transform: translateY(-12px);
		}
	}
	.card-levitate {
		animation: levitate 7s ease-in-out infinite;
	}

	/* Pulse-out loading spinner */
	.loader {
		width: 20px;
		height: 20px;
		border-radius: 50%;
		border: 3px solid rgba(255, 255, 255, 0.3);
		border-left-color: white;
		animation: spin 1s linear infinite;
	}
	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}
</style>
