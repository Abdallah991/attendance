<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
// response
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Http;
use DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;




class ApplicantController extends Controller
{



    // * Syncing applications 
    // !crone function
    public function syncApplicants(Request $request)
    {
        $apiToken =  config('app.GRAPHQL_TOKEN');

        $startDate = '2023-05-14';
        $endDate = $request->endDate;

        $query = <<<GQL
        query {
            toad_sessions( where: {_and: [{updated_at: {_gte: "$startDate"}}, {updated_at: {_lte: "$endDate"}}]}) {
                final_score
                created_at
                updated_at
                candidate {
                    login
                    roles {
                        name
                        }
                        firstName: attrs(path: "firstName")
                        lastName: attrs(path: "lastName")
                        email: attrs(path: "email")
                        phone: attrs(path: "Phone")
                        phoneNumber: attrs(path: "PhoneNumber")
                        gender: attrs(path: "gender")
                        dob:attrs(path: "dateOfBirth")
                        acadamicQualification:attrs(path: "qualification")
                        acadamicSpecialization:attrs(path: "Degree")
                        nationality:attrs(path: "country")
                        genders: attrs(path: "genders")
                        howDidYouHear: attrs(path: "qualifica")
                        employment: attrs(path: "employment")
                        progresses{
                            path
                            }
                        registrations {
                            registration {
                                path
                                    }
                                }
                         }
                    }
        }
        GQL;

        //  graph ql 
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $query
        ]);

        // return the data and convert it to json object
        $applicantsData = $response->json()['data']['toad_sessions'];
        // filter the array that has users with no roles
        $applicants = array_filter($applicantsData, function ($value) {
            // check if there is candidates has riles and if there is no count
            return isset($value['candidate']['roles']) && !count($value['candidate']['roles']);
        });


        // TODO: 
        // 1- Create Applicans if they dont exist yet
        // 2- update feilds firstName, lastName phone, gender, nationality, dob, acadamicQualification, acadamicSpecialization, status, progresses, howDidYouHear, score, lastGameDate, employment, howDidYouHear

        foreach ($applicants as $applicant) {
            // Check if the applicant already exists in the database
            $existingApplicant = Applicant::where('platformId', $applicant['candidate']['login'])->first();

            if ($existingApplicant) {
                // Update the existing applicant's fields with data from the API response
                $existingApplicant->firstName = $applicant['candidate']['firstName'] ? $applicant['candidate']['firstName'] : 'unKnown';
                $existingApplicant->lastName = $applicant['candidate']['lastName'] ? $applicant['candidate']['lastName'] : 'unKnown';
                $existingApplicant->email = $applicant['candidate']['email'] ?  $applicant['candidate']['email'] : 'unKnown@gamil.com';;
                $existingApplicant->phone = $applicant['candidate']['phone'] ?? $applicant['candidate']['phoneNumber'] ?? '';
                $existingApplicant->gender = $applicant['candidate']['gender'] ?? $applicant['candidate']['genders'] ?? null;
                $existingApplicant->nationality = $applicant['candidate']['nationality'] ?? null;

                // Parse the date of birth from the API response into a DateTime object and format it to the 'YYYY-MM-DD' format
                $dob = $applicant['candidate']['dob'] ? new DateTime(substr($applicant['candidate']['dob'], 0, 10)) : null;
                $dobStr = $dob ? $dob->format('Y-m-d') : null;
                $existingApplicant->dob = $dobStr;
                $existingApplicant->acadamicQualification = $applicant['candidate']['acadamicQualification'] ?? null;
                $existingApplicant->acadamicSpecialization = $applicant['candidate']['acadamicSpecialization'] ?? null;
                // TODO: update the status differently, with an API
                // $existingApplicant->status = 'Awaiting Call';
                $existingApplicant->score = $applicant['final_score'] ?? 0;
                $existingApplicant->lastGameDate = Carbon::parse($applicant['updated_at'])->toDateString();
                $existingApplicant->updatedBy = null;
                $existingApplicant->employment = $applicant['candidate']['employment'] ? $applicant['candidate']['employment'] : 'unknown';
                $existingApplicant->howDidYouHear = $applicant['candidate']['howDidYouHear'] ? $applicant['candidate']['howDidYouHear'] : 'unknown';
                $existingApplicant->progresses = json_encode($applicant['candidate']['progresses']);
                $existingApplicant->registrations = json_encode($applicant['candidate']['registrations']);


                // Save the updated Applicant model to the database
                $existingApplicant->save();
            } else {
                // Create a new Applicant model and set its fields based on the API response
                $newApplicant = new Applicant();
                $newApplicant->platformId = $applicant['candidate']['login'];
                $newApplicant->firstName = $applicant['candidate']['firstName'] ? $applicant['candidate']['firstName'] : 'Unknown';
                $newApplicant->lastName = $applicant['candidate']['lastName'] ? $applicant['candidate']['lastName'] : 'Unknown';
                $newApplicant->email = $applicant['candidate']['email'] ? $applicant['candidate']['email'] : 'unKnown@gamil.com';
                $newApplicant->phone = $applicant['candidate']['phone'] ?? $applicant['candidate']['phoneNumber'] ?? '';
                $newApplicant->gender = $applicant['candidate']['gender'] ?? $applicant['candidate']['genders'] ?? null;
                $newApplicant->nationality = $applicant['candidate']['nationality'] ?? null;
                // Parse the date of birth from the API response into a Unix timestamp and format it to the 'YYYY-MM-DD' format
                $dob = $applicant['candidate']['dob'] ? strtotime(substr($applicant['candidate']['dob'], 0, 10)) : null;
                $dobStr = $dob ? date('Y-m-d', $dob) : null;
                $newApplicant->dob = $dobStr;
                $newApplicant->acadamicQualification = $applicant['candidate']['acadamicQualification'] ?? null;
                $newApplicant->acadamicSpecialization = $applicant['candidate']['acadamicSpecialization'] ?? null;
                $newApplicant->status = 'Awaiting Call';
                $newApplicant->score = $applicant['final_score'] ?? 0;
                $newApplicant->lastGameDate = Carbon::parse($applicant['updated_at'])->toDateString();
                $newApplicant->updatedBy = null;
                $newApplicant->employment = $applicant['candidate']['employment'] ? $applicant['candidate']['employment'] : 'unknown';
                $newApplicant->howDidYouHear = $applicant['candidate']['howDidYouHear'] ? $applicant['candidate']['howDidYouHear'] : 'unknown';
                $newApplicant->progresses = json_encode($applicant['candidate']['progresses']);
                $newApplicant->registrations = json_encode($applicant['candidate']['registrations']);
                // Save the new Applicant model to the database
                $newApplicant->save();
            }
        }

        return $applicants;
    }

    // TODO: Applicants Controller suppose to
    // ! Getting the progresses 
    // * Getting applicants with filters

    public function applicants(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status; // pass, fail, all, or null
        $gradeStart = $request->gradeStart; // start grade (integer)
        $gradeEnd = $request->gradeEnd; // end grade (integer)
        $sort = $request->sort; // end grade (integer)

        $query = Applicant::query();

        // Filter by date range
        $query->whereBetween(DB::raw('DATE(lastGameDate)'), [$startDate, $endDate]);

        // Filter by status
        if ($status === 'pass') {
            $query->where('score', '>=', 25);
        } elseif ($status === 'fail') {
            $query->where('score', '<=', 25);
        } elseif ($status === 'all') {
            // do nothing
        } elseif ($status !== null) {
            return response()->json(['error' => 'Invalid status value'], 400);
        }

        // Filter by start grade
        if ($gradeStart !== null && $gradeStart !== 'all') {
            $query->where('score', '>=', $gradeStart);
        }

        // Filter by end grade
        if ($gradeEnd !== null && $gradeEnd !== 'all') {

            $query->where('score', '<=', $gradeEnd);
        }

        // Sort by score
        if ($sort === 'descending') {
            $query->orderBy('score', 'asc');
        } elseif ($sort === 'ascending') {
            $query->orderBy('score', 'desc');
        }

        $applicants = $query->get();

        return $applicants;
    }


    // ! make an api call for events 
    // !make an api call to registration with event id / registration id
    public function checkInCount(Request $request)
    {

        $apiToken =  config('app.GRAPHQL_TOKEN');

        $eventNumber = $request->eventId;

        // $startDate = '2023-05-14';
        // $endDate = $request->endDate;

        $query = <<<GQL
        query {
            registration (where : {id: {_eq: $eventNumber}}) { 
                users 
                {
                firstName: attrs(path: "firstName")
                lastName: attrs(path: "lastName")
                email: attrs(path: "email")
                phone: attrs(path: "Phone")
                phoneNumber: attrs(path: "PhoneNumber")
                gender: attrs(path: "gender")
                dob:attrs(path: "dateOfBirth")
                acadamicQualification:attrs(path: "qualification")
                acadamicSpecialization:attrs(path: "Degree")
                nationality:attrs(path: "country")
                genders: attrs(path: "genders")
                howDidYouHear: attrs(path: "qualifica")
                employment: attrs(path: "employment")
                }
                }
        }
        GQL;

        //  graph ql 
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $query
        ]);

        // return $response;

        $registrationInCheckIn = $response['data']['registration'][0]['users'];

        $numberOfRegistrations = count($registrationInCheckIn);

        return $numberOfRegistrations;
    }


    public function updateApplicantsStatus(Request $request)
    {
        // get the platform Id & status
        $platformId = $request->platformId;
        $status = $request->status;

        // return $platformId;
        // get the applicant updated 
        $existingApplicant = Applicant::where('platformId', $platformId)->first();


        $existingApplicant->status = $status;
        $existingApplicant->save();

        return $existingApplicant;
    }



    // ! make an api call for events 
    // !make an api call to registration with event id / registration id
    public function selectionPool(Request $request)
    {
        $apiToken =  config('app.GRAPHQL_TOKEN');
        $eventNumber = $request->eventId;


        $query = <<<GQL
        query {
            registration (where : {id: {_eq: $eventNumber}}) { 
                users 
                {
                firstName: attrs(path: "firstName")
                lastName: attrs(path: "lastName")
                email: attrs(path: "email")
                phone: attrs(path: "Phone")
                phoneNumber: attrs(path: "PhoneNumber")
                gender: attrs(path: "gender")
                dob:attrs(path: "dateOfBirth")
                acadamicQualification:attrs(path: "qualification")
                acadamicSpecialization:attrs(path: "Degree")
                nationality:attrs(path: "country")
                genders: attrs(path: "genders")
                howDidYouHear: attrs(path: "qualifica")
                employment: attrs(path: "employment")
                }
                }
        }
        GQL;

        //  graph ql 
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $query
        ]);

        // return $response;

        $registrationInSP = $response['data']['registration'][0]['users'];

        $numberOfRegistrations = count($registrationInSP);

        return $numberOfRegistrations;
    }

    // TODO: 
    public function selectionPoolApplicants()
    {
        $apiToken =  config('app.GRAPHQL_TOKEN');

        // query to get all selection pool users
        $query = <<<GQL
        query {
            event(where: {id: {_eq: 57}}) {
                users {
                    login
                    firstName: attrs(path: "firstName")
                    lastName: attrs(path: "lastName")
                    email: attrs(path: "email")
                    phone: attrs(path: "Phone")
                    phoneNumber: attrs(path: "PhoneNumber")
                    gender: attrs(path: "gender")
                    dob: attrs(path: "dateOfBirth")
                    acadamicQualification: attrs(path: "qualification")
                    acadamicSpecialization: attrs(path: "Degree")
                    nationality: attrs(path: "country")
                    genders: attrs(path: "genders")
                    howDidYouHear: attrs(path: "qualifica")
                    employment: attrs(path: "employment")
                    id: attrs(path: "id-cardUploadId")
                    profile: attrs(path: "pro-picUploadId")
                    }
                    }
        }
        GQL;

        // query to get all users progresses
        $queryProgresses = <<<GQL
        query {
            toad_sessions(where: {final_score: {_gte: 20}}) {
                final_score
                created_at
                updated_at
                candidate {
                    login
                    firstName
                    lastName
                    email
                    phone: attrs(path: "Phone")
                    PhoneNumber: attrs(path: "PhoneNumber")
                    progresses {
                        path
                        updatedAt
                        grade
                        isDone
                        
                        }
                        }
                        }
                        }
        GQL;

        //  graph ql 
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $query
        ]);

        //  graph ql 
        $responseProgresses = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $queryProgresses
        ]);


        $spApplicants = $response['data']['event'][0]['users'];
        $userProgresses = $responseProgresses['data']['toad_sessions'];

        // TODO: Continue adding the progresses for each user
        // TODO: Create a new table for selection pool candidates

        // return $userProgresses;

        for ($i = 0; $i < count($spApplicants); $i++) {
            // return $spApplicants[$i];

            foreach ($userProgresses as $spProgress) {

                if (isset($spApplicants[$i]['login']) && isset($spProgress['candidate']['login'])) {


                    if ($spApplicants[$i]['login'] == $spProgress['candidate']['login']) {
                        $candidateImageUrl = 'https://learn.reboot01.com/api/storage/?token=' . $apiToken . '&fileId=' . $spApplicants[$i]['profile'];

                        $spApplicants[$i]['progresses'] = $spProgress['candidate']['progresses'];
                        $spApplicants[$i]['profileImage'] = $candidateImageUrl;
                    }
                } else {
                    // return $spApplicants[$i];
                }
            }
        }

        return $spApplicants;
    }


    // TODO: create a table for selection pool candidates
    // TODO: Create a sync function 
    // TODO: create a function that gets the image of the candidate.
    // TODO: create a function that 
    // ! how are you going to handle progresses
    //* This is getting a specific user 
    function selectionPoolApplicant(Request $request)
    {

        // 1-  get student 
        $platformId = $request->platformId;
        $apiToken =  config('app.GRAPHQL_TOKEN');

        // query to get all users progresses
        $querySpCandidate = <<<GQL
        query {
            user(where:{login: {_eq: $platformId}}) {    
                firstName: attrs(path: "firstName")
                lastName: attrs(path: "lastName")
                email: attrs(path: "email")
                phone: attrs(path: "Phone")
                phoneNumber: attrs(path: "PhoneNumber")
                gender: attrs(path: "gender")
                dob: attrs(path: "dateOfBirth")
                acadamicQualification: attrs(path: "qualification")
                acadamicSpecialization: attrs(path: "Degree")
                nationality: attrs(path: "country")
                genders: attrs(path: "genders")
                howDidYouHear: attrs(path: "qualifica")
                employment: attrs(path: "employment")
                degree: attrs(path: "Degree")
                id: attrs(path: "id-cardUploadId")
                profile: attrs(path: "pro-picUploadId")
                progresses {
                    path
                    updatedAt
                    isDone
                    grade
                    }
                    }
                        }
        GQL;

        //  graph ql 
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $querySpCandidate
        ]);

        $candidate = $response['data']['user'][0];

        // 2- Formulate candidate image
        $candidateImageUrl = 'https://learn.reboot01.com/api/storage/?token=' . $apiToken . '&fileId=' . $candidate['profile'];
        // ! Imporant to retrive the images from candidates
        $filename =  $platformId . '.jpg';
        // how to save the file
        // $path = Storage::put($filename, file_get_contents($candidateImageUrl));
        // getting the whole path
        // $appFilePath = storage_path('app/' . $filename);
        // ! here

        return [
            'profileImage' => $candidateImageUrl,
            'candidate' => $candidate,
            // 'path' => $path
        ];
    }
}
