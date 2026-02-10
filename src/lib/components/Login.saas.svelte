<script lang="ts">
    import { createEventDispatcher } from 'svelte';
    import { fade, fly, scale, blur } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { Eye, EyeOff, Lock, Mail, ArrowRight, Zap, Shield, Users, Globe } from 'lucide-svelte';
    import type { User } from '../types';
    import { login } from '../api';
    import { LOGO_URL } from '../constants';

    const dispatch = createEventDispatcher<{ loginSuccess: User }>();

    let username = '';
    let password = '';
    let errorMessage = '';
    let loading = false;
    let showPassword = false;
    let focusedField = '';

    async function handleSubmit(): Promise<void> {
        errorMessage = '';
        loading = true;

        try {
            console.log('🔐 Intentando login con:', { username: username.trim(), password: '***' });
            
            // Simulate network delay for cinematic effect
            await new Promise((resolve) => setTimeout(resolve, 2000));
            
            // Demo mode for now
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

    const features = [
        { icon: Shield, text: "Seguridad de nivel empresarial" },
        { icon: Zap, text: "Rendimiento ultra rápido" },
        { icon: Users, text: "Gestión colaborativa" },
        { icon: Globe, text: "Acceso global 24/7" }
    ];
</script>

<div class="saas-login-container">
    <!-- Animated Background -->
    <div class="background-animation">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>
        <div class="gradient-overlay"></div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Left Side - Features -->
        <div 
            class="features-section"
            in:fly={{ x: -100, duration: 800, easing: cubicOut }}
        >
            <div class="features-content">
                <div class="brand-section">
                    <div class="brand-logo">
                        <img 
                            src={LOGO_URL}
                            alt="AdminLab Logo"
                            class="logo-img"
                            on:error={(e) => {
                                const target = e.currentTarget as HTMLImageElement;
                                target.style.display = 'none';
                                const fallback = target.nextElementSibling as HTMLElement;
                                if (fallback) fallback.style.display = 'flex';
                            }}
                        />
                        <div class="logo-fallback">
                            <Shield class="w-8 h-8 text-white" />
                        </div>
                    </div>
                    <h1 class="brand-title">AdminLab</h1>
                    <p class="brand-subtitle">Sistema de Gestión de Laboratorio Clínico</p>
                </div>

                <div class="features-list">
                    {#each features as feature, i}
                        <div 
                            class="feature-item"
                            in:fly={{ x: -50, duration: 600, delay: 200 + (i * 100), easing: cubicOut }}
                        >
                            <feature.icon class="feature-icon" />
                            <span class="feature-text">{feature.text}</span>
                        </div>
                    {/each}
                </div>

                <div class="stats-section">
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">5K+</div>
                        <div class="stat-label">Laboratorios</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">1M+</div>
                        <div class="stat-label">Análisis</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div 
            class="login-section"
            in:fly={{ x: 100, duration: 800, easing: cubicOut }}
        >
            <div class="login-card">
                <!-- Header -->
                <div 
                    class="login-header"
                    in:fade={{ duration: 600, delay: 300, easing: cubicOut }}
                >
                    <h2 class="login-title">Bienvenido de nuevo</h2>
                    <p class="login-subtitle">Accede a tu laboratorio clínico</p>
                </div>

                <!-- Form -->
                <form on:submit|preventDefault={handleSubmit} class="login-form">
                    <!-- Username Input -->
                    <div 
                        class="input-group"
                        class:focused={focusedField === 'username'}
                        in:fade={{ duration: 600, delay: 400, easing: cubicOut }}
                    >
                        <div class="input-container">
                            <Mail class="input-icon" />
                            <input
                                id="username"
                                type="text"
                                bind:value={username}
                                placeholder="Correo electrónico o usuario"
                                class="input-field"
                                on:focus={() => focusedField = 'username'}
                                on:blur={() => focusedField = ''}
                                disabled={loading}
                            />
                            <div class="input-border"></div>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div 
                        class="input-group"
                        class:focused={focusedField === 'password'}
                        in:fade={{ duration: 600, delay: 500, easing: cubicOut }}
                    >
                        <div class="input-container">
                            <Lock class="input-icon" />
                            <input
                                id="password"
                                type={showPassword ? 'text' : 'password'}
                                bind:value={password}
                                placeholder="Contraseña"
                                class="input-field"
                                on:focus={() => focusedField = 'password'}
                                on:blur={() => focusedField = ''}
                                disabled={loading}
                            />
                            <button
                                type="button"
                                class="password-toggle"
                                on:click={() => showPassword = !showPassword}
                                disabled={loading}
                            >
                                {#if showPassword}
                                    <EyeOff class="toggle-icon" />
                                {:else}
                                    <Eye class="toggle-icon" />
                                {/if}
                            </button>
                            <div class="input-border"></div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    {#if errorMessage}
                        <div 
                            class="error-message"
                            in:fade={{ duration: 300 }}
                        >
                            {errorMessage}
                        </div>
                    {/if}

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="submit-button"
                        disabled={loading || !username.trim() || !password}
                        in:fade={{ duration: 600, delay: 600, easing: cubicOut }}
                    >
                        {#if loading}
                            <div class="loading-spinner"></div>
                            <span>Iniciando sesión...</span>
                        {:else}
                            <span>Acceder al sistema</span>
                            <ArrowRight class="button-arrow" />
                        {/if}
                        <div class="button-glow"></div>
                    </button>

                    <!-- Additional Options -->
                    <div 
                        class="login-options"
                        in:fade={{ duration: 600, delay: 700, easing: cubicOut }}
                    >
<button type="button" class="forgot-password" on:click={() => console.log('Forgot password')}>¿Olvidaste tu contraseña?</button>
                        <div class="divider">o</div>
                        <button type="button" class="signup-link" on:click={() => console.log('Sign up')}>Crear cuenta nueva</button>
                    </div>
                </form>

                <!-- Footer -->
                <div 
                    class="login-footer"
                    in:fade={{ duration: 600, delay: 800, easing: cubicOut }}
                >
                    <p class="footer-text">
                        © 2024 AdminLab. Todos los derechos reservados.
                    </p>
                    <div class="footer-links">
<button type="button" class="footer-link" on:click={() => console.log('Terms')}>Términos</button>
                        <button type="button" class="footer-link" on:click={() => console.log('Privacy')}>Privacidad</button>
                        <button type="button" class="footer-link" on:click={() => console.log('Help')}>Ayuda</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Container */
    .saas-login-container {
        min-height: 100vh;
        display: flex;
        position: relative;
        overflow: hidden;
    }

    /* Animated Background */
    .background-animation {
        position: fixed;
        inset: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        z-index: 0;
    }

    .floating-shapes {
        position: absolute;
        inset: 0;
        overflow: hidden;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 20s infinite ease-in-out;
    }

    .shape-1 {
        width: 300px;
        height: 300px;
        top: -150px;
        left: -150px;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 200px;
        height: 200px;
        top: 50%;
        right: -100px;
        animation-delay: 5s;
    }

    .shape-3 {
        width: 150px;
        height: 150px;
        bottom: -75px;
        left: 30%;
        animation-delay: 10s;
    }

    .shape-4 {
        width: 250px;
        height: 250px;
        top: 20%;
        left: 60%;
        animation-delay: 15s;
    }

    .shape-5 {
        width: 100px;
        height: 100px;
        bottom: 20%;
        right: 20%;
        animation-delay: 7s;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
            opacity: 0.1;
        }
        25% {
            transform: translateY(-30px) rotate(90deg);
            opacity: 0.2;
        }
        50% {
            transform: translateY(-60px) rotate(180deg);
            opacity: 0.1;
        }
        75% {
            transform: translateY(-30px) rotate(270deg);
            opacity: 0.2;
        }
    }

    .gradient-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 50%, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
    }

    /* Main Content */
    .main-content {
        display: flex;
        width: 100%;
        position: relative;
        z-index: 1;
    }

    /* Features Section */
    .features-section {
        flex: 1;
        display: flex;
        align-items: center;
        padding: 4rem;
        color: white;
    }

    .features-content {
        max-width: 500px;
    }

    .brand-section {
        margin-bottom: 4rem;
    }

    .brand-logo {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(20px);
        border-radius: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
    }

    .logo-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.75rem;
    }

    .logo-fallback {
        display: none;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
    }

    .brand-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .brand-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    .features-list {
        margin-bottom: 4rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .feature-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(10px);
    }



    .feature-text {
        font-size: 0.95rem;
        font-weight: 500;
    }

    .stats-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-top: 3rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Login Section */
    .login-section {
        width: 50%;
        max-width: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 2rem;
        padding: 3rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.3);
        width: 100%;
        max-width: 450px;
    }

    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .login-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .login-subtitle {
        color: #4a5568;
        font-size: 1rem;
        font-weight: 500;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Input Groups */
    .input-group {
        position: relative;
    }

    .input-container {
        position: relative;
        display: flex;
        align-items: center;
    }



    .input-field {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        font-size: 1rem;
        font-weight: 500;
        background: white;
        color: #2d3748;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
    }

    .input-field::placeholder {
        color: #a0aec0;
    }

    .input-group.focused .input-icon {
        color: #667eea;
    }

    .input-group.focused .input-field {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .input-border {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: left;
    }

    .input-group.focused .input-border {
        transform: scaleX(1);
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        background: none;
        border: none;
        color: #a0aec0;
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
    }

    .password-toggle:hover {
        color: #667eea;
    }

    .toggle-icon {
        width: 18px;
        height: 18px;
    }

    /* Error Message */
    .error-message {
        background: linear-gradient(135deg, #fed7d7, #feb2b2);
        color: #c53030;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid #fc8181;
        text-align: center;
    }

    /* Submit Button */
    .submit-button {
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        border: none;
        border-radius: 1rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .submit-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    }

    .submit-button:active:not(:disabled) {
        transform: translateY(-1px);
    }

    .submit-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
    }

    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .button-arrow {
        width: 18px;
        height: 18px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .submit-button:hover:not(:disabled) .button-arrow {
        transform: translateX(4px);
    }

    .button-glow {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        opacity: 0;
        transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .submit-button:hover:not(:disabled) .button-glow {
        opacity: 1;
    }

    /* Login Options */
    .login-options {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

.forgot-password {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-size: inherit;
        font-family: inherit;
    }

    .forgot-password:hover {
        color: #5a67d8;
    }

    .divider {
        color: #a0aec0;
        font-weight: 500;
    }

.signup-link {
        color: #764ba2;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-size: inherit;
        font-family: inherit;
    }

    .signup-link:hover {
        color: #6b46c1;
    }

    /* Footer */
    .login-footer {
        margin-top: 2rem;
        text-align: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .footer-text {
        color: #718096;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
    }

.footer-link {
        color: #a0aec0;
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-family: inherit;
    }

    .footer-link:hover {
        color: #667eea;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .features-section {
            display: none;
        }
        
        .login-section {
            width: 100%;
            max-width: none;
        }
        
        .main-content {
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .login-section {
            padding: 2rem 1rem;
        }
        
        .login-card {
            padding: 2rem;
            border-radius: 1.5rem;
        }
        
        .login-title {
            font-size: 1.5rem;
        }
        
        .brand-title {
            font-size: 2rem;
        }
    }
</style>