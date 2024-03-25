<?php

namespace chatServer\Websocket;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class SistemaChat implements MessageComponentInterface
{

    protected $cliente;

    public function __construct()
    {
        $this->cliente = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->cliente->attach($conn);

        echo "nova conexão: {$conn->resourceId} \n\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->cliente as $cliente) {
            if ($from !== $cliente) {
                $cliente->send($msg);
            }
        }

        echo "Usuario: {$from->resourceId} enviou uma mensagem \n\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->cliente->detach($conn);

        echo "Usuario desconetou: {$conn->resourceId} \n\n";
    }


    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $conn->close();

        echo "Ocorreu um erro: {$e->getMessage()} \n\n";
    }
}
