# Contributing

We love your input! We want to make contributing to this project as easy and transparent as possible, whether it's:

- Reporting a bug
- Discussing the current state of the code
- Submitting a fix
- Proposing new features
- Becoming a maintainer

## Development Process

We use GitHub to host code, to track issues and feature requests, as well as accept pull requests.

## Pull Requests

Pull requests are the best way to propose changes to the codebase. We actively welcome your pull requests:

1. Fork the repo and create your branch from `main`.
2. If you've added code that should be tested, add tests.
3. If you've changed APIs, update the documentation.
4. Ensure the test suite passes.
5. Make sure your code lints.
6. Issue that pull request!

## Development Setup

1. Clone your fork of the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Run tests to ensure everything works:
   ```bash
   composer test
   ```

## Testing

We use PHPUnit for testing. Please ensure all tests pass before submitting a PR:

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Run specific test file
vendor/bin/phpunit tests/Unit/PwaServiceTest.php

# Run specific test method
vendor/bin/phpunit --filter testMethodName
```

### Writing Tests

- Write tests for all new functionality
- Follow existing test patterns and naming conventions
- Use descriptive test method names
- Include both positive and negative test cases
- Test edge cases and error conditions

### Test Structure

```php
/** @test */
public function it_does_something_specific()
{
    // Arrange
    $input = 'test data';
    
    // Act
    $result = $this->service->doSomething($input);
    
    // Assert
    $this->assertEquals('expected', $result);
}
```

## Code Style

We use Laravel Pint for code formatting:

```bash
# Format code
composer format

# Check code style
vendor/bin/pint --test
```

### Style Guidelines

- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Add docblocks for public methods
- Keep methods focused and small
- Use type hints where possible

## Documentation

- Update README.md if you change functionality
- Add or update docblocks for new methods
- Update configuration documentation for new options
- Include examples in documentation
- Keep documentation clear and concise

## Commit Messages

Write clear, descriptive commit messages:

```
Add icon generation validation

- Validate source image exists before processing
- Check output directory permissions
- Add proper error messages for common issues
- Include tests for validation logic
```

### Commit Message Format

- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit first line to 72 characters or less
- Reference issues and pull requests when applicable

## Issue Reporting

We use GitHub issues to track public bugs. Report a bug by [opening a new issue](https://github.com/alareqi/filament-pwa/issues).

### Bug Reports

Great bug reports tend to have:

- A quick summary and/or background
- Steps to reproduce
  - Be specific!
  - Give sample code if you can
- What you expected would happen
- What actually happens
- Notes (possibly including why you think this might be happening, or stuff you tried that didn't work)

### Feature Requests

We welcome feature requests! Please:

- Explain the problem you're trying to solve
- Describe the solution you'd like
- Consider alternative solutions
- Provide examples or mockups if applicable

## Development Guidelines

### Adding New Features

1. **Discuss First**: Open an issue to discuss major changes before implementing
2. **Start Small**: Break large features into smaller, manageable pieces
3. **Test Thoroughly**: Include comprehensive tests
4. **Document**: Update documentation and examples
5. **Backward Compatibility**: Maintain backward compatibility when possible

### Code Organization

- Keep related functionality together
- Use appropriate namespaces
- Follow existing patterns and conventions
- Separate concerns appropriately
- Use dependency injection where appropriate

### Configuration

When adding new configuration options:

- Add to the config file with sensible defaults
- Document the option thoroughly
- Include validation where appropriate
- Add environment variable support
- Update documentation

### Views and Assets

When modifying views or assets:

- Maintain backward compatibility
- Consider customization needs
- Test across different browsers
- Optimize for performance
- Follow accessibility guidelines

## Release Process

1. Update version numbers
2. Update CHANGELOG.md
3. Ensure all tests pass
4. Create release notes
5. Tag the release
6. Publish to Packagist

## Community Guidelines

### Be Respectful

- Use welcoming and inclusive language
- Be respectful of differing viewpoints and experiences
- Gracefully accept constructive criticism
- Focus on what is best for the community
- Show empathy towards other community members

### Be Collaborative

- Help others learn and grow
- Share knowledge and resources
- Provide constructive feedback
- Be patient with newcomers
- Celebrate others' contributions

## Getting Help

- Check existing documentation first
- Search existing issues before creating new ones
- Use clear, descriptive titles for issues
- Provide context and examples
- Be patient and respectful

## Recognition

Contributors will be recognized in:

- README.md contributors section
- CHANGELOG.md for significant contributions
- Release notes for major features
- GitHub contributors page

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

## Questions?

Don't hesitate to ask questions! You can:

- Open an issue for discussion
- Start a discussion in the repository
- Reach out to maintainers directly

Thank you for contributing to Filament PWA Plugin! 🚀
