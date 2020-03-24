# LARAPI

Opinated API Skeleton created with Laravel

## Configuring the project

Clone this repository and run the configure script:

```
git clone git@github.com:WendellAdriel/larapi.git && cd larapi && sh ./tools/configure.sh
```

The script will:
- Install the dependencies
- Copy the `.env.example` to `.env` in the project root;
- Generate the `APP_KEY`
- Generate the `JWT_SECRET`
- Install the `php-cs-fixer` globally and add the global Composer binaries directory in your `PATH`;
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

`Repositories`: All the application Repository classes are placed here, but no classes are allowed in the root of the directory. All repositories should be put into a namespace depending on its purpose, besides that, there is a `Traits` folder for repository specific Traits. If you create a Repository for a specific Model class, use the same namespace you gave to the Model class, per example the `Models\Auth\User` repository **MUST** be `Repositories\Auth\UserRepository`. **ALWAYS** put the suffix `Repository`.

`Common`: This is a **Module Folder** (all module folders **MUST HAVE** the same folder and file structure) created to have **ONLY** common and general purpose classes:

- **Commands:** Command files for this module;
- **Controllers:** Controller files for this module. **DON'T** use the name in the plural form and **ALWAYS** put the suffix `Controller`. Example: `UserController`, `ProductController`;
- **Events:** Event files for this module. Try to use verbs for the name and **ALWAYS** put the suffix `Event`. Example: `ActivateUserEvent`;
- **Listeners:** Listener files for this module. Try to use nouns for the name and **ALWAYS** put the suffix `Listener`. Example: for the `ActivateUserEvent` use `UserActivatedListener`;
- **Requests:** Custom request files for this module.Try to use verbs for the name and **ALWAYS** put the suffix `Request`. Example: `ActivateUserRequest`;
- **Responses:** Custom response files for this module. Try to use nouns for the name and **ALWAYS** put the suffix `Response`. Example: `UserActivatedResponse`;
- **Services:** Service files for this module. **ALWAYS** put the suffix `Service`;
- **Support:** Helper files for this module;
- **Traits:** Trait files for this module;

## Development Standards

- **Controllers:** Only handle requests and return responses;
- **Repositories:** Only place where database access should be done;
- **Services:** Handle business logic;
