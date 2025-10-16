<?php

namespace App\Traits;

trait ActivityLogUser
{
    public $default_activity_guard = "web";

    public function getActivityGuard()
    {
        return $this->activity_guard ?? $this->default_activity_guard;
    }

    public function activityCreate($message, $performOn = null, $properties = null, $event = null)
    {
        activity($this->logName)
            ->causedBy(auth($this->getActivityGuard())->id())
            ->when($performOn, fn($Q) => $Q->performedOn($performOn))
            ->when($performOn && !$properties, function ($Q) use ($performOn) {  // jika perform dan propertis kosong
                unset($performOn['created_at'], $performOn['updated_at'], $performOn['deleted_at']);
                $Q->withProperties(['attributes' => $performOn]);
            })
            ->when($properties, fn($Q) => $Q->withProperties($properties))
            ->event($event ?? 'created')
            ->log($message);
    }

    public function activityUpdate($message, $performOn = null, $properties = null, $event = null)
    {
        $activity = activity($this->logName)
            ->causedBy(auth($this->getActivityGuard())->id());

        if ($performOn && method_exists($performOn, 'getChanges')) {
            $changes = $performOn->getChanges();
            unset($changes['updated_at']);

            if (count($changes) > 0) {
                $activity->performedOn($performOn)
                    ->withProperties(['attributes' => $changes])
                    ->event($event ?? 'updated')
                    ->log($message);
            }
        }
        else {
            $activity->withProperties(['attributes' => $properties])
                ->event($event ?? 'updated')
                ->log($message);
        }
    }


    public function activityDelete($message, $performOn = null, $properties = null)
    {
        activity($this->logName)
            ->causedBy(auth($this->getActivityGuard())->id())
            ->when($performOn, fn($Q) => $Q->performedOn($performOn))
            ->when($performOn && !$properties, function ($Q) use ($performOn) {  // jika perform dan propertis null
                $attributes = $performOn->getChanges();
                unset($attributes['updated_at']); //unset updated at

                if (count($attributes) > 0) {
                    $Q->withProperties(['attributes' => $attributes]);
                }
            })
            ->when($properties, fn($Q) => $Q->withProperties(['attributes' => $properties]))
            ->event('deleted')
            ->log($message);
    }
}
