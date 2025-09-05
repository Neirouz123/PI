# EventaPlan

A comprehensive event venue booking and management system built with both JavaFX (desktop application) and Symfony 6.4 (web application), providing a complete solution for venue owners and event organizers.

## 🏢 Overview

EventaPlan is a dual-platform application that allows users to:
- Browse and search available venues
- Make reservations with calendar-based booking
- Process secure payments via Stripe integration
- Manage venue inventory (admin features)
- Track bookings and cart functionality

## 🛠️ Tech Stack

### Desktop Application (JavaFX)
- **Language**: Java
- **Framework**: JavaFX
- **Database**: MySQL/PostgreSQL (via JDBC)
- **Payment**: Stripe API integration
- **Architecture**: MVC pattern

### Web Application (Symfony)
- **Framework**: Symfony 6.4
- **Language**: PHP 8.1+
- **Database**: MySQL/PostgreSQL with Doctrine ORM
- **Payment**: Stripe API integration
- **Frontend**: Twig templates, HTML5, CSS3, JavaScript

## 📁 Project Structure

```
EventaPlan/
├── desktop-app/                    # JavaFX Desktop Application
│   ├── src/
│   │   ├── controller/            # JavaFX Controllers
│   │   ├── entities/              # Data Models
│   │   ├── services/              # Business Logic
│   │   └── resources/             # FXML files and assets
│   └── pom.xml                    # Maven dependencies
│
└── web-app/                       # Symfony Web Application
    ├── config/                    # Symfony configuration
    ├── src/
    │   ├── Controller/            # Symfony Controllers
    │   ├── Entity/                # Doctrine Entities
    │   ├── Repository/            # Data Access Layer
    │   └── Form                   #Form Making
    ├── templates/                 # Twig templates
    ├── public/                    # Web assets
    └── composer.json              # PHP dependencies
```

## 🚀 Features

### Core Functionality
- **Venue Management**: Add, edit, delete, and display venues with images
- **Search & Filter**: Real-time search by name, capacity, availability
- **Calendar Booking**: Interactive calendar for date selection
- **Shopping Cart**: Add multiple reservations before checkout
- **Secure Payments**: Stripe integration for payment processing
- **User Management**: User authentication and session management

### Desktop Application Features
- **LocalCard System**: Modern card-based venue display
- **Real-time Search**: Dynamic filtering as you type
- **Calendar View**: Visual calendar with availability status
- **Payment Integration**: Embedded Stripe checkout
- **Backoffice Management**: Admin panel for venue management

### Web Application Features
- **Responsive Design**: Mobile-friendly interface
- **RESTful API**: Clean API endpoints for data access
- **Admin Dashboard**: Web-based management interface
- **Multi-language Support**: Internationalization ready

## 📋 Prerequisites

### For Desktop Application
- Java 11 or higher
- JavaFX SDK
- Maven 3.6+
- MySQL/PostgreSQL database

### For Web Application
- PHP 8.1 or higher
- Composer
- Symfony CLI (optional but recommended)
- Node.js and npm (for asset compilation)
- MySQL/PostgreSQL database

## ⚙️ Installation

### Desktop Application Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/pi
   cd eventaplan/desktop-app
   ```

2. **Configure Database**
   - Create a MySQL/PostgreSQL database
   - Update database connection settings in `DataSource.java`

3. **Install Dependencies**
   ```bash
   mvn clean install
   ```

4. **Configure Stripe**
   - Add your Stripe API keys to the configuration
   - Update `StripePaymentService.java` with your credentials

5. **Run the Application**
   ```bash
   mvn javafx:run
   ```

### Web Application Setup

1. **Navigate to web application directory**
   ```bash
   cd eventaplan/web-app
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Configure Environment**
   ```bash
   cp .env .env.local
   # Edit .env.local with your database credentials
   ```

4. **Setup Database**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Install Node.js Dependencies (if using Webpack Encore)**
   ```bash
   npm install
   npm run build
   ```

6. **Start the Development Server**
   ```bash
   symfony server:start
   # or
   php -S localhost:8000 -t public/
   ```

## 🗄️ Database Schema

### Core Tables
- **venues** (`local`): Venue information with images
- **reservations**: Booking records with dates and times
- **users** (`utilisateur`): User accounts and authentication
- **payments**: Payment transaction records

### Key Relationships
- Users can have multiple reservations
- Venues can have multiple reservations
- Each reservation belongs to one user and one venue

## 💳 Payment Integration

The Desktop applications uses Stripe for secure payment processing:

### Desktop (JavaFX)
- Embedded WebView for Stripe Checkout
- Real-time payment status updates
- Automatic cart clearing on successful payment

### Web (Symfony)
- Translation

## 🔐 Security Features

- Input validation and sanitization
- SQL injection prevention
- Secure payment processing
- User session management
- CSRF protection (web app)

## 🎨 UI/UX Features

### Desktop Application
- Modern card-based layout
- Smooth animations and transitions
- Responsive design elements
- Intuitive navigation
- Real-time search feedback

### Web Application
- Bootstrap/responsive CSS framework
- Mobile-first design
- Accessible interface
- Progressive enhancement

## 🔧 Configuration

### Database Configuration
Update connection settings in:
- **Desktop**: `DatabaseConfig.java`
- **Web**: `.env.local` file

### Stripe Configuration
Add your Stripe keys:
- **Desktop**: `StripePaymentService.java`
- **Web**: Environment variables in `.env.local`

## 🚦 API Endpoints (Web Application)

```
GET    /api/venues              # List all venues
GET    /api/venues/{id}         # Get venue details
POST   /api/venues              # Create venue (admin)
PUT    /api/venues/{id}         # Update venue (admin)
DELETE /api/venues/{id}         # Delete venue (admin)

GET    /api/reservations        # List user reservations
POST   /api/reservations        # Create reservation
DELETE /api/reservations/{id}   # Cancel reservation

POST   /api/payments/create     # Create payment intent
POST   /api/payments/confirm    # Confirm payment
```

## 🧪 Testing

### Desktop Application
```bash
mvn test
```

### Web Application
```bash
php bin/phpunit
```

## 📈 Performance Considerations

- Database indexing on frequently queried fields
- Image optimization and lazy loading
- Connection pooling for database access
- Caching strategies for frequently accessed data
- Pagination for large datasets

## 🔄 Deployment

### Desktop Application
- Package as executable JAR with dependencies
- Create installer using tools like Launch4j or jpackage
- Consider auto-update mechanisms

### Web Application
- Deploy to production server (Apache/Nginx)
- Configure environment variables
- Set up SSL certificates
- Configure database backups
- Monitor application performance

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## 🆘 Support

For support and questions:
- Create an issue in the GitHub repository
- Contact the development team
- Check the documentation wiki

## 🏗️ Roadmap

- [ ] Mobile application (React Native/Flutter)
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] Integration with external calendar systems
- [ ] Advanced reporting features
- [ ] Email notification system
- [ ] SMS notifications for bookings

## 👥 Authors

- **Your Name** - *Initial work* - [YourGitHub](https://github.com/yourusername)

## 🙏 Acknowledgments

- JavaFX community for excellent documentation
- Symfony team for the robust framework
- Stripe for secure payment processing
- All contributors and testers
