<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::prefix("admin")->middleware("superadmin")->group(function(){

  Route::post("/user/make/author",      "Admin\UserController@makeuserauthor")->name("make.user.author");
  Route::get("/user/view/admin",        "Admin\UserController@showalladmin")->name("view.user.admin");
  Route::post("/user/make/admin",       "Admin\UserController@makeuseradmin")->name("make.user.admin");
  Route::get("/news/create" ,           "Admin\NewsSubScriptionController@show")->name("send.news");
  Route::post("/news" ,                 "Admin\NewsSubScriptionController@store")->name("news.store");
  Route::get("/news"  ,                 "Admin\NewsSubScriptionController@index")->name("news.index");
  Route::get("/news/{id}",              "Admin\NewsSubScriptionController@view")->name("news.show");
  Route::get("/subscriptions" ,         "Admin\NewsSubScriptionController@getAllSubscription")->name("subscription.all");
  Route::get("/subscribe/{id}"   ,       "Admin\NewsSubScriptionController@changeSubscription")->name("subscription.change");



});

Route::prefix("admin")->middleware("admin")->group(function(){




Route::post("/user/verified","Admin\UserController@changeVerified")->name("user.verified");

Route::get("/users/index","Admin\UserController@getUsers")->name("admin.users.index");
Route::get("/users/{id}","Admin\UserController@getSingleUser")->name("admin.users.single");
Route::delete("/users/{id}","Admin\UserController@deleteUser")->name("admin.user.delete");
Route::get("/user/search/{email}","Admin\UserController@searchuser")->name("admin.user.search");
Route::get("/sliders","Admin\SliderController@index")->name("admin.slider.index");
Route::post("/sliders","Admin\SliderController@store")->name("admin.slider.add");
Route::put("/sliders/{id}","Admin\SliderController@update")->name("admin.slider.update");
Route::delete("/sliders/{id}","Admin\SliderController@destroy")->name("admin.slider.delete");
Route::get("/sliders/data","Admin\SliderController@data")->name("admin.slider.data");

//Routes for the seasons
    Route::resource("/season","Admin\SeasonController");

//Routes for Formats
Route::resource("/format","Admin\FormatController");
Route::get("/format-ajax/","Admin\FormatController@getFormatAjax");
Route::get("/format/search/{value}","Admin\FormatController@search");
Route::get("/format/find/deck/{id}","Admin\DeckController@getDeckByFormatAdmin")->name("admin.deck.find");


//Routes for decks
Route::resource("/deck","Admin\DeckController",["as"=>"admin"]);
Route::get("/deck-ajax/","Admin\DeckController@deckAjax");
Route::get("/deck/search/{name}","Admin\DeckController@search");
Route::get("/deck/format/{id}","Admin\DeckController@getDeckByFormatAd");
//Routes for the messages
Route::get("/messages","Admin\MessageController@index")->name("admin.message.index");
Route::delete("/message/{id}","Admin\MessageController@delete")->name("admin.message.delete");
Route::get("/message/{id}","Admin\MessageController@seen")->name("admin.message.seen");

//Routes for the unverified deck
Route::get("/unverified-deck","Admin\DeckController@getUnverifiedDeck")->name("admin.deck.unverified");
Route::get("/unverified-deck/data/","Admin\DeckController@getUnverifiedDeckData")->name("admin.deck.unverified.data");
Route::get("/unverified-deck/approve/{id}","Admin\DeckController@approveUnverifiedDeck")->name("admin.deck.unverified.approve");
Route::get("/unverified-deck/edit/{id}","Admin\DeckController@editUnverifiedDeck");
Route::post("/unverifed-deck/merge","Admin\DeckController@mergeUnverifiedDeck")->name("merge.unverified.deck");
});

Route::prefix("/admin-ajax")->middleware("admin")->group(function (){
   Route::get("/deck-format-season","ajax\DeckAjaxController@getDeckByFormatAndSeason");
});

Route::middleware("author")->group(function(){
  Route::get("/post/search/{title}","Admin\PostController@search")->name("admin.post.search");
  Route::get("/post/all","Admin\PostController@index")->name("admin.post.index");
  Route::get("/post/add","Admin\PostController@add")->name("admin.post.add");
  Route::post("/post/add","Admin\PostController@store")->name("admin.post.store");
  Route::get("/post/{id}/edit","Admin\PostController@edit")->name("admin.post.edit");
  Route::put("/post/{id}","Admin\PostController@update")->name("admin.post.update");
  Route::delete("/post/{id}","Admin\PostController@delete")->name("admin.post.delete");


  Route::resource("/post-category","PostCategoryController");
  Route::resource("/post-tag","PostTagController");

});

