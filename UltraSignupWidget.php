<?php
/**
 * Small class to display our race info.
 * @author Jerry Abbott <ttobba71@gmail.com>
 */
require("UltraSignupInfo.php");

class UltraSignupWidget extends WP_Widget
{
    private $ultra_signup;

    public function __construct()
    {
        parent::__construct("ultrasignup_widget", "Ultra Signup Widget",
            array("description" => "Pull user's account information from UltraSignup.com"));
        $this->ultra_signup = new UltraSignupInfo();
    }

    public function widget($args, $instance)
    {
        echo '<aside id="tag_cloud-2" class="widget-container widget_tag_cloud">';
        echo '<h3 class="widget-title">Ultra Signup Results</h3>';
        $this->ultra_signup->first_name = $instance["first_name"];
        $this->ultra_signup->last_name = $instance["last_name"];
        $this->ultra_signup->uid = $instance["uid"];
        echo '<div class="ultraSignup">';

        $result = $this->ultra_signup->getUserHistory($this->ultra_signup->first_name, $this->ultra_signup->last_name, $this->ultra_signup->uid);

        if (isset($result)) {
            $uc = 0;
            $prev = 0;
            $cnt = 0;
            foreach ($result as $line => $event) {
                foreach ($event["Results"] as $items) {
                    if ($items["status"] == -1) {
                        if (!$uc) {
                            echo "<b>Up and Coming:</b><br>";
                            $uc = 1;
                        } else {
                            echo "<br>";
                        }
                        $dt = new DateTime($items["eventdate"]);
                        echo $dt->format('Y-m-d') . " - " . $items["eventname"];
                    }
                    if ($items["status"] == 1) {
                        if ($cnt > $this->ultra_signup->hist_num) {
                            continue;
                        }
                        if (!$prev) {
                            echo "<br><b>Previous:</b><br>";
                            $prev = 1;
                        } else {
                            echo "<br>";
                        }
                        $dt = new DateTime($items["eventdate"]);
                        echo $items["eventname"] . "<br>&nbsp;&nbsp;&nbsp;(" . $items["time"] . ")" . " - " . $dt->format('Y-m-d');
                        $cnt++;
                    }
                }
            }
        }
        echo '</div></aside>';

    }

    public function form($instance)
    {
        $first_name = "";
        $last_name = "";
        $uid = 0;

        // if instance is defined, populate the fields
        if (!empty($instance)) {
            $this->ultra_signup->first_name = $instance["first_name"];
            $this->ultra_signup->last_name = $instance["last_name"];
            $this->ultra_signup->uid = $instance["uid"];
        }

        //   $this->ultra_signup->first_name = $this->get_field_id("first_name");
        //   $this->ultra_signup->last_name = $this->get_field_name("last_name");
        //   $this->ultra_signup->uid = $this->get_field_name("uid");

        echo '<label for="' . $this->get_field_id("first_name") . '">First Name</label><br>';
        echo '<input id="' . $this->get_field_id("first_name") . '" type="text" name="' .
            $this->get_field_name("first_name") . '" value="' . $this->ultra_signup->first_name . '"><br>';

        echo '<label for="' . $this->get_field_id("last_name") . '">Last Name</label><br>';
        echo '<input id="' . $this->get_field_id("last_name") . '" type="text" name="' .
            $this->get_field_name("last_name") . '" value="' . $this->ultra_signup->last_name . '"><br>';

        echo '<label for="' . $this->get_field_id("uid") . '">UID</label><br>';
        echo '<input id="' . $this->get_field_id("uid") . '" type="text" name="' .
            $this->get_field_name("uid") . '" value="' . $this->ultra_signup->uid . '"><br>';
    }

    public function update($newInstance, $oldInstance)
    {
        $values = array();
        $values["first_name"] = htmlentities($newInstance["first_name"]);
        $values["last_name"] = htmlentities($newInstance["last_name"]);
        $values["uid"] = htmlentities($newInstance["uid"]);
        return $values;
    }
}