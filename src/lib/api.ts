// src/lib/api.ts
import type { AuthStatus, LabConfig, Patient, PatientWithExams, Entity, EntityReportResponse, PatientSearchResponse } from './types';

const API_BASE_URL = '/api'; // This would be your backend API base URL

interface LoginResponse {
  success: boolean;
  userName?: string;
  labId?: string;
  error?: string;
  labConfig?: LabConfig;
}

interface UpdateEntityResponse {
  success: boolean;
  message: string;
  entidad?: string;
}

// --- General API Utility ---
async function fetchApi<T>(endpoint: string, options?: RequestInit): Promise<T> {
  const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
  if (!response.ok) {
    const errorData = await response.json().catch(() => ({ message: response.statusText }));
    throw new Error(errorData.message || 'API request failed');
  }
  return response.json();
}

// --- Authentication API ---
export const auth = {
  login: async (password: string): Promise<LoginResponse> => {
    // In a real application, you would hash the password before sending or ensure HTTPS
    const response = await fetchApi<LoginResponse>('/auth/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ password })
    });
    return response;
  },

  logout: async (): Promise<{ success: boolean }> => {
    const response = await fetchApi<{ success: boolean }>('/auth/logout', {
      method: 'GET'
    });
    return response;
  },

  // Simulate getting initial session/config
  getInitialData: async (): Promise<{ isAuthenticated: boolean, userName: string | null, labConfig: LabConfig | null }> => {
    // In a real app, this would check server-side session
    // For now, we simulate unauthenticated state with mock config
    return {
      isAuthenticated: false,
      userName: null,
      labConfig: {
        nombreCorto: 'LAB',
        nombreLaboratorio: 'Laboratorio Clínico',
        urlLogoLaboratorio: '' // Placeholder
      }
    };
  }
};

// --- Patients & Exams API ---
export const patients = {
  getPatientsList: async (
    date: string,
    search: string = '',
    allDates: boolean = false
  ): Promise<{ patients: Patient[]; totalPatients: number }> => {
    const params = new URLSearchParams();
    params.append('date', date);
    if (search) params.append('search', search);
    if (allDates) params.append('allDates', 'true');

    // Mock data for now
    await new Promise(r => setTimeout(r, 500)); // Simulate network delay
    return {
      patients: [
        {
          identificacion: '12345',
          nombre_completo: 'Juan Perez',
          apellidos: 'Perez',
          nombres: 'Juan',
          edad: 30,
          genero: 'M',
          telefono: '123456789',
          correo: 'juan@example.com',
          ciudad_residencia: 'Bogota',
          direccion_residencia: 'Calle 10 # 20-30',
          entidad: 'EPS Sura'
        },
        {
          identificacion: '67890',
          nombre_completo: 'Maria Garcia',
          apellidos: 'Garcia',
          nombres: 'Maria',
          edad: 25,
          genero: 'F',
          telefono: '987654321',
          correo: 'maria@example.com',
          ciudad_residencia: 'Medellin',
          direccion_residencia: 'Carrera 50 # 100-10',
          entidad: 'Sanitas'
        }
      ],
      totalPatients: 2
    };
    // return fetchApi<{ patients: Patient[]; totalPatients: number }>(`/patients?${params.toString()}`);
  },

  updateExamEntity: async (
    identificacion: string,
    fechaExamen: string,
    codExamen: string,
    newEntity: string
  ): Promise<UpdateEntityResponse> => {
    const response = await fetchApi<UpdateEntityResponse>(
      `/exams/${identificacion}/${fechaExamen}/${codExamen}/entity`,
      {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ entity: newEntity })
      }
    );
    return response;
  },

  searchPatientsWithExams: async (criteria: {
    identificacion?: string;
    nombres?: string;
    telefono?: string;
    ciudad?: string;
    entidad?: string;
    includeExams?: boolean;
    onlyWithResults?: boolean;
    limit?: number;
  }): Promise<PatientSearchResponse> => {
    // Mock data for now
    await new Promise(r => setTimeout(r, 1000));
    return {
      success: true,
      pacientes: [
        {
          identificacion: '12345',
          nombre_completo: 'Juan Perez',
          apellidos: 'Perez',
          nombres: 'Juan',
          edad: 30,
          genero: 'M',
          telefono: '123456789',
          correo: 'juan@example.com',
          ciudad_residencia: 'Bogota',
          direccion_residencia: 'Calle 10 # 20-30',
          entidad: 'EPS Sura',
          examenes: [
            {
              fecha: '2023-01-15',
              codexamen: 'EX001',
              realizado: 'S',
              entidad: 'EPS Sura',
              nombre: 'Hemograma Completo',
              resultado: 'Normal',
              referencia: '4.5-6.0',
              estado: 'Completado'
            }
          ],
          total_examenes: 1,
          examenes_con_resultados: 1
        }
      ],
      total_pacientes: 1,
      criterios: criteria
    };
    // return fetchApi<PatientSearchResponse>('/patients/search', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify(criteria)
    // });
  }
};

// --- Entities API ---
export const entities = {
  getEntitiesList: async (): Promise<Entity[]> => {
    // Mock data for now
    await new Promise(r => setTimeout(r, 300));
    return [
      { id: '1', nombre: 'EPS Sura' },
      { id: '2', nombre: 'Sanitas' },
      { id: '3', nombre: 'Nueva EPS' }
    ];
    // return fetchApi<Entity[]>('/entities');
  },

  getReportByEntities: async (criteria: {
    entity?: string;
    startDate: string;
    endDate: string;
    onlyWithResults?: boolean;
    groupByDate?: boolean;
  }): Promise<EntityReportResponse> => {
    // Mock data for now
    await new Promise(r => setTimeout(r, 1500));
    return {
      success: true,
      resultados: [
        {
          fecha: '2023-01-01',
          cantidad: 2,
          examenes: [
            {
              identificacion: '12345',
              paciente: 'Juan Perez',
              edad: 30,
              genero: 'M',
              telefono: '123',
              fecha: '2023-01-01',
              fecha_examen: '2023-01-01',
              entidad: 'EPS Sura',
              codexamen: 'EX001',
              realizado: 'S',
              nombre: 'Hemograma',
              resultado: 'Normal',
              referencia: 'OK',
              estado: 'Completado'
            },
            {
              identificacion: '67890',
              paciente: 'Maria Garcia',
              edad: 25,
              genero: 'F',
              telefono: '456',
              fecha: '2023-01-01',
              fecha_examen: '2023-01-01',
              entidad: 'Sanitas',
              codexamen: 'EX002',
              realizado: 'S',
              nombre: 'Glicemia',
              resultado: '80 mg/dL',
              referencia: '70-100',
              estado: 'Completado'
            }
          ]
        },
        {
          fecha: '2023-01-02',
          cantidad: 1,
          examenes: [
            {
              identificacion: '12345',
              paciente: 'Juan Perez',
              edad: 30,
              genero: 'M',
              telefono: '123',
              fecha: '2023-01-02',
              fecha_examen: '2023-01-02',
              entidad: 'EPS Sura',
              codexamen: 'EX003',
              realizado: 'N',
              nombre: 'Colesterol',
              resultado: 'Pendiente',
              referencia: 'N/A',
              estado: 'Pendiente'
            }
          ]
        }
      ],
      total_registros: 3,
      total_fechas: 2
    };
    // return fetchApi<EntityReportResponse>('/reports/by-entities', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify(criteria)
    // });
  }
};
