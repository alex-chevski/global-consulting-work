## About Project

Test Task for Глобал Консалтинг

---

# How to run project

Run in Linux, MacOS or Windows WSL terminal the docker development server and build project

    cd global-consulting-work && make init

Run queue job

    cd global-consulting-work && make api-start-queue

After all stop the project

    cd global-consulting-work && make down

## Address

The application can now be accessed at

    https://localhost:8081

The mailer so confirm email, notify can be accessed at

    http://localhost:8082

## Change Role, Name

You change role in your .env

    ROLE_USER=user
    ROLE_USER=admin

### Author

- **[alex-chevski](https://github.com/alex-chevski)**
