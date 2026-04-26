# People's Bank CMS

A dynamic Content Management System built on PHP for People's Bank website, allowing administrators to create topics and add various types of content including text, images, and files.

## Features

- **Dynamic Topic Creation**: Create and manage topics with custom titles and descriptions
- **Multiple Content Types**: Add text content, images, and files to topics
- **Admin Panel**: User-friendly interface for content management
- **Public Display**: Topics are displayed on the public website with modal views
- **File Upload**: Secure file upload system for images and documents
- **Status Management**: Draft and published status for topics

## Setup Instructions

### 1. Database Setup

1. Create a MySQL database named `cms_db` on port **3309**
2. Run the SQL script in `database_setup.sql` to create the necessary tables:
   ```bash
   mysql -h localhost -P 3309 -u adminserver -p < database_setup.sql
   ```
3. Database credentials (configured in `db_function/db.php`):
   - Host: `localhost`
   - Port: `3309`
   - Database: `cms_db`
   - User: `adminserver`
   - Password: `admin123!@#`
   - Default Admin User: `admin` / `admin123`

### 2. File Permissions

Ensure the `uploads/` directory has write permissions for file uploads:

```bash
chmod 755 uploads/
```

### 3. Access the System

- **Public Website**: Visit `index.php` or `topics.php`
- **Admin Panel**: Visit `admin/index.php` and login with admin credentials

## Usage

### Admin Panel

1. **Login**: Use admin/admin123 or create new users
2. **Create Topics**:
   - Go to "CMS Topics" in the sidebar
   - Click "Create New Topic"
   - Fill in title, description, and set status
3. **Add Content to Topics**:
   - From the topics list, click "Manage Content"
   - Click "Add Content"
   - Choose content type (Text/Image/File)
   - Fill in details and upload files if needed

### Content Types

- **Text**: Rich text content with descriptions
- **Image**: Upload images (JPG, PNG, GIF) with captions
- **File**: Upload documents (PDF, DOC, etc.) for download

### Public View

- Topics are displayed on `topics.php`
- Click "View Details" to see full content in a modal
- Images are displayed inline, files show download links

## File Structure

```
/
├── index.php                 # Main public page
├── topics.php               # Public topics display
├── login.php                # Login page
├── admin/                   # Admin panel
│   ├── index.php           # Admin dashboard
│   ├── cms_topics.php      # Topic management
│   └── cms_content.php     # Content management
├── db_function/            # Database functions
│   ├── db.php              # Unified PDO database connection (port 3309)
│   ├── login_process.php   # Login handling
│   └── process_register.php # Registration
├── includes/               # Shared components
│   ├── navbar.php         # Navigation bar
│   ├── sidebar.php        # Admin sidebar
│   └── footer.php         # Footer
├── assets/                 # Static assets
│   ├── css/
│   └── img/
├── uploads/                # Uploaded files
└── database_setup.sql      # Database schema
```

## Database Connection

All files use the unified `db_function/db.php` connection file with:

- **PDO Driver**: For prepared statements and security
- **Port**: 3309
- **Auto-charset**: UTF-8

## Security Notes

- File uploads are restricted to common formats
- Admin access requires login
- SQL injection protection using prepared statements
- File paths are validated before access

## Customization

- Modify CSS in `assets/css/` for styling
- Update database schema in `database_setup.sql`
- Add new content types by extending the database and PHP logic
- Customize the admin interface in the admin/ directory files

## Support

For issues or enhancements, check the database connections and file permissions first.</content>
<parameter name="filePath">c:\My Work\Projects (Website)\https---github.com-supremojubil-PeoplesBankCMS\README.md
