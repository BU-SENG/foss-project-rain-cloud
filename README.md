[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/tfYzIujW)

## UI redesign (added Nov 2025)

Small improvements were added to modernize the site UI and the admin panel:

- Shared header/footer: `includes/header.php` and `includes/footer.php` (centralized Bootstrap and site nav)
- New assets: `assets/css/styles.css` and `assets/js/admin.js` (custom styles + image preview)
- Updated pages: `index.php`, `news.php`, `admin/news_list.php`, `admin/news_create.php`, `admin/news_edit.php`, `admin/login.php`, `admin/dashboard.php` to use the shared layout and improved styling

How to preview locally:

1. Ensure PHP and PostgreSQL are configured as in `config.php`.
2. Start a PHP server in the project root (for quick preview):

```bash
php -S localhost:8000
```

3. Open `http://localhost:8000/` in your browser. Admin pages are under `/admin/`.

Next steps (optional): add a sidebar layout for admin, more client-side validation, and nicer category management UI.
