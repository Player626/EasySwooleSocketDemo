<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */

\Swoole\Coroutine::create(function () {
    $client = new \Swoole\Coroutine\Http\Client('127.0.0.1', 9512);
    $ret = $client->upgrade('/');

    if ($ret) {
        $sendBody = json_encode([
            'controller' => 'Index',
            'action' => 'index'
        ]);
        $client->push($sendBody);

        var_dump($client->recv());
    }
});
