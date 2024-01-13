<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SP;
use App\Models\Comment;


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


        // TODO: 5- get the checkpoints from the users

        $checkpointProgresses = <<<GQL
         query {
            event(where: {id: {_eq:$spId}}) {
                progresses {
                    user {
                        login
                        firstName
                        lastName
                        progresses( where:{
                            _and:[ {path:{_regex:"checkpoint"}}, {grade:{_eq: 1}}, {updatedAt:{_gte:"2023-11-18"}}]},order_by:{updatedAt:asc}) {
                                path
                                updatedAt
                                grade
                                isDone
                                }
                                }
                                }
                                }
                         }
         GQL;

        $responseCheckpoint = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // maybe a cron function will work that
            'Authorization' => 'Bearer ' . $apiToken
        ])->post('https://learn.reboot01.com/api/graphql-engine/v1/graphql', [
            'query' => $checkpointProgresses
        ]);

        $checkpointProgress = $responseCheckpoint['data']['event'][0]['progresses'];
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
        // add checkpoint progresses
        for ($i = 0; $i < count($checkpointProgress); $i++) {
            for ($j = 0; $j < count($spApplicants); $j++) {
                if ($checkpointProgress[$i]['user']['login'] == $spApplicants[$j]['login']) {
                    $spApplicants[$j]['checkpoint'] = $checkpointProgress[$i]['user']['progresses'];
                }
            }
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
                // this to account when the token is changed, to update this as well
                $candidateImageUrl = 'https://learn.reboot01.com/api/storage/?token=' . $apiToken . '&fileId=' . $applicant['profile'];
                $candidateCPRUrl = 'https://learn.reboot01.com/api/storage/?token=' . $apiToken . '&fileId=' . $applicant['id'];

                if ($existingApplicant->pictureChanged) {
                    // TODO: Save CPR images on desk
                    // TODO: save personal images

                } else {
                    // the image links only updates for false value
                    // ! possibility to change and save images
                    // $this->saveImageOnDisk($existingApplicant->platformId, $candidateImageUrl);
                    $existingApplicant->profilePicture = $candidateImageUrl ?? 'unknown';
                }

                $existingApplicant->sp = $applicant['sp'] ?? 'unknown';
                $existingApplicant->xp = $applicant['xp'] ?? 'unknown';
                $existingApplicant->level = $applicant['level'] ?? 'unknown';
                $existingApplicant->cprPicture = $candidateCPRUrl ?? 'unknown';
                $existingApplicant->progresses = json_encode($applicant['progresses']);
                $existingApplicant->registrations = json_encode($applicant['registrations']);
                $existingApplicant->checkpoints = json_encode($applicant['checkpoint']);

                $existingApplicant->save();
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
                $newApplicant->checkpoints = json_encode($applicant['checkpoint']);
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
    // TODO: add the filters and sorters
    public function selectionPoolApplicants(Request $request)
    {

        // * Filtering and searching can be done here as well
        $spApplicants = SP::all();
        // add the comments to the response
        for ($i = 0; $i < count($spApplicants); $i++) {
            $comments = Comment::where('platformId', $spApplicants[$i]['platformId'])->get();
            if (count($comments)) {
                $spApplicants[$i]['comments'] = $comments;
            } else {
                $spApplicants[$i]['comments'] = [];
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
        // ! get the student using SP table
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
        // ! Depending on the Candidate picture changed, retrieve accordingly

        $candidateImageUrl = 'https://learn.reboot01.com/api/storage/?token=' . $apiToken . '&fileId=' . $candidate['profile'];
        $filename =  $platformId . '.jpg';

        return [
            'profileImage' => $candidateImageUrl,
            'candidate' => $candidate,
            // 'path' => $path
        ];
    }


    public function updateApplicantDecision(Request $request)
    {
        $platformId = $request->platformId;
        $decision = $request->decision;
        $comment = $request->comment;
        // set the decisionand comment
        $existingApplicant = SP::where('platformId', $platformId)->first();
        $existingApplicant->decision = $decision;
        $existingApplicant->finalComment = $comment;
        $existingApplicant->save();
        return $existingApplicant;
        // 
    }





    // TODO: Later release when updating all users
    // public function saveImageOnDisk($id, $url)
    // {
    //     $filename = $id . '.jpg'; // Desired filename for the saved image
    //     try {
    //         $imageContents = file_get_contents($url);
    //         Storage::disk('public')->put('images/' . $filename, $imageContents);
    //     } catch (Exception $e) {
    //     }
    // }
}
