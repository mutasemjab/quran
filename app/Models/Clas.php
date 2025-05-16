<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class Clas extends Model
{
    use HasFactory;
    protected $appends = ['generated_dates','dates_without_holidays'];

    protected $guarded=[];
    public const WEEKDAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public static function getDatesWithDayNames(array $weekdays, string $startDate, string $endDate): array {
        
        $period = CarbonPeriod::create($startDate, '1 day', $endDate);
    
        $normalizedWeekdays = array_map('strtolower', $weekdays);
    
        $dates = $period->filter(function (Carbon $date) use ($normalizedWeekdays) {
            return in_array(strtolower($date->englishDayOfWeek), $normalizedWeekdays);
        });
    
        // Format as "YYYY-MM-DD DayName"
        return array_map(fn($date) => $date->format('Y-m-d l'), iterator_to_array($dates));
    }
    public function getGeneratedDatesAttribute()
    {
        if (!$this->week_days || empty($this->week_days)) {
            return [];
        }
        return $this->getDatesWithDayNames(
            json_decode($this->week_days,true), // Assuming week_days is a comma-separated string
            $this->start_date,
            $this->finish_date
        );
    }

    public function getDatesWithoutHolidaysAttribute(): array
    {
        if (!$this->week_days || empty($this->week_days)) {
            return [];
        }
        $allDates = $this->getDatesWithDayNames(
            json_decode($this->week_days, true),
            $this->start_date,
            $this->finish_date
        );

        $holidays = json_decode($this->holidays, true);

        if (empty($holidays)) {
            return array_values($allDates); // Ensure sequential keys
        }

        // Filter out holidays and reindex the array
        return array_values(array_filter($allDates, fn($date) => !in_array($date, $holidays)));
    }
}
