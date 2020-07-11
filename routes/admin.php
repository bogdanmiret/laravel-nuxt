<?php

Route::get('/', 'HomeController@index')->name('index');

Route::post('/mark-translation', 'TranslationsController@markTranslation');
Route::post('/get-en-version', 'TranslationsController@getEnVersion');

Route::get('settings/{slug?}', 'SettingsController@index')->name('settings.index');
Route::put('settings/{settings}', 'SettingsController@update')->name('settings.update');
Route::get('/purge_redis_cache', 'HomeController@purgeRedisCache')->name('purge.redis');

Route::post('/accounts/get-dt-accounts/{type?}', 'UsersController@getUsers')->name('accounts.get-dt');
Route::get('/accounts/{type}', 'UsersController@index')->name('accounts.show-type');
Route::get('/accounts/{id}/change-role/', 'UsersController@changeRole')->name('accounts.change-role');
Route::put('/accounts/{id}/update-role/', 'UsersController@updateRole')->name('accounts.update-role');
Route::post('/accounts/{id}/change-status', 'UsersController@changeStatus')->name('accounts.change-status');
Route::resource('/accounts', 'UsersController', [
    'only' => [
        'index',
        'edit',
        'update',
        'destroy',
    ],
]);

if (config('base.Page.status')) {
    Route::post('/pages/get-dt-pages', 'PagesController@getPages')->name('pages.get-dt');
    Route::post('/pages/{id}/change-status', 'PagesController@changeStatus')->name('pages.change-status');
    Route::resource('/pages', 'PagesController', ['except' => ['show']]);
}

if (config('base.ContactForm.status')) {
    Route::post('/contact-forms/get-dt-contact-forms/{type}',
        'ContactFormsController@getContactForms')->name('contact-forms.get-dt');
    Route::post('/contact-forms/{id}/change-status',
        'ContactFormsController@changeCFStatus')->name('contact-forms.change-status');
    Route::get('/contact-forms/{id}/download-attachments',
        'ContactFormsController@downloadAttachments')->name('emails.download.attachments');
    Route::get('/contact-forms/type/{type?}', 'ContactFormsController@index')->name('contact-forms.index');
    Route::resource('/contact-forms', 'ContactFormsController', [
        'only' => [
//            'index',
            'show',
            'destroy',
        ],
    ]);

}

if (config('base.Faq.status')) {
    Route::post('/faq/get-dt-faq', 'FAQController@getFAQ')->name('faq.get-dt');
    Route::post('/faq/{id}/change-status', 'FAQController@changeStatus')->name('faq.change-status');
    Route::resource('/faq', 'FAQController');
}

if (config('base.FaqCategory.status')) {
    Route::post('/faq-categories/get-dt-faq-categories',
        'FAQCategoriesController@getFAQCategories')->name('faq-categories.get-dt');
    Route::post('/faq-categories/{id}/change-status',
        'FAQCategoriesController@changeStatus')->name('faq-categories.change-status');
    Route::resource('/faq-categories', 'FAQCategoriesController');
}

Route::get('blog/contentbuilder', 'BlogArticlesController@contentbuilder')->name('blog.contentbuilder');

if (config('base.BlogArticle.status')) {
    Route::post('/blog/get-dt-blogs', 'BlogArticlesController@getDTBlogArticles')->name('blog.get-dt');
    Route::post('/blog/{id}/change-status', 'BlogArticlesController@changeStatus')->name('blog.change-status');
    Route::resource('/blog', 'BlogArticlesController');
    Route::delete('/blog/{blog}/delete-picture', 'BlogArticlesController@deletePicture')->name('blog.delete-picture');
    Route::get('blog/republish/{id}', 'BlogArticlesController@republishBlog')->name('blog.republish');
}

if (config('base.FeedArticle.status')) {
    Route::post('/feed/get-dt-blogs', 'FeedController@getDTFeedArticles')->name('feed.get-dt');
    Route::post('/feed/{id}/change-status', 'FeedController@changeStatus')->name('feed.change-status');
    Route::resource('/feed', 'FeedController');
}

if (config('base.BlogCategory.status')) {
    Route::post('/blog-categories/get-dt-blog-categories',
        'BlogCategoriesController@getDTBlogCategories')->name('blog-categories.get-dt');
    Route::post('/blog-categories/get-sel-blog-categories',
        'BlogCategoriesController@getSelBlogCategories')->name('blog-categories.get-sel');
    Route::post('/blog-categories/{id}/change-status',
        'BlogCategoriesController@changeStatus')->name('blog-categories.change-status');
    Route::resource('/blog-categories', 'BlogCategoriesController', ['except' => ['show']]);
}

