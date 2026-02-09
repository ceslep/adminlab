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
			
			// Modo demo temporal
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

<div class="pixel-perfect-container">
	<!-- Background decorative elements -->
	<div class="background-elements">
		<div 
			class="bg-gradient-circle-1"
			in:scale={{ duration: 2000, easing: cubicOut }}
		></div>
		<div 
			class="bg-gradient-circle-2"
			in:scale={{ duration: 2000, delay: 500, easing: cubicOut }}
		></div>
	</div>

	<div in:fade={{ duration: 500, delay: 200 }}>
		<div
			in:fly={{ y: 50, duration: 600, delay: 200, easing: cubicOut }}
			class="login-card"
		>
			<form on:submit|preventDefault={handleSubmit} class="login-form">
				<!-- Header Element -->
				<div class="login-header">
					<div
						in:scale={{ duration: 500, delay: 400, easing: cubicOut }}
						class="logo-container"
					>
						<div class="logo-gradient">
							<div class="logo-overlay"></div>
							<img
								src={LOGO_URL}
								alt="Logo Laboratorio"
								class="logo-image"
							/>
							<Sparkles class="logo-sparkle" />
						</div>
					</div>
					<div in:fly={{ y: 20, duration: 500, delay: 600, easing: cubicOut }}>
						<h1 class="login-title">Laboratorio Clínico</h1>
						<p class="login-subtitle">
							<Sparkles class="subtitle-icon" />
							Ingrese sus credenciales de acceso
						</p>
					</div>
				</div>

				<!-- Error Message -->
				{#if errorMessage}
					<div
						in:fly={{ x: -20, duration: 300 }}
						class="error-message"
					>
						<Eye class="error-icon" />
						<span>{errorMessage}</span>
					</div>
				{/if}

				<!-- Username Input Element -->
				<div in:fade={{ duration: 400, delay: 620 }}>
					<div in:fly={{ y: 20, duration: 400, delay: 620, easing: cubicOut }}>
						<div class="input-group">
							<div class="input-icon">
								<Mail class="icon" />
							</div>
							<input
								type="text"
								id={formElements[1].id}
								bind:value={username}
								class="input-field"
								placeholder=" "
								required
								disabled={loading}
							/>
							<label
								for={formElements[1].id}
								class="input-label"
							>
								{formElements[1].label}
							</label>
							<div class="input-focus-ring"></div>
						</div>
					</div>
				</div>

				<!-- Password Input Element -->
				<div in:fade={{ duration: 400, delay: 740 }}>
					<div in:fly={{ y: 20, duration: 400, delay: 740, easing: cubicOut }}>
						<div class="input-group">
							<div class="input-icon">
								<Lock class="icon" />
							</div>
							<input
								type={showPassword ? "text" : "password"}
								id={formElements[2].id}
								bind:value={password}
								class="input-field"
								placeholder=" "
								required
								disabled={loading}
							/>
							<label
								for={formElements[2].id}
								class="input-label"
							>
								{formElements[2].label}
							</label>
							<button
								type="button"
								class="password-toggle"
								on:click={() => showPassword = !showPassword}
								disabled={loading}
							>
								{#if showPassword}
									<EyeOff class="icon" />
								{:else}
									<Eye class="icon" />
								{/if}
							</button>
							<div class="input-focus-ring"></div>
						</div>
					</div>
				</div>

				<!-- Button Element -->
				<div in:fade={{ duration: 400, delay: 860 }}>
					<div in:fly={{ y: 20, duration: 400, delay: 860, easing: cubicOut }}>
						<button
							type="submit"
							class="submit-button"
							disabled={loading}
						>
							<div class="button-overlay"></div>
							<span class="button-content">
								{#if loading}
									<Loader2 class="loading-spinner" />
								{:else}
									<Lock class="button-icon" />
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
	:global(*) {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	/* Pixel Perfect Container */
	.pixel-perfect-container {
		position: relative;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		width: 100%;
		height: 100vh;
		padding: 16px;
		overflow: hidden;
		background: linear-gradient(135deg, var(--slate-50) 0%, #ffffff 50%, var(--slate-100) 100%);
	}

	/* Background Elements */
	.background-elements {
		position: absolute;
		inset: 0;
		overflow: hidden;
		pointer-events: none;
	}

	.bg-gradient-circle-1 {
		position: absolute;
		top: -50%;
		right: -10%;
		width: 800px;
		height: 800px;
		border-radius: 50%;
		background: radial-gradient(circle at 30% 70%, rgba(99, 102, 241, 0.08) 0%, transparent 50%);
		filter: blur(40px);
	}

	.bg-gradient-circle-2 {
		position: absolute;
		bottom: -50%;
		left: -10%;
		width: 600px;
		height: 600px;
		border-radius: 50%;
		background: radial-gradient(circle at 70% 30%, rgba(139, 92, 246, 0.06) 0%, transparent 50%);
		filter: blur(40px);
	}

	/* Login Card */
	.login-card {
		position: relative;
		z-index: 10;
		width: 100%;
		max-width: 448px;
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

	/* Login Form */
	.login-form {
		display: flex;
		flex-direction: column;
		gap: 32px;
	}

	/* Header Section */
	.login-header {
		display: flex;
		flex-direction: column;
		align-items: center;
		margin-bottom: 32px;
		text-align: center;
	}

	.logo-container {
		margin-bottom: 32px;
	}

	.logo-gradient {
		position: relative;
		width: 96px;
		height: 96px;
		border-radius: 24px;
		background: linear-gradient(135deg, var(--indigo-500), var(--violet-500), var(--pink-500));
		box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
	}

	.logo-overlay {
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

	.logo-gradient:hover .logo-image {
		transform: scale(1.1);
	}

	.logo-sparkle {
		position: absolute;
		top: -8px;
		right: -8px;
		width: 24px;
		height: 24px;
		color: var(--amber-400);
		animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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

	.subtitle-icon {
		width: 16px;
		height: 16px;
		color: var(--indigo-500);
	}

	/* Error Message */
	.error-message {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 16px;
		border-radius: 16px;
		font-size: 14px;
		line-height: 20px;
		background: linear-gradient(135deg, var(--red-50), var(--rose-50));
		color: var(--red-700);
		border: 1px solid var(--red-200);
		box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.1);
	}

	.error-icon {
		width: 20px;
		height: 20px;
		flex-shrink: 0;
	}

	/* Input Groups */
	.input-group {
		position: relative;
	}

	.input-icon {
		position: absolute;
		left: 16px;
		top: 50%;
		transform: translateY(-50%);
		transition: color 300s cubic-bezier(0.4, 0, 0.2, 1);
		color: var(--slate-400);
	}

	.input-icon .icon {
		width: 20px;
		height: 20px;
	}

	.input-field {
		width: 100%;
		padding-left: 48px;
		padding-right: 16px;
		padding-top: 16px;
		padding-bottom: 16px;
		font-weight: 500;
		border-radius: 16px;
		border: 1px solid rgba(226, 232, 240, 0.6);
		outline: none;
		color: var(--slate-900);
		background: rgba(241, 245, 249, 0.6);
		transition: all 300s cubic-bezier(0.4, 0, 0.2, 1);
		font-size: 16px;
	}

	.input-field:hover {
		background: rgba(241, 245, 249, 0.8);
	}

	.input-field:focus {
		background: rgba(255, 255, 255, 0.9);
		border-color: var(--indigo-500);
		box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 0 0 1px rgba(99, 102, 241, 0.6);
	}

	.input-group:focus-within .input-icon {
		color: var(--indigo-500);
	}

	.input-label {
		position: absolute;
		left: 48px;
		pointer-events: none;
		font-weight: 500;
		transition: all 300s cubic-bezier(0.4, 0, 0.2, 1);
		transform-origin: left center;
		color: var(--slate-600);
		top: 16px;
		font-size: 14px;
	}

	.input-field:focus + .input-label,
	.input-field:not(:placeholder-shown) + .input-label {
		top: 2px;
		font-size: 11px;
		font-weight: 600;
		color: var(--indigo-600);
		transform: translateY(-10px);
	}

	.input-field:placeholder-shown + .input-label {
		top: 16px;
		font-size: 14px;
		color: var(--slate-500);
		transform: translateY(0);
	}

	.password-toggle {
		position: absolute;
		right: 16px;
		top: 50%;
		transform: translateY(-50%);
		width: 36px;
		height: 36px;
		border: none;
		background: none;
		color: var(--slate-400);
		cursor: pointer;
		transition: color 300s cubic-bezier(0.4, 0, 0.2, 1);
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 8px;
	}

	.password-toggle:hover {
		color: var(--slate-600);
		background: rgba(148, 163, 184, 0.1);
	}

	.password-toggle .icon {
		width: 20px;
		height: 20px;
	}

	.input-focus-ring {
		position: absolute;
		inset: 0;
		border-radius: 16px;
		border: 2px solid transparent;
		pointer-events: none;
		transition: all 300s cubic-bezier(0.4, 0, 0.2, 1);
	}

	.input-group:focus-within .input-focus-ring {
		border-color: rgba(99, 102, 241, 0.5);
		box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.25);
	}

	/* Submit Button */
	.submit-button {
		position: relative;
		width: 100%;
		padding: 20px 32px;
		border-radius: 16px;
		font-weight: 700;
		font-size: 14px;
		border: none;
		cursor: pointer;
		transition: all 500s cubic-bezier(0.4, 0, 0.2, 1);
		box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.35);
		overflow: hidden;
		background: linear-gradient(135deg, var(--indigo-500), var(--violet-500), var(--pink-500));
		color: white;
	}

	.submit-button:hover {
		transform: translateY(-2px);
		box-shadow: 0 15px 35px -5px rgba(99, 102, 241, 0.45);
		background: linear-gradient(135deg, var(--indigo-600), var(--violet-600), var(--pink-600));
	}

	.submit-button:active {
		transform: translateY(-1px);
	}

	.submit-button:disabled {
		opacity: 0.6;
		cursor: not-allowed;
		transform: none;
	}

	.button-overlay {
		position: absolute;
		inset: 0;
		background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
		transform: translateY(100%);
		transition: transform 500s cubic-bezier(0.4, 0, 0.2, 1);
	}

	.submit-button:hover .button-overlay {
		transform: translateY(0);
	}

	.button-content {
		position: relative;
		z-index: 10;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 12px;
	}

	.button-icon {
		width: 20px;
		height: 20px;
	}

	.loading-spinner {
		width: 20px;
		height: 20px;
		animation: spin 1s linear infinite;
	}

	@keyframes spin {
		to {
			transform: rotate(360deg);
		}
	}

	/* Responsive Design */
	@media (max-width: 768px) {
		.pixel-perfect-container {
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
</style>