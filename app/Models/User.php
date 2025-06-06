<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    private function permissions()
    {
        return Role::leftJoin("permissions","permissions.id")
        ->get();
    }
    private function roles()
    {
        return Role::leftJoin("permissions","permissions.id")
        ->get();
    }

    public function assignRole($role) {
        $role = Role::where('name',$role->name)->get()->first();
        return $this->role()->save($role);
    }

    private static function openForAll(string $route){
        return Permission::where("name","=",$route)->exists();
    }

    public static function hasPermissions(string $route) 
    {

        if(self::openForAll( $route)){
            return Auth::user()->role->flatMap->permissions->contains("name", $route);
        }else{
            return true;
        }
    }

    public static function hasRoleName(string $role) {
        return Auth::user()->role->contains("name", $role);
    }
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Summary of getUsersList
     * @param int $status
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUsersList(int $status = 100){
        return DB::table("users")
        ->select(
            "users.id", 
            "users.last_name",
            "users.first_name",
            "users.email",
            )
        ->where("users.status","=",$status)
        ->orderBy('last_name','asc')
        ->paginate();
    }


    public function getUserData(int $userID){
        return $this->select(
            "users.*",
            "variables.name"
        )
        ->leftJoin("variables AS user_role","users.role","=","user_role.id")
        ->leftJoin("variables AS user_sub_role","users.sub_role","=","user_sub_role.id")
        ->where("users","=",$userID)
        ->get();
    }

    /**
     * Retunts path to user avatar
     * @param string $name
     * @return string
     */
    public function getUserAvatar(string $filename){
        return Storage::url("img/avatars/default.jpg");
    }

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }

}
