<script lang="ts">
	import { createEventDispatcher } from 'svelte';
	import { fade, fly, scale } from 'svelte/transition';
	import { cubicOut } from 'svelte/easing';
	import { Eye, EyeOff, Lock, Mail, Sparkles, Loader2 } from 'lucide-svelte';
	import type { User } from '../types';
	import { login } from '../api';
	import { LOGO_URL } from '../constants';

	const dispatch = createEventDispatcher<{ loginSuccess: User }>();

	let username = '';
	let password = '';
	let errorMessage = '';
	let loading = false;
	let showPassword = false;

	async function handleSubmit(): Promise<void> {
		errorMessage = '';
		loading = true;

		try {
			console.log('🔐 Intentando login con:', { username: username.trim(), password: '***' });
			
			// Simulate network delay for cinematic effect
			await new Promise((resolve) => setTimeout(resolve, 1500));
			
			// Opción 1: Usar API real (comentar para usar modo demo)
			/*
			const data = await login({ username: username.trim(), password });
			
			console.log('📥 Respuesta del API:', data);

			if (data.success && data.user) {
				console.log('✅ Login exitoso, despachando evento:', data.user);
				dispatch('loginSuccess', data.user);
			} else {
				console.log('❌ Login fallido:', data.message);
				errorMessage = data.message || 'Error de autenticación.';
			}
			*/
			
			// Opción 2: Modo demo temporal (descomentar para usar)
			if (username.trim() && password) {
				console.log('🎭 Modo demo - Login exitoso');
				const mockUser = {
					nombre: 'Laboratorio Demo',
					usuario: username.trim(),
					lab_id: 'demo123'
				};
				dispatch('loginSuccess', mockUser);
			} else {
				errorMessage = 'Por favor ingrese usuario y contraseña.';
			}
			
		} catch (error) {
			console.error('🚨 Error durante la solicitud de login:', error);
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

<div class="login-container">
	<div in:fade={{ duration: 500, delay: 200 }}>
		<div
			in:fly={{ y: 50, duration: 600, delay: 200, easing: cubicOut }}
			class="login-card"
		>
			<form on:submit|preventDefault={handleSubmit} class="space-y-8">
				<!-- Header Element -->
				<div in:fade={{ duration: 400, delay: 500 }}>
					<div in:fly={{ y: 20, duration: 400, delay: 500 }}>
					<div class="login-header">
							<div
								in:scale={{ duration: 500, delay: 400, easing: cubicOut }}
								class="logo-glass"
							>
								<div class="logo-glow"></div>
								<img
									src={LOGO_URL}
									alt="Logo Laboratorio"
									class="logo-image"
								/>
								<Sparkles class="logo-sparkle" />
							</div>
							<div in:fly={{ y: 20, duration: 500, delay: 600, easing: cubicOut }}>
								<h1 class="login-title">Laboratorio Clínico</h1>
								<p class="login-subtitle">
									<Sparkles class="subtitle-icon" />
									Ingrese sus credenciales de acceso
								</p>
							</div>
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
	/* Pixel Perfect Login Container */
	.login-container {
		position: relative;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		width: 100vw;
		height: 100vh;
		padding: 16px;
		overflow: hidden;
		background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
	}

	.login-container::before,
	.login-container::after {
		content: '';
		position: absolute;
		border-radius: 50%;
		filter: blur(40px);
		pointer-events: none;
	}

	.login-container::before {
		top: -50%;
		right: -10%;
		width: 800px;
		height: 800px;
		background: radial-gradient(circle at 30% 70%, rgba(99, 102, 241, 0.08) 0%, transparent 50%);
	}

	.login-container::after {
		bottom: -50%;
		left: -10%;
		width: 600px;
		height: 600px;
		background: radial-gradient(circle at 70% 30%, rgba(139, 92, 246, 0.06) 0%, transparent 50%);
	}

	/* Pixel Perfect Login Card */
	.login-card {
		position: relative;
		z-index: 10;
		width: 100%;
		max-width: 512px;
		padding: 48px;
		background: rgba(255, 255, 255, 0.7);
		backdrop-filter: blur(20px);
		border: 1px solid rgba(255, 255, 255, 0.2);
		border-radius: 32px;
		box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
	}

	/* Card Levitation Animation */
	.login-card {
		animation: levitate 8s ease-in-out infinite;
	}

	@keyframes levitate {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-16px);
		}
	}

	/* Login Header */
	.login-header {
		text-align: center;
		margin-bottom: 32px;
	}

	.logo-glass {
		position: relative;
		width: 96px;
		height: 96px;
		border-radius: 24px;
		margin: 0 auto 32px;
		background: linear-gradient(135deg, var(--indigo-500), var(--violet-500), var(--pink-500));
		box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
		transition: transform 500s cubic-bezier(0.4, 0, 0.2, 1);
	}

	.logo-glass:hover {
		transform: scale(1.05);
	}

	.logo-glow {
		position: absolute;
		inset: 0;
		background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
		border-radius: 24px;
	}

	.logo-image {
		width: 100%;
		height: 100%;
		object-fit: contain;
		padding: 16px;
		position: relative;
		z-index: 10;
		transition: transform 500s cubic-bezier(0.4, 0, 0.2, 1);
	}



	@keyframes pulse {
		0%, 100% {
			opacity: 1;
		}
		50% {
			opacity: 0.5;
		}
	}

	.login-title {
		font-size: 48px;
		font-weight: 800;
		margin: 0 0 12px 0;
		line-height: 56px;
		background: linear-gradient(135deg, var(--slate-900), var(--violet-900), var(--slate-900));
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}

	.login-subtitle {
		font-size: 16px;
		font-weight: 500;
		color: var(--slate-600);
		margin: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		line-height: 24px;
	}



	/* Enhanced Form Elements */
	form {
		display: flex;
		flex-direction: column;
		gap: 24px;
	}

	/* Pixel Perfect Form Controls */
	.peer {
		width: 100%;
		padding-left: 48px;
		padding-right: 48px;
		padding-top: 16px;
		padding-bottom: 16px;
		font-weight: 500;
		border-radius: 16px;
		border: 1px solid var(--slate-200);
		outline: none;
		color: var(--slate-900);
		background: rgba(241, 245, 249, 0.6);
		transition: all 300s cubic-bezier(0.4, 0, 0.2, 1);
		font-size: 16px;
	}

	.peer:hover {
		background: rgba(241, 245, 249, 0.8);
	}

	.peer:focus {
		background: rgba(255, 255, 255, 0.9);
		border-color: var(--indigo-500);
		box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 0 0 1px rgba(99, 102, 241, 0.6);
	}


	
	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}

	/* Responsive Design */
	@media (max-width: 768px) {
		.login-container {
			padding: 16px;
		}

		.login-card {
			padding: 32px 24px;
		}

		.login-title {
			font-size: 36px;
			line-height: 44px;
		}

		.login-subtitle {
			font-size: 14px;
		}
	}
	
	/* Card Levitation Animation */
	.login-card {
		animation: levitate 8s ease-in-out infinite;
	}

	@keyframes levitate {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-16px);
		}
	}


	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}
</style>
