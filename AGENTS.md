# Project Context: Jorani Modernization

## 1. Current Technical Stack
- **Framework:** CodeIgniter 3.x (Legacy PHP Monolith).
- **Architecture:** Classic MVC (Tightly coupled PHP/HTML Views).
- **Database:** MySQL (InnoDB engine).
- **Dependency Management:** Mixed (Composer + legacy libraries in `/application/libraries`).
- **PHP Version Target:** Modernizing from 7.x to 8.2+.

## 2. Modernization Objectives (Priorities)
1. **Strict Typing:** Progressively migrate to PHP 8.2+ standards (Strict types, constructor promotion, union types).
2. **Decoupling:** Extract business logic from Controllers into a new `Service` layer.
3. **API-first Strategy:** Develop JSON REST endpoints to replace direct `header/footer` view rendering.
4. **Testing:** Implement PHPUnit for core date-calculation logic (leave balance, holidays).
5. **Containerization:** Ensure all changes are compatible with a Docker-based workflow.

## 3. Agent Guardrails & Guidelines
- **Security:** Ensure `defined('BASEPATH') OR exit('No direct script access allowed');` is present in all PHP files.
- **Database:** Use CodeIgniter's Query Builder (`$this->db->...`) exclusively to prevent SQL injection. No raw queries unless strictly necessary.
- **Code Style:** Follow **PSR-12** coding standards for all new or refactored code.
- **Naming Convention:** Use `CamelCase` for classes/services and `snake_case` for methods/variables to match existing CodeIgniter patterns.
- **Documentation:** All code comments must be in **English**. Explanations to the user should be in **French**.

## 4. Key Directory Map
- `application/controllers/`: Routing and request validation.
- `application/models/`: Database interactions (currently contains too much business logic).
- `application/services/`: (New) Target folder for extracted business logic.
- `application/views/`: Legacy HTML templates (to be deprecated by API endpoints).
- `assets/`: Static files (JS/CSS).

## 5. Database Schema & Truth
- **Schema Reference:** The source of truth for the database structure is located at `sql/jorani.sql`.
- **Relationship Knowledge:** Always refer to this file to identify table relationships (e.g., `leaves`, `users`, `entitlements`).
- **Data Integrity:** When proposing migrations or new services, ensure consistency with the existing SQL constraints defined in this file.
