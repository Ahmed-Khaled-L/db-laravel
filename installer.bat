@echo off
setlocal

REM ====================================================
REM CONFIGURATION
REM Change this to your actual GitHub repository URL
set REPO_USER=ahmed-khaled-l
set REPO_NAME=db-laravel
set BRANCH=main
REM ====================================================

echo.
echo ====================================================
echo   Auto-Installer for %REPO_NAME%
echo ====================================================
echo.

REM 1. Check if Docker is running
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker is not running!
    echo Please start Docker Desktop and try again.
    pause
    exit /b
)

echo [1/4] Downloading application from GitHub...
set ZIP_URL=https://github.com/%REPO_USER%/%REPO_NAME%/archive/refs/heads/%BRANCH%.zip
set ZIP_FILE=%REPO_NAME%.zip

powershell -Command "Invoke-WebRequest -Uri '%ZIP_URL%' -OutFile '%ZIP_FILE%'"

if not exist %ZIP_FILE% (
    echo [ERROR] Failed to download file. Check your internet connection.
    pause
    exit /b
)

echo [2/4] Unzipping files...
powershell -Command "Expand-Archive -Path '%ZIP_FILE%' -DestinationPath . -Force"

REM The unzip usually creates a folder like repo-main
cd %REPO_NAME%-%BRANCH%

echo [3/4] Starting Application Container...
echo This may take a few minutes the first time.
docker-compose up -d --build

echo.
echo ====================================================
echo   SUCCESS! The app is running.
echo ====================================================
echo.
echo Please open your browser to: http://localhost:8080
echo.
pause
