<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class MiddlewareKbumn
{
    //use HasFactory;
    private $mwApiUrl;
    private $kodeSso;
    private $guzzleClient;
    private $cacheDuration = 3600;

    public function __construct()
    {
        $this->mwApiUrl = env('MIDDLEWARE_URL') . '/api';
        $this->kodeSso = env('KODE_SSO');
        $this->guzzleClient = new Client();
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getMwUser($username)
    {
        $uri = $this->mwApiUrl . '/' . $this->kodeSso . '/user-profile/' . $username;
        $respond = $this->guzzleClient->request('GET', $uri);
        return json_decode($respond->getBody());
    }

    /**
     * @return mixed
     */
    public function getBumnAktif()
    {
        $cacheKey = 'mwGetBumnAktif';
        $uri = $this->mwApiUrl . '/' . $this->kodeSso . '/referensi-bumn';
        return $this->getResult($cacheKey, $uri);
    }

    /**
     * @return mixed
     */
    public function getKategoriUser()
    {
        $cacheKey = 'mwGetKategoriUser';
        $uri = $this->mwApiUrl . '/' . $this->kodeSso . '/referensi-kategoriuser';
        return $this->getResult($cacheKey, $uri);
    }

    /**
     * @param $username
     * @return mixed|null
     */
    public function getUserKategori($username)
    {
        $user = $this->getMwUser($username);
        if($user->data){
            $userKategori = collect($this->getKategoriUser()->data);
            return $userKategori
                ->where('id', $user->data->kategori_user_id)
                ->first();
        }
        return null;
    }

    public function getActivationInfo($email)
    {
        $uri = $this->mwApiUrl . '/' . $this->kodeSso . '/get-activation-info/' . $email;
        $respond = $this->guzzleClient->request('GET', $uri);
        return (empty(json_decode($respond->getBody())))
            ? null
            : json_decode($respond->getBody());
    }

    /**
     * @param $data
     */
    public function createMwUser($data)
    {
        $uri = $this->mwApiUrl . '/' . $this->kodeSso . '/user-crud';
        $respond = $this->guzzleClient->request('POST', $uri, [
            'form_params' => [
                'action'            => 'insert',
                'username'          => $data->username,
                'email'             => $data->email,
                'name'              => $data->name,
                'handphone'         => isset($data->handphone) ? $data->handphone : null,
                'kategori_user_id'  => isset($data->kategori_user_id) ? $data->kategori_user_id : null,
                'id_bumn'           => isset($data->id_bumn) ? $data->id_bumn : null,
                'asal_instansi'     => isset($data->asal_instansi) ? $data->asal_instansi : null,
            ]
        ]);
        $return = json_decode($respond->getBody()->getContents());

        if(!$return->status){
            throw new \Exception(implode(', ', $return->msg));
        }
        return $this->getMwUser($data->username);
    }

    /**
     * @param $data
     */
    public function updateMwUser($data)
    {
        $uri = $this->mwApiUrl . '/' . $this->kodeSso . '/user-crud';
        $respond = $this->guzzleClient->request('POST', $uri, [
            'form_params' => [
                'action'            => 'update',
                'username'          => $data->username,
                'email'             => $data->email,
                'name'              => $data->name,
                'handphone'         => isset($data->handphone) ? $data->handphone : null,
                'kategori_user_id'  => isset($data->kategori_user_id) ? $data->kategori_user_id : null,
                'id_bumn'           => isset($data->id_bumn) ? $data->id_bumn : null,
                'asal_instansi'     => isset($data->asal_instansi) ? $data->asal_instansi : null,
            ]
        ]);
        $return = json_decode($respond->getBody()->getContents());

        if(!$return->status){
            throw new \Exception(implode(', ', $return->msg));
        }
        return $this->getMwUser($data->username);
    }

    public function deleteMwUser($username)
    {
        $uri = $this->mwApiUrl . '/' . $this->kodeSso . '/user-crud';
        $respond = $this->guzzleClient->request('POST', $uri, [
            'form_params' => [
                'action'            => 'delete',
                'username'          => $username,
            ]
        ]);
        $return = json_decode($respond->getBody()->getContents());
        return $return->status;
    }

    private function getResult($cacheKey, $uri)
    {
        if(!Cache::has($cacheKey)){
            $result = json_decode(
                $this->guzzleClient
                    ->request('GET', $uri)
                    ->getBody()
            );
            Cache::add($cacheKey, $result, $this->cacheDuration);
        }
        return Cache::get($cacheKey);
    }
}