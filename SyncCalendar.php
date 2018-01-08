<?php

include_once 'CalendarApi.php';
error_reporting(9);

$code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
if (!empty($code)) {
    $client_id = '226056723685-s4c67s8ga3r0scq4iq9qq8vb4f5jmv6o.apps.googleusercontent.com';
    $client_secret = 'UuYaiwutHjLQ6R_4FJGro3TB';
    $redirect_uri = 'http://localhost/CalendarSample/SyncCalendar.php';

    $oauth2token_url = "https://accounts.google.com/o/oauth2/token";
    $clienttoken_post = array(
        "code" => $code,
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "redirect_uri" => $redirect_uri,
        "grant_type" => "authorization_code"
    );

    $curl = curl_init($oauth2token_url);

    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $clienttoken_post);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $json_response = curl_exec($curl);
    curl_close($curl);
    $obj = json_decode($json_response);
    if (!empty($json_response)) {
        $_SESSION["token"] = $obj;
        $token = $_SESSION["token"];
        $auth = $token->token_type .' '. $token->access_token;
        $calendarios = json_decode(getCalendars($auth));

    }

}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<div id="obtenercalendariosdiv">
    <form id="showcalform" action="https://accounts.google.com/o/oauth2/auth" method="GET">
        <input type="hidden" name="response_type" id="response_type" value="code">
        <input type="hidden" name="approval_prompt" id="approval_prompt" value="force">
        <input type="hidden" name="redirect_uri" id="redirect_uri" value="http://localhost/CalendarSample/SyncCalendar.php">
        <input type="hidden" name="client_id" id="client_id" value="226056723685-s4c67s8ga3r0scq4iq9qq8vb4f5jmv6o.apps.googleusercontent.com">
        <input type="hidden" name="access_type" id="access_type" value="offline">
        <input type="hidden" name="scope" id="scope" value="https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.readonly">
    </form>
    <input type="submit" id="showcalbutton" value="Show Calendars">
</div>

<div  id="sincronizardiv" style="display: none">
    <h1>Calendars</h1>
    <form id="syncform" method="GET">
        <div id="calendarscheck">
        <?foreach($calendarios as $calendario){?>
            <input type="checkbox" value="<?=$calendario?>" name="calendarios[]"/>
            <label><?=$calendario?></label>
            <br>
        <?}?>
        </div>
    </form>
    <input type="submit" id="syncbutton" value="Sync">
</div>

<div  id="eventosdiv" style="display: none">
    <h1>Events</h1>
    <div id="eventoslabel">
    </div>
</div>

<? if (!empty($calendarios)){?>
    <script>
        $('#obtenercalendariosdiv').toggle();
        $('#sincronizardiv').toggle();
    </script>
<?}?>

<script>
    $("#showcalbutton").click(function () {
        <?if (empty($_SESSION["token"])){ ?>
        $("#showcalform").submit();
        <?} else {
                $token = $_SESSION["token"];
                $auth = $token->token_type .' '. $token->access_token;
           ?>
        $.ajax({
            url: 'calendarapi.php?do=getCalendars&auth=<?=$auth?>',
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                var obj = JSON.parse(response);
                var html='';
                for (i = 0; i < obj.length; i++) {
                    html += '<input type="checkbox" value="'+obj[i]+'" name="calendarios[]"/>' + '<label>'+obj[i]+'</label>'+ '<br>';
                }
                $('#obtenercalendariosdiv').toggle();
                $('#sincronizardiv').toggle();
                $('#eventosdiv').toggle(false);
                $('#calendarscheck').html(html);

            },
            error: function () {
                alert('ERROR');
            }
        });

        <?}?>
    });

    <?if (!empty($_SESSION["token"])){
        $token = $_SESSION["token"];
        $auth = $token->token_type .' '. $token->access_token;
    ?>
    $("#syncbutton").click(function () {
        var data = new FormData(jQuery('form')[1]);

        $.ajax({
            url: 'calendarApi.php?do=getEventos&auth=<?=$auth?>',
            data:data,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                //alert(response);
                var obj = JSON.parse(response);
                var html='';
                for (i = 0; i < obj.length; i++) {
                    html += '<input type="checkbox" value="'+obj[i]+'" name="eventos[]"/>' + '<label>'+obj[i]+'</label>'+ '<br>';
                }
                $('#obtenercalendariosdiv').toggle();
                $('#sincronizardiv').toggle();
                $('#eventosdiv').toggle();
                $('#eventoslabel').html(html);
            },
            error: function () {
                alert('Error getting events for this calendar');
            }
        });

    });
    <?}?>
</script>
