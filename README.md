# LARAPI

Opinionated API Skeleton created with Laravel

## Configuring the project

```
composer create-project --prefer-dist wendelladriel/larapi my-app && cd my-app && sh ./tools/configure.sh
```

This will:
- Create the project
- Install the dependencies
- Copy the `.env.example` to `.env` in the project root;
- Generate the `APP_KEY`
- Generate the `JWT_SECRET`
- Configures the **Git Hooks** for the project;

Update your `.env` file:

Configure your local Virtual Host. After that visit the host URL and you will see a JSON response like below:

```json
{
    "application": "LarAPI",
    "environment": "local",
    "status": 200
}
```

## App Architecture

Inside the `app` folder will live only other folders, no files are allowed in the root. The basic architecture is composed by four main folders and other **N** folders that we will call **Module Folders**, each one represent a module of the API, per example the **Auth Module** or **Mail Module**:

`Core`: The basic architecture files only are placed here:

- Exception Handler and Custom Exceptions;
- Kernel files;
- Providers;
- Middlewares;
- Base files like BaseController and BaseRepository;

`Models`: All the application Model classes are placed here, but no classes are allowed in the root of the directory. All models should be put into a namespace depending on its purpose, besides that there are two other folders:

- Traits for model specific Traits;
- Relations for custom relationship classes;

`Repositories`: All the application Repository classes are placed here, but no classes are allowed in the root of the directory. All repositories should be put into a namespace depending on its purpose, besides that, there is a `Traits` folder for repository specific Traits. If you create a Repository for a specific Model class, use the same namespace you gave to the Model class, per example the `Models\Auth\User` repository **MUST** be `Repositories\Auth\UserRepository`. **ALWAYS** put the suffix `Repository`. All repositories **MUST** extend `LarAPI\Core\Repositories\BaseRepository`

`Common`: This is a **Module Folder** (all module folders **MUST HAVE** the same folder and file structure) created to have **ONLY** common and general purpose classes:

- **Commands:** Command files for this module;
- **Controllers:** Controller files for this module. **DON'T** use the name in the plural form and **ALWAYS** put the suffix `Controller`. Example: `UserController`, `ProductController`. All controllers **MUST** extend `LarAPI\Core\Http\BaseController`;
- **Events:** Event files for this module. Try to use verbs for the name and **ALWAYS** put the suffix `Event`. Example: `ActivateUserEvent`;
- **Listeners:** Listener files for this module. Try to use nouns for the name and **ALWAYS** put the suffix `Listener`. Example: for the `ActivateUserEvent` use `UserActivatedListener`;
- **Requests:** Custom request files for this module.Try to use verbs for the name and **ALWAYS** put the suffix `Request`. Example: `ActivateUserRequest`;
- **Responses:** Custom response files for this module. Try to use nouns for the name and **ALWAYS** put the suffix `Response`. Example: `UserActivatedResponse`;
- **Routing:** Route files for this module. Each file is a version of the API. Follow the format: `v1.php`, `v2.php`, etc;
- **Services:** Service files for this module. **ALWAYS** put the suffix `Service`;
- **Support:** Helper files for this module;
- **Traits:** Trait files for this module;

When you create a new **Module** in the app folder, add the module name to the `config/modules.php` file. This configuration file is also used to enable and disable modules in your API. The modules listed there will be the enabled ones.

## Development Standards

**Controllers:** Only handle requests and return responses;

**Repositories:** Only place where database access should be done;

**Services:** Handle business logic;

**DON'T** forget to document all API endpoints with the OpenAPI annotations;

Don't bypass the Git Hooks. The Git Hooks that are set up are going to lint the PHP code and also run the PHP tests before commit and push, but you can also run before the commit these commands: `composer lint` to run the PHP linter and `composer tests` to run the tests. If any of these show errors for you, please fix them before committing and pushing your code;

Don't pass the request object or an array for the service layer, create a DTO with the needed data. You can extend from the `LarAPI\Common\Support\DTOs\CommonDTO`. If it's a common DTO that will be used by multiple modules, create it inside `app/Common/Support/DTOs`. If it's something specific to a module create it on `app/MODULE/Support/DTOs`

All returns from the API must be in snake case. Avoid using `userId`, use `user_id`;

Always that you use the `new` keyword, use `()` with it. Example: **DON'T** write `new User;`, write `new User();`;

**DON'T** use any Facade without "importing" it first. Example: **DON'T** write `\Auth`. Import it from `use Illuminate\Support\Facades\Auth` and write `Auth` instead;

**DON'T** use hardcoded table names, when you need a table name use the method `getModelTable` that can be called from any repository. Example: `$this->getModelTable(new Model())` or `$this->getModelTable(new Model(), 'alias')`;

All custom exceptions **MUST** be placed on `app/Core/Exceptions/` and they **MUST** implement `LarAPI\Core\Exceptions\LarAPIExceptionInterface`;

