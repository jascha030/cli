# PHP CLI service

Interface and implementation of a CLI Service class for executing shell commands with php, using `symfony/process`
and `symfony/console`, **heavily** inspired
by [the CommandLine class](https://github.com/laravel/valet/blob/master/cli/Valet/CommandLine.php) from `laravel/valet`.

## Getting Started

### Requirements

* PHP `^8.0`
* Composer `*` (but preferred `2.2` or later)

### Installation

```shell
composer require jascha030/cli
```

## Usage

The package contents consist mostly of one simple service class implementing an interface. Next to that there are also
some derivatives of these classes and interfaces.

### ShellInterface

The main interface is the `Jascha030\CLI\Shell\ShellInterface`, which requires a class to implement four methods.

```php
interface ShellInterface
{
    /**
     * Run a shell command.
     */
    public function run(string $command, ?string $cwd = null, ?callable $onError = null): string;

    /**
     * Run a shell command with sudo user capabilities.
     */
    public function runAsUser(string $command, ?string $cwd = null, ?callable $onError = null): string;

    /**
     * Run a shell command without writing output to the STDOUT or php.
     */
    public function quietly(string $command, ?string $cwd = null, ?callable $onError = null): void;

    /**
     * Run a shell command with sudo user capabilities,  without writing output to the STDOUT or php.
     */
    public function quietlyAsUser(string $command, ?string $cwd = null, ?callable $onError = null): void;
}

```

### Default Implementation

This package also provides class as most-basic implementation of the `Jascha030\CLI\Shell\ShellInterface`,
The `Jascha030\CLI\Shell\Shell` class. This class implements all four methods.

All four methods use the `Shell::runCommand` method, which is also directly available.

```php
public function runCommand(string $command, ?string $cwd = null, ?callable $onError = null): string;
```

**Below is a simple example of basic usage of the class**

```php
<?php

use Jascha030\CLI\Shell\Shell;

/**
 * Include Composer's autoloader. 
 */
include __DIR__ . '/vendor/autoload.php';

$shell = new Shell();
// The console command's output is returned by the run method, as string.
echo $shell->run("`which php` -v");
```

**The above outputs the following**

```
PHP 8.0.16 (cli) (built: Feb 18 2022 09:31:10) ( NTS )
Copyright (c) The PHP Group
Zend Engine v4.0.16, Copyright (c) Zend Technologies
    with Xdebug v3.1.2, Copyright (c) 2002-2021, by Derick Rethans
    with Zend OPcache v8.0.16, Copyright (c), by Zend Technologies
```

> **Note:** this example output is specific to your environment, the specific version and extensions are, of-course dependent on your own environment.

**Execute a command silently**

```php
$shell->quietly($command);
```

**Executing commands as sudo user**

```php
// This acts as if you run your command prepended by sudo.
$shell->runAsUser($command);

// Which also can be done silently.
$shell->quetlyAsUser($command);
```

All the methods, demonstrated above take the optional `$cwd` (string) and `$onError` (callable) parameters.

The `$cwd` command, allows you to execute a command from another directory.

```php
// Runs the command two directories above the current script's directory.
$shell->run($command, dirname(__FILE__, 3));
```

The `$onError` is a callback that can be passed to handle command failure.

Both these parameters are directly passed to the `Process::fromShellCommandline()` method, for more info, refer
to [the documentation of symfony's Process component](https://symfony.com/components/Process).

### Shell program binaries

If you are writing a CLI based around a commandline program which comes as executable binary, you can implement
the `Jascha030\CLI\Shell\Binary\BinaryInterface`. These are just extensions on the `ShellInterface`, requiring you to
provide a name, a version, and a path to the binary.

```php
interface BinaryInterface extends ShellInterface
{
    public function getName(): string;

    public function getPath(): string;

    public function getVersion(): ?string;
}
```

To kickstart implementation, various Traits are available, together with an abstract class:

* `Jascha030\CLI\Shell\Binary\BinaryAbstract` Simple implementation of the three `BinaryInterface` methods, which values
  are provided through the constructor.
* `Jascha030\CLI\Shell\Binary\Traits\ShellDecoratorTrait` The easiest way to implement the rest of the methods required
  by the `ShellInterface`, by delegating the commands to another instance of the `ShellInterface`, provided through the
  abstract `ShellDecoratorTrait::getShell()` method.
* `Jascha030\CLI\Shell\Binary\Traits\SelfResolvingPathTrait`, For when the path to a binary can be provided by using
  the `which`, command (e.g. `which php`).
* `Jascha030\CLI\Shell\Binary\Traits\SelfResolvingVersionTrait`, For when the version of a binary can be provided by
  using a command.
    * Defaults to `-v`, but can be overridden, by overloading the `SelfResolvingVersionTrait::getVersionCommand()`
      method.
    * By default, it will match the output of the provided version command to a regex
      pattern (`/\\d{1,2}\\.\\d{1,2}\\.\\d{1,2}/`), this can be overridden by overloading
      the `SelfResolvingVersionTrait::getVersionRegex()` method. When this method returns `null` it will skip the regex
      matching, and use the command's full output.

### Helpers

A set of helper functions is included in `includes/helper.php` under the `Jascha030\Cli\Helpers` namespace.

**Get user, or sudo user when available.**

`\Jascha030\CLI\Helpers\user(): ?string;`

**Output a message to the console.**

`\Jascha030\CLI\Helpers\output(string $message, ?OutputInterface $output = null): void;`

**Output an error to the console.**

`\Jascha030\CLI\Helpers\error(string $message, ?OutputInterface $output = null): void;`

**Output an info message to the console.**

`\Jascha030\CLI\Helpers\info(string $message, ?OutputInterface $output = null): void;`

**Output a multiline message to the console.**

`\Jascha030\CLI\Helpers\multilineOutput(array $message, ?OutputInterface $output = null): void;`

**Output a multiline error to the console.**

`\Jascha030\CLI\Helpers\multilineError(array $message, ?OutputInterface $output = null): void;`

**Output a multiline info message to the console.**

`\Jascha030\CLI\Helpers\multilineInfo(array $message, ?OutputInterface $output = null): void;`

## Development

Clone this repo, and run `composer install` inside the repo.

### Code-style

A code-style is provided in the form of a `php-cs-fixer` configuration in `.php-cs-fixer.dist.php`. For easy execution,
use the provided Composer [script command](https://getcomposer.org/doc/articles/scripts.md).

```shell
composer run format
```

If you have php-cs-fixer installed globally, pass it to the `--config` argument of the `fix` command.

```shell
php-cs-fixer fix --config=.php-cs-fixer.dist.php
```

### Unit-testing

A configuration for `phpunit` is provided in `phpunit.xml`.

For easy execution, use the provided Composer [script command](https://getcomposer.org/doc/articles/scripts.md).

```shell
composer run phpunit
```

If you have phpunit installed globally, and want to use that, pass the config in the `--config` argument.

```shell
phpunit --config phpunit.xml
```

## Inspiration and Credits

This package is based around the `ShellInterface`, and specifically it's implementation, the `Shell` class. Even though
this package adds some more features, the class in itself is a slightly edited (for personal preference) version
of [the CommandLine class](https://github.com/laravel/valet/blob/master/cli/Valet/CommandLine.php) of
the `laravel/valet` package.

Next to that, it obviously makes heavy use of various [Symfony components](https://symfony.com/components), which makes
your life as a php-developer a lot easier in many cases. For one, by not having to re-invent the wheel. And also, the
usage of Symfony components, in almost every case guarantees, at least some level of interoperability with most other
php frameworks, libraries and of-course [composer](https://getcomposer.org/).

## License

This composer package is an open-sourced software licensed under
the [MIT License](https://github.com/jascha030/cli/blob/master/LICENSE.md)
