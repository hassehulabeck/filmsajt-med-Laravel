# En filmsajt mha Laravel
Nedan följer en guide till att skapa en webbapplikation (webbplats) för att visa info om filmer och skådespelare.
## Installation
Använd din terminal (Git bash, terminal e dyl) eftersom Laravel helst installeras via  [composer](https://getcomposer.org/)
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
(Om du inte gör dessa ändringar får du [detta fel.](https://laravel-news.com/laravel-5-4-key-too-long-error)

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
Prova att skriva ```App\Movie::findOrFail(1)```
Du kommer då förmodligen att få samma resultat, men med en annan metod.
När du är klar att gå vidare skriver du ```exit``` vilket stänger tinker.

## Seed & factories
När databastabellerna är strukturerade ska vi börja befolka dem med värden. Ett sätt att göra detta är att manuellt skriva in dem i PHPMyAdmin, men det är bättre att använda **seeders*.

En seeder skapar så kallad "mock-data", så att vi kan testa webbplatsen utan att själva behöva registrera användare i mängd eller liknande. I Laravel finns det en fil ```database/seeds/DatabaseSeeder.php``` i vilken vi skriver in på vilket sätt vi vill skapa mock-data.

Det vanligaste sättet är att använda en **Factory**, vilket är en funktion som producerar flera användare, produkter, movies eller vad vi nu vill ha för något. I den factory-fil vi ska skapa definierar vi hur de olika kolumnerna i databastabellen ska fyllas, och ett utmärkt tillägg för att göra detta på ett sätt som skapar meningsfulla värden är att använda [Faker av @fzaninotto](https://github.com/fzaninotto/Faker#installation). Detta tillägg installerar du genom att i terminalen skriva
```shellSession
composer require fzaninotto/faker
```
Därefter skapar vi en factory för vår movies-tabell genom att i terminalen skriva
```shellSession
php artisan make:factory MovieFactory --model=Movie
```
Genom att skriva in tillägget "--model=Movie" får vi en koppling mellan factory och model, vilket gör att mer av filen blir färdigskriven.

Öppna sedan denna fil (database/factories/MovieFactory) i din editor och fyll i enligt följande:
```php
$factory->define(Model::class, function (Faker $faker) {
    $slump = mt_rand(1,6);
    return [
        'title' => $faker->sentence($nbWords = $slump, $variableNbWords = true),
        'year' => $faker->year(),
        'director' => $faker->name,
    ];
});
```
och redigera sedan filen ```database/seeds/DatabaseSeeder.php``` så att den använder vår MovieFactory:
```php
    public function run()
    {
        factory(App\Movie::class, 10)->create();
    }
```
och till slut, för att göra verklighet av våra "ansträngningar", skriver vi följande i terminalen
```shellSession
php artisan db:seed
```
Om du tycker att namn enbart är engelska, så beror det på en inställning i filen ```config/app.php``` där du kan ändra värdet i faker_locale till exempelvis "sv_SE":
```php
    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',
```
### Uppgift: Skapa en liknande Actors-tabell
Skapa en migration-fil, en model, en controller och en factory för detta. Jag tycker att egenskaperna/kolumnerna name, birthday och country räcker till en början.

## Relationer mellan tabeller
Det är kul med två tabeller men de hänger inte ihop på något sätt. Om man tittar på vad man får ut i **tinker** från de båda tabellerna, så finns det ingen egenskap/kolumn som binder ihop tabellerna, dvs de båda tabellerna har ingen relation till varandra. 
```shellSession
$ php artisan tinker
Psy Shell v0.9.9 (PHP 7.2.1 — cli) by Justin Hileman
>>> App\Actor::find(2)
=> App\Actor {#2925
     id: 2,
     name: "Tina Norberg",
     birthday: "1978-05-23",
     country: "Komorerna",
     created_at: "2019-05-15 08:27:37",
     updated_at: "2019-05-15 08:27:37",
   }
>>> App\Movie::find(2)
=> App\Movie {#139
     id: 2,
     title: "Libero molestias.",
     year: 1975,
     director: "Prof. Leta Cummerata Sr.",
     created_at: "2019-05-15 07:43:56",
     updated_at: "2019-05-15 07:43:56",
   }
```
Och om vi vill veta vilken skådespelare som varit med i vilken film, eller vilka filmer en viss skådespelare varit med i, så måste vi lösa det problemet.
### Många-till-många?
Relationer mellan databastabeller i SQL kan delas in i tre sorter:
* En till en
  * En till en är förhållandet mellan en person och ett körkort. En person har aldrig fler än ett körkort, och ett körkort kan inte delas av flera personer.
* En till många
  * En till många kan vara en person och dennes bilar, för att fortsätta fordonsexemplet. En person kan äga flera bilar, men en bil kan inte ägas av flera personer (i alla fall inte i vårt exempel).
* Många till många
  * Actors och Movies är ett bra exempel på många till många, då en skådespelare kan vara med i flera filmer, och en film vanligtvis består av många skådespelare.
  
Så hur beskriver vi ett många till många-förhållande mellan skådespelare och film? Enklast är att skapa en så kallad "pivot-tabell", vilken innehåller få kolumner men många rader, och varje rad innehåller information om att en skådespelare varit med i en film. Utöver detta har tabellen en egen id-kolumn, så totalt har vi tre rader (id, actor_id, movie_id). Laravel brukar sätta ihop en pivottabell genom att slå ihop de båda tabellnamnen alfabetiskt sorterade, med ett underscore emellan, och kolumnerna i tabellen brukar se ut enligt formeln tabellens-namn+\_+id 

```shellSession
$ php artisan make:migration create_actor_movie_table
```

Pivot-tabeller är något som vi får konstruera själv i Laravel (om vi inte installerar [tillägget Generetors extended](https://github.com/laracasts/Laravel-5-Generators-Extended))

Därefter editerar vi vår pivot-migration så att den ser ut så här:
```php
    public function up()
    {
        Schema::create('actor_movie', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('actor_id')->unsigned()->nullable();
            $table->foreign('actor_id')->references('id')
                  ->on('actors')->onDelete('cascade');
      
            $table->bigInteger('movie_id')->unsigned()->nullable();
            $table->foreign('movie_id')->references('id')
                  ->on('movies')->onDelete('cascade');
    
            $table->timestamps();        
        });
    }
```
Observera att kolumnerna actor_id och movie_id i vår pivot-tabell har typen **bigInteger**. Detta för att den ska kunna lagra samma typ av värde som ligger i respektive actors- och movietabell.
Därefter gör vi först en rollback följt av en migration. En rollback tar bort befintliga tabeller i databasen, vilket kan vara nödvändigt då och då. Nu är ett bra tillfälle.

```shellSession
$ php artisan migrate:rollback
Rolling back: 2019_05_21_065952_create_actor_movie_table
Rolled back:  2019_05_21_065952_create_actor_movie_table
Rolling back: 2019_05_15_081708_create_actors_table
Rolled back:  2019_05_15_081708_create_actors_table
Rolling back: 2019_05_13_185423_create_movies_table
Rolled back:  2019_05_13_185423_create_movies_table
Rolling back: 2014_10_12_100000_create_password_resets_table
Rolled back:  2014_10_12_100000_create_password_resets_table
Rolling back: 2014_10_12_000000_create_users_table
Rolled back:  2014_10_12_000000_create_users_table

hans@LAPTOP-A0NBCAJK MINGW64 /c/MAMP/htdocs/filmsajt
$ php artisan migrate
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2019_05_13_185423_create_movies_table
Migrated:  2019_05_13_185423_create_movies_table
Migrating: 2019_05_15_081708_create_actors_table
Migrated:  2019_05_15_081708_create_actors_table
Migrating: 2019_05_21_065952_create_actor_movie_table
Migrated:  2019_05_21_065952_create_actor_movie_table
``` 

Och sedan ska vi redigera modellerna Actor och Movie, så att de får relationer till varandra. Eftersom vi tidigare konstaterat att det rör sig om many-to-many-förhållande, så använder vi ORMs belongsToMany-metod.

```php
class Actor extends Model
{
    public function movies() {
        return $this->belongsToMany('App\Movie');
    }
}
```
Detta innebär att modellen Actor nu har en ny egenskap (movies), vilket ska visa information om alla de filmer som skådespelaren varit med i.

På motsvarande sätt skriver du i modellen Movie, så att den får en koppling till actors.

Därefter seedar vi alla tre tabeller, så att actors befolkas med skådespelare, movies med filmer och actor_movie med referenser till de båda. I filen ```DatabaseSeeder.php``` kan man skriva in
```php
public function run()
    {
        factory(App\Actor::class, 100)->create();
        factory(App\Movie::class, 30)->create()->each(function($a) {
            $slumptal = mt_rand(1, 20);
            $a->actors()->attach(App\Actor::all()->random($slumptal));
        });
    }
```
vilket då skapar 100 skådisar, 30 filmer och för varje film ett slumpvist antal rader, dvs mellan 1-20 skådisar per film, när vi seedar.
```shellSession
php artisan db:seed
``` 

## paus
Gå och drick lite saft eller stå upp och sträck ut kroppen lite eller...


