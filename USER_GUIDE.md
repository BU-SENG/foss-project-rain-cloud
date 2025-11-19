# User Guide

Complete guide for using the School News Board System - for both administrators and students.

## 📖 Table of Contents

- [For Students (Public Users)](#for-students-public-users)
- [For Administrators](#for-administrators)
- [Features Overview](#features-overview)
- [Tips & Best Practices](#tips--best-practices)
- [FAQ](#faq)

---

## For Students (Public Users)

### Accessing the News Board

1. Open your web browser
2. Navigate to the school news board URL (e.g., `http://school-news.example.com`)
3. You'll see the homepage with all published news

### Browsing News

#### View All News
- The homepage displays all published news articles
- News is sorted by date (newest first)
- Each card shows:
  - Title
  - Category badge
  - Short excerpt
  - Publication date
  - "NEW" badge (if posted within 24 hours)

#### Filter by Category
1. Click on any category pill at the top:
   - 🌐 **All News** - View everything
   - 📚 **Academic** - School work, exams, results
   - ⚽ **Sports** - Games, tournaments, achievements
   - 🎉 **Events** - Upcoming activities, celebrations
   - ℹ️ **General** - Announcements, notices

2. Or use the dropdown menu on the right

### Searching for News

1. Use the search bar at the top
2. Enter keywords (e.g., "exam", "basketball", "cultural day")
3. Click **Search** button
4. Results show matching articles

**Search Tips:**
- Search works on both titles and content
- Try different keywords if you don't find what you need
- Use specific terms for better results

### Reading Full Articles

1. Click **Read More** on any news card
2. You'll see:
   - Full article with images
   - Publication date and time
   - Author information
   - Category

3. **Share the article:**
   - Click Facebook icon to share on Facebook
   - Click Twitter icon to share on Twitter
   - Click WhatsApp icon to share on WhatsApp

4. **View related articles:**
   - Scroll to bottom to see similar news in the same category

### Mobile Usage

The site works perfectly on mobile devices:
- Responsive design adapts to screen size
- Touch-friendly buttons
- Easy navigation
- Fast loading

---

## For Administrators

### First Time Setup

1. **Login:**
   - Go to `/admin/login.php`
   - Use default credentials (change immediately!)
     - Username: `admin`
     - Password: `admin123`

2. **Change Password:**
   - Go to profile settings (coming soon)
   - Or use SQL to update:
     ```sql
     UPDATE admins SET password = '$2y$10$...' WHERE username = 'admin';
     ```

3. **Create Admin Account:**
   - Go to `/admin/register.php`
   - Fill in your details
   - Click **Create Account**

### Dashboard Overview

After login, you'll see the admin dashboard with:

#### Statistics Cards
- **Total News** - All published articles
- **Posted Today** - Articles published today
- **Categories** - Number of news categories

#### Recent News
- Quick view of latest 5 articles
- Shows title, category, and date

#### Activity Log
- Track all admin actions
- Shows who did what and when
- Useful for accountability

### Creating News Articles

1. **Navigate:**
   - Click **Create News** in navigation
   - Or go to `/admin/news_create.php`

2. **Fill in Details:**
   - **Title:** Clear, descriptive headline (max 200 chars)
   - **Category:** Select from dropdown
     - Academic
     - Sports
     - Events
     - General
   - **Content:** Full article text (use line breaks for paragraphs)
   - **Image:** Optional, max 5MB (JPG, PNG, GIF)

3. **Upload Image:**
   - Click **Choose File**
   - Select image from computer
   - Preview will show after selection
   - **Supported formats:** JPG, PNG, GIF
   - **Max size:** 5MB

4. **Publish:**
   - Click **Publish News**
   - Article goes live immediately
   - Students can view it on homepage

**Best Practices:**
- Write clear, concise titles
- Break content into paragraphs
- Use proper grammar and spelling
- Add relevant images when possible
- Choose correct category
- Preview before publishing

### Managing Existing News

#### View All News
1. Click **Manage News** in navigation
2. See table with all articles:
   - ID
   - Title
   - Category
   - Publication date
   - Action buttons

#### Edit News
1. Click **Edit** button on any article
2. Modify any field:
   - Title
   - Content
   - Category
   - Image (upload new to replace)
3. Click **Update News**
4. Changes reflect immediately

**What You Can Edit:**
- ✅ Title
- ✅ Content
- ✅ Category
- ✅ Image
- ❌ Publication date (auto-updated)
- ❌ Author (fixed to creator)

#### Delete News
1. Click **Delete** button
2. Confirm deletion in popup
3. Article removed permanently
4. **Warning:** This cannot be undone!

#### View Published Article
1. Click **View** button
2. Opens article in new tab
3. Shows student view
4. Good for final check

### Activity Monitoring

The system logs all admin actions:
- Login/Logout
- Create News
- Edit News
- Delete News

**View Activity Log:**
1. Go to Dashboard
2. Scroll to **Activity Log** section
3. See recent actions with:
   - Admin username
   - Action performed
   - Date and time

**Uses:**
- Track team member contributions
- Audit changes
- Security monitoring
- Performance review

### Tips for Effective Management

#### Content Guidelines
- **Headlines:** 5-12 words, action-oriented
- **Length:** 150-500 words for most news
- **Tone:** Professional but friendly
- **Images:** High quality, relevant, properly sized

#### Category Usage
- **Academic:** Exams, results, curriculum changes
- **Sports:** Matches, tournaments, team selections
- **Events:** Upcoming activities, celebrations
- **General:** Everything else

#### Publishing Schedule
- Post important news ASAP
- Regular updates keep students engaged
- Check for errors before publishing
- Update old news if info changes

#### Image Best Practices
- Use landscape orientation (16:9 or 4:3)
- Minimum 800px wide
- Compress before upload
- Ensure images are school-appropriate
- Add descriptive alt text mentally

---

## Features Overview

### ✨ Key Features

#### For Students
- 📰 Browse all news easily
- 🔍 Search by keywords
- 🏷️ Filter by category
- 📱 Mobile-friendly interface
- 🔔 "NEW" badges for recent posts
- 📤 Social sharing
- 🔗 Related articles

#### For Admins
- ✏️ Create/Edit/Delete news
- 🖼️ Upload images
- 📊 View statistics
- 📝 Activity logging
- 👥 Multi-admin support
- 🔐 Secure authentication

### Security Features
- 🔒 Password hashing
- 🛡️ SQL injection prevention
- 🚫 XSS protection
- 📁 File upload validation
- 👤 Admin-only access to management

---

## Tips & Best Practices

### For Students
1. **Check daily** for new announcements
2. **Use search** to find specific information
3. **Share** important news with classmates
4. **Read full articles** before sharing

### For Administrators
1. **Proofread** before publishing
2. **Add images** to make news engaging
3. **Use correct categories** for easy filtering
4. **Delete outdated** news regularly
5. **Monitor activity log** for team coordination
6. **Backup regularly** (ask IT admin)
7. **Test on mobile** before publishing

### Content Creation Tips
- **Write for your audience** (students)
- **Use simple language**
- **Include important details** (date, time, location)
- **Add calls to action** when needed
- **Update old news** instead of creating duplicates
- **Use images** to increase engagement

---

## FAQ

### General Questions

**Q: Can students post news?**
A: No, only admins can create and manage news. Students can only read and share.

**Q: How recent is "NEW"?**
A: News posted within the last 24 hours shows the NEW badge.

**Q: Can I edit news after publishing?**
A: Yes! Admins can edit any article anytime.

**Q: What happens to old news?**
A: It stays published until manually deleted. Consider archiving old news periodically.

### Technical Questions

**Q: What image formats are supported?**
A: JPG, PNG, and GIF. Max size 5MB.

**Q: Can I upload videos?**
A: Not directly. Use YouTube/Vimeo and add link in content.

**Q: How many admins can I have?**
A: Unlimited! Create more via the registration page.

**Q: Can I recover deleted news?**
A: No, deletions are permanent. Be careful!

**Q: Does search work on old articles?**
A: Yes, search includes all published articles.

### Access & Permissions

**Q: I forgot my admin password. What do I do?**
A: Contact your IT administrator or another admin to reset via database.

**Q: Can I restrict certain categories to specific admins?**
A: Not currently. All admins have full access.

**Q: How do I logout?**
A: Click the **Logout** button in the admin navigation.

---

## Need More Help?

- 📧 **Email:** support@school.example.com
- 💬 **Report Issues:** GitHub Issues page
- 📚 **Developer Docs:** See [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- 🔧 **Installation Help:** See [INSTALLATION.md](INSTALLATION.md)

---

**Happy News Managing! 🎉**