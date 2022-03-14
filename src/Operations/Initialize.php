<?php

namespace ShockingHuman\ServerTools\Operations;

use ShockingHuman\ServerTools\Operations\Config;

class Initialize extends File
{
    public static function pingnet($user = "root", $root = __DIR__.'/../..')
    {
//        Create URL List
        File::process("$root/url_list", ["webdevjm.com"]);
//        Create systemd service file
        File::process("$root/pingnet.service", [
            "[Unit]",
            "Description=Check URL Status",
            "After=network.target",
            "StartLimitInterval=0",
            "[Service]",
            "Type=simple",
            "Restart=always",
            "RestartSec=1",
            "User=$user",
            "ExecStart=$root/bin/pingnet check --daemon",
            "[Install]",
            "WantedBy=multi-user.target"
        ]);
//        Link service file
        if(!file_exists("/etc/systemd/system/pingnet.service")) {
            exec("sudo ln -s $root/pingnet.service /etc/systemd/system/");
        }

    }
}