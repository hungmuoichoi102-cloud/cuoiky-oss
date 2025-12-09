@echo off
REM Setup script for Laravel backend

echo Setting up Laravel Backend...
echo.

REM Check if composer exists
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Composer is not installed. Please install Composer first.
    exit /b 1
)

REM Install dependencies
echo Installing dependencies...
call composer install

if %ERRORLEVEL% NEQ 0 (
    echo Error installing dependencies
    exit /b 1
)

echo.
echo Copying .env file...
if not exist .env (
    copy .env.example .env
    echo .env file created
) else (
    echo .env file already exists
)

echo.
echo Creating database.sqlite...
if not exist database.sqlite (
    type nul > database.sqlite
    echo database.sqlite created
) else (
    echo database.sqlite already exists
)

echo.
echo Generating app key...
php artisan key:generate

echo.
echo Running migrations...
php artisan migrate

echo.
echo Setup completed successfully!
echo.
echo To start the development server, run:
echo   php artisan serve
echo.
