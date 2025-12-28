# Developer Quick Start Guide

> **Goal**: Get development environment set up and make your first contribution in 30 minutes
> **Audience**: New developers joining the Workshop Management module project
> **Prerequisites**: Basic PHP knowledge, Git familiarity, Dolibarr experience helpful

## Table of Contents

- [Environment Setup (10 minutes)](#environment-setup-10-minutes)
- [Getting the Code (5 minutes)](#getting-the-code-5-minutes)
- [Understanding the Structure (10 minutes)](#understanding-the-structure-10-minutes)
- [Making Your First Change (5 minutes)](#making-your-first-change-5-minutes)
- [Next Steps](#next-steps)

---

## Environment Setup (10 minutes)

### Required Software

1. **PHP 7.4 or higher**

   ```bash
   php -v
   # Should show PHP 7.4.x or higher
   ```

2. **MySQL/MariaDB**

   ```bash
   mysql --version
   # Should show MySQL 5.7+ or MariaDB 10.3+
   ```

3. **Dolibarr 12.0 or higher**

   Download from: [https://www.dolibarr.org/downloads](https://www.dolibarr.org/downloads)

4. **Git**

   ```bash
   git --version
   ```

5. **Composer** (optional but recommended)

   ```bash
   composer --version
   ```

### Install Dolibarr Locally

#### Option 1: Using XAMPP/WAMP (Windows) or MAMP (Mac)

1. Install XAMPP/WAMP/MAMP
2. Download Dolibarr
3. Extract to `htdocs/dolibarr` (or equivalent)
4. Navigate to `http://localhost/dolibarr`
5. Complete installation wizard

#### Option 2: Using Docker (Recommended)

```bash
# Pull Dolibarr Docker image
docker pull dolibarr/dolibarr:latest

# Run Dolibarr container
docker run -d \
  --name dolibarr \
  -p 8080:80 \
  -e DOLI_DB_HOST=mysql \
  -e DOLI_DB_NAME=dolibarr \
  -e DOLI_DB_USER=dolibarr \
  -e DOLI_DB_PASSWORD=dolibarr \
  --link mysql:mysql \
  dolibarr/dolibarr

# Access at http://localhost:8080
```

#### Option 3: Manual Installation

```bash
# Download Dolibarr
wget https://github.com/Dolibarr/dolibarr/archive/refs/heads/develop.zip
unzip develop.zip
cd dolibarr-develop

# Set up web server to point to this directory
# Create database
mysql -u root -p
CREATE DATABASE dolibarr;
CREATE USER 'dolibarr'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON dolibarr.* TO 'dolibarr'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Navigate to http://localhost and complete setup
```

### Verify Installation

1. Navigate to Dolibarr URL
2. Login with admin credentials
3. Go to Home → Setup → Modules
4. Verify you can see the module list

---

## Getting the Code (5 minutes)

### Clone the Repository

```bash
# Clone the repository
git clone http://127.0.0.1:57733/git/idiey/wshop_man.git
cd wshop_man

# Checkout development branch
git checkout claude/analyze-workshop-artifact-yd959

# Verify you're on the right branch
git branch
```

### Link Module to Dolibarr

#### Symlink Method (Recommended for Development)

```bash
# Navigate to Dolibarr custom modules directory
cd /path/to/dolibarr/htdocs/custom/

# Create symlink to your workshop module
ln -s /path/to/wshop_man workshop

# Verify symlink
ls -la
# Should see: workshop -> /path/to/wshop_man
```

#### Copy Method

```bash
# Copy module to Dolibarr custom directory
cp -r /path/to/wshop_man /path/to/dolibarr/htdocs/custom/workshop
```

### Install Dependencies (if using Composer)

```bash
cd /path/to/wshop_man
composer install
```

---

## Understanding the Structure (10 minutes)

### Module Directory Structure

```text
wshop_man/
├── admin/                      # Administration pages
│   ├── setup.php              # Module configuration
│   └── about.php              # About page
├── class/                      # Business logic classes
│   ├── workshop.class.php     # Workshop entity
│   ├── workshopjob.class.php  # Job entity
│   └── api_workshop.class.php # API endpoints
├── core/
│   ├── modules/
│   │   └── modWorkshop.class.php  # Module descriptor
│   └── triggers/
│       └── interface_workshop.class.php  # Triggers
├── css/                        # Stylesheets
├── img/                        # Images and icons
├── js/                         # JavaScript files
├── langs/                      # Translations
│   ├── en_US/
│   │   └── workshop.lang
│   └── fr_FR/
│       └── workshop.lang
├── lib/                        # Helper functions
│   └── workshop.lib.php
├── sql/                        # Database scripts
│   ├── llx_workshop.sql       # Main table
│   ├── llx_workshop_job.sql   # Job table
│   └── data.sql               # Initial data
├── test/                       # Unit tests
├── docs/                       # Documentation
├── workshop_card.php           # Workshop detail page
├── workshop_list.php           # Workshop list page
└── workshop_job.php            # Job management page
```

### Key Files Explained

#### 1. Module Descriptor: `core/modules/modWorkshop.class.php`

This file defines the module to Dolibarr:

```php
class modWorkshop extends DolibarrModules
{
    public function __construct($db)
    {
        $this->db = $db;
        $this->numero = 500000;  // Unique module ID
        $this->family = "projects";
        $this->module_position = '50';
        $this->name = 'Workshop';
        $this->description = "Workshop Management";
        // ... more configuration
    }
}
```

#### 2. Entity Class: `class/workshop.class.php`

Main business logic for workshops:

```php
class Workshop extends CommonObject
{
    public function create($user) { }
    public function fetch($id) { }
    public function update($user) { }
    public function delete($user) { }
}
```

#### 3. UI Page: `workshop_card.php`

User interface for viewing/editing workshops:

```php
// Load environment
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/custom/workshop/class/workshop.class.php';

// Get object
$object = new Workshop($db);
$object->fetch($id);

// Display page
llxHeader();
// ... page content
llxFooter();
```

### Dolibarr Coding Patterns

#### Database Access

```php
// Using $db global
$sql = "SELECT rowid, ref, label FROM ".MAIN_DB_PREFIX."workshop";
$resql = $this->db->query($sql);
if ($resql) {
    $obj = $this->db->fetch_object($resql);
}
```

#### Permissions

```php
// Check permission
if (!$user->rights->workshop->read) {
    accessforbidden();
}
```

#### Translations

```php
// In code
$langs->load("workshop@workshop");
print $langs->trans("WorkshopManagement");

// In lang file (langs/en_US/workshop.lang)
WorkshopManagement=Workshop Management
```

---

## Making Your First Change (5 minutes)

### Task: Add a Field to Workshop Entity

Let's add a "location" field to workshops.

#### Step 1: Update Database Schema (sql/llx_workshop.sql)

```sql
ALTER TABLE llx_workshop
ADD COLUMN location VARCHAR(255) NULL
AFTER label;
```

#### Step 2: Update Class (class/workshop.class.php)

```php
class Workshop extends CommonObject
{
    public $location;  // Add property

    public function __construct($db)
    {
        $this->db = $db;
        // ... existing code

        // Add to fields array
        $this->fields['location'] = array(
            'type'=>'varchar(255)',
            'label'=>'Location',
            'enabled'=>1,
            'visible'=>1,
        );
    }
}
```

#### Step 3: Update UI (workshop_card.php)

```php
// Add to form
print '<tr><td>'.$langs->trans("Location").'</td>';
print '<td><input type="text" name="location" value="'.dol_escape_htmltag($object->location).'" class="minwidth300"></td></tr>';
```

#### Step 4: Update Translation (langs/en_US/workshop.lang)

```text
Location=Location
```

#### Step 5: Test Your Change

1. Refresh module in Dolibarr (Home → Setup → Modules → Workshop → Disable/Enable)
2. Navigate to workshop card page
3. Verify location field appears
4. Save and verify it persists

#### Step 6: Commit Your Change

```bash
git add .
git commit -m "[Feature] Add location field to workshop entity"
git push origin claude/analyze-workshop-artifact-yd959
```

---

## Development Workflow

### Daily Development Process

1. **Pull latest changes**

   ```bash
   git pull origin claude/analyze-workshop-artifact-yd959
   ```

2. **Create feature branch** (optional)

   ```bash
   git checkout -b feature/my-new-feature
   ```

3. **Make changes**
   - Edit code
   - Test locally
   - Write/update tests

4. **Commit frequently**

   ```bash
   git add .
   git commit -m "[Type] Description"
   ```

5. **Push changes**

   ```bash
   git push -u origin feature/my-new-feature
   ```

6. **Create pull request** (when ready)

### Testing Your Changes

#### Manual Testing

1. Disable/Enable module to refresh
2. Test in browser
3. Check error logs: `/path/to/dolibarr/documents/dolibarr.log`
4. Test different user permission levels

#### Automated Testing

```bash
# Run PHPUnit tests
cd /path/to/wshop_man
./vendor/bin/phpunit tests/

# Run specific test
./vendor/bin/phpunit tests/WorkshopTest.php
```

### Debugging Tips

#### Enable Debug Mode

In Dolibarr: Home → Setup → Display → Enable debug mode

#### Check Logs

```bash
tail -f /path/to/dolibarr/documents/dolibarr.log
```

#### Use var_dump Carefully

```php
dol_syslog("Debug value: " . var_export($variable, true));
```

#### SQL Debugging

```php
dol_syslog($sql);  // Log SQL query
```

---

## Common Tasks

### Adding a New Page

1. Create PHP file in module root (e.g., `workshop_report.php`)
2. Copy structure from existing page
3. Add to module menu in `modWorkshop.class.php`

```php
$this->menu[$r++] = array(
    'fk_menu'=>'fk_mainmenu=workshop',
    'type'=>'left',
    'titre'=>'Reports',
    'url'=>'/custom/workshop/workshop_report.php',
    'perms'=>'$user->rights->workshop->read'
);
```

### Adding a Database Table

1. Create SQL file in `/sql/` (e.g., `llx_workshop_attachment.sql`)
2. Add table definition to module descriptor
3. Create entity class
4. Test creation/rollback

### Adding Permissions

In `modWorkshop.class.php`:

```php
$this->rights[$r][0] = 500003;  // Unique ID
$this->rights[$r][1] = 'Export workshop data';
$this->rights[$r][4] = 'export';
$r++;
```

Use in code:

```php
if (!$user->rights->workshop->export) {
    accessforbidden();
}
```

---

## Helpful Resources

### Dolibarr Documentation

- [Module Development Guide](https://wiki.dolibarr.org/index.php/Module_development)
- [Coding Standards](https://wiki.dolibarr.org/index.php/Coding_standards)
- [Database Structure](https://wiki.dolibarr.org/index.php/Database_tables)
- [API Documentation](https://wiki.dolibarr.org/index.php/API)

### Project Documentation

- [Technical Architecture](./technical-architecture-document.md)
- [Sprint Planning](./sprint-planning.md)
- [Implementation Roadmap](./implementation-roadmap.md)

### Code Examples

Look at existing Dolibarr modules for patterns:

- `/htdocs/projet/` - Project module
- `/htdocs/product/` - Product module
- `/htdocs/comm/` - Commercial module

---

## Next Steps

### What to Work On

1. Check [Sprint 0 Daily Todos](./sprint-0-daily-todos.md) for current tasks
2. Look at GitHub Issues tagged "good first issue"
3. Review [Sprint Planning](./sprint-planning.md) for upcoming features

### Learning Path

**Week 1:**

- Complete environment setup
- Make first small contribution
- Review Dolibarr documentation

**Week 2:**

- Understand module architecture
- Implement a feature from sprint backlog
- Write tests for your code

**Week 3:**

- Work on more complex features
- Help review other's code
- Contribute to documentation

### Getting Help

- **Code Questions**: Check existing code, Dolibarr wiki
- **Bugs**: Create GitHub issue with details
- **Architecture Questions**: Review technical architecture doc
- **Stuck?**: Don't hesitate to ask the team

---

## Troubleshooting

### Module Doesn't Appear in Module List

- Check symlink is correct
- Verify file permissions
- Check Dolibarr custom modules path
- Look for PHP errors in logs

### Database Tables Not Creating

- Check SQL syntax
- Verify table definitions in module descriptor
- Check database user permissions
- Review Dolibarr logs

### Changes Not Appearing

- Disable and re-enable module
- Clear browser cache
- Check file permissions
- Verify code syntax (no PHP errors)

### Permission Denied Errors

- Check user has required permissions
- Verify permission definitions in module descriptor
- Re-enable module to refresh permissions

---

## Checklist: First Day Setup

- [ ] Install PHP, MySQL, and Dolibarr
- [ ] Clone wshop_man repository
- [ ] Link module to Dolibarr custom directory
- [ ] Verify Dolibarr is accessible
- [ ] Read project documentation
- [ ] Make first test change
- [ ] Successfully commit and push
- [ ] Join team communication channels
- [ ] Review Sprint 0 tasks
- [ ] Ask questions if stuck

**Estimated Time**: 30-60 minutes

---

**Welcome to the team! Happy coding!** 🚀

**Last Updated**: 2025-12-28
**Maintained By**: Workshop Management Development Team
