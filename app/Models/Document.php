<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
    HasOne,
    BelongsToMany
};
use App\Models\{
    Workflow,
    User,
    Category,
    Tag,
    DocumentVersion,
    Department,
    ApprovalRequest,
    Attachment,
    Version,
    Comment
};

class Document extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
protected $fillable = [
    'title',
    'description',
    'status',
    'user_id',
    'watermark',
    'author',
    'department',
    'file_path',
    'file_type',
    'category_id',
    'approved_at',
    'rejected_at',
    'approved_by',
    'rejected_by',
    'director_approval',
    'director_signature',
];


    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (Document $document) {
            $document->workflow()->create([
                'name' => 'Approval Process for ' . $document->title,
                'status' => 'pending',
            ]);
        });
    }

    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'document_user', 'document_id', 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'document_tag');
    }

    public function approvalRequests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getAuthorFirstName(): string
    {
        if (!$this->author) {
            return 'Unknown';
        }
        return explode(' ', $this->author)[0];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->user();
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Version::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function workflow(): HasOne
    {
        return $this->hasOne(Workflow::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function saveVersion(): void
    {
        $latestVersion = $this->versions()->max('version') ?? 0;

        $this->versions()->create([
            'version' => $latestVersion + 1,
            'content' => $this->description,
            'author' => $this->author,
            'department' => $this->department,
            'file_path' => $this->file_path,
            'file_type' => $this->file_type,
            'category_id' => $this->category_id,
        ]);
    }

    public function restoreVersion(int $versionId): void
    {
        $version = $this->versions()->findOrFail($versionId);

        $this->update([
            'description' => $version->content,
            'author' => $version->author,
            'department' => $version->department,
            'file_path' => $version->file_path,
            'file_type' => $version->file_type,
            'category_id' => $version->category_id,
            'status' => self::STATUS_PENDING,
        ]);
    }

    public function deleteVersions(): void
    {
        $this->versions()->delete();
    }

    public function getFormattedCreatedAt(): string
    {
        return $this->created_at ? $this->created_at->format('M d, Y') : 'N/A';
    }

    public function getFormattedApprovedAt(): string
    {
        return $this->approved_at ? $this->approved_at->format('M d, Y H:i') : 'N/A';
    }
}
