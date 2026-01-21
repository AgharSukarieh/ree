# Memoria - Digital Portfolio Platform

Memoria is a Laravel-based digital portfolio platform that allows users to create and share their professional profiles through QR codes. Perfect for students, professionals, and anyone who wants to showcase their skills and achievements.

## Features

- **User Management**: Create and manage user profiles with QR code identification
- **Comprehensive Profiles**: Include activities, skills, experiences, projects, and more
- **Multiple Skill Categories**: Support for technical skills, medical skills, and soft skills
- **Professional Display**: Beautiful, responsive web interface for profile viewing
- **QR Code Integration**: Easy sharing through QR codes
- **API Support**: Full REST API for all CRUD operations

## Database Schema

The application includes the following main entities:

### Core Tables
- **users**: Main user profiles with QR ID as primary key
- **activities**: User activities and events
- **experiences**: Work and internship experiences
- **projects**: Personal and professional projects
- **research**: Research publications and work

### Skills & Competencies
- **skills**: Technical skills with categories
- **skill_categories**: Categories for technical skills
- **medical_skills**: Medical-specific skills
- **medical_skill_categories**: Categories for medical skills
- **analytical_skills**: Analytical and problem-solving skills
- **soft_skills**: Soft skills and personal attributes
- **core_competencies**: Core professional competencies

### Additional Information
- **certifications**: Professional certifications
- **languages**: Language proficiency levels
- **interests**: Personal and professional interests
- **memberships**: Professional memberships
- **wishes**: Messages and wishes from others

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd memoria-app
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   Update your `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=memoria
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

## API Endpoints

### Users
- `GET /api/users` - List all users
- `POST /api/users` - Create a new user
- `GET /api/users/{qr_id}` - Get user by QR ID
- `PUT /api/users/{qr_id}` - Update user
- `DELETE /api/users/{qr_id}` - Delete user
- `GET /api/users/{qr_id}/profile` - Get user profile (public)

### Activities
- `GET /api/activities` - List all activities
- `POST /api/activities` - Create new activity
- `GET /api/activities/{id}` - Get activity by ID
- `PUT /api/activities/{id}` - Update activity
- `DELETE /api/activities/{id}` - Delete activity
- `GET /api/activities/user/{qr_id}` - Get activities by user

Similar endpoints exist for all other entities (skills, experiences, projects, etc.)

## Web Routes

- `/` - Home page
- `/dashboard` - User dashboard
- `/profile/{qr_id}` - User profile page

## Sample Data

The application includes seeders that create:
- Skill categories (24 technical skill categories)
- Medical skill categories (16 medical skill categories)
- Sample users with complete profiles
- Sample data for all related entities

## Usage

1. **View Profiles**: Visit `/dashboard` to see all active profiles
2. **Access Individual Profiles**: Use `/profile/{qr_id}` to view specific profiles
3. **API Integration**: Use the REST API endpoints for programmatic access
4. **QR Code Sharing**: Each user has a unique QR ID that can be used to share their profile

## Technologies Used

- **Laravel 11**: PHP framework
- **MySQL**: Database
- **Bootstrap 5**: Frontend framework
- **Font Awesome**: Icons
- **Eloquent ORM**: Database relationships

## Project Structure

```
memoria-app/
├── app/
│   ├── Http/Controllers/Api/    # API controllers
│   └── Models/                  # Eloquent models
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── resources/views/             # Blade templates
└── routes/
    ├── api.php                  # API routes
    └── web.php                  # Web routes
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).