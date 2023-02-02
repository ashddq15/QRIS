public function qris()
    {
        $clientId = 'xxxx';
        $sharedkey = 'xxx';
        date_default_timezone_set('UTC');
        $systrace = 'INV-' . time();
        $secretKey = 'xxx';
        $abc = $clientId . $sharedkey . $systrace;
        $words = hash_hmac('sha1', $abc, $secretKey, false);
        $urlsignon = 'https://staging.doku.com/dokupay/h2h/signon?clientId=' . $clientId . '&clientSecret=' . $secretKey . '&systrace=' . $systrace . '&words=' . $words . '&version=1.0&responseType=1';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlsignon,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $responsesignon = curl_exec($curl);
        curl_close($curl);
        $hasilsignon = json_decode($responsesignon, true);
        $amount = $this->request->getVar('amount');
        $accesstoken = $hasilsignon['accessToken'];
        date_default_timezone_set('UTC');
        $timestamp      = date('Y-m-d\TH:i:s\Z');
        $waktu = date('l, d F Y H:i:s', strtotime($timestamp . '+7 hours'));
        $transactionid = 'INV-' . time();
        $abc = $clientId . $systrace . $clientId . $sharedkey; //(clientId + systrace when SignOn + dpMallId + sharedkey)
        $wordscheckstatus = hash_hmac('sha1', $abc, $secretKey, false);
        $urlcheckstatus = 'https://staging.doku.com/dokupay/h2h/generateQris?clientId=' . $clientId . '&accessToken=' . $accesstoken . '&dpMallId=' . $clientId . '&words=' . $wordscheckstatus . '&version=3.0&terminalId=A01&amount=' . $amount . '&postalCode=99999&feeType=1';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlcheckstatus,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $responseqris = curl_exec($curl);
        curl_close($curl);
