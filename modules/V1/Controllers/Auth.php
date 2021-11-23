<?php

namespace Modules\V1\Controllers;

use Modules\V1\Traits\AuthValidate;
use Modules\V1\Lib\Session;
use React\Http\Message\Response;
use Plenusservices\PhpLdap\Conection;


class Auth
{
    use AuthValidate;

    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;


    public function __construct(public string $contentType = "application/json; charset=utf-8",  public array $requiredFields = ['user', 'password'])
    {
    }

    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     */
    public function login(\Psr\Http\Message\ServerRequestInterface $request): mixed
    {
        try {
            $data = json_decode((string) $request->getBody());
            $this->inputValidate((array)$data, $this->requiredFields);

            $this->user = $data->user;
            $this->password = $data->password;
            // config
            $ldapserver = $_ENV['LDAP_SERVER'];
            $ldapgroup  = $_ENV['LDAP_GROUP'];
            $ldaptree   = $_ENV['LDAP_TREE'];
            $ldap       = new Conection($this->user, $this->password, $ldapserver, $ldapgroup, fn () => true);
            $filter2    = "(&(samaccountname={$this->user}*)(memberOf={$ldapgroup}))";

            if ($ldap->Auth($ldaptree, $filter2)) {
                //successful authentication
                return new Response(
                    200,
                    ['Content-Type' => $this->contentType],
                    json_encode(['code' => 200, 'data' => [
                        'message' => "successful authentication",
                        //here is the data to be stored in the token:
                        'token'   => Session::Start([
                            'name'         => "{$this->user}",
                            'remember_me'  => (isset($data->remember_me) && $data->remember_me === "on") ? true : false
                        ]),
                    ]])
                );
            }
        } catch (\Throwable $th) {
            return new Response(
                400,
                ['Content-Type' => $this->contentType],
                json_encode(['code' => 400, 'data' => ['message' => $th->getMessage()]])
            );
        }
    }
}
