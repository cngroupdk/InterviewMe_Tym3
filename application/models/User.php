<?php

/**
 * A single user record
 *
 */
class User extends My_Db_Table_Row {

    public function updateFromArray(array $values, $isCreated) {
        $salt = bin2hex(openssl_random_pseudo_bytes(20));
        $values['salt'] = $salt;
        $values['password'] = sha1($values['password'] . $salt);


        $date = date("Y-m-d H:i:s");
        $this->setFromArray($values);
        if ($isCreated) {
            $this->setCreatedAt($date);
        }
        $this->setLastModifiedAt($date);
        $this->save();

        return $this;
    }

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function deleteUser() {
        $this->delete();
    }

}
