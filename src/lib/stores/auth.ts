// src/lib/stores/auth.ts
import { writable } from 'svelte/store';
import { auth as authApi } from '$lib/api';
import type { AuthStatus, LabConfig } from '$lib/types';

// Initial state for authentication
const initialAuthStatus: AuthStatus = {
  isAuthenticated: false,
  userName: null,
  labId: null
};

// Writable store for authentication status
export const authStatus = writable<AuthStatus>(initialAuthStatus);

// Writable store for global lab configuration
export const labConfig = writable<LabConfig | null>(null);

/**
 * Handles user login.
 * @param password The password to log in with.
 * @returns A promise that resolves to true if login is successful, false otherwise.
 */
export async function login(password: string): Promise<boolean> {
  try {
    const response = await authApi.login(password);
    if (response.success) {
      authStatus.set({
        isAuthenticated: true,
        userName: response.userName || 'Usuario',
        labId: response.labId || 'default_lab'
      });
      if (response.labConfig) {
        labConfig.set(response.labConfig);
      }
      return true;
    } else {
      authStatus.set({ ...initialAuthStatus, error: response.error || 'Credenciales inválidas.' });
      return false;
    }
  } catch (error) {
    console.error('Login API error:', error);
    authStatus.set({ ...initialAuthStatus, error: 'Error de conexión. Intente de nuevo.' });
    return false;
  }
}

/**
 * Handles user logout.
 */
export async function logout(): Promise<void> {
  try {
    await authApi.logout(); // Inform the backend
  } catch (error) {
    console.error('Logout API error:', error);
    // Continue with client-side logout even if API call fails
  } finally {
    authStatus.set(initialAuthStatus);
    labConfig.set(null);
  }
}

/**
 * Initializes the authentication state and loads initial lab configuration.
 * This should be called once when the app starts.
 */
export async function initializeAuth(): Promise<void> {
  try {
    const { isAuthenticated, userName, labConfig: initialLabConfig } = await authApi.getInitialData();
    if (isAuthenticated) {
      authStatus.set({ isAuthenticated, userName, labId: 'placeholder_lab_id' }); // labId might come from initial data too
    }
    if (initialLabConfig) {
      labConfig.set(initialLabConfig);
    }
  } catch (error) {
    console.error('Error initializing auth and lab config:', error);
    // Fallback to initial state
    authStatus.set(initialAuthStatus);
    labConfig.set(null);
  }
}

// Note: initializeAuth is now called from App.svelte for better loading experience
// initializeAuth();
