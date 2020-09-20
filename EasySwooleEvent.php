<?php


namespace EasySwoole\EasySwoole;


use App\Parser\TcpParser;
use EasySwoole\Command\CommandManager;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\Socket\Bean\Response;
use EasySwoole\Socket\Client\Tcp;
use EasySwoole\Socket\Dispatcher;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // tcp
        if (CommandManager::getInstance()->getOpt('mode') === 'tcp') {
            $config = new \EasySwoole\Socket\Config();
            $config->setType($config::TCP);
            $config->setParser(TcpParser::class);
            $dispatcher = new Dispatcher($config);
            $config->setOnExceptionHandler(function (\Swoole\Server $server, \Throwable $throwable, string $raw, Tcp $client, Response $response) {
                $response->setMessage('system error!');
                $response->setStatus($response::STATUS_RESPONSE_AND_CLOSE);
            });
            $register->set($register::onReceive, function (\Swoole\Server $server, int $fd, int $reactorId, string $data) use ($dispatcher) {
                $dispatcher->dispatch($server, $data, $fd, $reactorId);
            });
        }
    }
}