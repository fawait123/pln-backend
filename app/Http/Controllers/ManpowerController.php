<?php

namespace App\Http\Controllers;

use App\Models\Manpower;
use App\Traits\HasApiResource;
use App\Traits\HasList;
use Dedoc\Scramble\Attributes\Group;

#[Group('Master Manpower')]
class ManpowerController extends Controller
{
    use HasList, HasApiResource;

    protected $model = Manpower::class;
    protected array $search = ['name'];
    protected array $with = [];
    protected $rules = [];
}
