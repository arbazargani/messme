<?php
    require 'node.php';
    if(!isset($_COOKIE['UNIQUE_IDENTIFIER'])) {
        $unique = hash('sha256', time().$_SERVER['REMOTE_ADDR']);
        setcookie('UNIQUE_IDENTIFIER', $unique, time()+86400, '/');
        header('location: '.$_SERVER['PHP_SELF']);
    }
?>
<!DOCTYPE html>
<head>
    <title>Message Me!</title>

    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.7/dist/css/uikit-rtl.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.7/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.7/dist/js/uikit-icons.min.js"></script>

    <!-- Pusher -->
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>

</head>
<body>
<div class="uk-section uk-padding">
    <div class="uk-card uk-card-secondary uk-card-body uk-border-rounded">
        <p class="uk-h3 uk-card-title">
            Chat <br>
            <span style="font-size: 9px"><strong><i>ID: </i></strong></span><span style="font-size: 7px;"><?php echo $_COOKIE['UNIQUE_IDENTIFIER']; ?></span>
        </p>
        <hr class="uk-divider-small">
        <div class="uk-container uk-border-rounded uk-margin uk-grid-small uk-overflow-auto" id="chatroom-area" style="padding: 10px; border: 1px solid #9e9e9e; height: 300px" uk-grid>
            <!-- messages will appear here. -->
        </div>
        <div uk-grid>
            <div style="width: 70%">
                <textarea class="uk-text-area uk-width-1-1" rows="4" type="text" id="message"></textarea>
            </div>
            <div class="uk-width-expand">
                <a onclick="send()" class="uk-button uk-button-primary">send</a>
            </div>
        </div>
    </div>
</div>
<script>
    function updateScroll(){
        var element = document.getElementById("chatroom-area");
        element.scrollTop = element.scrollHeight;
    }

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    var unique_id = "<?php echo $_COOKIE['UNIQUE_IDENTIFIER']; ?>";

    var pusher = new Pusher('51256090141dd467b768', {
        cluster: 'ap1',
        forceTLS: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        //alert(JSON.stringify(data));
        if (unique_id != -1 && unique_id === data['id']) {
            // own message
            document.getElementById('chatroom-area').innerHTML += '<div class="uk-width-1-1"> <p style="border-radius: 10px 10px 1px 10px;color: darkgray"  class="uk-float-right uk-padding-small uk-margin-remove uk-background-primary"> <span style="color: #f4f4f4; font-size: 17px">' + data['message'] + '</span> <br> <a style="color: #172e2f; font-size: 12px">' + data['date'] + '| alireza</a> <span uk-icon="check"></span> </p> </div>';
            updateScroll();
        }
        if (unique_id != -1 && unique_id !== data['id']) {
            // others message
            document.getElementById('chatroom-area').innerHTML += '<div class="uk-width-1-1"> <p style="border-radius: 10px 10px 10px 1px;color: darkgray"  class="uk-float-left uk-padding-small uk-margin-remove uk-background-default"> <span style="color: #333333; font-size: 17px">' + data['message'] + '</span> <br> <a style="color: cadetblue; font-size: 12px">' + data['date'] + '| alireza</a> </p> </div>';
            updateScroll();

        }
    });
</script>

<script>
    function send() {
        var id = "<?php echo $_COOKIE['UNIQUE_IDENTIFIER']; ?>";
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // document.getElementById("demo").innerHTML = this.responseText;
            }
        };

        var message = document.getElementById('message').value;
        xhttp.open("POST", "node.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("message="+message+"&id="+id);
        var message = document.getElementById('message').value = '';


    }
</script>
</body>
</html>