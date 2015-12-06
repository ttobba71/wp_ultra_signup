<?php

/**
 * Small class to grab our race info from Ultrasignup.
 * @author Jerry Abbott <ttobba71@gmail.com>
 */
class UltraSignupInfo
{
    //https://ultrasignup.com/service/events.svc/history/Brian/Purcell/?pid=4835
    private $url = "https://ultrasignup.com/service/events.svc/history/";
    public $first_name = "";
    public $last_name = "";
    public $uid = 0;
    public $show_prev = 1;
    public $show_uc = 1;
    public $hist_num = 5;

    private $json_history = null;

    public function __construct($first_name = null, $last_name = null, $uid = null)
    {
        if (isset($first_name) && isset($last_name)) {
            $this->url .= $first_name . "/" . $last_name . "/?pid=" . $uid;
        }
    }

    public function getUserHistory($first_name = null, $last_name = null, $uid = null)
    {
        if (isset($first_name) && isset($last_name)) {
            $this->url .= $first_name . "/" . $last_name . "/?pid=" . $uid;
        }
        $result = $this->CallAPI("GET", $this->url);
        $this->json_history = json_decode($result, true);
        return $this->json_history;
    }

    protected function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

}