// Security and Compliance Module for Clinical Lab System
// HIPAA/GDPR Compliant Data Handling

export class DataSecurityManager {
    private static instance: DataSecurityManager;
    private encryptionKey: string = '';
    
    private constructor() {
        this.initializeSecurity();
    }
    
    static getInstance(): DataSecurityManager {
        if (!DataSecurityManager.instance) {
            DataSecurityManager.instance = new DataSecurityManager();
        }
        return DataSecurityManager.instance;
    }
    
    private initializeSecurity(): void {
        // Initialize encryption key from secure storage
        this.encryptionKey = this.getOrCreateEncryptionKey();
    }
    
    // Data Encryption/Decryption
    async encryptSensitiveData(data: any): Promise<string> {
        try {
            const jsonString = JSON.stringify(data);
            const encoded = new TextEncoder().encode(jsonString);
            
            // In production, use proper Web Crypto API
            // For demo, using basic encoding
            const encrypted = btoa(jsonString);
            
            // Add audit trail
            this.logDataAccess('ENCRYPT', { dataLength: jsonString.length });
            
            return encrypted;
        } catch (error) {
            console.error('Encryption failed:', error);
            throw new Error('Failed to encrypt sensitive data');
        }
    }
    
    async decryptSensitiveData(encryptedData: string): Promise<any> {
        try {
            // Log access attempt
            this.logDataAccess('DECRYPT', { dataLength: encryptedData.length });
            
            // In production, use proper Web Crypto API
            const decrypted = atob(encryptedData);
            return JSON.parse(decrypted);
        } catch (error) {
            console.error('Decryption failed:', error);
            const errorMessage = error instanceof Error ? error.message : 'Unknown error';
            this.logDataAccess('DECRYPT_FAILED', { error: errorMessage });
            throw new Error('Failed to decrypt sensitive data');
        }
    }
    
    // Data Masking for Display
    maskPatientData(patientData: any, userRole: string): any {
        const masked = { ...patientData };
        
        switch (userRole) {
            case 'admin':
                // Full access
                return masked;
                
            case 'doctor':
            case 'lab_technician':
                // Mask some sensitive fields
                if (masked.identificacion) {
                    masked.identificacion = this.maskIdentification(masked.identificacion);
                }
                return masked;
                
            case 'receptionist':
                // Limited access
                if (masked.email) {
                    masked.email = this.maskEmail(masked.email);
                }
                if (masked.telefono) {
                    masked.telefono = this.maskPhone(masked.telefono);
                }
                if (masked.identificacion) {
                    masked.identificacion = this.maskIdentification(masked.identificacion);
                }
                return masked;
                
            default:
                // Minimal access
                return {
                    nombre_completo: masked.nombre_completo,
                    edad: masked.edad,
                    genero: masked.genero
                };
        }
    }
    
    private maskIdentification(id: string): string {
        if (id.length <= 4) return '****';
        return id.substring(0, 2) + '****' + id.substring(id.length - 2);
    }
    
    private maskEmail(email: string): string {
        const [username, domain] = email.split('@');
        if (username.length <= 2) return '**@' + domain;
        return username.substring(0, 2) + '***@' + domain;
    }
    
    private maskPhone(phone: string): string {
        const cleaned = phone.replace(/\D/g, '');
        if (cleaned.length <= 4) return '****';
        return cleaned.substring(0, 2) + '****' + cleaned.substring(cleaned.length - 2);
    }
    
    // Audit Trail
    public logDataAccess(action: string, metadata: any): void {
        const logEntry = {
            timestamp: new Date().toISOString(),
            action,
            userId: this.getCurrentUserId(),
            sessionId: this.getSessionId(),
            userAgent: navigator.userAgent,
            ip: 'client-side', // In production, get from server
            metadata
        };
        
        // Store in secure storage or send to server
        this.persistAuditLog(logEntry);
    }
    
    private getCurrentUserId(): string {
        // Get current user ID from secure storage
        return localStorage.getItem('userId') || 'anonymous';
    }
    
    private getSessionId(): string {
        // Get or create session ID
        let sessionId = sessionStorage.getItem('sessionId');
        if (!sessionId) {
            sessionId = this.generateSecureId();
            sessionStorage.setItem('sessionId', sessionId);
        }
        return sessionId;
    }
    
    private generateSecureId(): string {
        return Array.from(crypto.getRandomValues(new Uint8Array(16)))
            .map(b => b.toString(16).padStart(2, '0'))
            .join('');
    }
    
    private persistAuditLog(logEntry: any): void {
        // In production, send to secure server
        // For demo, store in localStorage with rotation
        const logs = JSON.parse(localStorage.getItem('auditLogs') || '[]');
        logs.push(logEntry);
        
        // Keep only last 1000 entries
        if (logs.length > 1000) {
            logs.splice(0, logs.length - 1000);
        }
        
        localStorage.setItem('auditLogs', JSON.stringify(logs));
    }
    
