<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#"/>

    <link href="//fonts.googleapis.com/css?family=Nunito:300,400,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link href="{{ asset('assets/admin/plugins/contentbuilder2/contentbuilder/contentbuilder.css') }}"
          rel="stylesheet"/>
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet"/>

    <style>
        .container {
            margin: 120px auto;
            width: 100%;
            padding: 0 35px;
            box-sizing: border-box;
        }

        img {
            max-width: 100%;
        }

        .row {
            margin: 15px 0px;
        }
    </style>
</head>
<body>

<input type="hidden" id="content" name="content" value="">

<!-- This is just a sample content. Content can be loaded from a database. -->
<div class="posts post-detail">
    <div class="post-content">
        <div class="container" id="edited_content">
            {!! $description !== null ? $description : '' !!}
        </div>
    </div>
</div>

<div class="is-tool"
     style="position:fixed;width:210px;height:50px;border:none;top:30px;bottom:auto;left:auto;right:30px;text-align:right;display:block">
    <button id="btnViewSnippets" class="classic" style="width:70px;height:50px;">+ Add</button>
    <button id="btnViewHtml" class="classic" style="width:70px;height:50px;">HTML</button>
</div>

<script src="{{ asset('assets/admin/plugins/contentbuilder2/contentbuilder/contentbuilder.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/contentbuilder2/assets/minimalist-blocks/content.js') }}" type="text/javascript"></script>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function(){ 
    var obj = new ContentBuilder({
        container: '.container',
        row: 'row',
        cols: ['col-md-1', 'col-md-2', 'col-md-3', 'col-md-4', 'col-md-5', 'col-md-6', 'col-md-7', 'col-md-8', 'col-md-9', 'col-md-10', 'col-md-11', 'col-md-12'],
        framework: 'bootstrap',
        fontAssetPath: "{{ url('/assets/admin/plugins/contentbuilder2/assets/fonts/') }}/",
    });
    // obj.applyBehavior();
}, false);
</script>

</body>
</html>