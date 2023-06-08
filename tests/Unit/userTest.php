<?php

namespace Tests\Unit;

use App\Models\Day;
use App\Models\Classs;
use App\Models\Period;
use App\Models\Teacher;
use App\Models\CourseName;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public $coursename;
    public $day;
    public $teacher;
    public $class;
    public $period;

    public function __construct()
    {
        parent::__construct();

        $this->coursename = CourseName::find(1);
        $this->day = Day::find(7);
        $this->teacher = Teacher::find(1);
        $this->class = Classs::find(1);
        $this->period = Period::find(1);
    }

    public function testValidSolution()
    {
        $checkday = Day::find($this->day->id);
        if ($checkday->is_vacation == 1) {
            dd($checkday->is_vacation == 1);
        }

        // Add assertions or further test code
    }
}
