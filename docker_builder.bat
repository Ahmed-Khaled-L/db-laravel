@echo off
setlocal

echo ====================================================
echo   Inventory System Launcher
echo ====================================================
echo.

REM 1. Check if Docker is running
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker is not running! 
    echo Please start Docker Desktop and try again.
    echo.
    pause
    exit /b
)

REM 2. Run Docker Compose
echo [INFO] Building and starting the application...
echo        This may take a few minutes on the first run.
echo.

docker-compose up -d --build

if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Something went wrong. 
    echo         Ensure you are in the correct folder and Docker is running.
    pause
    exit /b
)

echo.
echo ====================================================
echo   SUCCESS! 
echo ====================================================
echo.
echo The application is running at: http://localhost:8080
echo.
pause
