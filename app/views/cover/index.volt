<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#db5945">
    <title>Cover</title>
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="/site.min.css" rel="stylesheet">
</head>
<body class="home-template">
<header class="site-header jumbotron">
    <div class="container">
        <div class="row">
            <div class="col-xs-12"><h1>Cover</h1>
                <div class="form-group">
                    <input type="text" class="form-control search" placeholder="请输入链接" id="input" name="url"
                           autocomplete="off" spellcheck="false">
                    <i class="fa fa-search" onclick="search($('#input').val());"></i>
                </div>
            </div>
        </div>
    </div>
</header>
<div id="result" class="container" style="text-align: center;">

</div>
<a href="https://github.com/hldh214/cover"><img style="position: absolute; top: 0; right: 0; border: 0;"
                                              src="https://camo.githubusercontent.com/652c5b9acfaddf3a9c326fa6bde407b87f7be0f4/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6f72616e67655f6666373630302e706e67"
                                              alt="Fork me on GitHub"></a>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script>
    function search(url) {
        $('#result').html('<h1>Loading~</h1><div class="progress progress-striped active"><div class="progress-bar progress-bar-info" style="width: 100%;"></div></div>');
        $.post('cover', {'url': url}, function (data) {
            var html = ''
            if (data) {
                html = '<a href="' + data + '" target="_blank"><img class="img-responsive center-block" src="' + data + '" alt="cover"></a>';
            } else {
                html = '<h1 class="text-danger">Not Found</h1>'
            }

            $('#result').html(html);
        });
    }

    $('#input').keydown(function (event) {
        var url = $('#input').val();
        if (event.keyCode == 13) {
            search(url);
        }
    });
</script>
</body>
</html>