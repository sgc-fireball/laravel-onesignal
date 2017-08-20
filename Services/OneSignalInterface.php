<?php

namespace HRDNS\LaravelPackages\OneSignal\Services;

use Illuminate\Support\Collection;

interface OneSignalInterface
{

    const API_URL = "https://onesignal.com/api/v1";

    const DEVICE_TYPE_IOS = 0;
    const DEVICE_TYPE_ANDROID = 1;
    const DEVICE_TYPE_AMAZON = 2;
    const DEVICE_TYPE_WINDOWS_PHONE_MPNS = 3;
    const DEVICE_TYPE_CHROME_APPS = 4;
    const DEVICE_TYPE_CHROME_WEB_PUSH = 5;
    const DEVICE_TYPE_WINDOWS_PHONE_WNS = 6;
    const DEVICE_TYPE_SAFARI = 7;
    const DEVICE_TYPE_FIREFOX = 8;
    const DEVICE_TYPE_MAC_OS = 9;

    /**
     * @param array $config
     * @return OneSignalInterface
     */
    public function setConfig(array $config): OneSignalInterface;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#create-notification
     * @param array $fields
     * @return Collection
     */
    public function createNotification(array $fields): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#cancel-notification
     * @param string $id
     * @return Collection
     */
    public function cancelNotification(string $id): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#view-devices
     * @param int $limit
     * @param int $offset
     * @return Collection
     */
    public function viewDevices(int $limit = 50, int $offset = 0): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#view-device
     * @param string $id
     * @return Collection
     */
    public function viewDevice(string $id): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#add-a-device
     * @param string $identifier
     * @param int $deviceType
     * @param array $fields
     * @return Collection
     */
    public function addDevice(string $identifier, int $deviceType, array $fields = []): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#edit-device
     * @param string $id
     * @param array $fields
     * @return Collection
     */
    public function editDevice(string $id, array $fields = []): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#csv-export
     * @return Collection
     */
    public function csvExport(): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#view-notification
     * @param string $id
     * @return Collection
     */
    public function viewNotification(string $id): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#view-notifications
     * @param int $limit
     * @param int $offset
     * @return Collection
     */
    public function viewNotifications(int $limit = 50, int $offset = 0): Collection;

    /**
     * @see https://documentation.onesignal.com/v3.0/reference#track-open
     * @param string $id Notification id
     * @param array $fields
     * @return Collection
     */
    public function trackOpen(string $id, array $fields = []): Collection;

}