Always that's possible use the method `applySort` from any `DTO` that extends from `LarAPI\Common\Support\DTOs\CommonTableDTO` to sort your collections. You can sort nested properties with dot notation, per example `'user.email'`;

Always that's possible use the method `applyFilter` form any `DTO` that extends from `LarAPI\Common\Support\DTOs\CommonTableDTO` to filter your collections;

### Working with Lazy Collections

Lazy Collections give us the ability to work with large datasets without having issues with memory, so everywhere that we deal with huge datasets and that's possible, use Lazy Collections instead of normal ones;

You can check the docs for Lazy Collections **[here](https://laravel.com/docs/7.x/collections#lazy-collections)**;

It's not possible to cache a Lazy Collection, so we have two choices that we can combine or choose one of them when working with Lazy Collections:

- Internal Lazy Collection cache calling the `remember()` method on a Lazy Collection;

- Transforming the Lazy Collection into a normal collection.

### Creating Scheduled Commands

All commands that will be scheduled on `Console/Kernel` **MUST** extends from `LarAPI\Core\Console\ApiTaskCommand`;

All commands **MUST** call this piece of code in the beginning of the `handle` function:

```php
if (!$this->initializeTask()) {
    return;
}
```

All the code for the command **MUST** be inside a `try/catch` block following this example:

```php
try {
    // YOUR COMMAND LOGIC HERE
    $this->finishTask();
} catch (Throwable $exception) {
    $this->finishTask($exception);
}
```

When scheduling the commands, all commands **MUST** be scheduled with the `->runInBackground();` method;

## Features

### API Documentation

**LarAPI** provides API documentation for your API using **Swagger**. You can check the API docs accessing: `HOST_URL/swagger`.

### Auth

**LarAPI** provides you **JWT Authentication**, **User Settings** and **User Roles** out-of-the-box. You can check the default authentication endpoints on the **Swagger Docs of the API**.

### Controllers

The `LarAPI\Core\Http\BaseController` class provides three helper methods for returning data from the API:

- `apiSimpleSuccessResponse(int $code = Response::HTTP_CREATED): JsonResponse`

- `apiSuccessResponse($data, int $code = Response::HTTP_OK): JsonResponse`

- `apiErrorResponse(string $message, Throwable $exception = null, $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse`

### DTOs

**LarAPI** provides you two generic DTOs classes that can be useful for requests of datatables.Check out `LarAPI\Common\Support\DTOs\CommonTableDTO` and `LarAPI\Common\Support\DTOs\DateRangeDTO`

### Exceptions

**LarAPI** provides a configured Exception Handler that will return only JSON responses and also a base interface for you to identify your custom exceptions that will be handled by the custom exception handler provided. It also provides a custom exception example.

### Formatter

The `LarAPI\Common\Support\Formatter` class provides a lot of functions to format data and helper constants to use within your code.

### Git Hooks

The project has some Git Hooks to update the API documentation using **Swagger**, to lint the PHP code using **PHP-CS-FIXER** and to run the tests using **PHPUnit**.

### Health-Check Route

**LarAPI** provides a health-check route if you need to check if your API is up.

### Module Management

You can use the following command to create a new module in your API:

```
php artisan make:module test
```

Using the `config/modules.php` file you can enable and disable modules of your API.

### Requests

**LarAPI** provides you two generic Request classes that can be useful for requests of datatables. Check out `LarAPI\Common\Requests\CommonTableRequest` and `LarAPI\Common\Requests\DateRangeRequest`

### Repositories

The `LarAPI\Core\Repositories\BaseRepository` class offers a lot of generic purposes util functions so you don't need to waste time creating functions for simple queries:

- `newQuery(array $columns = self::ALL_COLUMNS): Builder`

- `getBy(string $attribute, $value, string $compareType = self::COMPARE_DEFAULT, array $columns = self::ALL_COLUMNS): Builder`

- `getByParams(array $params, array $columns = self::ALL_COLUMNS): Builder`

- `getByIn(string $attribute, array $values, array $columns = self::ALL_COLUMNS): Builder`

- `getByParamsIn(array $params, array $columns = self::ALL_COLUMNS): Builder`

- `getTable(string $alias = null): string (PROTECTED)`

- `getModelTable(Model $model, string $alias = null): string (PROTECTED)`

### Scheduled Commands Manager

**LarAPI** provides you a custom manager for scheduled commands that will help you to prevent overlapping runs and also will make it easier to debug your scheduled commands. Check out the classes: `LarAPI\Core\Console\ApiTaskCommand`, `LarAPI\Models\Common\ApiTask` and `LarAPI\Repositories\Common\ApiTaskRepository`.

### Slack Integration

The `LarAPI\Common\Services\SlackClient` class provides you a simple way to send notifications to **Slack**. To enable this integration you need to provide the `SLACK_NOTIFICATIONS_WEBHOOK` ENV variable.