if (config('base.EmailSender.status')) {
    Route::post('/emails/get-dt', 'EmailsController@getDT')->name('emails.get-dt');
    Route::post('/emails/get-sel-recipients', 'EmailsController@getSelRecipients')->name('emails.get-sel-recipients');
    Route::resource('/emails', 'EmailsController', ['except' => ['edit, update']]);
    Route::post('/emails/reply-to-email', 'EmailsController@replyToEmail')->name('emails.reply-to-email');
}

if (config('base.config_page.status')) {
    Route::get('/config-page', 'ConfigPageController@index')->name('config-page');
    Route::post('/config-page/save-env', 'ConfigPageController@saveEnv')->name('config-page.save_env');
}

//Claim routes
Route::resource('claims', 'ClaimController');
Route::post('/claims/get', 'ClaimsTableController')->name('claims.get');


Route::get('status/mark/{claim}/{status}/{notification}',
    'ClaimStatusController@mark_status')->name('status.mark')->where(['status' => '[0,1]']);
Route::get('approved/mark/{claim}/{status}/{notification}',
    'ClaimStatusController@mark_approved')->name('approved.mark')->where(['status' => '[0,1]']);

//End claim routes

//Contribution routes

Route::resource('contribution', 'ContributionController');

Route::post('/contributions/get', 'ContributionTableController')->name('contributions.get');
Route::get('contributions/mark/{contribution}/{status}',
    'ContributionStatusController@mark_approved')->name('contribution.mark')->where(['status' => '[0,1]']);

//End contribution routes

if (config('base.Translation.status')) {
    
    Route::post('/translations/dt-update-field',
        'TranslationsController@DTUpdateFiled')->name('translations.dt-update-field');
    Route::post('/translations/get-dt/{folder?}',
        'TranslationsController@getDTTranslations')->name('translations.get-dt');
    
    Route::get('/translations/files',
        'TranslationsController@show')->name('translations.files'); //display all translations
    Route::post('/translations/update',
        'TranslationsController@update')->name('translations.update'); //update file translation
    Route::get('/translations/{lang?}',
        'TranslationsController@index')->name('translations.index'); //display all translations
    Route::post('/translations/sync',
        'TranslationsController@syncTranslations')->name('translations.sync'); //sync all translations
}

if (config('base.Language.status')) {
    Route::post('/languages/get-dt', 'LanguagesController@getDT')->name('languages.get-dt');
    Route::resource('/languages', 'LanguagesController');
//    Route::get('/languages', ['as' => 'languages.index', 'uses' => 'LanguagesController@index']); //display all translations
//    Route::get('/languages/create', ['as' => 'languages.create', 'uses' => 'LanguagesController@create']); //display all translations
}

Route::post('/tags/get-sel-tags', 'TagsController@getSelTags')->name('tags.get-sel-tags');


if (config('base.Role.status')) {
    Route::post('/roles/get-dt', 'RolesController@getRoles')->name('roles.get-dt');
    Route::post('/roles/{id}/change-status', 'RolesController@changeStatus')->name('roles.change-status');
    Route::resource('/roles', 'RolesController', ['except' => ['show']]);
}

if (config('base.EmailTemplate.status')) {
    Route::post('/email-templates/get-dt', 'EmailTemplatesController@getDT')->name('email-templates.get-dt');
    Route::post('/email-templates/{id}/change-status',
        'EmailTemplatesController@changeStatus')->name('email-templates.change-status');
    Route::resource('/email-templates', 'EmailTemplatesController', ['except' => ['show']]);

    Route::resource('/email-categories', 'EmailTemplatesCategoriesController', ['except' => ['getCategories']]);
    Route::post('/email-categories/get-dt', 'EmailTemplatesCategoriesController@getCategories')->name('email-categories.get-dt');

}

if (config('base.log_viewer.status')) {
    Route::get('/logs', 'LogViewerController@index')->name('logviewer');
}

//Route::get('/features' , function () {return (view('admin.features.index'));})->name('features');

Route::get('/fb-permission', 'SocialController@getFbPermissions')->name('social.facebook.permissions');
Route::get('/fb-token', 'SocialController@getFbToken')->name('social.facebook.token');
Route::post('/post-facebook', 'SocialController@postToFacebook')->name('social.facebook.post');
Route::post('/post-twitter', 'SocialController@postToTwitter')->name('social.twitter.post');


