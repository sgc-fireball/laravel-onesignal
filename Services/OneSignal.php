<?php

namespace HRDNS\LaravelPackages\OneSignal\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class OneSignal extends Client implements OneSignalInterface
{

    /**
     * @see config/onesignal.php
     * @var array
     */
    private $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['base_uri'] = OneSignalInterface::API_URL;
        $config['headers'] = $config['headers'] ?? [];
        $config['headers']['User-Agent'] = 'OneSignal/1.0 '.\GuzzleHttp\default_user_agent();
        $config['headers']['Content-Type'] = 'application/json; charset=utf-8';
        $config['headers']['Accept'] = 'application/json';
        parent::__construct($config);
    }

    /**
     * @param array $config
     * @return OneSignalInterface
     */
    public function setConfig(array $config): OneSignalInterface
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#create-notification
     * @param array $fields
     * @return Collection
     */
    public function createNotification(array $fields): Collection
    {
        $fields['app_id'] = $this->config['app_id'];

        return $this->_request('POST', 'notifications', ['json' => $fields]);
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#cancel-notification
     * @param string $id
     * @return Collection
     */
    public function cancelNotification(string $id): Collection
    {
        return $this->_request(
            'DELETE',
            sprintf('notifications/%s?%s', $id, http_build_query(['app_id' => $this->config['app_id']]))
        );
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#view-devices
     * @param int $limit
     * @param int $offset
     * @return Collection
     */
    public function viewDevices(int $limit = 50, int $offset = 0): Collection
    {
        return $this->_request(
            'GET',
            sprintf(
                'players?%s',
                http_build_query(
                    [
                        'app_id' => $this->config['app_id'],
                        'limit' => $limit,
                        'offset' => $offset,
                    ]
                )
            )
        );
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#view-device
     * @param string $id
     * @return Collection
     */
    public function viewDevice(string $id): Collection
    {
        return $this->_request(
            'GET',
            sprintf(
                'players/%s?%s',
                $id,
                http_build_query(
                    [
                        'app_id' => $this->config['app_id'],
                    ]
                )
            )
        );
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#add-a-device
     * @param string $identifier
     * @param int $deviceType
     * @param array $fields
     * @return Collection
     */
    public function addDevice(string $identifier, int $deviceType, array $fields = []): Collection
    {
        $fields['identifier'] = $identifier;
        $fields['device_type'] = $deviceType;

        return $this->_request(
            'POST',
            sprintf(
                'players?%s',
                http_build_query(
                    [
                        'app_id' => $this->config['app_id'],
                    ]
                )
            ),
            ['json' => $fields]
        );
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#edit-device
     * @param string $id
     * @param array $fields
     * @return Collection
     */
    public function editDevice(string $id, array $fields = []): Collection
    {
        $fields['app_id'] = $this->config['app_id'];

        return $this->_request(
            'PUT',
            sprintf('players/%s?%s', $id),
            ['json' => $fields]
        );
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#csv-export
     * @return Collection
     */
    public function csvExport(): Collection
    {
        return $this->_request(
            'POST',
            sprintf(
                'players/csv_export?%s',
                http_build_query(
                    [
                        'app_id' => $this->config['app_id'],
                    ]
                )
            )
        );
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#view-notification
     * @param string $id
     * @return Collection
     */
    public function viewNotification(string $id): Collection
    {
        return $this->_request(
            'GET',
            sprintf(
                'notification/%s?%s',
                $id,
                http_build_query(
                    [
                        'app_id' => $this->config['app_id'],
                    ]
                )
            )
        );
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#view-notifications
     * @param int $limit
     * @param int $offset
     * @return Collection
     */
    public function viewNotifications(int $limit = 50, int $offset = 0): Collection
    {
        return $this->_request(
            'GET',
            sprintf(
                'notifications?%s',
                http_build_query(
                    [
                        'app_id' => $this->config['app_id'],
                        'limit' => $limit,
                        'offset' => $offset,
                    ]
                )
            )
        );
    }

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#track-open
     * @param string $id Notification id
     * @param array $fields
     * @return Collection
     */
    public function trackOpen(string $id, array $fields = []): Collection
    {
        $fields['app_id'] = $this->config['app_id'];
        $fields['opened'] = isset($fields['opened']) ? $fields['opened'] : true;

        return $this->_request(
            'PUT',
            sprintf('notifications/%s', $id),
            ['json' => $fields]
        );
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return Collection
     */
    private function _request(string $method, string $url, array $options = []): Collection
    {
        $options['headers'] = $options['headers'] ?? [];
        $options['headers']['Authorization'] = $options['headers']['Authorization'] ?? 'Basic '.$this->config['rest_api'];
        $response = $this->request($method, self::API_URL.'/'.$url, $options);
        $body = $response->getBody()->getContents();

        return collect(json_decode($body, true));
    }

}