    // Data Validation
    validatePatientData(patientData: any): { isValid: boolean; errors: string[] } {
        const errors: string[] = [];
        
        // Required fields validation
        const requiredFields = ['nombre_completo', 'identificacion', 'edad', 'genero'];
        for (const field of requiredFields) {
            if (!patientData[field] || patientData[field].trim() === '') {
                errors.push(`${field} is required`);
            }
        }
        
        // Data format validation
        if (patientData.edad && (patientData.edad < 0 || patientData.edad > 150)) {
            errors.push('Invalid age value');
        }
        
        if (patientData.email && !this.isValidEmail(patientData.email)) {
            errors.push('Invalid email format');
        }
        
        if (patientData.telefono && !this.isValidPhone(patientData.telefono)) {
            errors.push('Invalid phone format');
        }
        
        return {
            isValid: errors.length === 0,
            errors
        };
    }
    
    private isValidEmail(email: string): boolean {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    private isValidPhone(phone: string): boolean {
        const phoneRegex = /^\+?[\d\s\-\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }
    
    // Data Retention
    cleanupOldData(): void {
        const retentionPeriod = 7 * 365 * 24 * 60 * 60 * 1000; // 7 years in ms
        const cutoffDate = new Date(Date.now() - retentionPeriod);
        
        // Clean up old audit logs
        const logs = JSON.parse(localStorage.getItem('auditLogs') || '[]');
        const filteredLogs = logs.filter((log: any) => 
            new Date(log.timestamp) > cutoffDate
        );
        
        localStorage.setItem('auditLogs', JSON.stringify(filteredLogs));
    }
    
    // Export for Compliance
    exportAuditLogs(dateRange?: { start: Date; end: Date }): any[] {
        const logs = JSON.parse(localStorage.getItem('auditLogs') || '[]');
        
        if (dateRange) {
            return logs.filter((log: any) => {
                const logDate = new Date(log.timestamp);
                return logDate >= dateRange.start && logDate <= dateRange.end;
            });
        }
        
        return logs;
    }
    
    // Patient Consent Management
    recordConsent(patientId: string, consentType: string, granted: boolean): void {
        const consent = {
            patientId,
            consentType,
            granted,
            timestamp: new Date().toISOString(),
            userId: this.getCurrentUserId(),
            ipAddress: 'client-side' // In production, get from server
        };
        
        const consents = JSON.parse(localStorage.getItem('patientConsents') || '[]');
        consents.push(consent);
        localStorage.setItem('patientConsents', JSON.stringify(consents));
        
        this.logDataAccess('CONSENT_RECORDED', { patientId, consentType, granted });
    }
    
    hasValidConsent(patientId: string, consentType: string): boolean {
        const consents = JSON.parse(localStorage.getItem('patientConsents') || '[]');
        const patientConsents = consents.filter((c: any) => 
            c.patientId === patientId && c.consentType === consentType
        );
        
        // Check if there's a granted consent within the last year
        const oneYearAgo = new Date(Date.now() - 365 * 24 * 60 * 60 * 1000);
        return patientConsents.some((c: any) => 
            c.granted && new Date(c.timestamp) > oneYearAgo
        );
    }
    
    private getOrCreateEncryptionKey(): string {
        let key = localStorage.getItem('encryptionKey');
        if (!key) {
            key = this.generateSecureId();
            localStorage.setItem('encryptionKey', key);
        }
        return key;
    }
}

// Data Access Control
export class DataAccessControl {
    private static permissions: Map<string, string[]> = new Map();
    
    static setPermissions(userId: string, permissions: string[]): void {
        this.permissions.set(userId, permissions);
    }
    
    static hasPermission(userId: string, permission: string): boolean {
        const userPermissions = this.permissions.get(userId);
        return userPermissions ? userPermissions.includes(permission) : false;
    }
    
    static canAccessPatientData(userId: string, action: 'read' | 'write' | 'delete'): boolean {
        const permission = `patient:${action}`;
        return this.hasPermission(userId, permission);
    }
    
    static canAccessLabResults(userId: string, action: 'read' | 'write' | 'approve'): boolean {
        const permission = `lab_results:${action}`;
        return this.hasPermission(userId, permission);
    }
}

// GDPR Compliance Helper
export class GDPRCompliance {
    static anonymizeData(data: any): any {
        const anonymized = { ...data };
        
        // Remove direct identifiers
        delete anonymized.nombre_completo;
        delete anonymized.identificacion;
        delete anonymized.email;
        delete anonymized.telefono;
        delete anonymized.direccion;
        
        // Keep only non-identifiable data
        return {
            edad: anonymized.edad,
            genero: anonymized.genero,
            entidad: anonymized.entidad,
            // Remove any other identifying fields
            ...Object.fromEntries(
                Object.entries(anonymized).filter(([key]) => 
                    !['nombre_completo', 'identificacion', 'email', 'telefono', 'direccion'].includes(key)
                )
            )
        };
    }
    
    static generateDataProcessingRecord(userId: string, purpose: string): string {
        const record = {
            id: this.generateRecordId(),
            userId,
            purpose,
            lawfulBasis: 'consent',
            retentionPeriod: '7 years',
            timestamp: new Date().toISOString(),
            dataCategories: ['health_data', 'personal_data']
        };
        
        return JSON.stringify(record);
    }
    
    private static generateRecordId(): string {
        return 'gdpr_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
}

// Initialize security manager
export const securityManager = DataSecurityManager.getInstance();