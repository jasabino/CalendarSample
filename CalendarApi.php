<?php
function getCalendars($auth) {
    $oauth2token_url = "https://www.googleapis.com/calendar/v3/users/me/calendarList";

    $curl = curl_init($oauth2token_url);

    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $headers = array();
    $headers[] = 'Content-Type: application/http';
    $headers[] = 'authorization: '.$auth;

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $json_response = curl_exec($curl);
    curl_close($curl);
    $obj = json_decode($json_response);
    $ids = array();

    foreach ($obj->items as $item){
        $item->id;
        array_push($ids, $item->id);
    }


    return json_encode($ids, JSON_UNESCAPED_UNICODE);
}

function getEventos($calendarios, $auth) {

    $eventos = array();
    foreach ($calendarios as $calendario){


        $singleEvents = 'true';
        $orderBy = 'startTime';
        $timeMin = urlencode(date("Y-m-d").'T'.date("H:i:sP"));
        $fecha = date('Y-m-d H:i:sP');
        $nuevafecha = strtotime ( '+12 month' , strtotime ( $fecha ) ) ;
        $timeMax = urlencode(date("Y-m-d", $nuevafecha).'T'.date("H:i:sP", $nuevafecha));

        $oauth2token_url = "https://www.googleapis.com/calendar/v3/calendars/".$calendario."/events?singleEvents=$singleEvents&orderBy=$orderBy&timeMin=$timeMin&timeMax=$timeMax";
        $curl = curl_init($oauth2token_url);

        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/http';
        $headers[] = 'authorization: '.$auth;

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $json_response = curl_exec($curl);
        curl_close($curl);
        $obj = json_decode($json_response);
        foreach ($obj->items as $item){
            $evento = $item->summary .' | '.(empty($item->start->dateTime)?$item->start->date:$item->start->dateTime).' | '.(empty($item->end->dateTime)?$item->end->date:$item->end->dateTime);
            array_push($eventos, $evento);
        }

    }

    return json_encode($eventos, JSON_UNESCAPED_UNICODE);
}


if ($_GET['do'] === "getCalendars") {
    echo getCalendars($_GET['auth']);
}else if ($_GET['do'] === "getEventos") {
    echo getEventos($_POST['calendarios'],$_GET['auth']);
}

?>