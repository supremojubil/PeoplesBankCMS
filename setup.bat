@echo off
echo Setting up People's Bank CMS Database...
echo.

REM Define MySQL path (adjust if needed)
set MYSQL_PATH="C:\\Program Files\\MySQL\MySQL Server 5.7\\bin\\mysql.exe"

REM Execute SQL file
%MYSQL_PATH% -u adminserver -P 3309 -padmin123!@# < database_setup.sql

if %errorlevel% equ 0 (
    echo.
    echo Database setup completed successfully!
    echo Default admin login: admin / admin123
) else (
    echo.
    echo Database setup failed. Please check your MySQL credentials.
)

pause