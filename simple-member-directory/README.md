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

## 🗂️ Plugin Structure

