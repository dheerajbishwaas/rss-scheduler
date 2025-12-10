# Social Media Post Automation – CodeIgniter 4

This project is a custom-built **Social Media Automation & RSS News Import System** using **CodeIgniter 4**, PHP, MySQL, and SimplePie RSS parser.  
The system automatically imports news articles from RSS feeds, extracts images, checks character limits, assigns posts to social media platforms, and provides a dashboard to manage the workflow.

---

## 🚀 Features Implemented

### **1. RSS Fetch + Auto Import**
The system imports posts automatically from RSS feeds such as:

```
https://timesofindia.indiatimes.com/rssfeedstopstories.cms
```

We extract:

- `title`
- `content`
- `pub_date`
- `guid`
- `link`
- `image_url` *(from enclosure tag)*
- `char_count` *(title character count)*

### **Image Extraction Logic**
RSS like Times of India provides image through:

```xml
<enclosure type="image/jpeg" url="IMAGE_URL" />
```

We save it into database column `image_url`.

---

## **2. Character Counting System**
We calculate and store:

```
char_count = strlen($title)
```

This is used for platform validation (especially X/Twitter).

---

## **3. X (Twitter) Character Limit Validation**
A manual check is added to restrict assigning posts to X if the title exceeds **280 characters**:

```php
if (in_array(2, $selected) && $post['char_count'] > 280) {
    return redirect()->back()->with('error', 'Post exceeds 280 chars for X (Twitter)');
}
```

Where:
- `2` = Platform ID for X/Twitter

---

## **4. Platform Assignment (Modal Based UI)**

Admin can assign each post to multiple social platforms:

- Facebook
- X (Twitter)
- Instagram
- LinkedIn
- TikTok
- Threads
- Bluesky

Assignments are stored in `post_platform` (pivot table).

### AJAX-based modal:
```
openAssignModal(postId, postTitle)
```

Fetches assigned platforms dynamically and updates UI.

---

## **5. Dashboard with Platform Filter**
The dashboard includes a dropdown:

- **All Platforms**
- Specific platform (e.g., X, Facebook, Instagram etc.)

If a platform is selected:
✔ Only posts assigned to that platform are shown  
✔ Posts without assignment are excluded

### Fix for SQL error:
We added fallback when no post IDs exist:

```php
if (!empty($postIds)) {
    ->whereIn('post_platform.post_id', $postIds);
}
```

---

## **6. Platform Tags in Dashboard Table**
Each post shows assigned platforms as tags:

```
Facebook  |  X  | Instagram
```

We fetch all assigned platforms in controller and group them by post ID.

---

## **7. Priority Ordering & Sorting**
We implemented drag-and-drop sorting using SortableJS:

- Posts can be rearranged
- Priority auto-updates in DB via AJAX

---

## **8. Pagination (Custom Design)**
We replaced default CodeIgniter pagination with a custom cleaner layout:

- Previous
- 1 2 3 … N
- Next
- Last

Supports large number of pages without listing all page numbers.

---

## **9. Layout System (Header + Footer Included)**
We implemented CodeIgniter 4 layout structure using:

```
<?= $this->include('partials/header') ?>
<?= $this->renderSection('content') ?>
<?= $this->include('partials/footer') ?>
```

All pages now share same header/footer.

---

## **10. Technologies Used**

- **PHP 8+**
- **CodeIgniter 4**
- **MySQL**
- **SimplePie (RSS Parser)**
- **HTML/CSS/Bootstrap**
- **SortableJS**
- **AJAX / Fetch API**

---

## ⚙ Installation Steps

### 1. Clone or Download
```
composer create-project codeigniter4/appstarter
```

### 2. Install SimplePie
```
composer require simplepie/simplepie
```

### 3. Copy `.env`
```
cp env .env
```

Update:

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080'
```

### 4. Enable intl extension  
Required for CodeIgniter:

Edit:
```
php.ini
```

Enable:
```
extension=intl
```

### 5. Run Migrations
```
php spark migrate
```

### 6. Run Seeder
```
php spark db:seed PlatformSeeder

```

### 7. Run Server
```
php spark serve
```


## ✔ Database Tables

### posts
- id  
- title  
- content  
- char_count  
- image_url  
- pub_date  
- priority  
- created_at  

### platforms
- id  
- name  

### post_platform
- post_id  
- platform_id  

---

## 🧪 Validations Implemented

### Character count check (Twitter/X)
✔ Blocks assignment if `char_count > 280`.

### Duplicate GUID prevention
✔ No duplicate RSS items.

### Missing image fallback
✔ Safety check if enclosure missing.

---

## 📄 License
This project is custom and proprietary for client use.

---

# 🎉 Completed — Full Feature Documentation Ready
You can directly **copy-paste** this README.md into your GitHub or project folder.
