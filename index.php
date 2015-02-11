<html lang="de">
<head>
    <meta charset="utf-8">

    <title>
        Real time output
    </title>

    <!-- Le styles -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet"
          type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <style>
        body {
            padding-top: 70px;
            font-family: Lato, sans-serif;
        }

        p {
            font-size: 1.1em;
        }
    </style>
</head>

<body>

<div class="container">
    Hello world
    <form id="action-form" action="command.php" method="POST">
        <input class="btn btn-success" type="submit" value="Start">
    </form>
    Output:
    <div id="output" class="well">

    </div>
    <div id="result">

    </div>
    <div id="status">

    </div>
</div>

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script>
    (function ($) {
        $('form').on('submit', function (e) {
            e.preventDefault();

            $('#result').html('<span id="status" class="label label-warning working">Working...</span>');
            var formSerialized = $(this).serialize();
            var xhr = new XMLHttpRequest();
            xhr.open('POST', $(this).attr('action'), true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send(formSerialized);
            var timer;
            var currentPos = 0;
            timer = window.setInterval(function () {
                var serverOutput = xhr.responseText.substring(currentPos);
                var $result = $('#output');
                serverOutput = serverOutput.replace(/\[4m|\[24m|\[36m|\[22;39m|°°filler°°|\[31m.\[0m/g, '');
                serverOutput = serverOutput.replace(/\[32m|\[0;32m|\[31m.\[32m|\[0m.\[32m/g, '<span style="color: green">');
                serverOutput = serverOutput.replace(/\[33m|\[1;33m|\[0;33m|\[0;35m/g, '<span style="color: darkorange">');
                serverOutput = serverOutput.replace(/\[31m|\[0;31m/g, '<span style="color: red;">');
                serverOutput = serverOutput.replace(/\[2;37m/g, '<span style="color: grey;">');
                serverOutput = serverOutput.replace(/\[34m|\[0;36m/g, '<span style="color: dodgerblue;">');
                serverOutput = serverOutput.replace(/\[0m|\[0;49m|\[39m/g, '</span>');

                if (serverOutput.length) {
                    $result.append(serverOutput + '<br>');
                    $(window).scrollTop($('#result').position().top);
                    $('#result').appendTo($result);
                }
                currentPos = xhr.responseText.length;

                if (xhr.readyState == XMLHttpRequest.DONE) {
                    window.clearTimeout(timer);
                    $('#status').removeClass('working label-warning label-important');
                    if ($result.text().match(/Error:/)) {
                        $('#status').addClass('label-important').text('Completed with errors!');
                    }
                    else {
                        $('#status').addClass('label-success').text('Completed.');
                    }
                    // updateServerStatus();
                }
            }, 50);
        });
    })(jQuery);
</script>

</body>
</html>

