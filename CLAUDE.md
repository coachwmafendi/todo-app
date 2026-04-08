# CLAUDE.md - Funky Todo List App

## Project Overview
A simple, "funky" todo list application built with Laravel and Livewire, featuring a Neo-brutalism design aesthetic.

## Tech Stack
- **Framework**: Laravel 13
- **Frontend**: Livewire 4, Tailwind CSS
- **Database**: SQLite
- **Design Style**: Neo-brutalism (bold borders, vibrant colors, hard shadows)

## Project Structure
- `app/Livewire/TodoList.php`: Main business logic for managing tasks.
- `resources/views/livewire/todo-list.blade.php`: The reactive UI for the todo list.
- `app/Models/Task.php`: Eloquent model for tasks.
- `resources/views/welcome.blade.php`: Main entry point/layout.

## Development Patterns
- **Reactivity**: Use Livewire components for all interactive elements to avoid full page reloads.
- **Styling**: Use Tailwind CSS with custom "funky" utilities (e.g., `border-4 border-black`, `shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]`).
- **Database**: Keep the schema simple; use SQLite for rapid local development.

## Common Commands
- **Start Server**: `php artisan serve`
- **Run Migrations**: `php artisan migrate`
- **Create Component**: `php artisan make:livewire ComponentName`
- **Model Creation**: `php artisan make:model ModelName -m`

## Design Guidelines
- Use a high-contrast color palette (Indigo, Yellow, Pink).
- Prefer bold typography and uppercase labels for headings.
- Implement "bouncy" transitions and active state transformations (e.g., `active:translate-x-1 active:translate-y-1`).
