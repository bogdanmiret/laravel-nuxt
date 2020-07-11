@extends('layouts.admin')

@section('content')
    <div class="nav-tabs-custom" data-example-id="togglable-tabs">
        <ul id="settingsTabs" class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#variables" id="variables-tab" role="tab" data-toggle="tab" aria-controls="variables" aria-expanded="true">{{trans('admin/config_page.variables')}}</a></li>
                <li><a href="#functions" id="variables-tab" role="tab" data-toggle="tab" aria-controls="variables" aria-expanded="true">{{trans('admin/config_page.functions')}}</a></li>
            @if(isset($envConfig))
            <li><a href="#env" id="variables-tab" role="tab" data-toggle="tab" aria-controls="variables" aria-expanded="true">{{trans('admin/config_page.env')}}</a></li>
            @endif
        </ul>


        <div id="myTabContent" class="tab-content main-tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="variables" aria-labelledby="variables-tab">

                    {!! Form::open(['url' => '']) !!}
                    <div class="tabbable pills">
                        <div class="tab-content">
                            {{getConfigVariables(config('base'))}}
                        </div>
                    </div>
                    <div class="box-footer">
                        {{Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit'), 'disabled' => 'disabled'])}}
                    </div>
                    {!! Form::close() !!}
                </div>
            <div role="tabpanel" class="tab-pane fade" id="functions" aria-labelledby="functions-tab">
                <h3 class="conf_page_subtitle">{{trans('admin/config_page.php_func_title')}}</h3>
                <div class="row">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">setting($key)</code>
                        <span>- Returns the value of a setting table row</span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">generateSlug($data = [])</code>
                        <span>- Generates a unique slug</span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">lurl($page)</code>
                        <span>- Returns localized URL</span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">contact_details($value)</code>
                        <span>- Returns email related settings value, with config fallback</span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">getRoles($type = false)</code>
                        <span>- Returns a list of role names</span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">getRoleName($type = false)</code>
                        <span>- Returns a role's display name</span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">defaultLocale()</code>
                        <span>- Returns default locale set in config</span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">keyPrefixArray($keyprefix, Array $array)</code>
                        <span>- </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">storage($value)</code>
                        <span>- Returns path to storage folder </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">language_switcher()</code>
                        <span>- Display a dropdown with the site's languages </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">get_user_location($what = false,$default = true)</code>
                        <span>- Returns user's location details by IP. Calling without parameters returns all the details as array. First parameter <code>$what</code> can have the following values: <i>false (returns all the location details as array), ip, isoCode, place_id, country, city, state, postal_code, lat, lon, timezone, continent, default (this return true if the values are from the config fallback)</i>. Second parameter <code>$default</code> if missing or called with <i>true</i>,  function will always return a value having as fallback the default values from config; calling with <i>false</i> returns empty if can't locate user by IP (database, fallback external api) </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">url_exists($url)</code>
                        <span>- Returns <i>true</i> if url exists (get_headers http response code is 200), returns <i>false</i> otherwise  </span>
                    </div>
                </div>
                <h3 class="conf_page_subtitle">{{trans('admin/config_page.js_func_title')}}</h3>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">getLatLongByDeviceOrIp(callback)</code>
                         <span>- Returns location coordinates (lat, lng) in an array as the callback function's parameter. Example:
                             <code><pre>
                                        $(function(){
                                            getLatLongByDeviceOrIp(function(result){
                                                var lat = result[0];
                                                var lng = result[1];
                                                console.log(lat + " - " + lng);
                                            });
                                        });
                              </pre></code>
                         </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">changeElementStatus(elem)</code>
                        <span>- Changes a datatable row's status (active / inactive) </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">deleteElement(elem)</code>
                        <span>- Delete a datatable row </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">deleteElement(elem)</code>
                        <span>- Delete a datatable row </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">changeAccountRole(elem)</code>
                        <span>- Changes a user account's role </span>
                    </div>
                </div>
                <div class="row func_wrapper">
                    <div class="col-sm-12">
                        <code class="admin_conf_func">updateAccountRole(elem)</code>
                        <span>- Changes a user account's role </span>
                    </div>
                </div>
            </div>
            @if(isset($envConfig))
            <div role="tabpanel" class="tab-pane fade" id="env" aria-labelledby="env-tab">
                <h3 class="conf_page_subtitle">{{trans('admin/config_page.env_title')}}</h3>
                {!! Form::open(array('url' => route('admin.config-page.save_env' ), 'method' => 'post')) !!}

                @foreach($envConfig as $envKey => $envVal)
                    <div @if(!in_array($envKey,config('base.env_config'))) class="hidden"@endif>
                        <label>
                            <h5 class="conf_page_subtitle">{{$envKey}}</h5>
                        </label>
                        <div class="form-group">
                            {{Form::text($envKey, $envVal === false ? 0 : $envVal, ["class" => "form-control"])}}
                        </div>
                    </div>
                @endforeach
                <div class="box-footer">
                    {{Form::submit(trans('global.btn.submit'), ['class' => config('base.btn.submit')])}}
                </div>
                {!! Form::close() !!}

            </div>
            @endif
        </div>

    </div>
@endsection