Route::middleware("auth")->group(function(){
    //Routes for deck
    Route::get("/dice","DiceController@index");
    Route::get("/life-counter","LifeCounterController@index");
    Route::get("/hypergeometic-calculator","HyperController@index");


    Route::get("/magic-decks","UserDeckController@index");
    Route::get("/magic-decks/table","UserDeckController@table");
  //Routes for adding the deck
    Route::get("/user/deck/add","UserDeckController@create");
Route::post("/user/deck/add","UserDeckController@store");
Route::get("/user/deck/{slug}/edit","UserDeckController@edit");
Route::get("/user/deck/{slug}/show","UserDeckController@edit");

//Routes to check any active game exists
Route::post("/checkevent","EventStatusController@check");


//Routes for tournament
Route::get("/tournament","TournamentStartController@start");
Route::post("/tournament","TournamentStartController@store");
Route::get("/tournament/complete/{slug}","TournamentStartController@continueTournament")->name("tournament.continue");
Route::get("/tournament/load-data/{slug}","TournamentStartController@loadActiveTournamentData");
Route::post("/tournament/complete","TournamentStartController@complete")->name("tournament.complete");





Route::get("/game/{name}","PageController@getindex")->name("game.match.index");
Route::post("/setname","Admin\UserController@setname")->name("game.name.set");
Route::get("/game/{game}/match","GameMatchController@start")->name("game.match.start");

Route::get("/game/{game}/{deck}/start/{event}","GameMatchController@selectformat");

Route::get("/load/{deck_id}","GameMatchController@loaddata");


Route::get("/format/{id}","Admin\DeckController@getDecksByFormat");

    Route::get("/user-format/{id}","UserDeckController@getDeckWithUserDeck");
Route::post("/match/save","GameMatchSaveController@store");
Route::post("/match/format","GameMatchController@selectformat")->name("format.select");

Route::post("/checkformat","GameMatchController@formatexist");
Route::post("/league/reset","GameMatchController@leaguereset")->name("league.reset");



Route::get("/league/complete/{id}","GameMatchController@leaguecontinue")->name("league.continue");




//Routes for Main statistic
    Route::post("/user/match/data"  ,              "StatusTestController@newData");
    Route::post("/user/match/view"   ,               "MatchHistoryController@index");


//Routes for profile setting
Route::get("/profile/setting","ProfileController@index")->name("user.profile.setting");
Route::post("/profile/setting","ProfileController@store")->name("user.profile.update");

//Routes for match edit
Route::get("/view/{id}","GameMatchController@view")->name("match.view");
Route::get("/match/edit/{id}","GameMatchController@edit")->name("match.edit");
Route::get("/match/edit-data/{id}","GameMatchController@loadDataMatch");
Route::post("/match/edit","GameMatchController@update");


//Routes for continuing active league
Route::get("/continue/{game}","EventStatusController@getActiveEvent")->name("league.active");

});


Route::get("/unsubscribe/{email}","Admin\NewsSubScriptionController@unsubscribe")->name("news.unsubscribe");

Route::get("/public/seasons","Admin\SeasonController@ajax");
Route::get("/public/deck-season","Admin\DeckController@getDeckBySeason");

Route::get("/games","Admin\GameController@all")->name("games.all");
Route::get("/gameformat/{id}","Admin\GameController@getFormatByGameid");
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get("/contact","HomeController@contact")->name("contact");
Route::post("/contact","HomeController@storeContact")->name("contact.post");


Route::get("/post/{slug}","BlogPostController@getBySlug")->name("post.single");
Route::get("/posts","BlogPostController@getall")->name("post.all");

Route::get("/public/post-tag/{id}","BlogPostController@getPostWithTags")->name("blog.post.tag");
Route::get("/public/post-category/{slug}","BlogPostController@getPostWithCategory")->name("blog.post.category");

Route::get("/resend/{token}","VerificationController@resendToken");


//Routes for authentication
Auth::routes();
Route::get("/sendverification","Auth\RegisterController@sendlink")->name("verifyemail");
Route::get("/verify/{token}","VerificationController@verificationDone")->name("send.email.verify");
Route::get("/guest/magic","PageController@showDataForGuest")->name("guest.magic");


Route::get("/change/{email}/{token}","ProfileController@changeEmail")->name("user.email.change");
Route::get("/checkauth","HomeController@checklogin")->name("auth.check");

Route::get("/matchstat-all/{game}/{deck}","StatusController@getMatchStatusAll");
Route::get("/guest/formatid}","Admin\FormatController@getFormatByGame");
Route::get("/guest/format/{id}","Admin\DeckController@getDecksByFormat");
Route::get("/blogimage/{first}/{second}/{third}","ImageController@blogimage");

//Route::get("/test","HomeController@test");

//Routes for comment
  Route::get("/post-comment/{id}","PostCommentController@show");
  Route::post("/post-comment","PostCommentController@store");



Route::get("legal", function(){
	return view('legal');
});

Route::prefix("/api")->group(function (){

    //Routes for format
    Route::get("/format-game","Api\FormatApiController@index");


    Route::get("/deck-format","Api\DeckApiController@getDeckByFormat");
    Route::get("/deck/{slug}","Api\DeckApiController@getDeckBySlug");

    Route::get("/deck-format-group","Api\DeckApiController@getDeckByFormatGrouped");
    Route::get("/deck-format-version","Api\DeckApiController@getVersionByGroupedDeckName");
    Route::get("/deck-format-version-season","Api\DeckApiController@getVersionByGroupedNameWithSeason");

    Route::post("/match-stat"  ,"StatusTestController@guestData" );
});

Route::prefix("/api")->middleware("auth")->group(function (){

    Route::post("/deck", "Api\DeckApiController@store");
    Route::post("/suggest","Api\DeckApiController@suggestBetter");

});
Route::prefix("/api")->middleware("admin")->group(function (){

    Route::get("/deck-unverified", "Api\DeckApiController@getUnverifiedDecks");


});




?>
