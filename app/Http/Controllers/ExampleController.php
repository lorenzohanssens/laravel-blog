<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class ExampleController extends Controller
{
    //
    public function homepage() {

        // let's say we just loaded data from the database
        $name = "Cleopatra";
        $jobs = ["Developer", "Tutor", "Instructor", "Cashier"];
        $templatingEngine = "blade";
        return view("homepage", ['name' => $name, 'templatingEngine' => $templatingEngine, 'jobs' => $jobs]);
    }
    public function aboutpage() {
        return view("single-post");
    }
}
