<?php

namespace Rmsramos\Activitylog\Actions;

use Filament\Actions\Action;
use Rmsramos\Activitylog\Actions\Concerns\ActionContent;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
class ActivityLogTimelineSimpleAction extends Action
{
    use ActionContent,  HasPageShield;
}
