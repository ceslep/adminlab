// src/lib/api.ts
import { API_BASE_URL, LOGIN_ENDPOINT, PACIENTES_ENDPOINT } from './constants';
import type { User, ApiResponse, Paciente } from './types'; // Import from types.ts

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

/**
 * Fetches patients data with optional search and date filtering.
 * @param search Optional search term to filter patients
 * @param fecha Optional date for filtering
 * @returns A promise that resolves with the patients API response
 */
export async function getPacientes(search?: string, fecha?: string): Promise<ApiResponse<Paciente[]>> {
    // Always use production API with POST method
    const formData = new FormData();
    if (search) formData.append('search', search);
    if (fecha) formData.append('fecha', fecha);

    try {
        const response = await fetch(`${API_BASE_URL}${PACIENTES_ENDPOINT}`, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            if (response.status === 404) {
                return { success: false, message: 'El endpoint de pacientes no está disponible. Por favor, configure el servidor API.' };
            }
            return { success: false, message: `HTTP error! status: ${response.status}` };
        }

        const data: ApiResponse<Paciente[]> = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching pacientes:', error);
        return { success: false, message: 'Error de conexión al obtener pacientes.' };
    }
}
