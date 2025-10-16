<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Ensure the user is authorized to view the given document.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Document $document
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    public function ensureUserCanView(User $user, Document $document)
    {
        if ($user->cannot('view', $document)) {
            abort(403, 'Unauuthorized');
        }
    }
}
