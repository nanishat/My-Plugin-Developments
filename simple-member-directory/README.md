# Simple Member Directory Plugin

A custom WordPress plugin to manage **Members** and **Teams** with a frontend directory, individual member profiles, and contact forms.

This project was built as part of a WordPress plugin development assignment.

---

## 📦 Features

### Member Fields

- First Name  
- Last Name  
- Email (**Unique**)  
- Profile Image  
- Cover Image  
- Address  
- Favorite Color (color picker)  
- Status: **Active / Draft**

---

### Team Fields

- Name  
- Short Description

---

### Relationships

- A **Member** can be assigned to **multiple Teams**.
- **Duplicate emails** are not allowed for members.

---

## 🌐 Frontend Functionality

### Single Member Page

- Custom URL structure:  
  `/first-name_last-name`

**Example:**  
`https://yourwebsite.com/john_doe`

**Displays:**

- Member profile & cover image  
- Member information  
- Contact form (sends email + stores submission)

---

### Member & Team Listings

| Page          | Shortcode       |
|---------------|----------------|
| All Members   | `[all_members]` |
| All Teams     | `[all_teams]`   |

Create WordPress pages and place these shortcodes to display the listings.

---

## ⚙️ Admin Features

- **Custom Post Types:**
  - `member`  
  - `team`
- Prevents duplicate email entries for members  
- Stores and manages contact form submissions (via `wp_options`)

---

## 📂 Plugin Structure

```text
simple-member-directory/
│
├── simple-member-directory.php       # Main plugin loader
├── includes/                         # All PHP classes
│   ├── Core.php                      # Plugin init & hooks
│   ├── MemberCPT.php                 # Member post type & meta fields
│   ├── TeamCPT.php                   # Team post type
│   ├── ContactHandler.php            # Handles contact form submissions
│   ├── Rewrite.php                   # Custom URL routing (/first_last)
│   └── Shortcodes.php                # Shortcodes for listings
│
├── templates/                        # Frontend templates
│   ├── single-member.php             # Single member page
│   ├── all-members.php               # All members listing
│   └── all-teams.php                 # All teams listing
│
├── assets/css/style.css              # Frontend styles
└── README.md                         # This file
```

---

## 🚀 Installation

1. **Clone this repo:**
git clone https://github.com/nanishat/My-Plugin-Developments.git

2. Copy the **`simple-member-directory/`** folder into:
wp-content/plugins/

3. Activate the plugin via **WordPress Admin → Plugins**

4. Go to **Settings → Permalinks → Save Changes** to refresh rewrite rules.

---

## 🧰 Usage

### Adding Members & Teams

- Use **WordPress Admin → Members → Add New**  
- Use **WordPress Admin → Teams → Add New**  

---

### Displaying Members & Teams

Create pages and insert shortcodes:

| Functionality | Shortcode       |
|---------------|----------------|
| All Members   | `[all_members]` |
| All Teams     | `[all_teams]`   |

---

### Contact Form

- Available on individual member pages  
- On form submit:
  - Sends an email to the member's email
  - Stores submission in `wp_options` (key: `member_messages`)

---

## 🔧 Development Notes

### Autoloading

Uses **PSR-4 style class autoloading** (manual implementation, no Composer):

- Namespace:  
  `MemberDirectory\`
- Class files loaded from `/includes/`

---

### Coding Standards

- Follows **WordPress best practices**  
- No third-party libraries used  
- Clean, modular, and optimized code

---

## 📂 GitHub Repository

**Repo:**  
[https://github.com/nanishat/My-Plugin-Developments](https://github.com/nanishat/My-Plugin-Developments)

---

## 💾 Commit History

| Commit                         | Description              |
|--------------------------------|--------------------------|
| `Initial commit`               | Setup base plugin structure |
| `Add member CPT and meta fields` | Member functionality |
| `Add team CPT`                 | Team functionality      |
| `Implement frontend templates` | Member & team pages     |
| `Add contact form handling`    | Email & submission logic |
| `Implement URL rewrites`       | Pretty URL routing      |
| `Add frontend CSS`             | UI styling              |

---

## 📃 License

This plugin is licensed under the **GNU General Public License v2.0 (GPL-2.0)**.

For more details, see the [GNU General Public License v2.0](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).

---

## Contact

For any queries, feel free to reach out.

