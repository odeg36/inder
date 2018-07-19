<?php

namespace AdminBundle\Service;

use Hautelook\Phpass\PasswordHash;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class InderEncoder implements PasswordEncoderInterface {

    public function encodePassword($raw, $salt) {
        $encrypted_password = md5($raw . $salt);
        $password = $encrypted_password;
        return $password; // Custom function for password encrypt
    }

    public function isPasswordValid($encoded, $raw, $salt) {
        $validation = false;

        $databasePassword = $encoded;
        $hash = explode(":", $databasePassword)[0];
        if (!$salt) {
            try {
                $salt = explode(":", $databasePassword)[1];
            } catch (\Exception $ex) {
                
            }
        }
        if ($salt) {
            $crypt = md5($raw . $salt);
            $validation = ($crypt === $hash);
        } else {
            $passwordHasher = new PasswordHash(10, true);
            $passwordHasher->HashPassword($raw);
            $validation = $passwordHasher->CheckPassword($raw, $databasePassword);
        }
        return $validation;
    }

}
