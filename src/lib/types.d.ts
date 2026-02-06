// src/lib/types.d.ts

/**
 * Represents the laboratory configuration.
 */
export interface LabConfig {
  nombreCorto: string;
  nombreLaboratorio: string;
  urlLogoLaboratorio: string;
}

/**
 * Represents the authentication status of a user.
 */
export interface AuthStatus {
  isAuthenticated: boolean;
  userName: string | null;
  labId: string | null;
  error?: string;
}

/**
 * Represents an entity (e.g., an insurance company).
 */
export interface Entity {
  id: string;
  nombre: string;
}

/**
 * Represents a patient.
 */
export interface Patient {
  identificacion: string;
  nombre_completo: string;
  apellidos: string;
  nombres: string;
  edad: number;
  genero: 'M' | 'F' | 'O';
  fecnac?: string; // Date of birth
  telefono: string;
  telefono_fijo?: string;
  telefono_movil?: string;
  telefono_residencia2?: string;
  correo: string;
  ciudad_residencia: string;
  direccion_residencia: string;
  entidad: string;
  total_visitas?: number;
  ultima_visita?: string;
}

/**
 * Represents an exam.
 */
export interface Exam {
  fecha: string;
  codexamen: string;
  realizado: 'S' | 'N';
  entidad: string;
  nombre?: string;
  tipo?: string;
  tabla?: string; // Dynamic table name for results
  procedimiento?: string;
  abreviatura?: string;
  resultado?: string;
  referencia?: string;
  estado?: string; // e.g., "Completado", "Pendiente", "Error"
  resultado_completo?: Record<string, any>; // Full raw result data

  // Patient-related fields that might be included in an exam object for convenience in reports/lists
  identificacion?: string; // Patient ID
  paciente?: string; // Patient full name
  edad?: number; // Patient age
  genero?: 'M' | 'F' | 'O'; // Patient gender
  telefono?: string; // Patient phone number
  fecha_examen?: string; // Exam date, often included when exams are listed with patient context
}

/**
 * Represents a patient with their associated exams.
 */
export interface PatientWithExams extends Patient {
  examenes: Exam[];
  total_examenes?: number;
  examenes_con_resultados?: number;
}

/**
 * Represents an exam result grouped by date (for entity reports).
 */
export interface GroupedExamResult {
  fecha: string;
  cantidad: number;
  examenes: Exam[];
}

/**
 * Response structure for entity report queries.
 */
export interface EntityReportResponse {
  success: boolean;
  resultados: GroupedExamResult[] | Exam[]; // Can be grouped or flat
  total_registros: number;
  total_fechas?: number;
  message?: string;
}

/**
 * Response structure for patient search queries.
 */
export interface PatientSearchResponse {
  success: boolean;
  pacientes: PatientWithExams[];
  total_pacientes: number;
  criterios: Record<string, any>; // Search criteria used
  message?: string;
}

/**
 * Generic API success response.
 */
export interface ApiResponse<T> {
  success: boolean;
  message?: string;
  data?: T;
  error?: string;
}
