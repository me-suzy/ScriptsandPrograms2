if exist "C:\Program Files\Java\j2re1.4.2_01\bin\java.exe" goto winc1421
if exist "C:\Program Files\Java\j2re1.4.1_02\bin\java.exe" goto winc1412
if exist "C:\Program Files\Java\j2re1.4.1_01\bin\java.exe" goto winc1411

if exist "D:\Program Files\Java\j2re1.4.2_01\bin\java.exe" goto wind1421
if exist "D:\Program Files\Java\j2re1.4.1_02\bin\java.exe" goto wind1412
if exist "D:\Program Files\Java\j2re1.4.1_01\bin\java.exe" goto wind1411

if exist "E:\Program Files\Java\j2re1.4.2_01\bin\java.exe" goto wine1421
if exist "E:\Program Files\Java\j2re1.4.1_02\bin\java.exe" goto wine1412
if exist "E:\Program Files\Java\j2re1.4.1_01\bin\java.exe" goto wine1411

if exist "F:\Program Files\Java\j2re1.4.2_01\bin\java.exe" goto winf1421
if exist "F:\Program Files\Java\j2re1.4.1_02\bin\java.exe" goto winf1412
if exist "F:\Program Files\Java\j2re1.4.1_01\bin\java.exe" goto winf1411

rem try the default
java -jar .\jar\FCInstall.jar -classpath .\jar\FCInstall.jar
goto end

:winc1421
"C:\Program Files\Java\j2re1.4.2_01\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:wind1421
"D:\Program Files\Java\j2re1.4.2_01\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:wine1421
"E:\Program Files\Java\j2re1.4.2_01\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:winf1421
"F:\Program Files\Java\j2re1.4.2_01\bin\java.exe" -jar .\jar\FCInstall.jar
goto end

:winc1412
"C:\Program Files\Java\j2re1.4.1_02\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:wind1412
"D:\Program Files\Java\j2re1.4.1_02\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:wine1412
"E:\Program Files\Java\j2re1.4.1_02\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:winf1412
"F:\Program Files\Java\j2re1.4.1_02\bin\java.exe" -jar .\jar\FCInstall.jar
goto end

:winc1411
"C:\Program Files\Java\j2re1.4.1_01\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:wind1411
"D:\Program Files\Java\j2re1.4.1_01\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:wine1411
"E:\Program Files\Java\j2re1.4.1_01\bin\java.exe" -jar .\jar\FCInstall.jar
goto end
:winf1411
"F:\Program Files\Java\j2re1.4.1_01\bin\java.exe" -jar .\jar\FCInstall.jar
goto end

rem end of the file
:end
