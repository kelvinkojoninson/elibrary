<?php

namespace App\Listeners;

use App\Events\UserLogs;
use App\Models\Logs;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class StoreUserLogs
{
    public $userIp;
    public $device;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->userIp = $request->ip();
        $this->device = $request->server('HTTP_USER_AGENT');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserLogs $event)
    {
        $locationData = Location::get($this->userIp);
        
        $saveLog = Logs::create([
            "userid" => $event->userid ?? 'anonymous',
            "module" => $event->module,
            "action" => $event->activity,
            "ipaddress" => $this->userIp,
            'device' => $this->device,
            'request' => $event->request,
            'status' => $event->status,
            "longitude" => $locationData->longitude ?? $this->userIp,
            "latitude" => $locationData->latitude ?? $this->userIp,
            'country_name' => $locationData->countryName ?? 'Unknown',
            'country_code' => $locationData->countryCode ?? 'Unknown',
            'region_name' => $locationData->regionName?? 'Unknown',
            'region_code' => $locationData->regionCode ?? 'Unknown',
            'city_name' => $locationData->cityName ?? 'Unknown',
            'zip_code' => $locationData->zipCode ?? 'Unknown',
        ]);

        return $saveLog;
    }
}
