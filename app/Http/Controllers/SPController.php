<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SP;





class SPController extends Controller
{

    // * Sync applicants and get lightening mode
    public function syncSelectionPoolApplicants(Request $request)
    {

        // get all query and api call variables
        // ! these variables are added for each selection pool
        $spId = $request->spId;
        $spConstant = $request->spConstant;

        $apiToken =  config('app.GRAPHQL_TOKEN');
        //TODO: 1- Get selection pool applicants
        $query = <<<GQL
        query {
            event(where: {id: {_eq: $spId}}) {
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
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $query
        ]);

        $spApplicants = $response['data']['event'][0]['users'];

        //TODO: 2- Get the XP of the students 
        $xpQuery = <<<GQL
        query{
        user {
            login
            xps {
                amount
                }
                }
        }
        GQL;

        $xpResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $xpQuery
        ]);
        // all users xp
        $xps = $xpResponse['data']['user'];
        // ! this array is too long, for all users in the platform
        //TODO: 3- Get the level of the students
        $levelsQuery = <<<GQL
        query {
            event_user(where: {eventId:{_eq:$spId}}) {
                userLogin
                level
                }
        }
        GQL;
        $levelsResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $levelsQuery
        ]);
        // all sp levels array
        $levels = $levelsResponse['data']['event_user'];

        //TODO: 4- get the progresses
        // query to get all users progresses
        $queryProgresses = <<<GQL
         query {
             toad_sessions(where: {final_score: {_gte: 20}}) {
                 final_score
                 candidate {
                     login
                     progresses(limit:3, order_by: {updatedAt: desc}) {
                         path
                         updatedAt
                         grade
                         isDone
                         
                         }
                     registrations {
                     createdAt
                     registration{
                       path
                       createdAt
                     }
                   }
                         }
                         }
                         }
         GQL;
        $responseProgresses = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $queryProgresses
        ]);

        $userProgresses = $responseProgresses['data']['toad_sessions'];


        //TODO: 5- Add or update the selection pool data
        // formulate a holistic object
        // add the levels
        foreach ($levels as $level) {
            $key = array_search($level['userLogin'], array_column($spApplicants, 'login'));
            $spApplicants[$key]['level'] = $level['level'];
        }

        // add the xps
        for ($i = 0; $i < count($spApplicants); $i++) {
            $key = array_search($spApplicants[$i]['login'], array_column($xps, 'login'));
            $spApplicants[$i]['xp'] = $xps[$key]['xps'];
        }


        for ($i = 0; $i < count($userProgresses); $i++) {
            // return $userProgresses[$i];
            $userProgresses[$i]['login'] = $userProgresses[$i]['candidate']['login'] ?? '-';
        }
        // add the progresses
        for ($i = 0; $i < count($spApplicants); $i++) {
            // return array_column($userProgresses, 'candidate');
            $key = array_search($spApplicants[$i]['login'], array_column($userProgresses, 'login'));
            $spApplicants[$i]['progresses'] = $userProgresses[$key]['candidate']['progresses'];
            $spApplicants[$i]['registrations'] = $userProgresses[$key]['candidate']['registrations'];
            $candidateImageUrl = 'https://learn.reboot01.com/api/storage/?token=' . $apiToken . '&fileId=' . $spApplicants[$i]['profile'];
            $candidateCPRUrl = 'https://learn.reboot01.com/api/storage/?token=' . $apiToken . '&fileId=' . $spApplicants[$i]['id'];
            $spApplicants[$i]['profileImage'] = $candidateImageUrl;
            $spApplicants[$i]['cpr'] = $candidateCPRUrl;
            $spApplicants[$i]['sp'] = 'SP4';
        }
        // accomulate the xps 
        for ($i = 0; $i < count($spApplicants); $i++) {
            $totalXp = 0;
            foreach ($spApplicants[$i]['xp'] as $xp) {
                $totalXp = $totalXp + $xp['amount'];
            }
            $spApplicants[$i]['xp'] = $totalXp;
        }

        foreach ($spApplicants as $applicant) {
            $existingApplicant = SP::where('platformId', $applicant['login'])->first();

            if ($existingApplicant) {
            } else {
                $newApplicant = new SP();
                $newApplicant->platformId = $applicant['login'];
                $newApplicant->firstName = $applicant['firstName'] ??  'Unknown';
                $newApplicant->lastName = $applicant['lastName'] ?? 'Unknown';
                $newApplicant->email = $applicant['email'] ?? 'unKnown@gamil.com';
                $newApplicant->phone = $applicant['phone'] ?? $applicant['phoneNumber'] ?? '';
                $newApplicant->gender = $applicant['gender'] ?? $applicant['genders'] ?? null;
                $newApplicant->nationality = $applicant['nationality'] ?? null;
                // Parse the date of birth from the API response into a Unix timestamp and format it to the 'YYYY-MM-DD' format
                $dob = $applicant['dob'] ? strtotime(substr($applicant['dob'], 0, 10)) : null;
                $dobStr = $dob ? date('Y-m-d', $dob) : null;
                $newApplicant->dob = $dobStr;
                $newApplicant->acadamicQualification = $applicant['acadamicQualification'] ?? null;
                $newApplicant->acadamicSpecialization = $applicant['acadamicSpecialization'] ?? null;
                $newApplicant->employment = $applicant['employment'] ?? 'unknown';
                $newApplicant->howDidYouHear = $applicant['howDidYouHear'] ?? 'unknown';
                $newApplicant->profilePicture = $applicant['profileImage'] ?? 'unknown';
                $newApplicant->cprPicture = $applicant['cpr'] ?? 'unknown';
                $newApplicant->sp = $applicant['sp'] ?? 'unknown';
                $newApplicant->xp = $applicant['xp'] ?? 'unknown';
                $newApplicant->level = $applicant['level'] ?? 'unknown';
                $newApplicant->progresses = json_encode($applicant['progresses']);
                $newApplicant->registrations = json_encode($applicant['registrations']);
                // Save the new Applicant model to the database
                $newApplicant->save();
            }
        }

        return SP::query()->get();
        return [
            'applicants' => $spApplicants,
        ];
    }

    // * get selection pool applicants from SIS database
    public function SelectionPoolApplicantsLightening(Request $request)
    {
        // TODO: 1- Get Selection pool sepecific
        // TODO: 2- Get Comments applicant specific 
        // TODO: 3- Formulate a response
    }

    //* selection pool Applicants
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
                    # Limit the query to last three items
                    progresses(limit:3, order_by: {updatedAt: desc}) {
                        path
                        updatedAt
                        grade
                        isDone
                        
                        }
                    registrations {
                    createdAt
                    registration{
                      path
                      createdAt
                    }
                  }
                        }
                        }
                        }
        GQL;

        //  API calls
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $query
        ]);

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

        for ($i = 0; $i < count($spApplicants); $i++) {

            foreach ($userProgresses as $spProgress) {

                if (isset($spApplicants[$i]['login']) && isset($spProgress['candidate']['login'])) {


                    if ($spApplicants[$i]['login'] == $spProgress['candidate']['login']) {
                        $candidateImageUrl = 'https://learn.reboot01.com/api/storage/?token=' . $apiToken . '&fileId=' . $spApplicants[$i]['profile'];

                        // add progresses and registrations to the applicants response
                        $spApplicants[$i]['progresses'] = $spProgress['candidate']['progresses'];
                        $spApplicants[$i]['registrations'] = $spProgress['candidate']['registrations'];

                        $spApplicants[$i]['profileImage'] = $candidateImageUrl;
                    }
                }
            }
        }

        return $spApplicants;
    }


    // TODO: create a table for selection pool candidates
    // TODO: Create a sync function 
    // TODO: create a function that gets the image of the candidate.
    // TODO: create a function that 
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
