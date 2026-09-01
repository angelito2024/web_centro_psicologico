@echo off
echo Iniciando servidor PHP en http://localhost:8000 ...
echo Panel del blog: http://localhost:8000/admin/login.php
echo (Deja esta ventana abierta mientras usas el sitio. Ctrl+C para detener.)
"C:\Users\ESTADISTICA-03\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe" -S localhost:8000
pause
