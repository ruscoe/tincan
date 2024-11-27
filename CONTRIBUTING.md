# Contributing to Tin Can Forum

Your contributions are welcome! You could consider:

* Reporting bugs
* Fixing bugs
* Creating new features
* Writing documentation
* Writing behat tests

## Coding standards

If you're contributing code, please run the following command from the root of the
project before submitting your pull request:

```shell
./vendor/bin/php-cs-fixer fix .
```

This helps keep all code in line with PHP coding standards.

## Author information

If creating a PHP file, please include a documentation block at the top of the file
that follows this format:

```php
/**
 * Describe the purpose of the file.
 *
 * @package TinCan
 * @author  $YOUR_NAME_OR_HANDLE <$YOUR_EMAIL> (email optional)
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   $CURRENT_TINCAN_FORUM_RELEASE
 */
```

Replace `$YOUR_NAME_OR_HANDLE` and `$YOUR_EMAIL` with your own information.

Replace `$CURRENT_TINCAN_FORUM_RELEASE` with the [latest release version](https://github.com/ruscoe/tincan/releases).

If modifying an exiting PHP file, please add yourself as an author by adding another
`@author` line:

```php
@author  $YOUR_NAME_OR_HANDLE <$YOUR_EMAIL> (email optional)
```

Thank you for your contributions!
