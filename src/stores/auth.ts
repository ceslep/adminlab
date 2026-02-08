import { writable, type Writable } from 'svelte/store';
import type { User } from '../lib/types'; // Import User from types.ts

const user: Writable<User | null> = writable(null);
const isAuthenticated: Writable<boolean> = writable(false);

// Function to initialize auth state from localStorage (if any)
function initializeAuth(): void {
    if (typeof window !== 'undefined') {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            try {
                const parsedUser: User = JSON.parse(storedUser);
                user.set(parsedUser);
                isAuthenticated.set(true);
            } catch (e) {
                console.error("Error parsing stored user from localStorage", e);
                logout(); // Clear potentially corrupted data
            }
        }
    }
}

// Function to handle login
function login(userData: User): void {
    user.set(userData);
    isAuthenticated.set(true);
    if (typeof window !== 'undefined') {
        localStorage.setItem('user', JSON.stringify(userData));
    }
}

// Function to handle logout
function logout(): void {
    user.set(null);
    isAuthenticated.set(false);
    if (typeof window !== 'undefined') {
        localStorage.removeItem('user');
    }
}

initializeAuth();

export { user, isAuthenticated, login, logout };
