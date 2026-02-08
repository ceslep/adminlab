// src/lib/api.ts
import { API_BASE_URL, LOGIN_ENDPOINT } from './constants';
import type { User, ApiResponse } from './types'; // Import from types.ts

/**
 * Handles user login.
 * @param credentials The login credentials containing username and password.
 * @returns A promise that resolves with the API response.
 */
export async function login(credentials: { username: string; password: string }): Promise<ApiResponse<User>> {
    const formData = new FormData();
    formData.append('usuario', credentials.username); // Cambiado a 'usuario' para coincidir con PHP
    formData.append('password', credentials.password);
    formData.append('login', '1'); // Mimic original PHP form submission

    try {
        const response = await fetch(`${API_BASE_URL}${LOGIN_ENDPOINT}`, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            // Handle HTTP errors (e.g., 404, 500)
            return { success: false, message: `HTTP error! status: ${response.status}` };
        }

        const data: ApiResponse<User> = await response.json();
        return data;
    } catch (error) {
        console.error('Error during login API call:', error);
        return { success: false, message: 'Error de conexión con el servidor.' };
    }
}

// You can add other API call functions here as needed,
// for example:
/*
export async function getMetrics(period: string, mode: string): Promise<ApiResponse<MetricsData>> {
    // ... implementation for metrics endpoint
}
*/
