# PLN UP2D CI - AI Coding Conventions

This document provides AI-driven guidance for developers working on the PLN UP2D CI codebase. It outlines the project's architecture, key workflows, and specific coding conventions to ensure consistency and efficiency.

## Big Picture Architecture

The PLN UP2D CI application is built on the CodeIgniter framework, following a modular structure that separates concerns and promotes code reusability. The application is organized into distinct modules, each responsible for a specific functionality.

### Key Modules

- **Gardu Induk (GI):** Manages data related to main substations.
- **Gardu Hubung (GH):** Handles data for distribution substations.
- **Pembangkit:** Manages power plant data.
- **Ulp:** Responsible for service unit data.
- **Unit:** Manages main unit data.

Each module follows a consistent structure, comprising:

- **Controllers:** Handle user requests and interact with models and views.
- **Models:** Manage data and business logic.
- **Views:** Present data to the user.

### Data Flow

The application follows a standard MVC data flow:

1. **Request:** A user request is received by a controller.
2. **Data Processing:** The controller interacts with the corresponding model to process the request and retrieve data.
3. **Response:** The controller passes the data to a view, which renders the final output.

## Developer Workflows

### Getting Started

1. **Clone the repository:**
   ```bash
   git clone https://github.com/rahmaayuananda/pln_up2d_ci.git
   ```
2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Configure the database:**
   - Create a database named `db_pln_up2d`.
   - Update the database credentials in `application/config/database.php`.

### Running the Application

- **Start the development server:**
  ```bash
  php -S localhost:8000
  ```

### Running Tests

- **Run unit tests:**
  ```bash
  vendor/bin/phpunit
  ```

## Coding Conventions

### Naming Conventions

- **Controllers:** Use `PascalCase` for class names (e.g., `Gardu_induk.php`).
- **Models:** Use `PascalCase` with a `_model` suffix (e.g., `Gardu_induk_model.php`).
- **Views:** Use `snake_case` for file names (e.g., `gardu_induk_view.php`).

### Code Style

- Follow the **PSR-12** coding style guide.
- Use **camelCase** for methods and variables.
- Add comments to explain complex logic.

## Integration Points

### External Dependencies

- **CodeIgniter:** The core framework for the application.
- **Argon Dashboard:** The front-end template for the user interface.

### Cross-Component Communication

- **Controllers** communicate with **models** to access data.
- **Controllers** load **views** to render the user interface.
- **AJAX** is used for asynchronous communication between the front-end and back-end.
