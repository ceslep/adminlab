// src/lib/constants.ts

// Detect development mode
const isDevelopment = import.meta.env.DEV;

export const API_BASE_URL: string = isDevelopment 
    ? 'http://localhost:5175/adminlab/api'  // Local development
    : 'https://mycar.iedeoccidente.com/api'; // Production

export const LOGIN_ENDPOINT: string = '/login.php';
export const LOGO_URL: string = 'https://mycar.iedeoccidente.com/printphp/logo.png';

// Endpoints para pacientes
export const PACIENTES_ENDPOINT: string = '/pacientes.php';
export const PACIENTES_SEARCH_ENDPOINT: string = '/pacientes.php';


// Add other endpoints as they are extracted or created
