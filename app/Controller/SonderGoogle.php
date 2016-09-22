<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/7/2016
 * Time: 1:17 AM
 */

namespace Kanboard\Controller;

class SonderGoogle extends Base
{
    public function connect()
    {
        echo 'connect';
die();
        $client = new Google_Client();
        $client->setApplicationName("Client_Library_Examples");
        $client->setDeveloperKey("YOUR_APP_KEY");

        $service = new Google_Service_Books($client);
        $optParams = array('filter' => 'free-ebooks');
        $results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);

        foreach ($results as $item) {
            echo $item['volumeInfo']['title'], "<br /> \n";
        }

        die();
    }
}