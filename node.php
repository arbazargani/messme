<?php
    require __DIR__ . '/vendor/autoload.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        $pusher = new Pusher\Pusher(
            '51256090141dd467b768',
            '120563befa3333df76f9',
            '975289',
            $options
        );

        $data['message'] = $_POST['message'];
        $data['id'] = $_POST['id'];
        $data['date'] = date("H:i");
        $pusher->trigger('my-channel', 'my-event', $data);
    }

?>