<?php

require(dirname(__FILE__) . "../../../../wp-load.php");
if (!isset($_POST["action"])) {
    echo json_encode(array("content" => "Invalid Request"));
    exit(0);
}
switch ($_POST["action"]) {
    case "get_meta":
        get_og_meta($_POST["post_id"]);
        break;
    case "save_meta":
        save_og_meta();
        break;
    default:
        break;
}

function save_og_meta() {
    $data = array();
    for ($i = 0; $i < count($_POST["props"]); $i++) {
        if ($_POST["props"][$i] != "" AND $_POST["vals"][$i] != "") {
            $data[$_POST["props"][$i]] = $_POST["vals"][$i];
        }
    }
    if (sizeof($data)) {
        if (update_post_meta($_POST["post_id"], $_POST["tag"], serialize($data))) {
            echo json_encode(array("code" => "success", "content" => "Successfully Updated"));
        } else {
            echo json_encode(array("code" => "error", "content" => "No changes were made"));
        }
    } else {
        echo json_encode(array("code" => "error", "content" => "No changes were made"));
    }
    exit(0);
}

function get_og_meta($post_id=null) {
    if (is_null($post_id)) {
        echo json_encode(array("content" => "Error : No Post Supplied"));
        exit(0);
    }
    // Now we get the meta
    $post_meta = get_post_meta($_REQUEST["post_id"], $_POST["tag"]);
    if (!sizeof($post_meta)) {
        exit(0);
    } else {
        $unserialized = unserialize($post_meta[0]);
        if ($unserialized) {
            $out_str = '<table width="100%" cellpadding="4" id="og_meta" cellspacing="0"><thead><th class="left">Property</th><th>Value</th></thead><tbody>';
            $count = 0;
            foreach ($unserialized as $k => $v) {
                $prop = end(explode(":", $k));
                $out_str.='
					<tr class="' . ((++$count % 2 == 0) ? "even" : "odd") . '"><td class="left">' . $prop . '</td><td>' . $v . '</td></tr>
				';
            }
            $out_str.="</tbody></table>";
            echo json_encode(array("content" => $out_str));
            exit(0);
        }
    }
}

echo json_encode(array("content" => ""));
exit(0);
?>