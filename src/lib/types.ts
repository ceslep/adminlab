// src/lib/types.ts

// Define the User interface
export interface User {
    nombre: string;
    usuario: string;
    lab_id: string;
}

// Define the Paciente interface
export interface Paciente {
    id_paciente: number;
    identificacion: string;
    nombre: string;
    apellido: string;
    nombre_completo: string;
    telefono: string;
    email: string;
    genero: string;
    genero_completo: string;
    color_genero: string;
    fecha_nacimiento: string;
    edad: number;
    edad_texto: string;
    estado: string;
    color_estado: string;
    entidad: string;
    usuario_registro: string;
    fecha_registro: string;
    examenes: string[];
    total_examenes: number;
}

// Define the structure of a generic API response
export interface ApiResponse<T> {
    success: boolean;
    message?: string;
    user?: T; // Generic user data, will be User type for login
    data?: T; // Generic data array, will be Paciente[] for pacientes
    total?: number; // Total count for array responses
    fecha_consulta?: string; // For pacientes response
}