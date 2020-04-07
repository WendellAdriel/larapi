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

## Git Hooks

The project has some Git Hooks to update the API documentation using **Swagger**, to lint the PHP code using **PHP-CS-FIXER** and to run the tests using **PHPUnit**.

## API Documentation

You can check the API docs accessing: `HOST_URL/swagger`.

## App Architecture

Inside the `app` folder will live only other folders, no files are allowed in the root. The basic architecture is composed by four main folders and other **N** folders that we will call **Module Folders**, each one represent a module of the API, per example the **Auth Module** or **Mail Module**:

`Core`: The basic architecture files only are placed here:

- Exception Handler and Custom Exceptions;
- Kernel files;
- Providers;
- Middlewares;
- Base Controller;

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

- **Controllers:** Only handle requests and return responses;

- **Repositories:** Only place where database access should be done;

- **Services:** Handle business logic;

- **DON'T** forget to document all API endpoints with the OpenAPI annotations;

- Don't bypass the Git Hooks. The Git Hooks that are set up are going to lint the PHP code and also run the PHP tests before commit and push, but you can also run before the commit these commands: `composer lint` to run the PHP linter and `composer tests` to run the tests. If any of these show errors for you, please fix them before commiting and pushing your code;

- Don't pass the request object or an array for the service layer, create a DTO with the needed data. You can extend from the `LarAPI\Common\Support\DTOs\CommonDTO`. If it's a common DTO that will be used by multiple modules, create it inside `app/Common/Support/DTOs`. If it's something specific to a module create it on `app/MODULE/Support/DTOs`
