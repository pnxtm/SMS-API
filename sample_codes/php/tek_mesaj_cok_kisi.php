<?
// SMS API ile tek mesajın çok kişiye gönderimi örneği (HTTP)
// Aşağıdaki örnek kodu kendinize özelleştirerek kullanabilirsiniz.

php function sendSMS($header, $message, $phones)
{ 
    $sms_msg = array(
        "username" => "xxxx", // https://oim.verimor.com.tr/sms_settings/edit adresinden öğrenebilirsiniz.
        "password" => "xxxx", // https://oim.verimor.com.tr/sms_settings/edit adresinden belirlemeniz gerekir.
        "source_addr" => $header, // Gönderici başlığı, https://oim.verimor.com.tr/headers adresinde onaylanmış olmalı, değilse 400 hatası alırsınız.
        //"valid_for" => "48:00",
        //"send_at" => "2015-02-20 16:06:00",
        //"datacoding" => "0",
        "custom_id" => "1424441160.9331344",
        "messages" => array(
            array(
                "msg" => $message,
                "dest" => $phones
            )
        )
    );
    
    $ch = curl_init('http://sms.verimor.com.tr/v2/send.json');
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_POSTFIELDS => json_encode($sms_msg),
    ));
    
    $http_response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($http_code != 200){
          echo "$http_code $http_response\n";
          return false;
    }

    return $http_response;
}

$source_addr = "BASLIGIM";
$message = "Test mesajıdır"; //This text should be UTF8, if not you will get 400 error. The strings from the database come from the encoding of the database connection, and you need to translate if it is not UTF8.
$dest = "905321234567,905321234568";

$campaign_id = sendSMS($source_addr, $message, $dest);
if($campaign_id === false)
      echo "Mesaj gonderme basarisiz.\n";
else
      echo "Mesaj basariyla gonderildi. Kampanya ID'si: $campaign_id\n";

?>
