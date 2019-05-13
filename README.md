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
```php
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
Om du har fått igång den bör du se ordet "Laravel" samt några länkar på den adressen. Om webbservern inte har startat, så har du förmodligen fått ett felmeddelande. Läs igenom det och googla på väl valda delar av det. Det brukar alltid finnas intressant information i början av felmeddelandet, samt kanske någon felkod?
När du tittat lite på den ganska ointressanta sidan, så kan du gå tillbaka till terminalen och stänga ner den med ```ctrl + c```.
Vi behöver terminalen till andra grejer nu (men du kan ju också öppna **två** terminaler, så slipper du starta och stänga av webbservern.)
## MVC
Nu är det dags att skapa grejer. Vi ska börja med att skapa lite filer som behövs för att både skapa en databastabell och sedan kommunicera med densamma. 
Laravel följer MVC, dvs delar upp koden i 
* Models
* Views
* Controllers

Detta innebär att allt som handlar om att **presentera** data sköts av Views. Det är i en view vi skriver HTML-kod.
I en Model finns en massa inbyggda metoder som gör att vi lätt kan hämta och skicka data till och från databasen. Dessutom kan vi själva skriva instruktioner om hur modellen ska fungera.
Controllern är den som oftast blir anropad när något ska göras. I en Controller finns bl a en massa **CRUD**-metoder, alltså de vi använder för att skapa data, hämta data, uppdatera datan och ta bort data. Controllern är "chefen", som säger till vad Model ska göra och vilken data som ska skickas till vilken View.
### Var ska man börja?
En bra metod är att börja med Model. Genom att definiera vad den är så blir våran programmering tydligare. Så vi startar där. 
En nyckelregel är att ha en model per databastabell. Om tabellen ska innehålla produkter, så bör den heta **products** (pluraländelse, eftersom en tabell förväntas innehålla flera produkter.)
Modellen heter då **Product** (Stor initialbokstav är standard, samt singularändelse, eftersom modellen beskriver vad *en* produkt är.)
## Artisan
Laravel kommer med ett trevligt verktyg som heter artisan. Det använder vi för att med korta, kärnfulla kommandon skapa färdiga eller halvfärdiga filer som vi sedan kan redigera. På så sätt slipper vi skriva så mycket.
Skriv nu 
```shellSession
php artisan make:model Movie -mcr
```
Följande sker i detta kommando
* PHP exekverar artisan
* Artisan kör sitt make-kommando, i det här fallet för att skapa en Model.
* Modellen heter Movie (notera initial versalbokstav)
* -mcr betyder "När du skapar modellen, kan du då samtidigt skapa en: 
  * Migration (fil som kommer att skapa databastabellen)
  * Controller (som kommer att få namnet MovieController)
  * Resource (utrustar Controllern med en full uppsättning CRUD-metoder, så vi slipper skriva dem själv.)

Resultatet ser förhoppningsvis ut så här i terminalen:
```shellSession
$ php artisan make:model Movie -mcr
Model created successfully.
Created Migration: 2019_05_13_185423_create_movies_table
Controller created successfully.
```
## Koden i editorn
Om du nu öppnar din texteditor och öppnar hela katalogen "filmsajt" som du skapat någonstans på din dator, så ska vi titta lite på var olika filer hamnar.

```shellSession
app/
artisan*
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
vendor/
./
../
.editorconfig
.env
.env.example
.gitattributes
.gitignore
webpack.mix.js
composer.json
package.json
composer.lock
yarn.lock
server.php
phpunit.xml
.styleci.yml
```
Den **Model** som du nyss skapade hittar du i katalogen app.

**Controllern** hittar du i app/Http/Controllers.

**Migrationsfilen** ligger i database/migrations

Öppna filen ```MovieController.php``` i din editor och titta snabbt igenom att den ser ut så här:

```php
<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
```
Som du ser finns alla CRUD-metoder (Create, Read (heter Index), Update och Delete (heter Destroy)) samt ytterligare tre (Store, Show och Edit) som vi behöver för att effektivt hantera data. Vi ska titta närmare på dem senare.
## Lägg till data
Öppna nu den migrationsfil som du skapade. Vi ska editera den lite grann så att den i sin tur kan skapa en databastabell åt oss. Filen heter något i stil med ```2019_05_13_185423_create_movies_table.php```
Ändra i filen så att du har följande innehåll:
```php
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->year('year');
            $table->string('director');
            $table->timestamps();
        });
    }
```
I funktionen up definierar vi vilka kolumner som databastabellen 'movies' ska ha. Den första ('id') och den sista ('timestamps') ligger med som förval, och de är bra att ha. Låt de alltså ligga kvar och fyll i resten.
Vi startar enkelt och lagrar bara filmens titel, året då den hade premiär samt vem som regisserat.
Spara filen och gå till terminalen. Det är dags att låta migrationsfilen jobba.
## Migrera
Men innan vi kan göra detta måste vi justera en fil. Det är nämligen så att LAMP/MAMP/XAMPP vanligtvis använder en lite äldre version av MySQL, vilket skapar lite problem med Laravel. 
Därför ska du leta upp filen ```app/Providers/AppServiceProvider.php``` och ändra till följande:
```php
public function boot()
{
    Schema::defaultStringLength(191);
}
```
Samt ett stycke högre upp i filen (ovanför rad 8, som börjar med ordet Class):
```php
use Illuminate\Support\Facades\Schema;
```
Spara, och gå sedan tillbaka till terminalen och skriv
```shellSession
php artisan migrate
```

Då bör du få se följande rader
```php
$ php artisan migrate
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2019_05_13_185423_create_movies_table
Migrated:  2019_05_13_185423_create_movies_table
```
som talar om för dig att det har gått bra med migreringarna, dvs att tabellerna i databasen har skapats. Om det istället gått dåligt och du fått felmeddelanden, så googlar du på dem så att du blir mer kunnig.

## Lägg till en film
Gå nu till PHPMyAdmin och välj databasen 'filmsajt' samt tabellen 'movies'. Den är tom, så vi ska manuellt lägga till en film. Skriv in "Hets", 1944 och "Alf Sjöberg" och tryck "Go" (eller vad det nu heter på svenska).
## Tinker
Lägg märke till att vi nästan inte skrivit en rad kod (egentligen har vi skrivit fem, men vem håller räkningen?), och nu ska du få se hur häftig Model egentligen är. För att du ska få se kraften i den ska vi använda verktyget **tinker**
Gå till terminalen och skriv ```php artisan tinker```
När du gjort det kommer du in i ett så kallat REPL (Read Evalute Print Loop), dvs ett läge där du kan skriva lite andra kommandon.
Skriv nu ```App\Movie::all()``` och tryck ENTER.
Om det funkar som det ska bör du se följande:
```shellSession
>>> App\Movie::all()
=> Illuminate\Database\Eloquent\Collection {#2925
     all: [
       App\Movie {#2926
         id: 1,
         title: "Hets",
         year: 1944,
         director: "Alf Sjöberg",
         created_at: null,
         updated_at: null,
       },
     ],
   }
>>>
```
Visst är det häftigt? Modellen Movie har med den inbyggda metoden all() förmågan att hämta alla filmer ur databastabellen movies. Lägg märke till plural- och singularformerna (movies och Movie). Modellen **Movie** är på det viset kopplad till databastabellen **movies**.

