<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $fillable = ['LOGIN', 'EMAIL', 'NAME', 'AGE', 'DESCRIPTION','PASSWORD','PHOTO','PHOTO_NAME'];
    protected $primaryKey = 'USER_ID';

    public function getAllUsers()
    {
        return $this->orderby('AGE', 'DESC')->get();

    }
    public function CreateUser($data)
    {
        self::forceCreate(['login' => $data['login'], 'password' => $data['password'], 'name' => $data['name'], 'age' => $data['age'], 'description' => $data['desc'], 'photo' => $data['photo'], 'photo_name' => $data['photo_name']]);
    }

    public function getUserByLogin($login)
    {
        return $this->where('login', $login)->first();
    }

    public function getFiles()
    {
        return self::all();
    }

    public function removeUser($remove_user_id)
    {
        return $this->where('USER_ID',$remove_user_id)->delete();
    }

    public function removePic($userId)
    {
        return $this->where('USER_ID', $userId)->update(['PHOTO' => null, 'PHOTO_NAME' => null]);
    }


    public function auth($login,$password)
    {
        return $this->where('LOGIN', $login)->where('PASSWORD', $password)->first();
    }

    public function getUserById($userId)
    {
        return $this->where('USER_ID', $userId)->first();
    }

    public function UpdateUser($userId, $data)
    {
        $user = $this->where('USER_ID', $userId)->first();

        if ($user !== null) {


            return $user->update(['LOGIN' => $data['login'], 'EMAIL' => $data['Email_Parse'], 'NAME' => $data['name'], 'AGE' => $data['age'], 'DESCRIPTION' => $data['desc'], 'PASSWORD' => $data['password'], 'PHOTO' => $data['photo'], 'PHOTO_NAME' => $data['photo_name']]);
        }
    }




    /*

    public function getFiles()
    {
        $sql = "SELECT user_id,photo,photo_name FROM users";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        $filesId = $sth->fetchAll();
        return $filesId;
    }


    public function addUserFromAdmin($data)
    {
        $sth = $this->db->prepare("INSERT INTO users(`LOGIN`, `EMAIL`, `PASSWORD`, `NAME`, `AGE`, `DESCRIPTION`, `PHOTO`, `PHOTO_NAME`) VALUES (:login,:email,:password,:name,:age,:desc, :link_photo, :photo_name)");
        $sth->execute(array(':login' => $data['login'], ':email' => $data['email'], ':password' => $data['password'], ':name' => $data['name'], ':age' => $data['age'], ':desc' => $data['desc'], ':link_photo' => $data['photo'], ':photo_name' => $data['photo_name']));
    }

    public function getUserById($userId)
    {
        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $sth = $this->db->prepare($sql);
        $sth->execute(['user_id' => $userId]);
        $user = $sth->fetch();

        return $user;
    }

    public function UpdateUser($data)
    {
        //var_dump($data);
        //exit(); //`PHOTO`= :photo,`PHOTO_NAME`= :photo_name
        $sql = "UPDATE `users` SET `LOGIN`= :login,`EMAIL`= :email,`PASSWORD`= :password,`NAME`= :name,`AGE`= :age, `DESCRIPTION`= :description WHERE USER_ID = :user_id";
        $sth = $this->db->prepare($sql);
        $sth->execute([':user_id' => $data['user_id'], ':login' => $data['login'], ':email' => $data['Email_Parse'], ':name' => $data['name'], ':age' => $data['age'], ':password' => $data['password'], ':description' => $data['desc']]);
        return $sth;
    }


*/
}
