<?php

namespace ShockingHuman\ServerTools\Operations;

use ShockingHuman\ServerTools\Operations\Config;

class PingNet
{
    protected static $input;
    protected static $output;

    public function __construct($input, $output)
    {
        self::$input = $input;
        self::$output = $output;
    }

    public static function check()
    {
        $output = self::$output;
//        Check url list once
        $output->writeln('INFO: Checking URLs');
        $results = CheckURL::checkMany(file(Config::return('url_list')));
        $error_messages = [];
        $error_report = '';

        foreach ($results as $result){
            if ($result->statusCode !== 200){
                $error_messages[] = "$result->statusCode - $result->url";
                $error_report .= "$result->statusCode - $result->url\n";
            }
        }

//        Report Errors
        if (count($error_messages) > 0){
//            By Screen
            $output->writeln(count($error_messages)." ERRORS:");
            $output->write($error_messages);
            $output->writeln('');
            $output->writeln('INFO: Reporting Errors');
//            By Email
            $mail_status = Mail::send(Config::return('mail_api_key'), Config::return('report_to'), 'ERROR Report', $error_report);
            if ($mail_status !== 200){
                $output->writeln("ERROR: REPORT FAILED - $mail_status");
            } else {
                $output->writeln("INFO: Report Sent");
            }
//            TODO: Report By Log Files
        } else {
            $output->writeln("INFO: Everything is ok!");
        }
    }

    public static function daemon()
    {
        while(true){
//            Memory usage control
            $mem_allocated = round(memory_get_usage(1) / 1024);
//            $mem_used = round(memory_get_usage() / 1024);
            if ($mem_allocated > 50000){
//                Report and Restart
                Mail::send(Config::return('mail_api_key'),
                    Config::return('report_to'),
                    'PingNet Memory Usage Report',
                    $mem_allocated);
                exit(exec('systemctl restart pingnet'));
            }
//            Check URLs
            self::check();
            sleep(Config::return('sleep_time'));
        }
    }
}