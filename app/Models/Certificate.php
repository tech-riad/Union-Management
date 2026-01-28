namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    public function applications()
    {
        return $this->hasMany(CertificateApplication::class);
    }
}
