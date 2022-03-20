<?php
// namespace log;

class Logger
{
    const DEFAULT_LOG_FILE = 'log';

    const FILE_EXTENSION = 'txt';

    const DEFAULT_LOG_FOLDER = 'logs';

    private ?string $logFile = null;

    private string $logFolder;

    private mixed $logInfo;

    private ?string $date;

    public function __construct(?string $logFolder = null)
    {
        $this->logFolder = $logFolder ?: self::DEFAULT_LOG_FOLDER;
        $this->date = null;
    }

    public function getLogFile(): ?string
    {
        return $this->logFile;
    }

    public function setLogFile(?string $logFile): void
    {
        $this->logFile = $logFile;
    }

    public function getLogFolder(): string
    {
        return $this->logFolder;
    }

    public function setLogFolder(string $logFolder): void
    {
        $this->logFolder = $logFolder;
    }

    public function getLogInfo(): mixed
    {
        return $this->LogInfo;
    }

    public function setLogInfo(mixed $logInfo): void
    {
        $this->logInfo = $logInfo;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function addLog(mixed $logInfo = null, ?string $logFile = null): void
    {
        $date = $this->date ?: date('d.m.Y H:i:s');
        // print_r($date);
        $file =
            $this->logFolder .
            '/' .
            ($logFile ?? $this->logFile ?? self::DEFAULT_LOG_FILE) .
            '.' .
            self::FILE_EXTENSION;
        // print_r($file);
        file_put_contents(
            $file,
            $date . ' ==> ' . print_r($logInfo ?: $this->logInfo, true) . "\n",
            FILE_APPEND
        );
    }
}
