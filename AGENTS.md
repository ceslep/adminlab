# AGENTS.md - Development Guidelines for AdminLab

## Project Overview
AdminLab is a Svelte + TypeScript + Vite clinical laboratory dashboard application. This document provides essential guidelines for agentic coding agents working in this repository.

## Build & Development Commands

### Core Commands
- `npm run dev` - Start development server on port 3000
- `npm run build` - Build for production (optimized for enterprise performance)
- `npm run preview` - Preview production build on port 4173
- `npm run deploy` - Build and deploy to GitHub Pages

### Type Checking & Validation
- `npm run check` - Run Svelte type checking and TypeScript compilation
- No separate lint command configured - relies on Svelte check for type validation

### Testing
- No test framework currently configured in this project
- When adding tests, follow Svelte Testing Library conventions

## Code Structure & Architecture

### Path Aliases
Use these configured path aliases for clean imports:
- `$lib` → `./src/lib`
- `$components` → `./src/lib/components`
- `$styles` → `./src/styles`
- `$utils` → `./src/lib/utils`
- `$assets` → `./src/assets`

### File Organization
```
src/
├── lib/
│   ├── components/
│   │   ├── dashboard/
│   │   │   ├── patient/     # Patient-specific components
│   │   │   └── shared/      # Reusable UI components
│   │   └── Login.svelte     # Authentication components
│   ├── api.ts              # API service layer
│   ├── types.ts            # TypeScript type definitions
│   ├── constants.ts        # Application constants
│   └── security/           # Security utilities
├── stores/
│   └── auth.ts             # Svelte stores for state management
└── main.ts                # Application entry point
```

## Code Style Guidelines

### TypeScript & Svelte Patterns
- Use TypeScript strict mode - all interfaces must be properly typed
- Export interfaces from `src/lib/types.ts` for reuse across components
- Use Svelte stores for state management (see `src/stores/auth.ts` pattern)
- All Svelte components must use `<script lang="ts">`

### Import Conventions
```typescript
// External libraries first
import { writable, type Writable } from 'svelte/store';

// Internal imports using path aliases
import type { User, ApiResponse } from '$lib/types';
import { login } from '$lib/api';
```

### Component Architecture
- Follow the shared component pattern in `src/lib/components/dashboard/shared/`
- Use exported props with TypeScript interfaces
- Implement proper loading states and error handling
- Components should be self-contained with clear prop interfaces

### API Integration
- All API calls should follow the pattern in `src/lib/api.ts`
- Use the `ApiResponse<T>` generic interface for consistent responses
- Always include proper error handling with user-friendly messages
- Use FormData for POST requests to match backend expectations

### CSS & Styling
- Use Tailwind CSS v4 with the design system variables in `src/app.css`
- Follow the CSS custom property pattern for theming
- Components should use scoped styles with `<style>` blocks
- Leverage the existing design system colors and spacing

## Error Handling Patterns

### API Error Handling
```typescript
try {
  const response = await fetch(url, options);
  if (!response.ok) {
    return { success: false, message: `HTTP error! status: ${response.status}` };
  }
  const data = await response.json();
  return data;
} catch (error) {
  console.error('Error message:', error);
  return { success: false, message: 'User-friendly error message' };
}
```

### Component Error Boundaries
- Use Svelte's error boundaries for graceful error handling
- Always provide fallback UI for failed states
- Include loading spinners for async operations

## Security Considerations

- Never commit secrets or API keys to the repository
- Use environment variables for sensitive configuration
- Validate all user inputs on both client and server side
- Follow the security patterns in `src/lib/security/`

## Performance Guidelines

- Use Svelte's reactivity system efficiently
- Implement proper loading states for better UX
- Leverage the build optimizations configured in `vite.config.ts`
- Use the CSS injection strategy for better runtime performance

## Development Workflow

1. Run `npm run check` before committing changes
2. Use the configured development server with HMR
3. Follow the existing component patterns when creating new features
4. Maintain the Spanish language interface for user-facing text
5. Keep the clinical laboratory domain context in mind for all features

## Key Dependencies
- Svelte 5.43.8 (latest with Svelte 5 syntax)
- TypeScript 5.9.3
- Tailwind CSS 4.1.18
- Framer Motion 12.34.0 for animations
- Lucide Svelte for icons

## Browser Compatibility
- Target: ES2020+ (configured in vite.config.ts)
- Modern browsers only (no legacy browser support needed)
- Use CSS features available in modern browsers