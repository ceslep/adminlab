// src/lib/types.ts

// Define the User interface
export interface User {
    nombre: string;
    usuario: string;
    lab_id: string;
    // Add other user properties as needed
}

// Define the structure of a generic API response
export interface ApiResponse<T> {
    success: boolean;
    message?: string;
    user?: T; // Generic user data, will be User type for login
    // Add other common response fields here
}
