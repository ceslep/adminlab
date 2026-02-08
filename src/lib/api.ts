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
    // Development mode - use mock JSON
    if (import.meta.env.DEV) {
        try {
            const response = await fetch('/api/pacientes.json');
            const data: ApiResponse<Paciente[]> = await response.json();
            
            // Apply client-side filtering for search
            if (search && data.data) {
                const searchLower = search.toLowerCase();
                data.data = data.data.filter(paciente => 
                    paciente.nombre_completo.toLowerCase().includes(searchLower) ||
                    paciente.telefono.toLowerCase().includes(searchLower) ||
                    paciente.email.toLowerCase().includes(searchLower)
                );
                data.total = data.data.length;
            }
            
            return data;
        } catch (error) {
            console.error('Error fetching mock pacientes:', error);
            return { success: false, message: 'Error al cargar datos de prueba.' };
        }
    }

    // Production mode - use PHP API
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (fecha) params.append('fecha', fecha);

    try {
        const response = await fetch(`${API_BASE_URL}${PACIENTES_ENDPOINT}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
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
