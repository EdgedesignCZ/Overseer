<?php

namespace Edge\Overseer;

/**
 * @author VeN <vaclav.novotny@edgedesign.cz>
 */
class FileChecker
{

    const BACKUP_AFFIX = '.overseer_backup';

    private $emails = array();

    private $name;

    private $nameOfFileToCheck;

    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

    public function __construct($name, $fileName, array $emails)
    {
        $this->name = $name;
        $this->nameOfFileToCheck = $fileName;

        foreach ($emails as $email) {
            $this->emails[] = str_replace("\n", '', $email);
        }
    }

    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

    public function check()
    {
        $diff = null;

        if (!file_exists($this->nameOfFileToCheck)) {
            return;
        }

        $backupFileName = $this->generateBackupNameForFile($this->nameOfFileToCheck);

        if (!file_exists($backupFileName)) {
            $diff = file_get_contents($this->nameOfFileToCheck);
        } else {
            ob_start();
            passthru("diff $backupFileName $this->nameOfFileToCheck | grep '>'");
            $diff = ob_get_clean();
        }

        if ($diff) {
            $this->sendDiff($diff);
        }

        copy($this->nameOfFileToCheck, $backupFileName);
    }

    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

    private function sendDiff($diff)
    {
        $contentToBeSend = sprintf("%s:\n%s", $this->nameOfFileToCheck, $diff);

        foreach ($this->emails as $email) {
            mail($email, $this->name, $contentToBeSend);
        }
    }

    private function generateBackupNameForFile($filename)
    {
        return $filename . self::BACKUP_AFFIX;
    }

}
