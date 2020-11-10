<?php

/**
 * Class MailChimp
 *
 * @author Matteo Ferrone 
 * @since 2020-11-10
 * @version 1.2
 */
class MailChimp {

    private $apiKey;
    private $urlRoot;
    private $list;

    public function __construct($list, $apiKey, $urlRoot) {
        $this->apiKey = $apiKey;
        $this->urlRoot = $urlRoot;
        $this->list = $list;
    }

    /**
     * @return bool|string
     */
    public function getAll() {
        $url = $this->urlRoot . 'lists/' . $this->list . '/members';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * @param $email
     * @return bool|string
     */
    public function getByEmail($email) {
        $memberHash = md5(strtolower($email));
        $url = $this->urlRoot . 'lists/' . $this->list . '/members/' . $memberHash;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * @param $data
     * @param $method
     * @return bool|string
     */
    public function addOrUpdate($data, $method) {
        $json = json_encode(
            [
                'email_address' => $data['email'],
                'status' => $data['status'],
                'merge_fields' => [
                    'FNAME' => $data['firstname'],
                    'LNAME' => $data['lastname']
                ]
            ]);
        if ($method == 'PUT') {
            $memberHash = md5(strtolower($data['email']));
            $url = $this->urlRoot . 'lists/' . $this->list . '/members/' . $memberHash;
        } elseif ($method == 'POST') {
            $url = $this->urlRoot . 'lists/' . $this->list . '/members';
        }
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * @param $email
     * @return bool|string
     */
    public function delByEmail($email) {
        $memberHash = md5(strtolower($email));
        $url = $this->urlRoot . 'lists/' . $this->list . '/members/' . $memberHash;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}
