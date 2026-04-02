@echo off
"C:\Program Files\Git\cmd\git.exe" init
"C:\Program Files\Git\cmd\git.exe" remote add origin https://github.com/wakeyboy429/expanse-tracker-backend.git
"C:\Program Files\Git\cmd\git.exe" add .
"C:\Program Files\Git\cmd\git.exe" commit -m "Initial Laravel 12 project setup"
"C:\Program Files\Git\cmd\git.exe" branch -M main
"C:\Program Files\Git\cmd\git.exe" checkout -b dev
"C:\Program Files\Git\cmd\git.exe" checkout -b feature/project-setup
"C:\Program Files\Git\cmd\git.exe" branch
echo Git setup complete.
