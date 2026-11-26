# 📜PHP ToDo Lista (AFP tárgyra)
## ❓Rövid Leírás
Egy szimpla ToDo lista ami böngészőn keresztül localhost kapcsolattal fut. Az oldalon belül lehet csapatosan is lehet használni egy csatlakozássi kóddal. A csinált ToDo-kat valamint a csapatokat és a felhasználói adatokat egy adatbázisban mentődnek le.\
*Ez az oldal egy egyetemi projektre készül*

## 🖱️Alapvetők funkciók:
+ Felhasználói fiók (Bejelentkezés/Regisztráció)
+ Egy ToDo létrehozása/szerkesztése
+ Csapatok

## ✍️Nyelvek amiben írva van
+ Oldal: HTML / CSS / PHP
+ Pop-Up ablakok/Sötét-Világos Mód: JavaScript
+ Adatbázis: mySQL
+ IDE: VS Code

## 🖥️Oldal részei:
+ Főoldal (`Startpage.php`): Főoldal azon belül lehet a felhasználó adatait lehet módosítani
+ Régi ToDo lista (`index.php`): Egy ToDo lista oldal ahol lehet szerkeszteni és hozzáadni todo-kat, (*régi változat ami nem kerül frissítésre*)
+ ToDo Lista (`index_v2.php`): A ToDo lista up-to-date változata, ezen keresztül lehet drag-n-drop -ot használna valamint egy AI asszisztenst (*az alábbi kettő dolgozás alatt*)

## 👥Csapatok
Csapatokat az oldal főoldalján hozhat létre, valamint csatlakozhat egy kóddal. Az csapat admin tud egy tagnak szerepkört módosítani (Admin/Viewer/Member) vagy tagokat kirúgni

## ⌨️Használat:
Mivel ez localhost-on keresztül fut, ezért szükséges XAMPP letöltése (Linux: elég ha a mySQL és az apache vannak letöltve). Az oldal tartalmait a `C:/xampp/htdocs` mappába kell berakni (linuxon a `/var/html/www` + írási engedély)

## 🗞️Dokumentáció
Az oldal dokumentációja a `Documents` mappában