Route::get('/social', 'SocialController@create')->name('social.create');

Route::get('/external_links/get-links', 'ExternalLinksController@Getlinks')->name('get.external_links');
Route::resource('external_links', 'ExternalLinksController');

Route::post('/json_lists/get-json-lists', 'JsonListsController@getJsonLists')->name('get.json_lists');
Route::resource('/json_lists', 'JsonListsController');

Route::get('regenerate_env_files', 'MiscController@regenerateEnv')->name('regenerate.env');

// Bugster Routes
Route::get('bugster', 'BugsterController@index')->name('bugster.index');
Route::get('bugster/show/{id?}', 'BugsterController@show')->name('bugster.show');
Route::post('/bugster/list', 'BugsterController@datatable')->name('post.bugster');
Route::delete('bugster/delete/{id}', 'BugsterController@destroy')->name('bugster.destroy');
// End bugster Routes

// Feedback Routes
Route::get('feedback', 'FeedbackController@index')->name('feedback.index');
Route::get('feedback/show/{id?}', 'FeedbackController@show')->name('feedback.show');
Route::post('/feedback/list', 'FeedbackController@datatable')->name('post.feedback');
Route::delete('feedback/delete/{feedback}', 'FeedbackController@destroy')->name('feedback.destroy');
Route::get('feedback/status/mark/{feedback}/{status}',
    'FeedbackController@mark_status')->name('feedback.status.mark')->where(['status' => '[0,1]']);
// End Feedback Routes

Route::get('packages', 'PackagesController@index')->name('packages.index');
Route::post('/packages/get-dt', 'PackagesController@getPackages')->name('packages.get-dt');
Route::post('/package/{id}/change-status', 'PackagesController@changeStatus')->name('package.change-status');
Route::resource('packages', 'PackagesController', ['exclude' => ['index', 'show']]);

Route::post('/package-options/{id}/change-status', 'PackageOptionsController@changeStatus')->name('package-options.change-status');
Route::post('/package-options/get-dt', 'PackageOptionsController@getPackageOptions')->name('package-options.get-dt');
Route::resource('/package-options', 'PackageOptionsController', ['except' => ['show']]);

Route::get('/advertising/get-dt', 'AdvertisingController@getAdvertisings')->name('advertising.get-dt');
Route::post('/advertising/{id}/change-status', 'AdvertisingController@changeStatus')->name('advertising.change-status');
Route::resource('/advertising', 'AdvertisingController', ['except' => ['show', 'store', 'create']]);

//Suggestions Routes
Route::get('/suggestions', 'SuggestionController@index')->name('suggestions.index');
Route::get('suggestions/show/{id}', 'SuggestionController@show')->name('suggestion.show');
Route::post('/suggestions/list', 'SuggestionController@datatable')->name('post.suggestions');
Route::delete('suggestions/delete/{suggestion}', 'SuggestionController@destroy')->name('suggestion.destroy');
Route::get('suggestion/mark_status/{id}/{status}', "SuggestionController@mark_processed")->name('toggle.suggestion.processed');
Route::get('suggestion/mark_closed/{id}', "SuggestionController@mark_closed")->name('suggestions.mark_closed');
//End Suggestions Routes

Route::get('/delete_media_id/{media_id}/from/{class_name}/with_id/{model_id}', 'MiscController@deleteMediaFromModel')->name('delete.media.id');

Route::get('/set_main_media/{media_id}/for/{class_name}/with_id/{model_id}', 'MiscController@setMainMediaForModel')->name('set.main.media');

// End Dishes Routes

Route::get('/media', 'MediaController@index')->name('media.index');
Route::get('/media/create', 'MediaController@create')->name('media.create');
Route::post('/media', 'MediaController@store')->name('media.store');
Route::delete('/media/{media}', 'MediaController@delete')->name('media.delete');

// Statistics
Route::get('/statistics/{type}', 'StatisticsController@index')->name('statistics');
Route::post('/statistics/dt/{type}', 'StatisticsController@dt')->name('statistics.dt');

// Keywords Research
if (config('base.KeyWordsResearch.status')) {
    Route::resource('keywords', 'KeywordsResearchController', ['except' => ['show', 'index']]);
    Route::post('keywords/dt/{type?}', 'KeywordsResearchController@getDtKeywords')->name('keywords.dt');
    Route::get('keywords/{type?}', 'KeywordsResearchController@index')->name('keywords.index');
}
