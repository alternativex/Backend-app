<?php

use Illuminate\Console\Command;

abstract class LockedConcurrentCommand extends Command {

    private $uniqueRunId;

    public function fire()
    {
        $this->start();
        if ($this->isRunByThisProcess()) {
            $this->fireLocked();
        } else {
            $this->log("LOCK -> another instance is already running");
        }
        $this->end();
    }

    protected function start()
    {
        $this->uniqueRunId = $this->unique();
        $this->log("START");
        if (!$this->isRunning()) {
            $this->createLockFile();
        }
    }

    public static function unique()
    {
        return self::generateToken(10);
    }

    public static function generateToken($length)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    protected function log($message)
    {
        echo date("Y-m-d H:i:s") . " " . $this->commandId() . $this->uniqueRunId . " -> " . $message . "\n";
    }

    public function commandId()
    {
        return str_replace([':', '-'], "_", $this->name);
    }

    /**
     * returns trus if there is a file that has $this->getCronjobId() and .lock in name
     * @return bool
     */
    protected function isRunning()
    {
	$this->log($this->getTempDir() . $this->commandId() . "_*.lock");
        $filenames = glob($this->getTempDir() . $this->commandId() . "_*.lock");
        if ($filenames != false && count($filenames) > 0) {
            return true;
        }
        return false;
    }

    protected function getTempDir()
    {
        return storage_path('temp') . "/";
    }

    protected function createLockFile()
    {
        $this->log("LOCK -> create lock file");
        $lockFile = $this->getLockFileName();
        $fp       = fopen($lockFile, "a+");
        fclose($fp);
        $this->log("LOCK -> successfully created lock file " . $lockFile);
    }

    protected function getLockFileName()
    {
        $tempDir = $this->getTempDir();
        chmod($tempDir, 0777);
        return $tempDir . $this->commandId() . "_" . $this->uniqueRunId . ".lock";
    }

    protected function isRunByThisProcess()
    {
        //returns true if the .lock file has this->uniqueRunId
        return file_exists($this->getLockFileName());
    }

    public abstract function fireLocked();

    protected function end()
    {
        if ($this->isRunByThisProcess()) {
            $this->deleteLockFile();
        }
        $this->log("END");
    }

    protected function deleteLockFile()
    {
        $this->log("LOCK -> delete lock file");
        $lockFile = $this->getLockFileName();
        unlink($lockFile);
        $this->log("LOCK -> successfully deleted lock file " . $lockFile);
    }
}
