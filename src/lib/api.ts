// src/lib/api.ts
import { API_BASE_URL, LOGIN_ENDPOINT, PACIENTES_ENDPOINT } from './constants';
import type { User, ApiResponse } from './types';

/**
 * Handles user login.
 * @param credentials The login credentials containing username and password.
 * @returns A promise that resolves with the API response.
 */
export async function login(credentials: { username: string; password: string }): Promise<ApiResponse<User>> {
    const formData = new FormData();
    formData.append('usuario', credentials.username);
    formData.append('password', credentials.password);
    // Not needed: formData.append('login', '1'); // El backend no espera este parámetro

    try {
        const response = await fetch(`${API_BASE_URL}${LOGIN_ENDPOINT}`, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
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
export async function getPatientDetails(identificacion: string): Promise<ApiResponse<any>> {
    const formData = new FormData();
    formData.append('identificacion', identificacion);

    try {
        const response = await fetch(`${API_BASE_URL}/get_patient_details.php`, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            return { success: false, message: `HTTP error! status: ${response.status}` };
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching patient details:', error);
        return { success: false, message: 'Error de conexión al obtener detalles del paciente.' };
    }
}

export async function getEntidades(): Promise<ApiResponse<any[]>> {
    try {
        const response = await fetch(`${API_BASE_URL}/entidades.php`, {
            method: 'POST',
            body: new FormData()
        });

        if (!response.ok) {
            return { success: false, message: `HTTP error! status: ${response.status}` };
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching entidades:', error);
        return { success: false, message: 'Error de conexión al obtener entidades.' };
    }
}

export async function getPacientes(search?: string, fecha?: string): Promise<ApiResponse<any[]>> {
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

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching pacientes:', error);
        return { success: false, message: 'Error de conexión al obtener pacientes.' };
    }
}