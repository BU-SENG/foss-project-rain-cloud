# Contributing to School News Board System

Thank you for considering contributing to the School News Board System! This document provides guidelines for contributing to this FOSS project.

## 🤝 How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with:
- Clear description of the bug
- Steps to reproduce
- Expected vs actual behavior
- Screenshots if applicable
- Your environment (OS, PHP version, PostgreSQL version)

### Suggesting Features

We welcome feature suggestions! Please:
- Check existing issues first to avoid duplicates
- Clearly describe the feature and its use case
- Explain how it benefits users
- Provide mockups or examples if possible

### Code Contributions

1. **Fork the Repository**
   ```bash
   git clone https://github.com/your-username/school-news-board.git
   cd school-news-board
   ```

2. **Create a Branch**
   ```bash
   git checkout -b feature/your-feature-name
   # or
   git checkout -b fix/bug-description
   ```

3. **Make Your Changes**
   - Write clean, readable code
   - Follow existing code style
   - Comment complex logic
   - Test thoroughly

4. **Commit Your Changes**
   ```bash
   git add .
   git commit -m "feat: add user notification system"
   ```

   **Commit Message Format:**
   - `feat:` New feature
   - `fix:` Bug fix
   - `docs:` Documentation changes
   - `style:` Code style/formatting
   - `refactor:` Code refactoring
   - `test:` Adding tests
   - `chore:` Maintenance tasks

5. **Push and Create Pull Request**
   ```bash
   git push origin feature/your-feature-name
   ```
   Then create a PR on GitHub with a clear description.

## 📋 Code Guidelines

### PHP Code Style
- Use 4 spaces for indentation
- Follow PSR-12 coding standards
- Use meaningful variable names
- Add comments for complex logic
- Sanitize all user inputs
- Use prepared statements for database queries

### Database
- All queries must use prepared statements
- Never use string concatenation for SQL
- Always validate foreign key relationships
- Add indexes for frequently queried columns

### Security
- **Never** store passwords in plain text
- Always use `password_hash()` and `password_verify()`
- Sanitize all user inputs with `htmlspecialchars()`
- Use HTTPS in production
- Validate file uploads (type, size)

### Frontend
- Keep HTML semantic and accessible
- Use Bootstrap classes consistently
- Ensure mobile responsiveness
- Add alt text to images
- Test on multiple browsers

## ✅ Pull Request Checklist

Before submitting a PR, ensure:
- [ ] Code follows project style guidelines
- [ ] All new features are tested
- [ ] No console errors or warnings
- [ ] Documentation is updated if needed
- [ ] Commit messages are clear and descriptive
- [ ] Code is commented where necessary
- [ ] No sensitive data (passwords, API keys) in code

## 🧪 Testing

Test your changes:
1. Create test data in database
2. Test all CRUD operations
3. Test edge cases (empty inputs, long text, special characters)
4. Test on mobile devices
5. Check browser console for errors
6. Verify security (SQL injection, XSS prevention)

## 🎯 Priority Areas

We especially welcome contributions in:
- **Security improvements**
- **Performance optimization**
- **Accessibility features**
- **Multi-language support**
- **Advanced search functionality**
- **Email notifications**
- **User comments system**
- **Dark mode**

## 📞 Getting Help

- **Questions?** Open a discussion on GitHub
- **Stuck?** Check existing issues or create a new one
- **Need clarification?** Contact the maintainers

## 🌟 Recognition

All contributors will be:
- Listed in our README
- Acknowledged in release notes
- Given credit in commit history

## 📜 Code of Conduct

Please read and follow our [Code of Conduct](CODE_OF_CONDUCT.md) to keep our community welcoming and inclusive.

## 📄 License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

**Thank you for making School News Board better! 🎉**