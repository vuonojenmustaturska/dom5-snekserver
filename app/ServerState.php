<?php

namespace Snek;

class ServerState
{
    public $era = 0;
    public $nations = [];
    public $mods = [];
    private $address = '';
    private $port = 0;
    public $status = 0;
    public $name = 0;
    public $tth = 0;
    public $turn = 0;

    private $socket;

    public static $NationStatus_Human = 1;
    public static $NationStatus_AI = 2;
    public static $NationStatus_Independent = 3;
    public static $NationStatus_Closed = 253;
    public static $NationStatus_DefeatedThisTurn = 254;
    public static $NationStatus_Defeated = 255;

    function __construct($address, $port)
    {
        $this->address = $address;
        $this->port = $port;
    }

    function connect()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($this->socket === false) {
            throw new Exception("socket_create() failed: reason: " . socket_strerror(socket_last_error()));
        }

        try 
        {
            $result = socket_connect($this->socket, $this->address, $this->port);
        }
        catch (Exception $e)
        {
        }
        
        if ($result === false) {
            throw new Exception("socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($this->socket)) . "\n");
        } 
    }

    function fetch()
    {
        if (!$this->socket)
            $this->connect();


        $message = "\x66\x48\x01\x00\x00\x00\x03";

        socket_write($this->socket, $message, strlen($message));

        $data = socket_read($this->socket, 512);

        $data = substr($data, 10);

        $data = gzuncompress($data);

        $arr = unpack('C6Message/Z*Name', $data);


        $this->name = $arr['Name'];

        $data = substr($data, 6+strlen($arr['Name']));

        $arr2 = unpack('CEra/C5/VTTH/C2Junk/C250NationStatus/C250Submitted/C250Connected/VTurn/CClientCanStart', $data);
        //var_dump($arr2['Junk3']);
        $this->era = $arr2['Era'];
        $this->status = $arr['Message6'];
        $this->turn = $arr2['Turn'];
        $this->tth = $arr2['TTH'];

        for ($i = 5; $i < 201; $i++)
        {
            if ($arr2['NationStatus'.$i] != 0)
            {
                $this->nations[$i-1] = ['status' => $arr2['NationStatus'.$i], 'submitted' => $arr2['Submitted'.$i],'connected' => $arr2['Connected'.$i]];
            }
        }
            
    }

    function getMods()
    {
        if (!$this->socket)
            $this->connect();

        $message = "\x66\x48\x01\x00\x00\x00\x11";

        socket_write($this->socket, $message, strlen($message));

        $data = socket_read($this->socket, 512);


        $header = unpack('C7/vModCount', $data);

        $moddata = substr($data, 9);

        if ($header['ModCount'] != 65535)
        {
            for ($i = 0; $i < ($header['ModCount']+1)*2; $i++)
            {
                $mod = unpack('v2Version/Z*Name', $moddata);
                $moddata = substr($moddata, 4+strlen($mod['Name']));
                $modfooter = unpack('Z*', $moddata);

                $moddata = substr($moddata, strlen($modfooter[1]));
                if (stripos(strrev($mod['Name']), 'md.') === 0)
                    $this->mods[] = $mod;
            }
        }




        /* $data = substr($data, 10);


        $data = gzuncompress($data);

        var_dump(bin2hex($data)); */
    }
}
