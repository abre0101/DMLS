<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\document;
class Report extends Model
{
    use HasFactory;

  protected $fillable = [
    'title',
    'description',
    'status',
    'generated_by',
    'document_id', // must be fillable
];

 


public function document()
{
    return $this->belongsTo(Document::class);

   }   // In Report model
public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function user()
{
    return $this->belongsTo(User::class, 'generated_by');
}
// In App\Models\Report
public function generatedBy()
{
    return $this->belongsTo(User::class, 'user_id'); // Assuming 'user_id' is the foreign key
}


}
