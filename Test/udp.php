<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */

\Swoole\Coroutine::create(function () {
    $client = new \Swoole\Coroutine\Client(SWOOLE_UDP);

    $sendBody = json_encode([
        'controller' => 'Index',
        'action' => 'index'
    ]);

    $client->sendto('127.0.0.1', 9511, $sendBody);


    $recvBody = $client->recv();
    var_dump(json_decode($recvBody));
});
