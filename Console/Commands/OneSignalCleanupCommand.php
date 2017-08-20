<?php

namespace HRDNS\LaravelPackages\OneSignal\Console\Commands;

use GuzzleHttp\Client;
use HRDNS\LaravelPackages\OneSignal\Models\OneSignalDevice;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class OneSignalCleanupCommand extends Command
{

    /** @var string */
    protected $signature = 'hrdns:onesignal:cleanup';

    /** @var string */
    protected $description = 'Cleanup invalid push tokens.';

    /**
     * @return int
     */
    public function handle(): int
    {
        $json = $this->requestCsvFile();
        $path = $this->downloadCsv($json['csv_file_url']);
        $path = $this->uncompressCsv($path);
        $this->workOnCsv($path);
        return 0;
    }

    /**
     * @return Collection
     */
    private function requestCsvFile(): Collection
    {
        $this->getOutput()->write('[....] ' . __METHOD__);
        $json = onesignal()->csvExport();
        $this->getOutput()->write("\r[DONE]\n");
        return $json;
    }

    /**
     * @param string $url
     * @return string
     */
    private function downloadCsv(string $url): string
    {
        $this->getOutput()->write('[....] ' . __METHOD__);
        $file = tempnam(sys_get_temp_dir(), 'csvgz');
        #$url = str_replace('https://','http://',$url);

        sleep(5); // amazon and one signal sucks ...

        (new Client())->request('GET', $url, ['sink' => $file]);
        $this->getOutput()->write("\r[DONE]\n");
        return $file;
    }

    /**
     * @param string $path
     * @return string
     */
    private function uncompressCsv(string $path): string
    {
        $this->getOutput()->write('[....] ' . __METHOD__);
        file_put_contents($path, gzdecode(file_get_contents($path)));
        $this->getOutput()->write("\r[DONE]\n");
        return $path;
    }

    /**
     * @param $path
     */
    private function workOnCsv($path)
    {
        $this->getOutput()->write('[....] ' . __METHOD__);
        $fp = fopen($path, 'r');
        $header = null;
        while (!feof($fp) && $line = fgetcsv($fp, null, ',')) {
            if ($header === null) {
                $header = $line;
                continue;
            }
            $row = array_combine($header, $line);
            $this->workOnRow($row);
        }
        $this->getOutput()->write("\r[DONE]\n");
    }

    /**
     * id
     * identifier
     * session_count
     * language
     * timezone
     * game_version
     * device_os
     * device_type
     * device_model
     * ad_id
     * tags
     * last_active
     * playtime
     * amount_spent
     * created_at
     * invalid_identifier
     * badge_count
     */
    private function workOnRow(array $row)
    {
        if ($row['invalid_identifier'] !== 'f') {
            return;
        }
        /** @var OneSignalDevice|null $pushToken */
        if ($pushToken = OneSignalDevice::where('one_signal_id', '=', $row['id'])->first()) {
            $pushToken->delete();
        }
    }

}
