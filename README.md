# En filmsajt mha Laravel
Nedan följer en guide till att skapa en webbapplikation (webbplats) för att visa info om filmer och skådespelare.
## Installation
Använd din terminal (Git bash, terminal e dyl)
Installeras helst via  [composer](https://getcomposer.org/)
När det är installerat kan du skriva ```composer global require "laravel/installer"``` för att installera Laravel. 
## Börja bygga
Därefter skapar du ett projekt genom att skriva ```laravel new filmsajt```
## Databas
Laravel är gjort för att jobba mot en databas, så vi använder [LAMP](https://bitnami.com/stack/lamp/installer), [MAMP](https://www.mamp.info/en/mamp/) eller [XAMPP](https://www.apachefriends.org/index.html).
Alla dessa innehåller vad vi behöver för att ha en databas lokalt på datorn, och förvalt är MySql. 
Så installera något av de tre, och starta sedan igång den (Du bör se att den startar både apache (webbserver) och MySQL (databasmotor)).
Därefter öppnar du upp **PHPMyAdmin** (skriv in adressen [http://localhost:8080/phpMyAdmin](http://localhost:8080/phpMyAdmin) i webbläsaren) och skapar en ny databas. 
**OBS** Undvik helst XAMPP om du har en Mac, eftersom jag har erfarenhet av att det är problematiskt och svårt att få att fungera. Använd då hellre MAMP (Första M:et står för Mac!)
**OBS** Det kan hända att din LAMP/MAMP/XAMPP använder en annan port än 8080. I så fall ersätter du med dina värden.
För att skapa en ny databas kan du följa instruktionerna i [den officiella dokumentationen](https://www.phpmyadmin.net/docs/) eller försöka följa mina instruktioner. 
Jag brukar klicka på "New" längst upp i vänsterspalten, och sedan skriva i namnet i fältet under "Create database".
När du skapat en databas som heter **filmsajt** så skapar du också i PHPMyAdmin en användare som vi ska använda för att kommunicera med databasen. 
Vi kan kalla användaren **janitor** och ge den lösenordet **br0MMABL0cks**. Se också till att denna användare ska ansluta via **localhost**
Jag brukar skapa användaren direkt efter att jag har skapat databasen, och då klickar jag på "Privileges"/"Användarrättigheter" i den övre knappraden.
Därefter brukar man få skriva in namnet på användaren samt varifrån denna användare kan koppla upp sig (localhost) och sedan skriva lösenordet två gånger. 
Leta sedan upp en knapp längst ner i högra hörnet ("Ok"/"Go") och klicka på den. Då öppnas en vy där du får bestämma vilka rättigheter som användaren ska ha i databasen. Klicka i alla och återigen på "Go".
## Konfigurera Laravel
När vi skapat en tom databas och en användare som har rättigheter till den, så måste vi skriva in uppgifterna någonstans.
För detta finns filen ```.env```.
Det är en av de filer som vi aldrig ska ladda upp på Github eller liknande, och därför återfinns filnamnet i filen .gitignore. 
Anledningen till att vi inte ska ladda upp den beror på att det är här vi skriver in känslig information som lösenord o dyl.
Ändra informationen i din .env-fil så att det står:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=filmsajt
DB_USERNAME=janitor
DB_PASSWORD=br0MMABL0cks
```
## Starta Laravel
Gå till terminalen och skriv ```php artisan serve``` och se vilken localhost-adress din webbplats körs på. Hos mig ser det ut så här:
```shellSession
$ php artisan serve
Laravel development server started: <http://127.0.0.1:8000>
```

## MVC
Nu ska vi stanna upp lite och fundera. Laravel följer MVC, dvs delar upp koden i 
* Models
* Views
* Controllers
Detta innebär att allt som handlar om att **presentera** data sköts av Views. Det är i en view vi skriver HTML-kod.
I en Model finns en massa inbyggda metoder som gör att vi kan kommunicera med en databas. Dessutom kan vi själva skriva instruktioner om hur modellen ska fungera.
Controllern är den som oftast blir anropad när något ska göras. I en Controller finns bl a en massa **CRUD**-metoder, alltså de vi använder för att skapa data, hämta data, uppdatera datan och ta bort data.
### Var ska man börja?
En bra metod är att börja med Model. Genom att definiera vad den är så blir våran programmerin tydligare. Så vi startar där. 
En nyckelregel är att ha en model per databastabell. Om tabellen ska ha produkter, så bör den i databasen heta **products** (pluraländelse, eftersom en tabell förväntas innehålla flera produkter.)
Modellen heter då **Product** (Stor initialbokstav är standard, samt singularändelse, eftersom modellen beskriver vad *en* produkt är.)
## Artisan
Laravel kommer med ett trevligt verktyg som heter artisan. Det använder vi för att med korta, kärnfulla kommandon skapa färdiga eller halvfärdiga filer som vi sedan kan redigera. På så sätt slipper vi skriva så mycket.
Skriv nu ```php artisan make:model Movie -mcr```


