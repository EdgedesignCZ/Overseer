<?php

namespace Edge\Overseer;

class FileChecker
{

    const BACKUP_AFFIX = '.overseer_backup';
    const BACKUP_DIR = '/.overseer/';

    private $emails = array();
    private $grep;
    private $name;
    private $nameOfFileToCheck;

    public function __construct($name, $fileName, array $emails, array $ignore = array())
    {
        $this->name = $name;
        $this->nameOfFileToCheck = $fileName;
        $this->grep = new Diff($ignore);

        foreach ($emails as $email) {
            $this->emails[] = str_replace("\n", '', $email);
        }
    }

    public function check()
    {
        if (!file_exists($_SERVER['HOME'] . self::BACKUP_DIR)) {
            mkdir($_SERVER['HOME'] . self::BACKUP_DIR);
        }
        if (!file_exists($this->nameOfFileToCheck)) {
            return;
        }
        $backupFileName = $this->generateBackupNameForFile($this->nameOfFileToCheck);
        $diff = $this->grep->diffFiles($this->nameOfFileToCheck, $backupFileName);
        if ($diff) {            
            $this->sendDiff($diff);
        }
        copy($this->nameOfFileToCheck, $backupFileName);
    }

    private function sendDiff($diff)
    {
        $contentToBeSend = sprintf("%s:\n%s", $this->nameOfFileToCheck, $diff);

        foreach ($this->emails as $email) {
            mail($email, $this->name, $contentToBeSend);
        }
    }

    private function generateBackupNameForFile($filename)
    {
        $hashDir = $_SERVER['HOME'] . self::BACKUP_DIR . md5($filename);

        if (!file_exists($hashDir)) {
            mkdir($hashDir);
        }

        return $hashDir . '/' . basename($filename) . self::BACKUP_AFFIX;
    }
}
